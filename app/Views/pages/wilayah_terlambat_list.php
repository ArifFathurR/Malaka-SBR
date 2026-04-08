<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="layers"></i></div>
                        Data Kegiatan Terlambat dari Jadwal Selesai
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4">

    <div class="card mb-4">
        <div class="card-body">
            <table id="datatablesSimple" class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Proses</th>
                        <th>Tim</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Target</th>
                        <th>Realisasi</th>
                        <th>Progres</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($kegiatanTerlambat as $row): 
                        $target = floatval($row['target']);
                        $realisasi = floatval($row['realisasi']);
                        $progres = ($target > 0) ? round(($realisasi / $target) * 100) : 0;
                        $progres = min($progres, 100);
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['uraian_proses'] ?></td>
                        <td><?= $row['nama_tim'] ?></td>
                        <td><?= date('d M Y', strtotime($row['tanggal_mulai'])) ?></td>
                        <td><?= date('d M Y', strtotime($row['tanggal_selesai'])) ?></td>
                        <td><?= $row['target'] ?></td>
                        <td><?= $row['realisasi'] ?></td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar <?= $progres >= 80 ? 'bg-success' : ($progres >= 50 ? 'bg-warning' : 'bg-danger') ?>"
                                    role="progressbar" style="width: <?= $progres ?>%;"
                                    aria-valuenow="<?= $progres ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?= $progres ?>%
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="<?= base_url('mastercontroller/wilayah_menu/' . $row['id_kegiatan_detail_proses']) ?>"
                               class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
