<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - KostKu</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- AdminLTE 3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        body.login-page {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
        }
        .login-box {
            width: 440px;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .demo-card {
            background-color: #f8f9fa;
            border: 1px dashed #ced4da;
            border-radius: 0.5rem;
            padding: 10px 15px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .demo-card:hover {
            background-color: #e9ecef;
            border-color: #007bff;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box my-4">
    <!-- Card Login -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center bg-white py-4">
            <a href="<?= base_url() ?>" class="h1 font-weight-bold text-dark text-decoration-none">
                <i class="fas fa-home text-warning mr-1"></i> Kost<span class="text-primary">Ku</span>
            </a>
            <p class="text-muted text-sm mt-1 mb-0">Sistem Informasi Manajemen Kos & Kontrakan</p>
        </div>
        <div class="card-body login-card-body p-4">
            <p class="login-box-msg font-weight-bold text-secondary mb-3">Silakan Masuk ke Akun Anda</p>

            <form action="<?= base_url('login') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group mb-3">
                    <label class="text-sm font-weight-bold">Username atau Email</label>
                    <div class="input-group">
                        <input type="text" name="login" id="login_input" class="form-control" placeholder="Masukkan username/email" value="<?= old('login') ?>" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope text-primary"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="text-sm font-weight-bold">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password_input" class="form-control" placeholder="Masukkan password" required>
                        <div class="input-group-append">
                            <div class="input-group-text" style="cursor: pointer;" onclick="togglePassword()">
                                <span class="fas fa-eye" id="toggle_icon"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold">
                            <i class="fas fa-sign-in-alt mr-1"></i> MASUK SEKARANG
                        </button>
                    </div>
                </div>
            </form>

            <hr class="my-4">

            <!-- Demo Account Helper -->
            <div class="text-center mb-2">
                <span class="badge badge-light border text-muted px-2 py-1"><i class="fas fa-key mr-1"></i> Akun Demo Siap Uji (Klik untuk Isi Cepat)</span>
            </div>

            <div class="row">
                <div class="col-6 pr-1">
                    <div class="demo-card text-center" onclick="fillLogin('admin@kostku.com', 'admin123')">
                        <span class="badge badge-danger text-uppercase mb-1">Admin (Owner)</span>
                        <div class="font-weight-bold text-dark">admin@kostku.com</div>
                        <small class="text-muted">Pass: admin123</small>
                    </div>
                </div>
                <div class="col-6 pl-1">
                    <div class="demo-card text-center" onclick="fillLogin('budi@kostku.com', 'penghuni123')">
                        <span class="badge badge-success text-uppercase mb-1">Penghuni (Budi)</span>
                        <div class="font-weight-bold text-dark">budi@kostku.com</div>
                        <small class="text-muted">Pass: penghuni123</small>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="<?= base_url() ?>" class="text-sm text-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Halaman Depan
                </a>
            </div>
        </div>
        <!-- /.login-card-body -->
    </footer>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function togglePassword() {
        const pass = document.getElementById('password_input');
        const icon = document.getElementById('toggle_icon');
        if (pass.type === 'password') {
            pass.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            pass.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function fillLogin(user, pass) {
        document.getElementById('login_input').value = user;
        document.getElementById('password_input').value = pass;
        Swal.fire({
            icon: 'info',
            title: 'Kredensial Terisi!',
            text: 'Klik tombol MASUK SEKARANG untuk login.',
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }

    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Masuk!',
            text: '<?= addslashes(session()->getFlashdata('error')) ?>',
            confirmButtonColor: '#d33'
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= addslashes(session()->getFlashdata('success')) ?>',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    <?php endif; ?>
</script>
</body>
</html>
