<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<!-- SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
#mapGCUpdate {
  height: 320px;
  min-height: 320px;
  width: 100%;
  display: block;
  border-radius: 0.375rem;
}
</style>

<div class="container mt-4">
  <h4 class="mb-3">Pencarian Usaha (GC)</h4>

  <!-- Flash Messages disembunyikan, akan ditampilkan via SweetAlert -->
  <div class="d-none">
    <?php if (session('success')): ?>
      <div id="flash-success" data-message="<?= esc(session('success')) ?>"></div>
    <?php endif; ?>
    <?php if (session('error')): ?>
      <div id="flash-error" data-message="<?= esc(session('error')) ?>"></div>
    <?php endif; ?>
  </div>

  <!-- FORM CARI ID SBR -->
  <div class="card mb-3">
    <div class="card-body">
      <form method="get" action="<?= base_url('sbr/gc') ?>" class="row g-2 align-items-end" autocomplete="off">
        <div class="col-md-6">
          <label class="form-label">ID SBR</label>
          <input type="text"
                 name="idsbr"
                 class="form-control"
                 placeholder="Masukkan ID SBR..."
                 value="<?= esc($idsbr ?? '') ?>"
                 autofocus>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-search"></i> Cari
          </button>
        </div>
      </form>
    </div>
  </div>

  <?php if (!empty($idsbr) && empty($row)): ?>
    <div class="alert alert-warning" role="alert">
      <i class="bi bi-exclamation-triangle"></i> ID SBR <strong><?= esc($idsbr) ?></strong> tidak ditemukan.
    </div>
  <?php endif; ?>

  <?php if (!empty($row)): ?>
    <!-- DETAIL USAHA -->
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0 text-white">Detail Usaha</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-8">
            <table class="table table-sm table-borderless">
              <tr>
                <td width="150" class="text-muted">ID SBR</td>
                <td><strong><?= esc($row['id_sbr']) ?></strong></td>
              </tr>
              <tr>
                <td class="text-muted">Nama Usaha</td>
                <td><strong><?= esc($row['nama_usaha'] ?? '-') ?></strong></td>
              </tr>
              <tr>
                <td class="text-muted">Alamat</td>
                <td><?= esc($row['alamat'] ?? '-') ?></td>
              </tr>
              <tr>
                <td class="text-muted">Kabupaten</td>
                <td><?= esc($row['nmkab'] ?? '-') ?></td>
              </tr>
              <tr>
                <td class="text-muted">Kecamatan</td>
                <td><?= esc($row['nmkec'] ?? '-') ?></td>
              </tr>
              <tr>
                <td class="text-muted">Desa/Kelurahan</td>
                <td><?= esc($row['nmdesa'] ?? '-') ?></td>
              </tr>
              <?php
                $keberadaanUsahaMap = [
                    1  => '1. Aktif',
                    2  => '2. Tutup Sementara',
                    3  => '3. Belum Beroperasi/Berproduksi',
                    4  => '4. Tutup',
                    5  => '5. Alih Usaha',
                    6  => '6. Tidak Ditemukan',
                    7  => '7. Aktif Pindah',
                    8  => '8. Aktif Nonrespon',
                    9  => '9. Duplikat',
                    10 => '10. Salah Kode Wilayah',
                ];
                
                $statusSBR = $row['keberadaan_usaha'] ?? null;
                ?>
            <tr>
              <td class="text-muted">Status SBR</td>
              <td>
                <?= esc($keberadaanUsahaMap[$statusSBR] ?? '-') ?>
              </td>
            </tr>

              
              <tr>
                <td class="text-muted">Status GC</td>
                <td>
                  <?php
                    $status = $row['gc_status'] ?? '';
                    $badge = 'secondary';
                    $text = 'Belum diupdate';
                    
                    switch($status) {
                      case '1':
                        $badge = 'success';
                        $text = 'Ditemukan';
                        break;
                      case '3':
                        $badge = 'warning';
                        $text = 'Tutup';
                        break;
                      case '4':
                        $badge = 'danger';
                        $text = 'Ganda';
                        break;
                      case '9':
                        $badge = 'dark';
                        $text = 'Tidak Ditemukan';
                        break;
                    }
                  ?>
                  <span class="badge bg-<?= $badge ?>"><?= $text ?></span>
                </td>
              </tr>
              <tr>
                <td class="text-muted">Koordinat Awal</td>
                <td>
                  <?php if(!empty($row['latitude']) && !empty($row['longitude'])): ?>
                    <?= esc($row['latitude']) ?>, <?= esc($row['longitude']) ?>
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
              </tr>
              <tr>
                <td class="text-muted">Koordinat GC</td>
                <td>
                  <?php if(!empty($row['gc_latitude']) && !empty($row['gc_longitude'])): ?>
                    <strong class="text-success"><?= esc($row['gc_latitude']) ?>, <?= esc($row['gc_longitude']) ?></strong>
                  <?php else: ?>
                    <span class="text-muted">Belum diupdate</span>
                  <?php endif; ?>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <hr>

        <button type="button" 
                class="btn btn-success btn-lg"
                data-bs-toggle="modal"
                data-bs-target="#gcModal"
                id="btnOpenGCModal"
                data-id_sbr="<?= esc($row['id_sbr']) ?>"
                data-nama="<?= esc($row['nama_usaha'] ?? '-') ?>"
                data-alamat="<?= esc($row['alamat'] ?? '-') ?>"
                data-status="<?= esc($row['gc_status'] ?? '') ?>"
                data-lat="<?= esc($row['gc_latitude'] ?? $row['latitude'] ?? '') ?>"
                data-lng="<?= esc($row['gc_longitude'] ?? $row['longitude'] ?? '') ?>">
          <i class="bi bi-geo-alt-fill"></i> Update 
        </button>
      </div>
    </div>
  <?php endif; ?>
</div>

<!-- MODAL UPDATE GC -->
<div class="modal fade" id="gcModal" tabindex="-1" aria-labelledby="gcModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="post" action="<?= base_url('sbr/gc/' . ($row['id_sbr'] ?? '')) ?>" id="formGC">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">

      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="gcModalLabel">
            <i class="bi text-white"></i> Update Data SBR
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <!-- Info Usaha -->
          <div class="mb-3 text-center p-3 bg-light rounded">
            <h5 id="gc_nama_usaha" class="fw-semibold text-primary mb-1"></h5>
            <small id="gc_alamat" class="text-muted"></small>
          </div>

          <!-- STATUS -->
          <div class="mb-3">
            <label class="form-label fw-semibold">
              Status Usaha <span class="text-danger">*</span>
            </label>
            <select name="gc_status" id="gc_status" class="form-select" required>
              <option value="">-- Pilih Status --</option>
              <option value="9">0. Tidak Ditemukan</option>
              <option value="1">1. Ditemukan</option>
              <option value="3">3. Tutup</option>
              <option value="4">4. Ganda</option>
            </select>
          </div>

          <!-- KOORDINAT + GPS -->
          <div class="mb-3">
            <label class="form-label fw-semibold mb-2">
              Koordinat GC <span class="text-danger">*</span>
            </label>

            <!-- Button GPS + Input Koordinat -->
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
              <button type="button" class="btn btn-outline-primary" id="btnGpsGC">
                <i class="bi bi-crosshair"></i> Ambil Lokasi Saya
              </button>

              <div class="input-group" style="max-width: 250px;">
                <span class="input-group-text">Lat</span>
                <input type="text"
                       class="form-control"
                       id="gc_lat"
                       name="gc_lat"
                       placeholder="Latitude"
                       required>
              </div>

              <div class="input-group" style="max-width: 250px;">
                <span class="input-group-text">Lng</span>
                <input type="text"
                       class="form-control"
                       id="gc_lng"
                       name="gc_lng"
                       placeholder="Longitude"
                       required>
              </div>
            </div>

            <!-- Peta -->
            <div id="mapGCUpdate" class="border"></div>

            <small class="text-muted d-block mt-2">
              <i class="bi bi-info-circle"></i> 
              Klik pada peta atau drag marker untuk menyesuaikan koordinat secara manual.
            </small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Batal
          </button>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-save"></i> Simpan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom JS -->
<script>
// Variable Global untuk Map dan Marker
let gcMap = null;
let gcMarker = null;

/**
 * Tampilkan Flash Message dengan SweetAlert
 */
function showFlashMessage() {
  const flashSuccess = document.getElementById('flash-success');
  const flashError = document.getElementById('flash-error');

  if (flashSuccess) {
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: flashSuccess.dataset.message,
      timer: 3000,
      timerProgressBar: true,
      showConfirmButton: true,
      confirmButtonText: 'OK',
      confirmButtonColor: '#198754'
    });
  }

  if (flashError) {
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: flashError.dataset.message,
      confirmButtonText: 'OK',
      confirmButtonColor: '#dc3545'
    });
  }
}

/**
 * Inisialisasi Peta Leaflet
 */
function initGcMap() {
  const latInput = document.getElementById('gc_lat');
  const lngInput = document.getElementById('gc_lng');

  let lat = parseFloat(latInput.value);
  let lng = parseFloat(lngInput.value);

  // Default ke Jakarta jika koordinat tidak valid
  if (isNaN(lat) || isNaN(lng)) {
    lat = -6.2;
    lng = 106.816667;
    latInput.value = lat;
    lngInput.value = lng;
  }

  // Inisialisasi peta jika belum ada
  if (!gcMap) {
    gcMap = L.map('mapGCUpdate').setView([lat, lng], 15);
    
    // Tambah tile layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '© OpenStreetMap contributors'
    }).addTo(gcMap);

    // Tambah marker yang bisa di-drag
    gcMarker = L.marker([lat, lng], { 
      draggable: true,
      autoPan: true
    }).addTo(gcMap);

    // Fungsi sinkronisasi koordinat
    function syncCoordinates(position) {
      latInput.value = position.lat.toFixed(8);
      lngInput.value = position.lng.toFixed(8);
    }

    // Event saat marker di-drag
    gcMarker.on('dragend', function() {
      const position = gcMarker.getLatLng();
      syncCoordinates(position);
    });

    // Event saat peta di-klik
    gcMap.on('click', function(e) {
      gcMarker.setLatLng(e.latlng);
      syncCoordinates(e.latlng);
    });

  } else {
    // Update posisi jika peta sudah ada
    gcMap.setView([lat, lng], 15);
    gcMarker.setLatLng([lat, lng]);
  }

  // Refresh ukuran peta
  setTimeout(() => {
    gcMap.invalidateSize(true);
  }, 200);
}

/**
 * Event: Saat tombol modal dibuka
 */
document.getElementById('btnOpenGCModal')?.addEventListener('click', function() {
  // Isi data ke form modal
  document.getElementById('gc_nama_usaha').textContent = this.dataset.nama;
  document.getElementById('gc_alamat').textContent = this.dataset.alamat;
  document.getElementById('gc_status').value = this.dataset.status || '';
  document.getElementById('gc_lat').value = this.dataset.lat || '';
  document.getElementById('gc_lng').value = this.dataset.lng || '';
});

/**
 * Event: Saat modal ditampilkan (shown)
 */
const gcModal = document.getElementById('gcModal');
gcModal?.addEventListener('shown.bs.modal', function() {
  initGcMap();
});

/**
 * Event: Tombol Ambil Lokasi GPS
 */
document.getElementById('btnGpsGC')?.addEventListener('click', function() {
  // Cek support Geolocation
  if (!navigator.geolocation) {
    Swal.fire({
      icon: 'error',
      title: 'Tidak Didukung',
      text: 'Browser Anda tidak mendukung Geolocation API'
    });
    return;
  }

  // Loading indicator
  Swal.fire({
    title: 'Mengambil Lokasi...',
    html: 'Mohon izinkan akses lokasi pada browser Anda',
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });

  // Request GPS position
  navigator.geolocation.getCurrentPosition(
    // Success callback
    function(position) {
      const lat = position.coords.latitude;
      const lng = position.coords.longitude;
      const accuracy = position.coords.accuracy;

      // Update input koordinat
      document.getElementById('gc_lat').value = lat.toFixed(8);
      document.getElementById('gc_lng').value = lng.toFixed(8);

      // Update peta dan marker
      if (gcMap && gcMarker) {
        gcMarker.setLatLng([lat, lng]);
        gcMap.setView([lat, lng], 17);
      }

      // Tampilkan notifikasi sukses
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        html: `Lokasi berhasil diambil<br><small>Akurasi: ±${Math.round(accuracy)} meter</small>`,
        timer: 2000,
        showConfirmButton: false
      });
    },
    // Error callback
    function(error) {
      let errorMessage = 'Gagal mengambil lokasi';
      
      switch(error.code) {
        case error.PERMISSION_DENIED:
          errorMessage = 'Akses lokasi ditolak. Mohon izinkan akses lokasi pada browser Anda.';
          break;
        case error.POSITION_UNAVAILABLE:
          errorMessage = 'Informasi lokasi tidak tersedia. Pastikan GPS perangkat Anda aktif.';
          break;
        case error.TIMEOUT:
          errorMessage = 'Waktu permintaan lokasi habis. Silakan coba lagi.';
          break;
        default:
          errorMessage = 'Terjadi kesalahan: ' + error.message;
      }
      
      Swal.fire({
        icon: 'error',
        title: 'Gagal Mengambil Lokasi',
        text: errorMessage,
        confirmButtonText: 'OK'
      });
    },
    // Options
    {
      enableHighAccuracy: true,  // Gunakan GPS presisi tinggi
      timeout: 15000,            // Timeout 15 detik
      maximumAge: 0              // Tidak gunakan cache lokasi
    }
  );
});

/**
 * Validasi form sebelum submit
 */
document.getElementById('formGC')?.addEventListener('submit', function(e) {
  e.preventDefault(); // Prevent default submit
  
  const lat = parseFloat(document.getElementById('gc_lat').value);
  const lng = parseFloat(document.getElementById('gc_lng').value);
  const status = document.getElementById('gc_status').value;
  
  // Validasi koordinat
  if (isNaN(lat) || isNaN(lng)) {
    Swal.fire({
      icon: 'warning',
      title: 'Peringatan',
      text: 'Koordinat tidak valid. Silakan ambil lokasi atau klik pada peta.'
    });
    return false;
  }

  // Validasi status
  if (!status) {
    Swal.fire({
      icon: 'warning',
      title: 'Peringatan',
      text: 'Mohon pilih status usaha terlebih dahulu.'
    });
    return false;
  }

  // Konfirmasi sebelum submit
  Swal.fire({
    title: 'Konfirmasi Penyimpanan',
    html: `<div class="text-start">
            <strong>Status:</strong> ${document.getElementById('gc_status').selectedOptions[0].text}<br>
            <strong>Latitude:</strong> ${lat.toFixed(8)}<br>
            <strong>Longitude:</strong> ${lng.toFixed(8)}<br><br>
            Apakah Anda yakin data sudah benar?</div>`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#198754',
    cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="bi bi-check-circle"></i> Ya, Simpan',
    cancelButtonText: '<i class="bi bi-x-circle"></i> Periksa Kembali',
    showLoaderOnConfirm: true,
    preConfirm: () => {
      // Submit form
      return new Promise((resolve) => {
        this.submit();
        resolve();
      });
    }
  });

  return false;
});

// Jalankan flash message saat halaman load
document.addEventListener('DOMContentLoaded', showFlashMessage);
</script>

<?= $this->endSection() ?>