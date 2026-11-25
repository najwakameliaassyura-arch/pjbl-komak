<?php

namespace App\Controllers\Penghuni;

use App\Controllers\BaseController;
use App\Models\KeluhanModel;
use App\Models\PenghuniModel;

class KeluhanController extends BaseController
{
    protected KeluhanModel $keluhanModel;
    protected PenghuniModel $penghuniModel;

    public function __construct()
    {
        $this->keluhanModel  = new KeluhanModel();
        $this->penghuniModel = new PenghuniModel();
    }

    private function getTenant(): ?array
    {
        $userId = (int) session()->get('user_id');
        return $this->penghuniModel->where('user_id', $userId)->first();
    }

    public function index()
    {
        $tenant = $this->getTenant();
        if (! $tenant) {
            return redirect()->to(base_url('logout'));
        }

        $keluhan = $this->keluhanModel->getKeluhanByPenghuni((int) $tenant['id']);

        $data = [
            'title'   => 'Laporan Keluhan Kamar Saya',
            'keluhan' => $keluhan,
            'tenant'  => $tenant,
        ];

        return view('penghuni/keluhan/index', $data);
    }

    public function new()
    {
        $tenant = $this->getTenant();
        if (! $tenant || ! $tenant['kamar_id']) {
            return redirect()->to(base_url('penghuni/dashboard'))->with('error', 'Anda belum terdaftar menempati kamar.');
        }

        $data = [
            'title'  => 'Buat Laporan Keluhan / Kerusakan Fasilitas',
            'tenant' => $tenant,
        ];

        return view('penghuni/keluhan/form', $data);
    }

    public function create()
    {
        $tenant = $this->getTenant();
        if (! $tenant || ! $tenant['kamar_id']) {
            return redirect()->to(base_url('penghuni/dashboard'))->with('error', 'Data tidak valid.');
        }

        $rules = [
            'judul_keluhan'     => 'required|min_length[5]|max_length[200]',
            'deskripsi_keluhan' => 'required|min_length[10]',
            'foto_keluhan'      => 'permit_empty|uploaded[foto_keluhan]|is_image[foto_keluhan]|mime_in[foto_keluhan,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto_keluhan,3072]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fotoKeluhan = null;
        $fileFoto    = $this->request->getFile('foto_keluhan');
        if ($fileFoto && $fileFoto->isValid() && ! $fileFoto->hasMoved()) {
            $fotoKeluhan = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'uploads/keluhan', $fotoKeluhan);
        }

        $this->keluhanModel->insert([
            'penghuni_id'       => (int) $tenant['id'],
            'kamar_id'          => (int) $tenant['kamar_id'],
            'judul_keluhan'     => trim((string) $this->request->getPost('judul_keluhan')),
            'deskripsi_keluhan' => trim((string) $this->request->getPost('deskripsi_keluhan')),
            'foto_keluhan'      => $fotoKeluhan,
            'status'            => 'Pending',
        ]);

        return redirect()->to(base_url('penghuni/keluhan'))->with('success', 'Keluhan Anda berhasil dikirimkan. Pengelola akan segera menindaklanjutinya.');
    }
}
