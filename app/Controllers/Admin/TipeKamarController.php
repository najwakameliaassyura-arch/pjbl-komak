<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TipeKamarModel;
use App\Models\KamarModel;

class TipeKamarController extends BaseController
{
    protected TipeKamarModel $tipeKamarModel;
    protected KamarModel $kamarModel;

    public function __construct()
    {
        $this->tipeKamarModel = new TipeKamarModel();
        $this->kamarModel     = new KamarModel();
    }

    public function index()
    {
        $tipeKamar = $this->tipeKamarModel->orderBy('id', 'DESC')->findAll();

        // Hitung jumlah kamar per tipe
        foreach ($tipeKamar as &$tipe) {
            $tipe['jumlah_kamar'] = $this->kamarModel->where('tipe_id', $tipe['id'])->countAllResults();
        }

        $data = [
            'title'     => 'Manajemen Tipe Kamar',
            'tipeKamar' => $tipeKamar,
        ];

        return view('admin/tipe_kamar/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah Tipe Kamar Baru',
        ];
        return view('admin/tipe_kamar/form', $data);
    }

    public function create()
    {
        $rules = [
            'nama_tipe'     => 'required|min_length[3]|max_length[100]',
            'harga_bulanan' => 'required|numeric|greater_than[0]',
            'fasilitas'     => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->tipeKamarModel->insert([
            'nama_tipe'     => trim((string) $this->request->getPost('nama_tipe')),
            'harga_bulanan' => (float) $this->request->getPost('harga_bulanan'),
            'fasilitas'     => trim((string) $this->request->getPost('fasilitas')),
            'deskripsi'     => trim((string) $this->request->getPost('deskripsi')),
        ]);

        return redirect()->to(base_url('admin/tipe-kamar'))->with('success', 'Tipe kamar berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $tipe = $this->tipeKamarModel->find($id);
        if (! $tipe) {
            return redirect()->to(base_url('admin/tipe-kamar'))->with('error', 'Tipe kamar tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Tipe Kamar: ' . esc($tipe['nama_tipe']),
            'tipe'  => $tipe,
        ];
        return view('admin/tipe_kamar/form', $data);
    }

    public function update(int $id)
    {
        $tipe = $this->tipeKamarModel->find($id);
        if (! $tipe) {
            return redirect()->to(base_url('admin/tipe-kamar'))->with('error', 'Tipe kamar tidak ditemukan.');
        }

        $rules = [
            'nama_tipe'     => 'required|min_length[3]|max_length[100]',
            'harga_bulanan' => 'required|numeric|greater_than[0]',
            'fasilitas'     => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->tipeKamarModel->update($id, [
            'nama_tipe'     => trim((string) $this->request->getPost('nama_tipe')),
            'harga_bulanan' => (float) $this->request->getPost('harga_bulanan'),
            'fasilitas'     => trim((string) $this->request->getPost('fasilitas')),
            'deskripsi'     => trim((string) $this->request->getPost('deskripsi')),
        ]);

        return redirect()->to(base_url('admin/tipe-kamar'))->with('success', 'Tipe kamar berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $count = $this->kamarModel->where('tipe_id', $id)->countAllResults();
        if ($count > 0) {
            return redirect()->to(base_url('admin/tipe-kamar'))->with('error', "Gagal menghapus: Tipe kamar ini masih digunakan oleh {$count} kamar.");
        }

        $this->tipeKamarModel->delete($id);
        return redirect()->to(base_url('admin/tipe-kamar'))->with('success', 'Tipe kamar berhasil dihapus.');
    }
}
