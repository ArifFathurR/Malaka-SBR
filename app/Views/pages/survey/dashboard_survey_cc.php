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
                    <div class="page-header-subtitle">VOTING CALON CHANGE CHAMPION BPS PROVINSI RIAU</div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main page content-->
<div class="container-xl px-4">
    <div class="card mt-n10">
        <div class="card-header">Pengantar</div>
        <div class="card-body">
                Dalam rangka Penguatan Zona Integritas, WBK, dan Menuju WBBM, BPS Provinsi Riau mengadakan seleksi Calon Change Champion 2025. <br><br>
                
                Silahkan berikan pilihan Bapak/Ibu pada Bakal Calon yang tersedia. 
                Atas partisipasi Bapak/Ibu diucapkan Terima Kasih. <br><br>
                <p style="float: right;"><b>Gunakan PC/Laptop untuk tampilan pengisian terbaik.</b></p>

                <br><br>
                <a href="<?= base_url('survey/isi_survey_1') ?>" class="btn btn-success" type="button" style="float: right; margin-right: 20px;">Lanjut</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>