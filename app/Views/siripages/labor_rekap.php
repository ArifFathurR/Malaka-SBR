<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="file-text"></i></div>
                        Rekapitulasi Labor (Dokumen Prelist L)
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4 mt-4">

    <form method="get" class="row mb-4">
        <div class="col-md-5">
            <label class="fw-semibold">Pilih Desa</label>
            <select name="iddesa" class="form-select" onchange="this.form.submit()">
                <option value="">-- Pilih Desa --</option>
                <?php foreach ($filteredDesa as $id => $nama): ?>
                    <option value="<?= esc($id) ?>" <?= $iddesa == $id ? 'selected' : '' ?>>
                        <?= esc($nama) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
    
    <?php if ($iddesa && !empty($rekapSls)): ?>
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:120px">Kode SLS</th>
                            <th>Nama SLS</th>
                            <th style="width:160px" class="text-center">Total Usaha</th>
                            <th style="width:160px" class="text-center text-success">Sudah Update</th>
                            <th style="width:160px" class="text-center text-danger">Belum Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rekapSls as $r): ?>
                        <tr>
                            <td><?= esc($r['kdsls']) ?></td>
                            <td>
                                <?php
                                    $labelSls = $r['kdsls'] ? $r['nmsls'] : 'Belum masuk SLS manapun';
                                    echo esc($labelSls);
                                ?>
                            </td>
                            <td class="text-center fw-bold"><?= esc($r['total_usaha']) ?></td>
                            <td class="text-center text-success fw-bold"><?= esc($r['sudah_update']) ?></td>
                            <td class="text-center text-danger fw-bold"><?= esc($r['belum_update']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>


<?= $this->endSection() ?>
