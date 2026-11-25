<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KamarModel;
use App\Models\TipeKamarModel;
use App\Models\PenghuniModel;

class KamarController extends BaseController
{
    protected KamarModel $kamarModel;
    protected TipeKamarModel $tipeKamarModel;
    protected PenghuniModel $penghuniModel;

    public function __construct()
    {
        $this->kamarModel     = new KamarModel();
        $this->tipeKamarModel = new TipeKamarModel();
        $this->penghuniModel  = new PenghuniModel();
    }

    public function index()
    {
        $kamar = $this->kamarModel->getKamarWithTipe();

        // Cari tahu nama penghuni aktif saat ini jika kamar 'Terisi'
        foreach ($kamar as &$k) {
            $penghuni = $this->penghuniModel->where('kamar_id', $k['id'])
                                            ->where('status', 'Aktif')
                                            ->first();
            $k['penghuni_aktif'] = $penghuni ? $penghuni['nama_lengkap'] : '-';
        }

        $data = [
            'title' => 'Manajemen Kamar Kost',
            'kamar' => $kamar,
        ];

        return view('admin/kamar/index', $data);
    }

    public function new()
    {
        $data = [
            'title'     => 'Tambah Kamar Baru',
            'tipeKamar' => $this->tipeKamarModel->findAll(),
        ];
        return view('admin/kamar/form', $data);
    }

    public function create()
    {
        $rules = [
            'nomor_kamar' => 'required|min_length[2]|max_length[50]|is_unique[kamar.nomor_kamar]',
            'tipe_id'     => 'required|numeric',
            'lantai'      => 'required',
            'status'      => 'required|in_list[Tersedia,Terisi,Perbaikan]',
            'foto'        => 'permit_empty|uploaded[foto]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fotoNama = null;
        $fotoFile = $this->request->getFile('foto');
        if ($fotoFile && $fotoFile->isValid() && ! $fotoFile->hasMoved()) {
            $fotoNama = $fotoFile->getRandomName();
            $fotoFile->move(FCPATH . 'uploads/kamar', $fotoNama);
        }

        $this->kamarModel->insert([
            'nomor_kamar' => trim((string) $this->request->getPost('nomor_kamar')),
            'tipe_id'     => (int) $this->request->getPost('tipe_id'),
            'lantai'      => trim((string) $this->request->getPost('lantai')),
            'status'      => (string) $this->request->getPost('status'),
            'keterangan'  => trim((string) $this->request->getPost('keterangan')),
            'foto'        => $fotoNama,
        ]);

        return redirect()->to(base_url('admin/kamar'))->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $kamar = $this->kamarModel->find($id);
        if (! $kamar) {
            return redirect()->to(base_url('admin/kamar'))->with('error', 'Kamar tidak ditemukan.');
        }

        $data = [
            'title'     => 'Edit Kamar ' . esc($kamar['nomor_kamar']),
            'kamar'     => $kamar,
            'tipeKamar' => $this->tipeKamarModel->findAll(),
        ];
        return view('admin/kamar/form', $data);
    }

    public function update(int $id)
    {
        $kamar = $this->kamarModel->find($id);
        if (! $kamar) {
            return redirect()->to(base_url('admin/kamar'))->with('error', 'Kamar tidak ditemukan.');
        }

        $rules = [
            'nomor_kamar' => "required|min_length[2]|max_length[50]|is_unique[kamar.nomor_kamar,id,{$id}]",
            'tipe_id'     => 'required|numeric',
            'lantai'      => 'required',
            'status'      => 'required|in_list[Tersedia,Terisi,Perbaikan]',
            'foto'        => 'permit_empty|uploaded[foto]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fotoNama = $kamar['foto'];
        $fotoFile = $this->request->getFile('foto');
        if ($fotoFile && $fotoFile->isValid() && ! $fotoFile->hasMoved()) {
            // Hapus foto lama jika ada
            if (! empty($kamar['foto']) && file_exists(FCPATH . 'uploads/kamar/' . $kamar['foto'])) {
                unlink(FCPATH . 'uploads/kamar/' . $kamar['foto']);
            }
            $fotoNama = $fotoFile->getRandomName();
            $fotoFile->move(FCPATH . 'uploads/kamar', $fotoNama);
        }

        $this->kamarModel->update($id, [
            'nomor_kamar' => trim((string) $this->request->getPost('nomor_kamar')),
            'tipe_id'     => (int) $this->request->getPost('tipe_id'),
            'lantai'      => trim((string) $this->request->getPost('lantai')),
            'status'      => (string) $this->request->getPost('status'),
            'keterangan'  => trim((string) $this->request->getPost('keterangan')),
            'foto'        => $fotoNama,
        ]);

        return redirect()->to(base_url('admin/kamar'))->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $kamar = $this->kamarModel->find($id);
        if (! $kamar) {
            return redirect()->to(base_url('admin/kamar'))->with('error', 'Kamar tidak ditemukan.');
        }

        // Cek jika masih ada penghuni aktif
        $penghuniAktif = $this->penghuniModel->where('kamar_id', $id)
                                            ->where('status', 'Aktif')
                                            ->countAllResults();
        if ($penghuniAktif > 0) {
            return redirect()->to(base_url('admin/kamar'))->with('error', 'Kamar tidak bisa dihapus karena masih dihuni oleh penyewa aktif.');
        }

        if (! empty($kamar['foto']) && file_exists(FCPATH . 'uploads/kamar/' . $kamar['foto'])) {
            unlink(FCPATH . 'uploads/kamar/' . $kamar['foto']);
        }

        $this->kamarModel->delete($id);
        return redirect()->to(base_url('admin/kamar'))->with('success', 'Kamar berhasil dihapus.');
    }
}
