<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PenghuniModel;
use App\Models\KamarModel;
use App\Models\UserModel;

class PenghuniController extends BaseController
{
    protected PenghuniModel $penghuniModel;
    protected KamarModel $kamarModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->penghuniModel = new PenghuniModel();
        $this->kamarModel    = new KamarModel();
        $this->userModel     = new UserModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Manajemen Penghuni Kost',
            'penghuni' => $this->penghuniModel->getPenghuniWithDetails(),
        ];
        return view('admin/penghuni/index', $data);
    }

    public function new()
    {
        $kamarTersedia = $this->kamarModel->getKamarTersedia();

        $data = [
            'title'         => 'Pendaftaran Penghuni Baru',
            'kamarTersedia' => $kamarTersedia,
        ];
        return view('admin/penghuni/form', $data);
    }

    public function create()
    {
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'nik'          => 'required|numeric|min_length[16]|max_length[20]',
            'no_hp'        => 'required|min_length[10]|max_length[20]',
            'pekerjaan'    => 'required|max_length[100]',
            'kamar_id'     => 'required|numeric',
            'tgl_masuk'    => 'required|valid_date',
            'username'     => 'required|min_length[3]|is_unique[users.username]',
            'email'        => 'required|valid_email|is_unique[users.email]',
            'password'     => 'required|min_length[6]',
            'foto_ktp'     => 'permit_empty|uploaded[foto_ktp]|is_image[foto_ktp]|mime_in[foto_ktp,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto_ktp,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $kamarId = (int) $this->request->getPost('kamar_id');
        $kamar   = $this->kamarModel->find($kamarId);
        if (! $kamar || $kamar['status'] !== 'Tersedia') {
            return redirect()->back()->withInput()->with('error', 'Kamar yang dipilih tidak tersedia.');
        }

        // Upload KTP jika ada
        $fotoKtp = null;
        $ktpFile = $this->request->getFile('foto_ktp');
        if ($ktpFile && $ktpFile->isValid() && ! $ktpFile->hasMoved()) {
            $fotoKtp = $ktpFile->getRandomName();
            $ktpFile->move(FCPATH . 'uploads/ktp', $fotoKtp);
        }

        // 1. Buat User Akun Penghuni
        $userId = $this->userModel->insert([
            'nama_lengkap' => trim((string) $this->request->getPost('nama_lengkap')),
            'username'     => trim((string) $this->request->getPost('username')),
            'email'        => trim((string) $this->request->getPost('email')),
            'password'     => password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'         => 'penghuni',
            'is_active'    => 1,
        ]);

        // 2. Buat Record Penghuni
        $this->penghuniModel->insert([
            'user_id'      => $userId,
            'kamar_id'     => $kamarId,
            'nama_lengkap' => trim((string) $this->request->getPost('nama_lengkap')),
            'nik'          => trim((string) $this->request->getPost('nik')),
            'no_hp'        => trim((string) $this->request->getPost('no_hp')),
            'pekerjaan'    => trim((string) $this->request->getPost('pekerjaan')),
            'foto_ktp'     => $fotoKtp,
            'tgl_masuk'    => $this->request->getPost('tgl_masuk'),
            'status'       => 'Aktif',
        ]);

        // 3. Otomatis Update Status Kamar menjadi 'Terisi'
        $this->kamarModel->update($kamarId, ['status' => 'Terisi']);

        return redirect()->to(base_url('admin/penghuni'))->with('success', 'Penghuni baru berhasil didaftarkan dan kamar otomatis berstatus Terisi.');
    }

    public function edit(int $id)
    {
        $penghuni = $this->penghuniModel->getPenghuniWithDetails($id);
        if (! $penghuni) {
            return redirect()->to(base_url('admin/penghuni'))->with('error', 'Data penghuni tidak ditemukan.');
        }

        // Dapatkan kamar tersedia + kamar yang sedang ditempati penghuni ini
        $kamarList = $this->kamarModel->getKamarTersedia();
        if ($penghuni['kamar_id']) {
            $currentKamar = $this->kamarModel->getKamarWithTipe($penghuni['kamar_id']);
            if ($currentKamar && ! in_array($currentKamar['id'], array_column($kamarList, 'id'))) {
                array_unshift($kamarList, $currentKamar);
            }
        }

        $data = [
            'title'         => 'Edit Data Penghuni: ' . esc($penghuni['nama_lengkap']),
            'penghuni'      => $penghuni,
            'kamarTersedia' => $kamarList,
        ];
        return view('admin/penghuni/form', $data);
    }

    public function update(int $id)
    {
        $penghuni = $this->penghuniModel->find($id);
        if (! $penghuni) {
            return redirect()->to(base_url('admin/penghuni'))->with('error', 'Penghuni tidak ditemukan.');
        }

        $userId = $penghuni['user_id'];
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'nik'          => 'required|numeric|min_length[16]|max_length[20]',
            'no_hp'        => 'required|min_length[10]|max_length[20]',
            'pekerjaan'    => 'required|max_length[100]',
            'kamar_id'     => 'required|numeric',
            'tgl_masuk'    => 'required|valid_date',
            'username'     => "required|min_length[3]|is_unique[users.username,id,{$userId}]",
            'email'        => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'foto_ktp'     => 'permit_empty|uploaded[foto_ktp]|is_image[foto_ktp]|mime_in[foto_ktp,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto_ktp,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fotoKtp = $penghuni['foto_ktp'];
        $ktpFile = $this->request->getFile('foto_ktp');
        if ($ktpFile && $ktpFile->isValid() && ! $ktpFile->hasMoved()) {
            if (! empty($penghuni['foto_ktp']) && file_exists(FCPATH . 'uploads/ktp/' . $penghuni['foto_ktp'])) {
                unlink(FCPATH . 'uploads/ktp/' . $penghuni['foto_ktp']);
            }
            $fotoKtp = $ktpFile->getRandomName();
            $ktpFile->move(FCPATH . 'uploads/ktp', $fotoKtp);
        }

        $newKamarId = (int) $this->request->getPost('kamar_id');
        $oldKamarId = (int) $penghuni['kamar_id'];

        // Jika pindah kamar
        if ($newKamarId !== $oldKamarId) {
            if ($oldKamarId) {
                $this->kamarModel->update($oldKamarId, ['status' => 'Tersedia']);
            }
            $this->kamarModel->update($newKamarId, ['status' => 'Terisi']);
        }

        // Update User
        $userData = [
            'nama_lengkap' => trim((string) $this->request->getPost('nama_lengkap')),
            'username'     => trim((string) $this->request->getPost('username')),
            'email'        => trim((string) $this->request->getPost('email')),
        ];
        $newPassword = (string) $this->request->getPost('password');
        if (! empty($newPassword)) {
            $userData['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }
        $this->userModel->update($userId, $userData);

        // Update Penghuni
        $this->penghuniModel->update($id, [
            'kamar_id'     => $newKamarId,
            'nama_lengkap' => trim((string) $this->request->getPost('nama_lengkap')),
            'nik'          => trim((string) $this->request->getPost('nik')),
            'no_hp'        => trim((string) $this->request->getPost('no_hp')),
            'pekerjaan'    => trim((string) $this->request->getPost('pekerjaan')),
            'foto_ktp'     => $fotoKtp,
            'tgl_masuk'    => $this->request->getPost('tgl_masuk'),
        ]);

        return redirect()->to(base_url('admin/penghuni'))->with('success', 'Data penghuni berhasil diperbarui.');
    }

    public function checkout(int $id)
    {
        $penghuni = $this->penghuniModel->find($id);
        if (! $penghuni) {
            return redirect()->to(base_url('admin/penghuni'))->with('error', 'Penghuni tidak ditemukan.');
        }

        $kamarId = $penghuni['kamar_id'];

        // 1. Ubah status penghuni menjadi Nonaktif & catat tanggal keluar
        $this->penghuniModel->update($id, [
            'status'     => 'Nonaktif',
            'tgl_keluar' => date('Y-m-d'),
        ]);

        // 2. Nonaktifkan akun login penghuni
        if ($penghuni['user_id']) {
            $this->userModel->update($penghuni['user_id'], ['is_active' => 0]);
        }

        // 3. Otomatis kembalikan status kamar menjadi 'Tersedia'
        if ($kamarId) {
            $this->kamarModel->update($kamarId, ['status' => 'Tersedia']);
        }

        return redirect()->to(base_url('admin/penghuni'))->with('success', "Penghuni berhasil di-checkout. Status kamar otomatis kembali Tersedia.");
    }

    public function delete(int $id)
    {
        $penghuni = $this->penghuniModel->find($id);
        if (! $penghuni) {
            return redirect()->to(base_url('admin/penghuni'))->with('error', 'Penghuni tidak ditemukan.');
        }

        $kamarId = $penghuni['kamar_id'];
        $userId  = $penghuni['user_id'];

        // Hapus foto KTP jika ada
        if (! empty($penghuni['foto_ktp']) && file_exists(FCPATH . 'uploads/ktp/' . $penghuni['foto_ktp'])) {
            unlink(FCPATH . 'uploads/ktp/' . $penghuni['foto_ktp']);
        }

        // Kembalikan status kamar
        if ($kamarId && $penghuni['status'] === 'Aktif') {
            $this->kamarModel->update($kamarId, ['status' => 'Tersedia']);
        }

        $this->penghuniModel->delete($id);
        if ($userId) {
            $this->userModel->delete($userId);
        }

        return redirect()->to(base_url('admin/penghuni'))->with('success', 'Data penghuni dan akun login berhasil dihapus.');
    }
}
