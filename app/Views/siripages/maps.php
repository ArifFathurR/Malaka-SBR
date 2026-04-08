<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="container-fluid px-4">
    <h3>Geser Titik Lokasi - <?= esc($usaha['placeName']) ?></h3>

    <div id="map" style="height: 400px;"></div>

    <form method="post" action="<?= base_url('usaha/updateKoordinat/' . $usaha['placeId']) ?>" id="formKoordinat">
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <!-- Tambahan hidden wilayah -->
        <input type="hidden" name="kabupaten" value="<?= esc($_GET['kabupaten'] ?? '') ?>">
        <input type="hidden" name="kecamatan" value="<?= esc($_GET['kecamatan'] ?? '') ?>">
        <input type="hidden" name="desa" value="<?= esc($_GET['desa'] ?? '') ?>">

        <button type="button" onclick="konfirmasiSimpan()" class="btn btn-success mt-3">Simpan Koordinat</button>
        <a href="<?= base_url('usaha') ?>" class="btn btn-secondary mt-3">Batal</a>
    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
    const lat = <?= $usaha['placeLatitude'] ?: '0.5333' ?>;
    const lng = <?= $usaha['placeLongitude'] ?: '101.45' ?>;

    const map = L.map('map').setView([lat, lng], 17);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    marker.on('dragend', function (e) {
        const pos = marker.getLatLng();
        document.getElementById('latitude').value = pos.lat.toFixed(6);
        document.getElementById('longitude').value = pos.lng.toFixed(6);
    });

    function konfirmasiSimpan() {
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;

        if (!lat || !lng) {
            Swal.fire('Gagal', 'Silakan geser marker terlebih dahulu.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Simpan Koordinat?',
            text: "Koordinat akan diperbarui.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formKoordinat').submit();
            }
        });
    }
</script>

<?= $this->endSection() ?>
