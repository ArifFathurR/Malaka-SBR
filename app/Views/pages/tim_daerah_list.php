<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="users"></i></div>
                        Tim Kerja BPS Kabupaten/Kota
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4">
    <!-- Filter Tim -->
    <form method="get" action="<?= base_url('mastercontroller/read_tim_daerah') ?>">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="id_tim" class="form-label">Pilih Tim</label>
                <select name="id_tim" id="id_tim" class="form-control select2" onchange="this.form.submit()" required>
                    <option value="">-- Semua Tim --</option>
                    <?php foreach ($all_teams as $team): ?>
                        <option value="<?= esc($team->id_tim) ?>" <?= $selected_team == $team->id_tim ? 'selected' : '' ?>>
                            <?= esc($team->nama_tim) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

    <!-- Tabel Subtim Daerah -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Daftar Subtim per Kabupaten/Kota</h4>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Subtim</th>
                        <th>IdKabkot</th>
                        <th>PJ Kab/Kota</th>
                        <th>Anggota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($subtim_daerah as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($row['nama_tim_wakil']) ?></td>
                            <td><?= esc($row['idkab']) ?></td>
                            <td><?= esc($row['pj_user'] ?? 'Tidak Ada') ?></td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="loadAnggota(<?= $row['id_tim_wakil'] ?>, <?= $row['idkab'] ?>)">
                                    Lihat Anggota
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Anggota -->
<div class="modal fade" id="anggotaModal" tabindex="-1" aria-labelledby="anggotaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anggotaModalLabel">Daftar Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr><th>No</th><th>Nama Anggota</th></tr>
                    </thead>
                    <tbody id="anggotaContent">
                        <!-- AJAX fill -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function loadAnggota(id_tim_wakil, idkab) {
    fetch(`<?= base_url('mastercontroller/read_anggota_daerah') ?>/${id_tim_wakil}/${idkab}`)
        .then(response => response.json())
        .then(data => {
            let html = '';
            if (data.status === 'success' && data.anggota.length > 0) {
                data.anggota.forEach((user, index) => {
                    html += `<tr><td>${index + 1}</td><td>${user.nama_user}</td></tr>`;
                });
            } else {
                html = `<tr><td colspan="2" class="text-center">Tidak ada anggota</td></tr>`;
            }
            document.getElementById('anggotaContent').innerHTML = html;
            new bootstrap.Modal(document.getElementById('anggotaModal')).show();
        }).catch(error => {
            alert('Gagal mengambil data.');
            console.error(error);
        });
}
</script>

<?= $this->endSection() ?>
