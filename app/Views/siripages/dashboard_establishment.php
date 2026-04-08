<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="bar-chart-2"></i></div>
                        Dashboard Usaha
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">% Usaha Terupdate: <?= $persentase_usaha ?>%</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('usaha/detail_update_kabupaten') ?>">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Jumlah User Update: <?= $jumlah_user ?></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('usaha/detail_user_updater') ?>">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    Total Usaha Terupdate: <?= $jumlah_update ?><br>
                    <small class="d-block mt-1" style="font-size: 0.8em;">
                        Aktif: <?= $statusRingkasan[1] ?? 0 ?> |
                        Tidak Aktif: <?= $statusRingkasan[2] ?? 0 ?> |
                        Bukan Usaha: <?= $statusRingkasan[3] ?? 0 ?> |
                        Tidak Ditemukan: <?= $statusRingkasan[4] ?? 0 ?>
                    </small>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('usaha/detail_update_kabupaten') ?>">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">% Desa/Kel Terupdate: <?= $persentase_desa ?>%</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('usaha/detail_update_desa') ?>">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Bar -->
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-chart-bar me-1"></i> Progress Update per Kabupaten</div>
        <div class="card-body">
            <canvas id="chartKabupaten" height="100"></canvas>
        </div>
    </div>

    <!-- Tabel Terakhir Update -->
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-clock me-1"></i> 10 Update Terbaru</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Usaha</th>
                            <th>Nama Kab</th>
                            <th>Alamat</th>
                            <th>Status Usaha</th>
                            <th>Kategori</th>
                            <th>Terakhir Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latestUpdates as $row): ?>
                            <tr>
                                <td><?= esc($row['placeName']) ?></td>
                                <td><?= esc($row['nmkab']) ?></td>
                                <td><?= esc($row['placeAddress']) ?></td>
                                <td><?= esc($row['status_text']) ?></td>
                                <td><?= esc($row['kategori_text']) ?></td>
                                <td><?= esc($row['last_update_by']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartKabupaten');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [
                {
                    label: 'Total Awal',
                    backgroundColor: 'rgba(255,99,132,0.5)',
                    data: <?= json_encode($chart_total) ?>,
                },
                {
                    label: 'Sudah Update',
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    data: <?= json_encode($chart_updated) ?>,
                },
                {
                    label: 'Penambahan Baru',
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    data: <?= json_encode($chart_baru) ?>,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Rekapitulasi Usaha per Kabupaten'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Usaha'
                    }
                }
            }
        },
    });
</script>


<?= $this->endSection() ?>
