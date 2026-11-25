<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if (session()->get('logged_in')) {
            $role = session()->get('role');
            return redirect()->to(base_url($role === 'admin' ? 'admin/dashboard' : 'penghuni/dashboard'));
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        $rules = [
            'login'    => 'required|min_length[3]',
            'password' => 'required|min_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Silakan isi username/email dan password dengan benar.');
        }

        $login    = trim((string) $this->request->getPost('login'));
        $password = (string) $this->request->getPost('password');

        $user = $this->userModel->findByEmailOrUsername($login);

        if (! $user) {
            return redirect()->back()->withInput()->with('error', 'Username atau Email tidak ditemukan.');
        }

        if (! (int) $user['is_active']) {
            return redirect()->back()->withInput()->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi pengelola.');
        }

        if (! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password yang Anda masukkan salah.');
        }

        // Regenerasi session ID untuk proteksi session fixation
        session()->regenerate();

        $sessionData = [
            'user_id'      => (int) $user['id'],
            'nama_lengkap' => $user['nama_lengkap'],
            'username'     => $user['username'],
            'email'        => $user['email'],
            'role'         => $user['role'],
            'foto_profil'  => $user['foto_profil'],
            'logged_in'    => true,
        ];

        session()->set($sessionData);

        $targetUrl = ($user['role'] === 'admin') ? 'admin/dashboard' : 'penghuni/dashboard';
        return redirect()->to(base_url($targetUrl))->with('success', 'Selamat datang kembali, ' . esc($user['nama_lengkap']) . '!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Anda telah berhasil logout.');
    }
}
