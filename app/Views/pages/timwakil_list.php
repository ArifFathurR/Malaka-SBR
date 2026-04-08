<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>


<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="layers"></i></div>
                        Master Tim Kerja Provinsi
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4">

    <!-- Dropdown Filter Tim -->
    <form method="get" action="<?= base_url('mastercontroller/read_subtim') ?>">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="id_tim" class="form-label">Pilih Tim</label>
                <select name="id_tim" id="id_tim" class="form-control select2" onchange="this.form.submit()" required>
                    <option value="">-- Semua Tim --</option>
                    <?php foreach ($all_teams as $team): ?>
                        <option value="<?= esc($team['id_tim']) ?>" 
                            <?= isset($selected_team) && $selected_team == $team['id_tim'] ? 'selected' : '' ?>>
                            <?= esc($team['nama_tim']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

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
                        <th>Nama Subtim</th>
                        <th>Nama PJ</th>
                        <th>Anggota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($timwakil as $tim): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($tim['nama_tim_wakil']) ?></td>
                        <td><?= esc($tim['pj_tim'] ?? 'Tidak ada PJ') ?></td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="loadAnggota(<?= esc($tim['id_tim_wakil']) ?>)">
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Anggota</th>
                        </tr>
                    </thead>
                    <tbody id="anggotaContent">
                        <!-- Data dari AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function loadAnggota(id_tim_wakil) {
        fetch("<?= base_url('mastercontroller/read_anggota/') ?>" + id_tim_wakil)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    let rows = "";
                    let nomor = 1;
                    if (data.anggota.length > 0) {
                        data.anggota.forEach(row => {
                            rows += `<tr>
                                        <td>${nomor++}</td>
                                        <td>${row.nama_user}</td>
                                     </tr>`;
                        });
                    } else {
                        rows = `<tr><td colspan="2" class="text-center">Tidak ada anggota</td></tr>`;
                    }
                    document.getElementById("anggotaContent").innerHTML = rows;
                    new bootstrap.Modal(document.getElementById("anggotaModal")).show();
                } else {
                    alert("Gagal mengambil data anggota!");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Terjadi kesalahan saat mengambil data!");
            });
    }
</script>

<?= $this->endSection() ?>
