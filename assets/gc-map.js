/**
 * GC Map JavaScript
 * File: public/assets/js/gc-map.js
 * 
 * Handles map initialization, GPS location, and coordinate updates
 */

(function() {
  'use strict';

  // Variable Global untuk Map dan Marker
  let gcMap = null;
  let gcMarker = null;

  /**
   * Inisialisasi Peta Leaflet
   */
  function initGcMap() {
    const latInput = document.getElementById('gc_lat');
    const lngInput = document.getElementById('gc_lng');

    if (!latInput || !lngInput) {
      console.error('Input koordinat tidak ditemukan');
      return;
    }

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
      const mapElement = document.getElementById('mapGCUpdate');
      
      if (!mapElement) {
        console.error('Element map tidak ditemukan');
        return;
      }

      gcMap = L.map('mapGCUpdate').setView([lat, lng], 15);
      
      // Tambah tile layer OpenStreetMap
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(gcMap);

      // Tambah marker yang bisa di-drag
      gcMarker = L.marker([lat, lng], { 
        draggable: true,
        autoPan: true,
        title: 'Drag untuk memindahkan lokasi'
      }).addTo(gcMap);

      // Tambah popup ke marker
      gcMarker.bindPopup('Koordinat Usaha<br>Drag marker untuk mengubah lokasi');

      // Fungsi sinkronisasi koordinat
      function syncCoordinates(position) {
        const newLat = position.lat.toFixed(8);
        const newLng = position.lng.toFixed(8);
        
        latInput.value = newLat;
        lngInput.value = newLng;

        // Update popup
        gcMarker.setPopupContent(
          `<strong>Koordinat Usaha</strong><br>` +
          `Lat: ${newLat}<br>` +
          `Lng: ${newLng}`
        );

        // Visual feedback
        validateCoordinateInputs();
      }

      // Event saat marker di-drag
      gcMarker.on('dragend', function() {
        const position = gcMarker.getLatLng();
        syncCoordinates(position);
      });

      // Event saat marker di-drag (realtime)
      gcMarker.on('drag', function() {
        const position = gcMarker.getLatLng();
        latInput.value = position.lat.toFixed(8);
        lngInput.value = position.lng.toFixed(8);
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
    setTimeout(function() {
      if (gcMap) {
        gcMap.invalidateSize(true);
      }
    }, 200);
  }

  /**
   * Validasi Input Koordinat
   */
  function validateCoordinateInputs() {
    const latInput = document.getElementById('gc_lat');
    const lngInput = document.getElementById('gc_lng');

    if (!latInput || !lngInput) return;

    const lat = parseFloat(latInput.value);
    const lng = parseFloat(lngInput.value);

    // Validasi latitude (-90 to 90)
    if (isNaN(lat) || lat < -90 || lat > 90) {
      latInput.classList.add('gc-coordinate-invalid');
      latInput.classList.remove('gc-coordinate-valid');
    } else {
      latInput.classList.add('gc-coordinate-valid');
      latInput.classList.remove('gc-coordinate-invalid');
    }

    // Validasi longitude (-180 to 180)
    if (isNaN(lng) || lng < -180 || lng > 180) {
      lngInput.classList.add('gc-coordinate-invalid');
      lngInput.classList.remove('gc-coordinate-valid');
    } else {
      lngInput.classList.add('gc-coordinate-valid');
      lngInput.classList.remove('gc-coordinate-invalid');
    }
  }

  /**
   * Handle GPS Location Request
   */
  function handleGPSRequest() {
    // Cek support Geolocation
    if (!navigator.geolocation) {
      Swal.fire({
        icon: 'error',
        title: 'Tidak Didukung',
        text: 'Browser Anda tidak mendukung Geolocation API',
        confirmButtonColor: '#dc3545'
      });
      return;
    }

    // Loading indicator
    Swal.fire({
      title: 'Mengambil Lokasi...',
      html: '<div class="mb-2">Mohon izinkan akses lokasi pada browser Anda</div>' +
            '<div class="spinner-border text-primary" role="status">' +
            '<span class="visually-hidden">Loading...</span></div>',
      allowOutsideClick: false,
      allowEscapeKey: false,
      showConfirmButton: false,
      didOpen: function() {
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

        console.log('GPS Location:', { lat, lng, accuracy });

        // Update input koordinat
        document.getElementById('gc_lat').value = lat.toFixed(8);
        document.getElementById('gc_lng').value = lng.toFixed(8);

        // Update peta dan marker
        if (gcMap && gcMarker) {
          gcMarker.setLatLng([lat, lng]);
          gcMap.setView([lat, lng], 17);
          
          // Update popup
          gcMarker.setPopupContent(
            `<strong>Lokasi Saat Ini</strong><br>` +
            `Lat: ${lat.toFixed(8)}<br>` +
            `Lng: ${lng.toFixed(8)}<br>` +
            `<small>Akurasi: ±${Math.round(accuracy)}m</small>`
          ).openPopup();
        }

        // Validasi input
        validateCoordinateInputs();

        // Tampilkan notifikasi sukses
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          html: `<div class="mb-2">Lokasi berhasil diambil</div>` +
                `<small class="text-muted">Akurasi: ±${Math.round(accuracy)} meter</small>`,
          timer: 2500,
          showConfirmButton: false,
          timerProgressBar: true
        });
      },
      // Error callback
      function(error) {
        console.error('GPS Error:', error);

        let errorTitle = 'Gagal Mengambil Lokasi';
        let errorMessage = 'Terjadi kesalahan saat mengambil lokasi';
        let errorIcon = 'error';
        
        switch(error.code) {
          case error.PERMISSION_DENIED:
            errorMessage = 'Akses lokasi ditolak. Mohon izinkan akses lokasi pada browser Anda melalui pengaturan.';
            errorIcon = 'warning';
            break;
          case error.POSITION_UNAVAILABLE:
            errorMessage = 'Informasi lokasi tidak tersedia. Pastikan GPS perangkat Anda aktif dan sinyal tersedia.';
            break;
          case error.TIMEOUT:
            errorMessage = 'Waktu permintaan lokasi habis. Silakan coba lagi atau periksa koneksi GPS Anda.';
            break;
          default:
            errorMessage = 'Terjadi kesalahan: ' + error.message;
        }
        
        Swal.fire({
          icon: errorIcon,
          title: errorTitle,
          html: `<div class="text-start">${errorMessage}</div>`,
          confirmButtonText: 'OK',
          confirmButtonColor: '#0d6efd',
          footer: '<small>Pastikan Anda menggunakan HTTPS dan GPS aktif</small>'
        });
      },
      // Options
      {
        enableHighAccuracy: true,  // Gunakan GPS presisi tinggi
        timeout: 15000,            // Timeout 15 detik
        maximumAge: 0              // Tidak gunakan cache lokasi
      }
    );
  }

  /**
   * Validasi Form Sebelum Submit
   */
  function validateForm(event) {
    const lat = parseFloat(document.getElementById('gc_lat').value);
    const lng = parseFloat(document.getElementById('gc_lng').value);
    const status = document.getElementById('gc_status').value;
    
    // Validasi status
    if (!status) {
      event.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Peringatan',
        text: 'Mohon pilih status usaha terlebih dahulu.',
        confirmButtonColor: '#ffc107'
      });
      return false;
    }

    // Validasi koordinat
    if (isNaN(lat) || isNaN(lng)) {
      event.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Koordinat Tidak Valid',
        text: 'Silakan ambil lokasi menggunakan GPS atau klik pada peta.',
        confirmButtonColor: '#ffc107'
      });
      return false;
    }

    // Validasi range koordinat
    if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
      event.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Koordinat Di Luar Jangkauan',
        html: 'Koordinat tidak valid:<br>' +
              '<small>Latitude harus antara -90 hingga 90<br>' +
              'Longitude harus antara -180 hingga 180</small>',
        confirmButtonColor: '#dc3545'
      });
      return false;
    }

    // Konfirmasi sebelum submit
    event.preventDefault();
    
    Swal.fire({
      title: 'Konfirmasi Penyimpanan',
      html: `<div class="text-start">` +
            `<strong>Status:</strong> ${document.getElementById('gc_status').selectedOptions[0].text}<br>` +
            `<strong>Latitude:</strong> ${lat.toFixed(8)}<br>` +
            `<strong>Longitude:</strong> ${lng.toFixed(8)}<br><br>` +
            `Apakah Anda yakin data sudah benar?</div>`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#198754',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Simpan',
      cancelButtonText: 'Periksa Kembali'
    }).then(function(result) {
      if (result.isConfirmed) {
        // Submit form
        document.getElementById('formGC').submit();
      }
    });

    return false;
  }

  /**
   * Manual Coordinate Input Handler
   */
  function handleManualCoordinateInput() {
    const latInput = document.getElementById('gc_lat');
    const lngInput = document.getElementById('gc_lng');

    if (!latInput || !lngInput) return;

    const lat = parseFloat(latInput.value);
    const lng = parseFloat(lngInput.value);

    if (!isNaN(lat) && !isNaN(lng)) {
      if (gcMap && gcMarker) {
        gcMarker.setLatLng([lat, lng]);
        gcMap.setView([lat, lng], 15);
      }
      validateCoordinateInputs();
    }
  }

  /**
   * Initialize All Events
   */
  function initializeEvents() {
    // Event: Saat tombol modal dibuka
    const btnOpenModal = document.getElementById('btnOpenGCModal');
    if (btnOpenModal) {
      btnOpenModal.addEventListener('click', function() {
        const gcNamaUsaha = document.getElementById('gc_nama_usaha');
        const gcAlamat = document.getElementById('gc_alamat');
        const gcStatus = document.getElementById('gc_status');
        const gcLat = document.getElementById('gc_lat');
        const gcLng = document.getElementById('gc_lng');

        if (gcNamaUsaha) gcNamaUsaha.textContent = this.dataset.nama || '-';
        if (gcAlamat) gcAlamat.textContent = this.dataset.alamat || '-';
        if (gcStatus) gcStatus.value = this.dataset.status || '';
        if (gcLat) gcLat.value = this.dataset.lat || '';
        if (gcLng) gcLng.value = this.dataset.lng || '';
      });
    }

    // Event: Saat modal ditampilkan
    const gcModal = document.getElementById('gcModal');
    if (gcModal) {
      gcModal.addEventListener('shown.bs.modal', function() {
        initGcMap();
      });
    }

    // Event: Tombol GPS
    const btnGps = document.getElementById('btnGpsGC');
    if (btnGps) {
      btnGps.addEventListener('click', handleGPSRequest);
    }

    // Event: Form Submit
    const formGC = document.getElementById('formGC');
    if (formGC) {
      formGC.addEventListener('submit', validateForm);
    }

    // Event: Manual input koordinat
    const latInput = document.getElementById('gc_lat');
    const lngInput = document.getElementById('gc_lng');
    
    if (latInput) {
      latInput.addEventListener('blur', handleManualCoordinateInput);
      latInput.addEventListener('input', validateCoordinateInputs);
    }
    
    if (lngInput) {
      lngInput.addEventListener('blur', handleManualCoordinateInput);
      lngInput.addEventListener('input', validateCoordinateInputs);
    }
  }

  /**
   * DOM Ready
   */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeEvents);
  } else {
    initializeEvents();
  }

  // Export untuk debugging (optional)
  window.gcMapDebug = {
    getMap: function() { return gcMap; },
    getMarker: function() { return gcMarker; },
    reinitMap: initGcMap
  };

})();