<?php

namespace App\Controllers\Penghuni;

use App\Controllers\BaseController;
use App\Models\PenghuniModel;
use App\Models\UserModel;

class ProfilController extends BaseController
{
    protected PenghuniModel $penghuniModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->penghuniModel = new PenghuniModel();
        $this->userModel     = new UserModel();
    }

    public function index()
    {
        $userId   = (int) session()->get('user_id');
        $penghuni = $this->penghuniModel->getByUserId($userId);
        $user     = $this->userModel->find($userId);

        if (! $penghuni) {
            return redirect()->to(base_url('logout'));
        }

        $data = [
            'title'    => 'Profil & Pengaturan Akun',
            'penghuni' => $penghuni,
            'user'     => $user,
        ];

        return view('penghuni/profil/index', $data);
    }

    public function update()
    {
        $userId   = (int) session()->get('user_id');
        $penghuni = $this->penghuniModel->where('user_id', $userId)->first();

        if (! $penghuni) {
            return redirect()->to(base_url('logout'));
        }

        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'no_hp'        => 'required|min_length[10]|max_length[20]',
            'pekerjaan'    => 'required|max_length[100]',
            'email'        => "required|valid_email|is_unique[users.email,id,{$userId}]",
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $namaLengkap = trim((string) $this->request->getPost('nama_lengkap'));
        $email       = trim((string) $this->request->getPost('email'));

        // Update User
        $this->userModel->update($userId, [
            'nama_lengkap' => $namaLengkap,
            'email'        => $email,
        ]);

        // Update Penghuni
        $this->penghuniModel->update($penghuni['id'], [
            'nama_lengkap' => $namaLengkap,
            'no_hp'        => trim((string) $this->request->getPost('no_hp')),
            'pekerjaan'    => trim((string) $this->request->getPost('pekerjaan')),
        ]);

        // Update session
        session()->set('nama_lengkap', $namaLengkap);
        session()->set('email', $email);

        return redirect()->to(base_url('penghuni/profil'))->with('success', 'Profil Anda berhasil diperbarui.');
    }

    public function updatePassword()
    {
        $userId = (int) session()->get('user_id');
        $user   = $this->userModel->find($userId);

        $rules = [
            'password_lama'       => 'required',
            'password_baru'       => 'required|min_length[6]',
            'konfirmasi_password' => 'required|matches[password_baru]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $passwordLama = (string) $this->request->getPost('password_lama');
        if (! password_verify($passwordLama, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama yang Anda masukkan salah.');
        }

        $passwordBaru = (string) $this->request->getPost('password_baru');
        $this->userModel->update($userId, [
            'password' => password_hash($passwordBaru, PASSWORD_BCRYPT),
        ]);

        return redirect()->to(base_url('penghuni/profil'))->with('success', 'Password Anda berhasil diubah.');
    }
}
