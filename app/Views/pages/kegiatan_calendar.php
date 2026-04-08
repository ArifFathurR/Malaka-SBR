<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<!-- FullCalendar hanya untuk halaman ini -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<div class="container-fluid px-4">
    <div class="card mb-4">
        <div class="card-header">
            <h4>Kalender Kegiatan</h4>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
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

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      height: 650,
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
</style>

<?= $this->endSection() ?>
