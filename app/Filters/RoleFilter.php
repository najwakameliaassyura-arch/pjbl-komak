<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (! $session->get('logged_in')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = $session->get('role');

        if (! empty($arguments)) {
            // Jika role user tidak termasuk dalam argumen yang diizinkan
            if (! in_array($userRole, $arguments, true)) {
                if ($userRole === 'admin') {
                    return redirect()->to(base_url('admin/dashboard'))->with('warning', 'Akses ditolak: Anda tidak memiliki wewenang untuk halaman tersebut.');
                } else {
                    return redirect()->to(base_url('penghuni/dashboard'))->with('warning', 'Akses ditolak: Anda tidak memiliki wewenang untuk halaman tersebut.');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
