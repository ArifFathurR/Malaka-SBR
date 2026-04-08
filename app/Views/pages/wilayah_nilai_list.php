<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="layers"></i></div>
                        Data Rekapitulasi Nilai
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4">
    <!-- Filter -->
    <div class="mb-3">
        <form class="form-inline">
            <select class="form-select form-select-sm d-inline w-auto" name="bulan" onchange="this.form.submit()">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>" <?= $i == $bulan ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $i, 1)) ?></option>
                <?php endfor; ?>
            </select>

            <select class="form-select form-select-sm d-inline w-auto" name="id_tim" onchange="this.form.submit()">
                <option value="">- Pilih Tim -</option>
                <?php foreach ($timList as $tim): ?>
                    <option value="<?= $tim->id_tim ?>" <?= $tim->id_tim == $id_tim ? 'selected' : '' ?>>
                        <?= $tim->nama_tim ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if ($id_tim): ?>
            <select class="form-select form-select-sm d-inline w-auto" name="uraian_proses" onchange="this.form.submit()">
                <option value="ALL">Semua Proses</option>
                <?php foreach ($prosesList as $p): ?>
                    <option value="<?= $p->uraian_proses ?>" <?= $p->uraian_proses == $uraian_proses ? 'selected' : '' ?>>
                        <?= $p->uraian_proses ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tabel Rekap -->
    <div class="card mb-4">
        <div class="card-body">
            <table id="datatablesSimple" class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Uraian Proses</th>
                        <th>Tim</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Kode Kab/Kota</th>
                        <th>Target</th>
                        <th>Realisasi</th>
                        <th>Nilai Target (max 70)</th>
                        <th>Nilai Realisasi (max 7.5)</th>
                        <th>Nilai Selesai (max 7.5)</th>
                        <th>Nilai Rating (max 15)</th>
                        <th>Total Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rekap as $row): ?>
                        <tr>
                            <td><?= $row['no'] ?></td>
                            <td><?= esc($row['uraian_proses']) ?></td>
                            <td><?= esc($row['nama_tim']) ?></td>
                            <td><?= date('d M Y', strtotime($row['tanggal_mulai'])) ?></td>
                            <td><?= date('d M Y', strtotime($row['tanggal_selesai'])) ?></td>
                            <td><?= esc($row['idkab']) ?> - <?= esc($row['nmkab']) ?></td>
                            <td><?= $row['target'] ?></td>
                            <td><?= $row['realisasi'] ?></td>
                            <td><?= $row['nilai_target'] ?></td>
                            <td><?= $row['nilai_realisasi'] ?></td>
                            <td><?= $row['nilai_selesai'] ?></td>
                            <td><?= $row['nilai_rating'] ?></td>
                            <td><strong><?= $row['total_nilai'] ?></strong></td>
                            <td>
                                <a href="<?= base_url('mastercontroller/wilayah_menu/' . $row['id_kegiatan_detail_proses']) ?>" class="btn btn-info btn-sm">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($rekap)): ?>
                        <tr><td colspan="14" class="text-center">Tidak ada data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script Filter -->
<script>
function applyFilter() {
    const bulan = document.getElementById('bulan').value;
    const tim = document.getElementById('id_tim').value;
    window.location.href = `?bulan=${bulan}&id_tim=${tim}`;
}
</script>

<?= $this->endSection() ?>
