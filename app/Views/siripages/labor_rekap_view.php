<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="file-text"></i></div>
                        Rekapitulasi Labor (Dokumen P)
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4 mt-4">

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link <?= ($tab == '1' ? 'active' : '') ?>" data-bs-toggle="tab" href="#desa">Rekap Desa</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($tab == '2' ? 'active' : '') ?>" data-bs-toggle="tab" href="#sls">Rekap SLS</a>
    </li>
</ul>


<div class="tab-content mt-3">

<!-- ============================ -->
<!--          TAB 1               -->
<!-- ============================ -->
<div class="tab-pane fade <?= ($tab == '1' ? 'show active' : '') ?>" id="desa">

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-bordered table-striped table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Kode Desa</th>
                        <th>Nama Desa</th>
                        <th>Jumlah Usaha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rekapDesa as $row): ?>
                    <tr>
                        <td><?= esc($row['iddesa']) ?></td>
                        <td><?= esc($desaList[$row['iddesa']] ?? '-') ?></td>
                        <td><?= esc($row['jml_usaha']) ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<!-- ============================ -->
<!--          TAB 2               -->
<!-- ============================ -->
<div class="tab-pane fade <?= ($tab == '2' ? 'show active' : '') ?>" id="sls">

    <form method="get" class="row mb-4">
        <input type="hidden" name="tab" value="2">
        <div class="col-md-5">
            <label>Pilih Desa</label>
            <select name="iddesa" class="form-select" onchange="this.form.submit()">
                <option value="">-- Pilih Desa --</option>
                <?php foreach ($desaList as $id => $nama): ?>
                <option value="<?= esc($id) ?>" <?= ($iddesa == $id ? 'selected' : '') ?>>
                    <?= esc($nama) ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
    </form>

    <?php if ($iddesa): ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode SLS</th>
                        <th>Nama SLS</th>
                        <th>Jumlah Usaha</th>
                        <th>Validasi Nomor Urut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rekapSls as $row): ?>
                    <tr>
                        <td><?= esc($row['kdsls']) ?></td>
                        <td><?= esc($row['nmsls']) ?></td>
                        <td><?= esc($row['jml_usaha']) ?></td>
                        <td class="<?= $row['warning'] ? 'text-danger fw-bold' : 'text-success' ?>">
                            <?= $row['warning'] ?: 'Lengkap' ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

        </div>
    </div>

    <?php endif; ?>

</div>

</div>
</div>

<?= $this->endSection() ?>
