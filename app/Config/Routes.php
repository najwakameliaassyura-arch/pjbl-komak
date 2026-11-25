<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Routes
$routes->get('/', 'LandingController::index');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

// Admin Routes (Protected by auth & role:admin)
$routes->group('admin', ['filter' => ['auth', 'role:admin']], static function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');

    // Tipe Kamar
    $routes->get('tipe-kamar', 'Admin\TipeKamarController::index');
    $routes->get('tipe-kamar/new', 'Admin\TipeKamarController::new');
    $routes->post('tipe-kamar', 'Admin\TipeKamarController::create');
    $routes->get('tipe-kamar/edit/(:num)', 'Admin\TipeKamarController::edit/$1');
    $routes->post('tipe-kamar/update/(:num)', 'Admin\TipeKamarController::update/$1');
    $routes->post('tipe-kamar/delete/(:num)', 'Admin\TipeKamarController::delete/$1');

    // Kamar
    $routes->get('kamar', 'Admin\KamarController::index');
    $routes->get('kamar/new', 'Admin\KamarController::new');
    $routes->post('kamar', 'Admin\KamarController::create');
    $routes->get('kamar/edit/(:num)', 'Admin\KamarController::edit/$1');
    $routes->post('kamar/update/(:num)', 'Admin\KamarController::update/$1');
    $routes->post('kamar/delete/(:num)', 'Admin\KamarController::delete/$1');

    // Penghuni
    $routes->get('penghuni', 'Admin\PenghuniController::index');
    $routes->get('penghuni/new', 'Admin\PenghuniController::new');
    $routes->post('penghuni', 'Admin\PenghuniController::create');
    $routes->get('penghuni/edit/(:num)', 'Admin\PenghuniController::edit/$1');
    $routes->post('penghuni/update/(:num)', 'Admin\PenghuniController::update/$1');
    $routes->post('penghuni/delete/(:num)', 'Admin\PenghuniController::delete/$1');
    $routes->post('penghuni/checkout/(:num)', 'Admin\PenghuniController::checkout/$1');

    // Tagihan
    $routes->get('tagihan', 'Admin\TagihanController::index');
    $routes->get('tagihan/new', 'Admin\TagihanController::new');
    $routes->post('tagihan', 'Admin\TagihanController::create');
    $routes->get('tagihan/edit/(:num)', 'Admin\TagihanController::edit/$1');
    $routes->post('tagihan/update/(:num)', 'Admin\TagihanController::update/$1');
    $routes->post('tagihan/delete/(:num)', 'Admin\TagihanController::delete/$1');
    $routes->post('tagihan/generate-massal', 'Admin\TagihanController::generateMassal');

    // Pembayaran
    $routes->get('pembayaran', 'Admin\PembayaranController::index');
    $routes->get('pembayaran/new', 'Admin\PembayaranController::new');
    $routes->get('pembayaran/new/(:num)', 'Admin\PembayaranController::new/$1');
    $routes->post('pembayaran', 'Admin\PembayaranController::create');
    $routes->post('pembayaran/verifikasi/(:num)', 'Admin\PembayaranController::verifikasi/$1');

    // Keluhan
    $routes->get('keluhan', 'Admin\KeluhanController::index');
    $routes->post('keluhan/update/(:num)', 'Admin\KeluhanController::update/$1');

    // User Management
    $routes->get('user', 'Admin\UserController::index');
    $routes->get('user/new', 'Admin\UserController::new');
    $routes->post('user', 'Admin\UserController::create');
    $routes->get('user/edit/(:num)', 'Admin\UserController::edit/$1');
    $routes->post('user/update/(:num)', 'Admin\UserController::update/$1');
    $routes->post('user/delete/(:num)', 'Admin\UserController::delete/$1');
    $routes->post('user/reset-password/(:num)', 'Admin\UserController::resetPassword/$1');

    // Laporan & Cetak PDF
    $routes->get('laporan/pemasukan', 'Admin\LaporanController::pemasukan');
    $routes->get('laporan/pemasukan/pdf', 'Admin\LaporanController::pemasukanPdf');
    $routes->get('laporan/penghuni/pdf', 'Admin\LaporanController::penghuniPdf');
    $routes->get('laporan/kwitansi/(:num)', 'Admin\LaporanController::kwitansi/$1');
});

// Penghuni Routes (Protected by auth & role:penghuni)
$routes->group('penghuni', ['filter' => ['auth', 'role:penghuni']], static function ($routes) {
    $routes->get('dashboard', 'Penghuni\DashboardController::index');

    // Tagihan & Pembayaran
    $routes->get('tagihan', 'Penghuni\TagihanController::index');
    $routes->get('tagihan/bayar/(:num)', 'Penghuni\TagihanController::bayar/$1');
    $routes->post('tagihan/bayar/(:num)', 'Penghuni\TagihanController::storeBayar/$1');
    $routes->get('tagihan/kwitansi/(:num)', 'Penghuni\TagihanController::kwitansi/$1');

    // Keluhan
    $routes->get('keluhan', 'Penghuni\KeluhanController::index');
    $routes->get('keluhan/new', 'Penghuni\KeluhanController::new');
    $routes->post('keluhan', 'Penghuni\KeluhanController::create');

    // Profil & Password
    $routes->get('profil', 'Penghuni\ProfilController::index');
    $routes->post('profil/update', 'Penghuni\ProfilController::update');
    $routes->post('profil/update-password', 'Penghuni\ProfilController::updatePassword');
});
