<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Daftar Akun - Mentari BPS Riau</title>
        <link href="<?= base_url('public/sbadmin/dist/css/styles.css') ?>" rel="stylesheet">
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary" style="background: url('<?= base_url('public/file/background_outside.jpg') ?>') no-repeat center center fixed; background-size: cover;">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container-xl px-4">
                        <div class="row justify-content-center">
                            <div class="col-xl-6 col-lg-7 col-md-9 col-sm-11">
                                <div class="card mt-5 mb-5">
                                    <div class="card-body">
                                        <p class="login-box-msg fw-bold mb-1">Daftar Akun Mitra</p>
                                        <p class="text-muted text-center small mb-3">Isi data berikut untuk membuat akun baru</p>

                                        <?php if (session()->getFlashdata('error')): ?>
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <?= session()->getFlashdata('error') ?>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (session()->getFlashdata('success')): ?>
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <?= session()->getFlashdata('success') ?>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        <?php endif; ?>

                                        <form action="<?= base_url('do/register') ?>" method="post">

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">SOBAT ID <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                    <input type="text" class="form-control" placeholder="Masukkan SOBAT ID" name="id_siri_user" value="<?= old('id_siri_user') ?>" required>
                                                </div>
                                                <div class="form-text text-muted">SOBAT ID adalah nomor identitas mitra statistik</div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    <input type="text" class="form-control" placeholder="Masukkan nama lengkap" name="nama_user" value="<?= old('nama_user') ?>" required>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Kabupaten/Kota <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                    <select class="form-select" name="idkab" required>
                                                        <option value="">-- Pilih Kabupaten/Kota --</option>
                                                        <?php foreach ($kabupaten as $kab): ?>
                                                            <option value="<?= $kab['idkab'] ?>" <?= old('idkab') == $kab['idkab'] ? 'selected' : '' ?>>
                                                                <?= esc($kab['nmkab']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">No. HP / WhatsApp</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    <input type="text" class="form-control" placeholder="Contoh: 08123456789" name="hp" value="<?= old('hp') ?>">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Email</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    <input type="email" class="form-control" placeholder="Contoh: nama@email.com" name="email" value="<?= old('email') ?>">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                    <input type="password" class="form-control" placeholder="Buat password" name="password" id="password" required>
                                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                        <i class="fas fa-eye" id="eyeIcon"></i>
                                                    </button>
                                                </div>
                                                <div class="form-text text-muted">Minimal 6 karakter</div>
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label small fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                    <input type="password" class="form-control" placeholder="Ulangi password" name="konfirmasi_password" id="konfirmasiPassword" required>
                                                </div>
                                            </div>

                                            <div class="d-grid mb-3">
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    <i class="fas fa-user-plus me-1"></i> Daftar Sekarang
                                                </button>
                                            </div>

                                            <hr>

                                            <div class="text-center">
                                                <span class="small text-muted">Sudah punya akun? </span>
                                                <a href="<?= base_url('homelogin/mitra') ?>" class="small fw-semibold">Masuk di sini</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="footer-admin mt-auto footer-light">
                    <div class="container-xl px-4">
                        <div class="row">
                            <div class="col-md-6 small">Copyright &copy; BPS Provinsi Riau 2025</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('public/sbadmin/dist/js/scripts.js') ?>"></script>
        <script>
            // Toggle show/hide password
            document.getElementById('togglePassword').addEventListener('click', function () {
                const pwInput = document.getElementById('password');
                const eyeIcon = document.getElementById('eyeIcon');
                if (pwInput.type === 'password') {
                    pwInput.type = 'text';
                    eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    pwInput.type = 'password';
                    eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });

            // Validasi konfirmasi password di sisi client
            document.querySelector('form').addEventListener('submit', function (e) {
                const pw = document.getElementById('password').value;
                const kpw = document.getElementById('konfirmasiPassword').value;
                if (pw.length < 6) {
                    e.preventDefault();
                    alert('Password minimal 6 karakter!');
                    return;
                }
                if (pw !== kpw) {
                    e.preventDefault();
                    alert('Password dan konfirmasi password tidak cocok!');
                    return;
                }
            });
        </script>
    </body>
</html>
