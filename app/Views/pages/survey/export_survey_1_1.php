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
                    <div class="page-header-subtitle">SURVEI TAHUNAN TERINTEGRASI BPS PROVINSI RIAU</div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main page content-->
<div class="container-xl px-4">
    <div class="card mt-n10">
        <div class="card-header">Export Data</div>
        <div class="card-body">
                Klik Disini <br>
                <a href="<?= base_url('surveyshp/proses_export') ?>" class="btn btn-success" type="button" style="float: left;">Export Data</a>
        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-header border-bottom">
            <ul class="nav nav-tabs card-header-tabs" id="cardTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="sudah-tab" href="#sudah" data-bs-toggle="tab" role="tab" aria-controls="sudah" aria-selected="true">Sudah Isi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="belum-tab" href="#belum" data-bs-toggle="tab" role="tab" aria-controls="belum" aria-selected="false">Belum isi</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="cardTabContent">
                <div class="tab-pane fade show active" id="sudah" role="tabpanel" aria-labelledby="sudah-tab">
                    <table id="datatablesSimple" class="table datatable-export">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>IDKab</th>
                                <th>Nama Pegawai</th>
                                <th>NIP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users_sudah)): ?>
                                <?php $nomor = 1; // Inisialisasi nomor ?>
                                <?php foreach ($users_sudah as $user): ?>
                                    <tr>
                                        <td><?= $nomor++; ?></td>
                                        <td><?= esc($user['idkab']) ?></td>
                                        <td><?= esc($user['nama_user']) ?></td>
                                        <td><?= esc($user['id_user']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">---</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="tab-pane fade" id="belum" role="tabpanel" aria-labelledby="belum-tab">
                    <table id="datatablesSimple" class="table datatable-export">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>IDKab</th>
                                <th>Nama Pegawai</th>
                                <th>NIP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users_belum)): ?>
                                <?php $nomor = 1; // Inisialisasi nomor ?>
                                <?php foreach ($users_belum as $user): ?>
                                    <tr>
                                        <td><?= $nomor++; ?></td>
                                        <td><?= esc($user['idkab']) ?></td>
                                        <td><?= esc($user['nama_user']) ?></td>
                                        <td><?= esc($user['id_user']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Mantap, sudah isi semua!</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>