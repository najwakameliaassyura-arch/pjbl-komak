<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KeluhanModel;

class KeluhanController extends BaseController
{
    protected KeluhanModel $keluhanModel;

    public function __construct()
    {
        $this->keluhanModel = new KeluhanModel();
    }

    public function index()
    {
        $data = [
            'title'   => 'Manajemen Keluhan & Kerusakan Kamar',
            'keluhan' => $this->keluhanModel->getKeluhanWithDetails(),
        ];
        return view('admin/keluhan/index', $data);
    }

    public function update(int $id)
    {
        $keluhan = $this->keluhanModel->find($id);
        if (! $keluhan) {
            return redirect()->to(base_url('admin/keluhan'))->with('error', 'Data keluhan tidak ditemukan.');
        }

        $rules = [
            'status'          => 'required|in_list[Pending,Diproses,Selesai]',
            'tanggapan_admin' => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(base_url('admin/keluhan'))->with('error', 'Status keluhan tidak valid.');
        }

        $this->keluhanModel->update($id, [
            'status'          => (string) $this->request->getPost('status'),
            'tanggapan_admin' => trim((string) $this->request->getPost('tanggapan_admin')),
        ]);

        return redirect()->to(base_url('admin/keluhan'))->with('success', 'Status dan respon keluhan berhasil diperbarui.');
    }
}
