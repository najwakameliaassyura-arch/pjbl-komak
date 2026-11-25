<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Pengguna (User Management)',
            'users' => $this->userModel->orderBy('id', 'DESC')->findAll(),
        ];
        return view('admin/user/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah Pengguna Baru',
        ];
        return view('admin/user/form', $data);
    }

    public function create()
    {
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'username'     => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email'        => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'password'     => 'required|min_length[6]',
            'role'         => 'required|in_list[admin,penghuni]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->insert([
            'nama_lengkap' => trim((string) $this->request->getPost('nama_lengkap')),
            'username'     => trim((string) $this->request->getPost('username')),
            'email'        => trim((string) $this->request->getPost('email')),
            'password'     => password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'         => (string) $this->request->getPost('role'),
            'is_active'    => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to(base_url('admin/user'))->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to(base_url('admin/user'))->with('error', 'Pengguna tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Pengguna: ' . esc($user['username']),
            'user'  => $user,
        ];
        return view('admin/user/form', $data);
    }

    public function update(int $id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to(base_url('admin/user'))->with('error', 'Pengguna tidak ditemukan.');
        }

        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'username'     => "required|min_length[3]|max_length[100]|is_unique[users.username,id,{$id}]",
            'email'        => "required|valid_email|max_length[150]|is_unique[users.email,id,{$id}]",
            'role'         => 'required|in_list[admin,penghuni]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'nama_lengkap' => trim((string) $this->request->getPost('nama_lengkap')),
            'username'     => trim((string) $this->request->getPost('username')),
            'email'        => trim((string) $this->request->getPost('email')),
            'role'         => (string) $this->request->getPost('role'),
            'is_active'    => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $newPassword = (string) $this->request->getPost('password');
        if (! empty($newPassword)) {
            $updateData['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        $this->userModel->update($id, $updateData);

        return redirect()->to(base_url('admin/user'))->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        if ((int) session()->get('user_id') === $id) {
            return redirect()->to(base_url('admin/user'))->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $this->userModel->delete($id);
        return redirect()->to(base_url('admin/user'))->with('success', 'Pengguna berhasil dihapus.');
    }

    public function resetPassword(int $id)
    {
        $rules = [
            'password_baru' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(base_url('admin/user'))->with('error', 'Password baru minimal 6 karakter.');
        }

        $passwordBaru = (string) $this->request->getPost('password_baru');
        $this->userModel->update($id, [
            'password' => password_hash($passwordBaru, PASSWORD_BCRYPT),
        ]);

        return redirect()->to(base_url('admin/user'))->with('success', 'Password pengguna berhasil di-reset.');
    }
}
