<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
    <div class="container-xl px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="layout"></i></div>
                        BPS RIAU HEBAT
                    </h1>
                    <div class="page-header-subtitle">Profesional, Integritas, dan Amanah</div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main page content-->
<div class="container-xl px-4">
    <div class="card mt-n10">
        <!-- Icon Card Example -->
            <div class="card card-icon">
                <div class="row no-gutters">
                    <div class="col-auto card-icon-aside bg-success">
                        <i data-feather="layers"></i>
                    </div>
                    <div class="col">
                        <div class="card-body py-5">
                            <h5 class="card-title">Terima Kasih, Anda telah berpartisipasi!</h5>
                        </div>
                        <a class="nav-link" href="<?= base_url('surveyshp/editData/' . session()->get('id_user')) ?>">
                            <button class="btn btn-prev btn-secondary me-2 my-1"  style="margin-left: 20px;" type="button">Edit Data</button>
                        </a><br><br>

                    </div>
                </div>
            </div
    </div>
</div>

<?= $this->endSection() ?>