<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'KostKu - Sistem Informasi Manajemen Kos') ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- DataTables Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- AdminLTE 3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        .brand-link {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .main-sidebar {
            box-shadow: 0 4px 20px 0 rgba(0,0,0,.08);
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .badge {
            font-size: 88%;
            font-weight: 600;
            padding: 0.4em 0.7em;
        }
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.08);
        }
        .card-header {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-navbar navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="<?= base_url() ?>" target="_blank" class="nav-link"><i class="fas fa-external-link-alt mr-1"></i> Lihat Halaman Publik</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-user-circle fa-lg text-primary mr-1"></i>
                    <span class="d-none d-md-inline font-weight-bold"><?= esc(session()->get('nama_lengkap')) ?></span>
                    <span class="badge badge-<?= session()->get('role') === 'admin' ? 'danger' : 'success' ?> ml-1 text-uppercase">
                        <?= esc(session()->get('role')) ?>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <li class="user-header bg-primary">
                        <i class="fas fa-user-circle fa-4x text-white mb-2"></i>
                        <p>
                            <?= esc(session()->get('nama_lengkap')) ?>
                            <small><?= esc(session()->get('email')) ?> (<?= ucfirst(session()->get('role')) ?>)</small>
                        </p>
                    </li>
                    <li class="user-footer d-flex justify-content-between">
                        <?php if (session()->get('role') === 'penghuni') : ?>
                            <a href="<?= base_url('penghuni/profil') ?>" class="btn btn-default btn-flat"><i class="fas fa-id-card mr-1"></i> Profil</a>
                        <?php else: ?>
                            <a href="<?= base_url('admin/user') ?>" class="btn btn-default btn-flat"><i class="fas fa-users-cog mr-1"></i> Kelola User</a>
                        <?php endif; ?>
                        <a href="<?= base_url('logout') ?>" class="btn btn-danger btn-flat"><i class="fas fa-sign-out-alt mr-1"></i> Logout</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="<?= base_url(session()->get('role') === 'admin' ? 'admin/dashboard' : 'penghuni/dashboard') ?>" class="brand-link text-center">
            <i class="fas fa-home fa-lg text-warning mr-1"></i>
            <span class="brand-text font-weight-bold">Kost<span class="text-warning">Ku</span></span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
                <div class="image">
                    <i class="fas fa-user-shield fa-2x text-light"></i>
                </div>
                <div class="info">
                    <a href="#" class="d-block font-weight-bold text-white"><?= esc(session()->get('nama_lengkap')) ?></a>
                    <small class="badge badge-<?= session()->get('role') === 'admin' ? 'danger' : 'success' ?>">
                        <?= strtoupper(session()->get('role')) ?>
                    </small>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                    
                    <?php if (session()->get('role') === 'admin') : ?>
                        <!-- ================= ADMIN MENU ================= -->
                        <li class="nav-item">
                            <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= uri_string() === 'admin/dashboard' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-header text-uppercase font-weight-bold text-muted">DATA MASTER</li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/tipe-kamar') ?>" class="nav-link <?= strpos(uri_string(), 'admin/tipe-kamar') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Tipe Kamar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/kamar') ?>" class="nav-link <?= strpos(uri_string(), 'admin/kamar') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-door-open"></i>
                                <p>Data Kamar</p>
                            </a>
                        </li>

                        <li class="nav-header text-uppercase font-weight-bold text-muted">TRANSAKSI KOS</li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/penghuni') ?>" class="nav-link <?= strpos(uri_string(), 'admin/penghuni') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-user-friends"></i>
                                <p>Data Penghuni</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/tagihan') ?>" class="nav-link <?= strpos(uri_string(), 'admin/tagihan') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>Tagihan Bulanan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/pembayaran') ?>" class="nav-link <?= strpos(uri_string(), 'admin/pembayaran') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-money-check-alt"></i>
                                <p>Pembayaran & Verif</p>
                            </a>
                        </li>

                        <li class="nav-header text-uppercase font-weight-bold text-muted">LAYANAN & PERAWATAN</li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/keluhan') ?>" class="nav-link <?= strpos(uri_string(), 'admin/keluhan') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tools"></i>
                                <p>Keluhan Fasilitas</p>
                            </a>
                        </li>

                        <li class="nav-header text-uppercase font-weight-bold text-muted">LAPORAN & CETAK PDF</li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/laporan/pemasukan') ?>" class="nav-link <?= strpos(uri_string(), 'admin/laporan/pemasukan') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Laporan Pemasukan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/laporan/penghuni/pdf') ?>" target="_blank" class="nav-link">
                                <i class="nav-icon fas fa-file-pdf text-danger"></i>
                                <p>Rekap Kamar & Penghuni</p>
                            </a>
                        </li>

                        <li class="nav-header text-uppercase font-weight-bold text-muted">PENGATURAN</li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/user') ?>" class="nav-link <?= strpos(uri_string(), 'admin/user') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>Manajemen User</p>
                            </a>
                        </li>

                    <?php else: ?>
                        <!-- ================= PENGHUNI MENU ================= -->
                        <li class="nav-item">
                            <a href="<?= base_url('penghuni/dashboard') ?>" class="nav-link <?= uri_string() === 'penghuni/dashboard' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard Saya</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('penghuni/tagihan') ?>" class="nav-link <?= strpos(uri_string(), 'penghuni/tagihan') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>Tagihan & Pembayaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('penghuni/keluhan') ?>" class="nav-link <?= strpos(uri_string(), 'penghuni/keluhan') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tools"></i>
                                <p>Lapor Keluhan Kamar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('penghuni/profil') ?>" class="nav-link <?= strpos(uri_string(), 'penghuni/profil') !== false ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-user-cog"></i>
                                <p>Profil & Password</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item mt-3 pt-3 border-top border-secondary">
                        <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p class="font-weight-bold">Logout</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 font-weight-bold text-dark"><?= esc($title ?? 'KostKu') ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url() ?>">KostKu</a></li>
                            <li class="breadcrumb-item active"><?= esc($title ?? 'Dashboard') ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid pb-4">
                <?= $this->renderSection('content') ?>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer text-sm">
        <div class="float-right d-none d-sm-inline">
            <b>Versi</b> 1.0.0 (PJBL CodeIgniter 4)
        </div>
        <strong>&copy; <?= date('Y') ?> <a href="<?= base_url() ?>">KostKu</a>.</strong> Sistem Informasi Manajemen Kos & Kontrakan.
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables & Plugins -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
    // Inisialisasi DataTable Default dengan Bahasa Indonesia
    $(function () {
        if ($('.dataTable-standard').length) {
            $('.dataTable-standard').DataTable({
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "search": "Cari Data:",
                    "lengthMenu": "Tampilkan _MENU_ entri per halaman",
                    "zeroRecords": "Tidak ada data yang cocok",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "infoFiltered": "(difilter dari _MAX_ total entri)",
                    "paginate": {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        }
    });

    // Flashdata Notification via SweetAlert2
    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= addslashes(session()->getFlashdata('success')) ?>',
            timer: 3500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Perhatian / Gagal!',
            text: '<?= addslashes(session()->getFlashdata('error')) ?>',
            confirmButtonColor: '#d33'
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('warning')) : ?>
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: '<?= addslashes(session()->getFlashdata('warning')) ?>',
            confirmButtonColor: '#f39c12'
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('info')) : ?>
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: '<?= addslashes(session()->getFlashdata('info')) ?>',
            confirmButtonColor: '#17a2b8'
        });
    <?php endif; ?>

    // Global SweetAlert konfirmasi delete
    $(document).on('click', '.btn-delete-confirm', function (e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const message = $(this).data('message') || 'Data ini akan dihapus secara permanen!';
        
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Global SweetAlert konfirmasi checkout
    $(document).on('click', '.btn-checkout-confirm', function (e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const nama = $(this).data('nama') || 'penghuni';
        
        Swal.fire({
            title: 'Konfirmasi Check-out',
            text: `Proses check-out untuk ${nama}? Status kamar akan otomatis kembali menjadi 'Tersedia'.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-sign-out-alt"></i> Ya, Check-out!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
