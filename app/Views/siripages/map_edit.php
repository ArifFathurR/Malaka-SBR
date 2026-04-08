<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<?php
// $rec (associative array or object) passed from controller
$rec = $rec ?? null;
$canSaveCoords = $canSaveCoords ?? false;
$googleMapsKey = $googleMapsKey ?? 'AIzaSyAYaRBajjixtB0T1HqWEXOHbTDpJ8Tjcyc';
if (! $rec) {
    echo '<div class="container mt-4"><div class="alert alert-danger">Data tidak ditemukan.</div></div>';
    return $this->endSection();
}
$placeId = is_array($rec) ? $rec['placeId'] : $rec->placeId;
$placeName = is_array($rec) ? $rec['placeName'] : $rec->placeName;
$lat = is_array($rec) ? ($rec['placeLatitude'] ?? '') : ($rec->placeLatitude ?? '');
$lng = is_array($rec) ? ($rec['placeLongitude'] ?? '') : ($rec->placeLongitude ?? '');
?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="map-pin"></i></div>
                        Geser Lokasi — <?= esc($placeName) ?>
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4">
    

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Place ID:</strong> <?= esc($placeId) ?> &nbsp; | &nbsp; <strong>Desa:</strong> <?= esc($rec['nmdesa'] ?? ($rec->nmdesa ?? '')) ?></p>

            <div id="mapEdit" style="height:520px;border:1px solid #ddd;"></div>

            <div class="mt-3 d-flex gap-2">
                <input type="text" id="edit_lat" class="form-control" value="<?= esc($lat) ?>" readonly>
                <input type="text" id="edit_lng" class="form-control" value="<?= esc($lng) ?>" readonly>
                <?php if ($canSaveCoords): ?>
                    <button id="btnSaveCoords" class="btn btn-success">Simpan</button>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled title="Hanya penanggung jawab/update sebelumnya atau admin yang dapat menyimpan">Simpan (tidak diizinkan)</button>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<!-- Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?= esc($googleMapsKey) ?>"></script>
<script>
(function () {
    // Default center jika lat/lng kosong atau = 0
    var defaultCenter = { 
        lat: 0.5101861235432819, 
        lng: 101.45462433769832 
    };

    // Ambil nilai dari server (pastikan variabel PHP tersedia)
    var rawLat = parseFloat('<?= esc($lat ?? '') ?>');
    var rawLng = parseFloat('<?= esc($lng ?? '') ?>');

    // Anggap kosong jika NaN atau === 0
    var hasValidLat = !isNaN(rawLat) && rawLat !== 0;
    var hasValidLng = !isNaN(rawLng) && rawLng !== 0;

    var initLat = hasValidLat ? rawLat : defaultCenter.lat;
    var initLng = hasValidLng ? rawLng : defaultCenter.lng;

    var placeId = '<?= esc($placeId ?? '') ?>';
    var canSave = <?= isset($canSaveCoords) && $canSaveCoords ? 'true' : 'false' ?>; // atur dari PHP

    var map = new google.maps.Map(document.getElementById('mapEdit'), {
        center: { lat: initLat, lng: initLng },
        zoom: (hasValidLat && hasValidLng) ? 17 : 15,
        streetViewControl: false
    });

    var marker = new google.maps.Marker({
        position: { lat: initLat, lng: initLng },
        map: map,
        draggable: !!canSave
    });

    // set field helper (6 desimal)
    function setFields(lat, lng) {
        var latStr = (typeof lat === 'number') ? lat.toFixed(6) : String(lat);
        var lngStr = (typeof lng === 'number') ? lng.toFixed(6) : String(lng);
        var fLat = document.getElementById('edit_lat');
        var fLng = document.getElementById('edit_lng');
        if (fLat) fLat.value = latStr;
        if (fLng) fLng.value = lngStr;
    }

    // Inisialisasi field awal agar form dapat disubmit segera
    setFields(initLat, initLng);

    // Jika boleh simpan -> aktifkan interaksi: klik map & drag marker
    if (canSave) {
        google.maps.event.addListener(marker, 'dragend', function () {
            var pos = marker.getPosition();
            setFields(pos.lat(), pos.lng());
        });

        map.addListener('click', function (e) {
            var ll = e.latLng;
            marker.setPosition(ll);
            setFields(ll.lat(), ll.lng());
        });
    } else {
        // kalau tidak boleh simpan, pastikan marker tidak draggable
        marker.setDraggable(false);
    }

    // Helper cek Swal
    function hasSwal() {
        return (typeof Swal !== 'undefined' && typeof Swal.fire === 'function');
    }

    // Simpan koordinat via AJAX POST (x-www-form-urlencoded)
    async function doSaveCoords(latVal, lngVal) {
        var body = new URLSearchParams();
        body.append('placeId', placeId);
        body.append('lat', latVal);
        body.append('lng', lngVal);

        // CSRF jika terpakai (opsional)
        <?php if (function_exists('csrf_token') && function_exists('csrf_hash')): ?>
            body.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        <?php endif; ?>

        if (hasSwal()) {
            Swal.fire({
                title: 'Menyimpan koordinat...',
                html: 'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
        }

        try {
            var res = await fetch('<?= base_url('usaha/updateCoordinates') ?>', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: body.toString()
            });

            var json = await res.json();

            if (res.ok && json && json.success) {
                if (hasSwal()) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan',
                        text: json.message || 'Koordinat berhasil disimpan.',
                        timer: 1600,
                        showConfirmButton: false
                    });
                } else {
                    alert('Koordinat berhasil disimpan.');
                }
            } else {
                var msg = (json && json.message) ? json.message : 'Gagal menyimpan koordinat.';
                if (hasSwal()) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
                } else {
                    alert('Gagal: ' + msg);
                }
            }
        } catch (err) {
            console.error('save error', err);
            if (hasSwal()) {
                Swal.fire({ icon: 'error', title: 'Kesalahan jaringan', text: 'Terjadi kesalahan saat menyimpan.' });
            } else {
                alert('Kesalahan jaringan saat menyimpan.');
            }
        }
    }

    // Event tombol Simpan
    document.getElementById('btnSaveCoords')?.addEventListener('click', function () {
        if (!canSave) return;

        var latVal = document.getElementById('edit_lat')?.value;
        var lngVal = document.getElementById('edit_lng')?.value;

        if (!latVal || !lngVal) {
            if (hasSwal()) {
                Swal.fire({ icon: 'warning', title: 'Koordinat tidak valid', text: 'Pastikan marker sudah dipilih.' });
            } else {
                alert('Koordinat tidak valid. Pastikan marker sudah dipilih.');
            }
            return;
        }

        if (hasSwal()) {
            Swal.fire({
                title: 'Yakin simpan koordinat baru?',
                text: "Perubahan hanya akan menyimpan koordinat (tidak mengubah last_update_by).",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (result.isConfirmed) {
                    doSaveCoords(latVal, lngVal);
                }
            });
        } else {
            if (confirm('Simpan koordinat baru?')) {
                doSaveCoords(latVal, lngVal);
            }
        }
    });

})();
</script>



<?= $this->endSection() ?>
