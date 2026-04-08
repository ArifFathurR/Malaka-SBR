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
                    <div class="page-header-subtitle">SURVEY SHP</div>
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
        Perkenalkan, Saya Asep Riyadi, S.Si.,M.M., Kepala BPS Provinsi Riau. Saya sedang melanjutkan studi di Program Doktor Ilmu Manajemen (DIM) Universitas Pasundan Bandung. 
        Saat ini, saya sedang melakukan penelitian untuk mendapatkan gelar Doktor dengan penelitian mengenai PENGARUH KEPEMIMPINAN MELAYANI, 
        MODAL INTELEKTUAL DAN BUDAYA KERJA TERHADAP KOMITMEN YANG BERIMPLIKASI PADA KINERJA KEPALA BPS KABUPATEN / KOTA SE INDONESIA. 
        <br>
        Anda merupakan responden terpilih dalam penelitian ini. Oleh karena itu, saya sangat mengharapkan anda dapat mengisi kuesioner berikut dengan jujur. 
        Kerahasiaan responden akan sangat dijaga dalam penelitian kali ini. Saya Ucapkan terima kasih banyak atas kesediaan dan partisipasi anda dalam penelitian ini.
        <br><br>
                <p style="float: right;"><b>Gunakan PC/Laptop untuk tampilan pengisian terbaik.</b></p>

                <br><br>
                <a href="<?= base_url('surveyshp/isi_survey_shp') ?>" class="btn btn-success" type="button" style="float: right; margin-right: 20px;">Lanjut</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>