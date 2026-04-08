<?php $this->extend('layouts/master') ?>
<?php $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="map-pin"></i></div>
                        Daftar Usaha Berdasarkan Lokasi
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4">
    <div id="mapAll" style="height: 500px; border: 1px solid #ccc; border-radius: 8px;"></div>

    <div class="mt-3">
        <a href="<?= base_url('usaha') . '?kabupaten=' . $selected_kabupaten . '&kecamatan=' . $selected_kecamatan . '&desa=' . $selected_desa ?>" class="btn btn-secondary">
            <i data-feather="list"></i> Kembali ke Tabel
        </a>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const map = L.map('mapAll').setView([-0.5, 101.45], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const usahaList = <?= json_encode($usaha) ?>;
    const statusLabels = <?= json_encode($statusLabels) ?>;
    const kategoriLabels = <?= json_encode($kategoriLabels) ?>;

    const greenIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    const orangeIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    const renderedMarkers = {};

    function renderMarkersAtZoom() {
        const zoom = map.getZoom();
        if (zoom >= 17) {
            usahaList.forEach(u => {
                if (!u.placeLatitude || !u.placeLongitude || renderedMarkers[u.placeId]) return;

                const lat = parseFloat(u.placeLatitude);
                const lng = parseFloat(u.placeLongitude);
                const latLng = [lat, lng];

                const statusText = statusLabels[u.status_usaha] ?? 'Belum diupdate';
                let kategoriText = '-';
                if (u.status_usaha === 1 || u.status_usaha === '1') {
                    kategoriText = kategoriLabels[u.kategori] ?? '-';
                }

                let updateBy = '-';
                if (u.last_update_by && u.last_update_by.includes('oleh')) {
                    const parts = u.last_update_by.split(' oleh ');
                    if (parts.length === 2) {
                        const dt = new Date(parts[0]);
                        const tanggal = dt.toLocaleDateString('id-ID', {
                            day: '2-digit', month: 'short', year: 'numeric',
                            hour: '2-digit', minute: '2-digit'
                        });
                        updateBy = `${tanggal}<br>oleh ${parts[1]}`;
                    }
                }

                const popupContent = `
                    <table class="table table-sm table-bordered" style="font-size: 12px;">
                        <tr><th>Nama Usaha</th><td>${u.placeName}</td></tr>
                        <tr><th>Alamat</th><td>${u.placeAddress}</td></tr>
                        <tr><th>Status</th><td>${statusText}</td></tr>
                        <tr><th>Kategori</th><td>${kategoriText}</td></tr>
                        <tr><th>Update Terakhir</th><td>${updateBy}</td></tr>
                        <tr><td colspan="2" class="text-center">
                            <button class="btn btn-primary btn-sm mt-1" onclick="openUpdateModal('${u.placeId}', \`${u.placeName.replace(/`/g, "\\`")}\`)">
                                Update
                            </button>
                        </td></tr>
                    </table>
                `;

                const icon = u.status_usaha ? greenIcon : orangeIcon;

                const marker = L.marker(latLng, { icon: icon }).bindPopup(popupContent).addTo(map);
                renderedMarkers[u.placeId] = marker;
            });
        } else {
            Object.values(renderedMarkers).forEach(marker => map.removeLayer(marker));
            Object.keys(renderedMarkers).forEach(key => delete renderedMarkers[key]);
        }
    }

    renderMarkersAtZoom();
    map.on('zoomend', renderMarkersAtZoom);

    function openUpdateModal(placeId, placeName) {
        document.getElementById('placeIdInput').value = placeId;
        document.getElementById('namaUsahaModal').textContent = placeName;
        const modal = new bootstrap.Modal(document.getElementById('updateModal'));
        modal.show();
    }
</script>

<?php $this->endSection() ?>
