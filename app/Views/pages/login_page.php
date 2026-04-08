<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - Mentari BPS Riau</title>
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
                            <!-- Create Organization-->
                            <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11">
                                <div class="card mt-5">
                                    <div class="card-body p-5 text-center">
                                        <div class="icons-org-create align-items-center mx-auto">
                                            <img class="img-fluid" src="<?= base_url('public/file/LogoRiauHebat.png') ?>"  style="max-width: 300px; height: auto;" />
                                        </div>
                                        <div class="h3 .text-black-75 mb-0"></div>
                                    </div>
                                    <hr class="m-0" />
                                    <div class="card-body p-5">
                                        <form>
                                            <div class="d-grid"><a class="btn btn-primary" href="<?= base_url(); ?>sso/login">Masuk</a></div>
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
    </body>
</html>
