<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif;
            color: #333;
        }
        .hero-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #fff;
            padding: 90px 0 70px 0;
            position: relative;
        }
        .room-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.06);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            overflow: hidden;
            height: 100%;
        }
        .room-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }
        .price-tag {
            font-size: 1.4rem;
            font-weight: 700;
            color: #007bff;
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(0,123,255,0.1);
            color: #007bff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-3" style="background-color: #1a2a44 !important;">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url() ?>">
            <i class="fas fa-home text-warning mr-2"></i> Kost<span class="text-warning">Ku</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active"><a class="nav-link" href="#beranda">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#kamar-kosong">Kamar Tersedia</a></li>
                <li class="nav-item"><a class="nav-link" href="#fasilitas">Fasilitas</a></li>
                <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
            </ul>
            <div class="navbar-nav ml-auto">
                <?php if (session()->get('logged_in')) : ?>
                    <a href="<?= base_url(session()->get('role') === 'admin' ? 'admin/dashboard' : 'penghuni/dashboard') ?>" class="btn btn-warning px-4 font-weight-bold">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard Saya (<?= esc(session()->get('nama_lengkap')) ?>)
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="btn btn-primary px-4 font-weight-bold">
                        <i class="fas fa-sign-in-alt mr-1"></i> Masuk / Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="beranda" class="hero-section text-center text-md-left">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge badge-warning px-3 py-2 font-weight-bold mb-3 text-uppercase">
                    <i class="fas fa-shield-alt mr-1"></i> Hunian Nyaman, Bersih & Strategis
                </span>
                <h1 class="display-4 font-weight-bold mb-3">Selamat Datang di Kost<span class="text-warning">Ku</span></h1>
                <p class="lead mb-4 text-light">
                    Solusi sewa kos dan kontrakan modern dengan fasilitas lengkap, lingkungan aman kondusif, dan kemudahan manajemen tagihan digital serta layanan penghuni 24/7.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="#kamar-kosong" class="btn btn-warning btn-lg font-weight-bold mr-3 mb-2">
                        <i class="fas fa-door-open mr-1"></i> Lihat Kamar Kosong (<?= $totalTersedia ?> Unit)
                    </a>
                    <a href="<?= base_url('login') ?>" class="btn btn-outline-light btn-lg font-weight-bold mb-2">
                        <i class="fas fa-user-circle mr-1"></i> Login Penghuni / Pemilik
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center mt-5 mt-lg-0">
                <div class="p-4 bg-white text-dark rounded-lg shadow-lg">
                    <i class="fas fa-building fa-4x text-primary mb-3"></i>
                    <h4 class="font-weight-bold">Status Hunian Saat Ini</h4>
                    <p class="text-muted text-sm">Update langsung secara realtime dari sistem KostKu</p>
                    <div class="row text-center mt-3">
                        <div class="col-6 border-right">
                            <h2 class="font-weight-bold text-success mb-0"><?= $totalTersedia ?></h2>
                            <small class="text-muted text-uppercase font-weight-bold">Kamar Siap Huni</small>
                        </div>
                        <div class="col-6">
                            <h2 class="font-weight-bold text-primary mb-0"><?= count($semuaTipe) ?></h2>
                            <small class="text-muted text-uppercase font-weight-bold">Pilihan Tipe Kamar</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Keunggulan Fasilitas -->
<section id="fasilitas" class="py-5 bg-light">
    <div class="container text-center">
        <h6 class="text-primary font-weight-bold text-uppercase">Kenapa Memilih KostKu?</h6>
        <h2 class="font-weight-bold mb-5">Fasilitas Utama Yang Anda Dapatkan</h2>

        <div class="row">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="feature-icon"><i class="fas fa-wifi"></i></div>
                <h5 class="font-weight-bold">WiFi Cepat 50 Mbps</h5>
                <p class="text-muted text-sm">Koneksi internet stabil untuk kuliah online, streaming, maupun work from home.</p>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="feature-icon"><i class="fas fa-video"></i></div>
                <h5 class="font-weight-bold">CCTV & Keamanan 24 Jam</h5>
                <p class="text-muted text-sm">Keamanan terpantau dengan kartu akses pintu utama dan pengawasan lingkungan kos.</p>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="feature-icon"><i class="fas fa-utensils"></i></div>
                <h5 class="font-weight-bold">Dapur & Kulkas Bersama</h5>
                <p class="text-muted text-sm">Fasilitas memasak lengkap dengan kompor gas, air mineral galon gratis, dan kulkas.</p>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="feature-icon"><i class="fas fa-headset"></i></div>
                <h5 class="font-weight-bold">Layanan Perawatan Cepat</h5>
                <p class="text-muted text-sm">Sistem lapor kerusakan langsung dari akun penghuni dengan penanganan teknisi sigap.</p>
            </div>
        </div>
    </div>
</section>

<!-- Daftar Kamar Tersedia -->
<section id="kamar-kosong" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-primary font-weight-bold text-uppercase">Info Unit Kosong</h6>
            <h2 class="font-weight-bold">Daftar Kamar Yang Sedang Tersedia</h2>
            <p class="text-muted">Pilih kamar idaman Anda dan hubungi kami segera sebelum kehabisan!</p>
        </div>

        <?php if (empty($kamarTersedia)) : ?>
            <div class="alert alert-info text-center py-4">
                <h5><i class="fas fa-info-circle mr-2"></i> Mohon Maaf, Semua Kamar Sedang Terisi Penuh</h5>
                <p class="mb-0">Silakan hubungi kontak pengelola untuk masuk ke daftar tunggu (waiting list) periode berikutnya.</p>
            </div>
        <?php else : ?>
            <div class="row">
                <?php foreach ($kamarTersedia as $k) : ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card room-card">
                            <?php if (! empty($k['foto']) && file_exists(FCPATH . 'uploads/kamar/' . $k['foto'])) : ?>
                                <img src="<?= base_url('uploads/kamar/' . $k['foto']) ?>" class="card-img-top" style="height: 220px; object-fit: cover;" alt="Kamar <?= esc($k['nomor_kamar']) ?>">
                            <?php else : ?>
                                <div class="bg-secondary text-white text-center py-5 d-flex align-items-center justify-content-center" style="height: 220px;">
                                    <div>
                                        <i class="fas fa-door-open fa-3x mb-2 text-warning"></i>
                                        <div>Foto Kamar Segera Hadir</div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Tersedia</span>
                                    <span class="badge badge-light border text-muted"><i class="fas fa-layer-group mr-1"></i> <?= esc($k['lantai']) ?></span>
                                </div>
                                <h4 class="card-title font-weight-bold mb-1">Kamar <?= esc($k['nomor_kamar']) ?></h4>
                                <h6 class="text-primary font-weight-bold mb-3"><?= esc($k['nama_tipe']) ?></h6>
                                
                                <div class="price-tag mb-3">
                                    Rp <?= number_format($k['harga_bulanan'], 0, ',', '.') ?>
                                    <small class="text-muted font-weight-normal text-sm">/ bulan</small>
                                </div>

                                <div class="mb-3">
                                    <strong class="text-sm text-dark d-block mb-1">Fasilitas Utama:</strong>
                                    <p class="text-muted text-sm mb-0">
                                        <?= esc($k['fasilitas']) ?>
                                    </p>
                                </div>

                                <div class="mt-auto pt-3 border-top">
                                    <a href="https://wa.me/6281234567890?text=Halo%20Pengelola%20KostKu,%20saya%20tertarik%20dengan%20Kamar%20<?= urlencode($k['nomor_kamar']) ?>%20tipe%20<?= urlencode($k['nama_tipe']) ?>.%20Apakah%20masih%20tersedia?" target="_blank" class="btn btn-success btn-block font-weight-bold">
                                        <i class="fab fa-whatsapp mr-1"></i> Pesan / Tanya Kamar Ini
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
<footer id="kontak" class="bg-dark text-white py-5" style="background-color: #111a29 !important;">
    <div class="container">
        <div class="row">
            <div class="col-md-5 mb-4 mb-md-0">
                <h4 class="font-weight-bold text-warning mb-3"><i class="fas fa-home mr-2"></i> KostKu</h4>
                <p class="text-muted">
                    Sistem Informasi Manajemen Kos & Kontrakan terpadu yang mempermudah calon penghuni menemukan hunian nyaman serta memfasilitasi administrasi sewa, tagihan, dan laporan perawatan kamar.
                </p>
                <div class="text-muted">
                    <p class="mb-1"><i class="fas fa-map-marker-alt text-warning mr-2"></i> Jl. Terusan Telekomunikasi No. 1219, Bandung</p>
                    <p class="mb-1"><i class="fab fa-whatsapp text-warning mr-2"></i> 0812-3456-7890 (Pengelola KostKu)</p>
                    <p class="mb-0"><i class="fas fa-envelope text-warning mr-2"></i> admin@kostku.com</p>
                </div>
            </div>
            <div class="col-md-3 mb-4 mb-md-0">
                <h5 class="font-weight-bold mb-3">Tautan Cepat</h5>
                <ul class="list-unstyled text-muted">
                    <li class="mb-2"><a href="#beranda" class="text-light">Beranda</a></li>
                    <li class="mb-2"><a href="#kamar-kosong" class="text-light">Kamar Tersedia</a></li>
                    <li class="mb-2"><a href="#fasilitas" class="text-light">Fasilitas Kos</a></li>
                    <li class="mb-2"><a href="<?= base_url('login') ?>" class="text-warning font-weight-bold">Portal Login</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5 class="font-weight-bold mb-3">Portal Penghuni & Admin</h5>
                <p class="text-muted">Sudah menjadi penyewa di KostKu? Masuk ke portal untuk mengecek tagihan dan layanan kamar.</p>
                <a href="<?= base_url('login') ?>" class="btn btn-outline-warning btn-block font-weight-bold py-2">
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk ke Akun Anda
                </a>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <div class="text-center text-muted text-sm">
            &copy; <?= date('Y') ?> <strong>KostKu</strong>. Hak Cipta Dilindungi Undang-Undang. Tugas PJBL Blok Sistem Informasi.
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
