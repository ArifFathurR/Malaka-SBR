<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="layers"></i></div>
                        Data Transaksi Kegiatan
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="mb-4" style="max-width: 400px;">
        <form method="get">
            <label for="id_tim">Pilih Tim:</label>
            <select name="id_tim" id="id_tim" class="form-control select2" onchange="this.form.submit()">
                <option value="">-- Pilih Tim --</option>
                <?php foreach ($tim_list as $tim): ?>
                    <option value="<?= $tim->id_tim ?>" <?= ($selected_tim ?? '') == $tim->id_tim ? 'selected' : '' ?>>
                        <?= $tim->nama_tim ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if (!empty($transaksi_list)): ?>
        <!-- Tabel Detail Tim Wakil -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Detail Tim Wakil</h4>
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Uraian Proses</th>
                            <th>Nama Kegiatan</th>
                            <th>Target</th>
                            <th>Realisasi</th>
                            <th>Progres</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transaksi_list as $i => $row): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $row->uraian_proses ?></td>
                                <td><?= $row->nama_kegiatan_detail ?></td>
                                <td>
                                    <?= $row->target ?>
                                    <?php if ($row->target != $row->target_master): ?>
                                        <span class="text-danger">(!)</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $row->realisasi ?></td>
                                <td>
                                    <?php
                                        $progress = 0;
                                        if ($row->target > 0) {
                                            $progress = round(($row->realisasi / $row->target) * 100);
                                        }

                                        if ($progress == 100) {
                                            $class = 'bg-success';
                                        } elseif ($progress >= 80) {
                                            $class = 'bg-primary';
                                        } elseif ($progress >= 50) {
                                            $class = 'bg-warning';
                                        } else {
                                            $class = 'bg-danger';
                                        }
                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar <?= $class ?>" role="progressbar"
                                            style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <?= $progress ?>%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?= base_url('mastercontroller/wilayah_menu/' . $row->id_kegiatan_detail_proses) ?>" class="btn btn-sm btn-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
