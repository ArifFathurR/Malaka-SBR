<!-- Dashboard Blade Template -->

<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<!-- FullCalendar hanya untuk halaman ini -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard Kegiatan</h1>
    <div class="row">
        <!-- Card 1 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Kegiatan Sedang Berlangsung: <?= $prosesAktif ?></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('mastercontroller/wilayah_menu_berlangsung') ?>">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Presentase Realisasi: <?= $persentaseRealisasi ?> % </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white stretched-link">Terhadap Progres</span>
                </div>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">Kegiatan Terlambat: <?= $kegiatantelatSelesai ?></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('mastercontroller/wilayah_menu_terlambat') ?>">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <!-- Card 4 -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">Total Tim: <?= $jumlahTim ?></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('mastercontroller/read_tim') ?>">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-line me-1"></i>
                    Kalender Kegiatan
                </div>
                <div class="card-body" style="max-height: 440px; overflow-y: auto;">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Ringkasan Seluruh Kegiatan per Wilayah
                </div>
                <div class="card-body">
                    <form id="filterForm" class="row g-2 mb-3">
                        <div class="col-md-6">
                            <select id="bulan" class="form-select form-select-sm">
                                <?php for ($b = 1; $b <= 12; $b++): ?>
                                    <option value="<?= $b ?>" <?= ($b == $bulan) ? 'selected' : '' ?>>
                                        <?= date('F', mktime(0, 0, 0, $b, 1)) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select id="tahun" class="form-select form-select-sm">
                                <?php for ($t = 2023; $t <= date('Y'); $t++): ?>
                                    <option value="<?= $t ?>" <?= ($t == $tahun) ? 'selected' : '' ?>><?= $t ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </form>

                    <!-- Loader Spinner -->
                    <div id="loader" class="text-center mb-3" style="display: none;">
                        <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="small mt-2 text-muted">Memuat data...</div>
                    </div>

                    <!-- Chart Canvas -->
                    <div style="position: relative; height: 350px;">
                        <canvas id="wilayahChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Kegiatan -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabel Kegiatan
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Kegiatan</th>
                        <th>Proses</th>
                        <th>Tim Kerja</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Target</th>
                        <th>Realisasi</th>
                        <th>Progres</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kegiatanList as $item): 
                        $target = floatval($item['target']);
                        $realisasi = floatval($item['realisasi']);
                        $progres = ($target > 0) ? round(($realisasi / $target) * 100) : 0;
                        $progres = min($progres, 100); // jaga-jaga jika > 100
                    ?>
                        <tr>
                            <td><?= $item['nama_kegiatan_detail'] ?></td>
                            <td><?= $item['uraian_proses'] ?></td>
                            <td><?= $item['nama_tim'] ?></td>
                            <td><?= $item['tanggal_mulai'] ?></td>
                            <td><?= $item['tanggal_selesai'] ?></td>
                            <td><?= $item['target'] ?></td>
                            <td><?= $item['realisasi'] ?></td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar <?= $progres == 100 ? 'bg-success' : ($progres >= 50 ? 'bg-info' : 'bg-warning') ?>" 
                                        role="progressbar" style="width: <?= $progres ?>%;" 
                                        aria-valuenow="<?= $progres ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?= $progres ?>%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalDetail" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Kegiatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <th style="width: 30%;">Kegiatan Utama</th>
              <td id="nama_kegiatan_detail"></td>
            </tr>
            <tr>
              <th>Proses Kegiatan</th>
              <td id="uraian_proses"></td>
            </tr>
            <tr>
              <th>Tim</th>
              <td id="sub"></td>
            </tr>
            <tr>
              <th>Tanggal Mulai</th>
              <td id="start"></td>
            </tr>
            <tr>
              <th>Tanggal Selesai</th>
              <td id="end"></td>
            </tr>
            <tr>
              <th>Target</th>
              <td id="target"></td>
            </tr>
            <tr>
              <th>Realisasi</th>
              <td id="realisasi"></td>
            </tr>
            <tr>
                <th>Progress</th>
                <td>
                    <div class="progress">
                    <div id="progressBar" class="progress-bar" role="progressbar"
                        style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        0%
                    </div>
                    </div>
                </td>
            </tr>
          </tbody>
        </table>

        <!-- tombol lihat data -->
        <div class="text-end mt-3">
            <a id="btnLihatData" href="#" class="btn btn-info" target="_blank">Lihat Data</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Script Chart.js -->
<script>
    let wilayahChart;

    function initWilayahChart(data) {
        const canvas = document.getElementById('wilayahChart');
        const ctx = canvas.getContext('2d');

        if (wilayahChart) {
            wilayahChart.destroy();
        }

        wilayahChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.dataset.label}: ${context.formattedValue}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10
                        }
                    }
                }
            }
        });
    }

    window.addEventListener('load', function () {
        // ⬇️ Gunakan data dari PHP langsung
        initWilayahChart(<?= json_encode($chartWilayah) ?>);
    });

    document.getElementById('bulan').addEventListener('change', () => {
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        window.location.href = `?bulan=${bulan}&tahun=${tahun}`;
    });

    document.getElementById('tahun').addEventListener('change', () => {
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        window.location.href = `?bulan=${bulan}&tahun=${tahun}`;
    });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      height: 600,
      contentHeight: 'auto', // ✅ tinggi mengikuti isi event
      headerToolbar: {
        left: 'title',
        center: '',
        right: 'today prev,next'
      },
      events: '<?= base_url('mastercontroller/get_kegiatan_events') ?>',
      eventClick: function (info) {
        const e = info.event.extendedProps;

        // Format tanggal
        function formatTanggalIndonesia(dateStr) {
          const bulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
          ];
          const date = new Date(dateStr);
          const tgl = date.getDate();
          const bln = bulan[date.getMonth()];
          const thn = date.getFullYear();
          return `${tgl} ${bln} ${thn}`;
        }

        // Set nilai ke elemen modal
        document.getElementById('nama_kegiatan_detail').textContent = e.nama_kegiatan_detail;
        document.getElementById('uraian_proses').textContent = e.uraian_proses;
        document.getElementById('sub').textContent = e.nama_tim;
        document.getElementById('start').textContent = formatTanggalIndonesia(e.tanggal_mulai);
        document.getElementById('end').textContent = formatTanggalIndonesia(e.tanggal_selesai);
        document.getElementById('target').textContent = e.target;
        document.getElementById('realisasi').textContent = e.realisasi;

        // Hitung progress
        let persen = Math.round((e.realisasi / e.target) * 100);
        if (isNaN(persen)) persen = 0;

        const progressBar = document.getElementById('progressBar');
        progressBar.style.width = persen + '%';
        progressBar.setAttribute('aria-valuenow', persen);
        progressBar.textContent = persen + '%';

        // Ganti warna progress bar sesuai nilai
        progressBar.classList.remove('bg-success', 'bg-warning', 'bg-danger');
        if (persen >= 80) {
          progressBar.classList.add('bg-success');
        } else if (persen >= 50) {
          progressBar.classList.add('bg-warning');
        } else {
          progressBar.classList.add('bg-danger');
        }

        // Set link tombol "Lihat Data"
        const btnLink = document.getElementById('btnLihatData');
        btnLink.href = `<?= base_url('mastercontroller/wilayah_menu') ?>/${e.id_kegiatan_detail_proses}`;

        // Tampilkan modal
        new bootstrap.Modal(document.getElementById('modalDetail')).show();
      }
    });

    calendar.render();
  });
</script>


<style>
  .fc-event {
    cursor: pointer !important; /* cursor tangan saat hover */
  }

  .fc-event:hover {
    opacity: 0.9;
    transform: scale(1.01);
    transition: 0.2s ease-in-out;
  }

  #calendar {
    font-size: 0.85em;
  }
</style>


<?= $this->endSection() ?>
