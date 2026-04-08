<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="map-pin"></i></div>
                        Geotagging Labor Kab/Kota (Dokumen P)
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4 mt-3">

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success"><?= session('success') ?></div>
    <?php elseif (session()->has('error')): ?>
        <div class="alert alert-danger"><?= session('error') ?></div>
    <?php endif; ?>

    <!-- Filter Desa & SLS -->
    <form id="filterForm"
          method="get"
          action="<?= base_url('usaha/laborkabkot_geo') ?>"
          class="row mb-4">

        <div class="col-md-5 mb-2">
            <label class="form-label">Pilih Desa</label>
            <select name="iddesa" class="form-select" onchange="submitFilter()">
                <option value="">-- Pilih Desa --</option>
                <?php foreach ($desaList as $id => $nama): ?>
                    <option value="<?= esc($id) ?>" <?= ($iddesa == $id ? 'selected' : '') ?>>
                        <?= esc($nama) ?> (<?= esc($id) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-5 mb-2">
            <label class="form-label">Pilih SLS</label>
            <select name="kdsls" class="form-select" onchange="submitFilter()">
                <option value="">-- Pilih SLS --</option>
                <?php if (!empty($iddesa)): ?>
                    <?php foreach ($slsList as $sls): ?>
                        <option value="<?= esc($sls['kdsls']) ?>" <?= ($kdsls == $sls['kdsls'] ? 'selected' : '') ?>>
                            <?= esc($sls['nmsls']) ?> (<?= esc($sls['kdsls']) ?>)
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </form>

    <!-- Overlay loading -->
    <div id="loadingOverlay"
         style="display:none; position:fixed; inset:0; background:rgba(255,255,255,0.75);
                z-index:9999; text-align:center; backdrop-filter:blur(2px);">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
            <div class="spinner-border text-primary" style="width:3rem; height:3rem;"></div>
            <div class="mt-3 fw-semibold text-dark">Memuat data...</div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3" id="laborTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tabTableBtn" data-bs-toggle="tab"
                    data-bs-target="#tabTable" type="button" role="tab">
                Tabel
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= ($iddesa && $kdsls) ? '' : 'disabled' ?>"
                    id="tabMapBtn" data-bs-toggle="tab"
                    data-bs-target="#tabMap" type="button" role="tab">
                Peta
            </button>
        </li>
    </ul>

    <div class="tab-content" id="laborTabContent">

        <!-- TAB TABEL -->
        <div class="tab-pane fade show active" id="tabTable" role="tabpanel">
            <?php if (!empty($iddesa) && !empty($kdsls)): ?>

                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">
                        + Tambah Data Geotagging P
                    </button>
                </div>

                <?php if (!empty($laborList)): ?>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No Urut Usaha</th>
                                            <th>Nama Usaha</th>
                                            <th>Alamat</th>
                                            <th>Koordinat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; foreach ($laborList as $row): ?>
                                            <tr>
                                                <td><?= esc($row['no_urut_usaha']) ?></td>
                                                <td><?= esc($row['laborName']) ?></td>
                                                <td><?= esc($row['laborAddress']) ?></td>
                                                <td><?= esc($row['laborLatitude']) ?>, <?= esc($row['laborLongitude']) ?></td>
                                                <td>
                                                    <button
                                                        class="btn btn-sm btn-primary btnEdit"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEdit"
                                                        data-id="<?= esc($row['laborId']) ?>"
                                                        data-nourut="<?= esc($row['no_urut_usaha']) ?>"
                                                        data-nama="<?= esc($row['laborName']) ?>"
                                                        data-alamat="<?= esc($row['laborAddress']) ?>"
                                                        data-lat="<?= esc($row['laborLatitude']) ?>"
                                                        data-lng="<?= esc($row['laborLongitude']) ?>">
                                                        Edit
                                                    </button>

                                                    <button
                                                        class="btn btn-sm btn-danger btnDelete ms-1"
                                                        data-id="<?= esc($row['laborId']) ?>">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Belum ada data labor untuk kombinasi desa & SLS ini.</div>
                <?php endif; ?>

            <?php else: ?>
                <div class="alert alert-info">Silakan pilih desa dan SLS terlebih dahulu.</div>
            <?php endif; ?>
        </div>

        <!-- TAB PETA -->
        <div class="tab-pane fade" id="tabMap" role="tabpanel">
            <?php if (!empty($iddesa) && !empty($kdsls)): ?>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div id="mapCanvas"
                             style="width:100%; height:600px; border-radius:8px; border:1px solid #ddd;"></div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info mt-2">Peta akan aktif jika desa dan SLS sudah dipilih.</div>
            <?php endif; ?>
        </div>

    </div>

    <!-- MODAL TAMBAH -->
    <div class="modal fade" id="modalAdd" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="post" action="<?= base_url('usaha/laborInsert') ?>" id="formAddLabor">
                <?= csrf_field() ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Labor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="iddesa" value="<?= esc($iddesa) ?>">
                        <input type="hidden" name="kdsls" value="<?= esc($kdsls) ?>">

                        <div class="mb-2">
                            <label class="form-label">No Urut Usaha (Kolom (6) Dokumen P)</label>
                            <input type="number" class="form-control" name="no_urut_usaha" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Nama Usaha</label>
                            <input type="text" class="form-control" name="laborName" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Alamat Usaha (Optional)</label>
                            <textarea class="form-control" name="laborAddress" rows="2"></textarea>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Pilih Koordinat (klik/drag marker)</label>
                            <div id="mapAdd" style="height:350px; border:1px solid #ccc; border-radius:8px;"></div>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Latitude</label>
                                <input id="addLat" name="laborLatitude" class="form-control" readonly required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Longitude</label>
                                <input id="addLng" name="laborLongitude" class="form-control" readonly required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnAddSave" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="post" action="<?= base_url('usaha/laborUpdate') ?>" id="formEditLabor">
                <?= csrf_field() ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data Labor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="editLaborId" name="laborId">
                        <input type="hidden" name="iddesa" value="<?= esc($iddesa) ?>">
                        <input type="hidden" name="kdsls" value="<?= esc($kdsls) ?>">

                        <div class="mb-2">
                            <label class="form-label">No Urut Usaha</label>
                            <input type="number" id="editNoUrut" class="form-control" name="no_urut_usaha" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Nama Usaha</label>
                            <input type="text" id="editNama" class="form-control" name="laborName" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Alamat Usaha</label>
                            <textarea id="editAlamat" class="form-control" name="laborAddress" rows="2"></textarea>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Pilih Koordinat (klik/drag marker)</label>
                            <div id="mapEdit" style="height:350px; border:1px solid #ccc; border-radius:8px;"></div>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Latitude</label>
                                <input id="editLat" name="laborLatitude" class="form-control" readonly required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Longitude</label>
                                <input id="editLng" name="laborLongitude" class="form-control" readonly required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnEditSave" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div> <!-- /container-fluid -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($googleMapsKey)): ?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= esc($googleMapsKey) ?>&libraries=geometry"></script>
<?php endif; ?>

<script>
function submitFilter() {
    const overlay = document.getElementById('loadingOverlay');
    overlay.style.display = 'block';
    setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 150);
}
window.addEventListener('pageshow', () => {
    const overlay = document.getElementById('loadingOverlay');
    overlay.style.display = 'none';
});

function getUserLocation(callback) {
    if (!navigator.geolocation) {
        callback(null);
        return;
    }

    navigator.geolocation.getCurrentPosition(
        pos => {
            callback({
                lat: pos.coords.latitude,
                lng: pos.coords.longitude
            });
        },
        err => {
            callback(null); // user menolak atau error
        },
        {
            enableHighAccuracy: true,
            timeout: 5000,
        }
    );
}


// ================== MAP TAB (POLYGON + MARKERS) ==================
<?php if (!empty($iddesa) && !empty($kdsls) && !empty($googleMapsKey)): ?>
let mapMain, dataLayer, markersLabor = [];

document.getElementById('tabMapBtn').addEventListener('shown.bs.tab', function () {
    if (!mapMain) {
        initMainMap();
    }
});

function initMainMap() {
    mapMain = new google.maps.Map(document.getElementById('mapCanvas'), {
        center: {lat: 0.5, lng: 101.4},
        zoom: 14,
        streetViewControl: false,
    });

    // polygon SLS
    fetch("<?= base_url('usaha/getPolygon') ?>?iddesa=<?= urlencode($iddesa) ?>&kdsls=<?= urlencode($kdsls) ?>")
        .then(r => r.json())
        .then(json => {
            if (!json.success || !json.data) return;

            dataLayer = new google.maps.Data({map: mapMain});
            dataLayer.addGeoJson(json.data);
            dataLayer.setStyle({
                fillColor: '#00AEEF',
                fillOpacity: 0.25,
                strokeColor: '#0074A8',
                strokeWeight: 2
            });

            const bounds = new google.maps.LatLngBounds();
            dataLayer.forEach(feature => {
                processGeom(feature.getGeometry(), bounds.extend, bounds);
            });
            if (!bounds.isEmpty()) {
                mapMain.fitBounds(bounds);
            }
        });

    // markers usaha
    const laborList = <?= json_encode($laborList ?? []) ?>;
    laborList.forEach(row => {
        const lat = parseFloat(row.laborLatitude);
        const lng = parseFloat(row.laborLongitude);
        if (isNaN(lat) || isNaN(lng)) return;

        const marker = new google.maps.Marker({
            position: {lat, lng},
            map: mapMain,
            label: String(row.no_urut_usaha || ''),
        });

        const info = new google.maps.InfoWindow({
            content: '<strong>' + (row.laborName || '') + '</strong>'
        });

        marker.addListener('click', () => info.open(mapMain, marker));
        markersLabor.push(marker);
    });
}

function processGeom(geometry, callback, thisArg) {
    if (geometry instanceof google.maps.LatLng) {
        callback.call(thisArg, geometry);
    } else if (geometry instanceof google.maps.Data.Point) {
        callback.call(thisArg, geometry.get());
    } else {
        geometry.getArray().forEach(g => processGeom(g, callback, thisArg));
    }
}
<?php endif; ?>

// ================== MAP MODAL ADD ==================
let mapAdd, markerAdd;
const modalAddEl = document.getElementById('modalAdd');
if (modalAddEl) {
    modalAddEl.addEventListener('shown.bs.modal', function () {
        if (!mapAdd) {
            // Ambil lokasi user dulu
            getUserLocation(function (userPos) {
        
                let defaultPos = userPos || {lat: 0.5, lng: 101.4};
        
                mapAdd = new google.maps.Map(document.getElementById('mapAdd'), {
                    center: defaultPos,
                    zoom: 16,
                });
        
                markerAdd = new google.maps.Marker({
                    position: defaultPos,
                    map: mapAdd,
                    draggable: true
                });
        
                // Set input awal
                document.getElementById('addLat').value = defaultPos.lat;
                document.getElementById('addLng').value = defaultPos.lng;
        
                // Event marker
                google.maps.event.addListener(markerAdd, 'dragend', function (e) {
                    document.getElementById('addLat').value = e.latLng.lat();
                    document.getElementById('addLng').value = e.latLng.lng();
                });
        
                // Event click map
                mapAdd.addListener('click', function (e) {
                    markerAdd.setPosition(e.latLng);
                    document.getElementById('addLat').value = e.latLng.lat();
                    document.getElementById('addLng').value = e.latLng.lng();
                });
            });
        }

    });

    document.getElementById('btnAddSave').addEventListener('click', function () {
        const lat = document.getElementById('addLat').value;
        const lng = document.getElementById('addLng').value;

        if (!lat || !lng) {
            Swal.fire('Error', 'Koordinat belum dipilih.', 'error');
            return;
        }

        Swal.fire({
            title: 'Yakin simpan data?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal'
        }).then(res => {
            if (res.isConfirmed) {
                document.getElementById('formAddLabor').submit();
            }
        });
    });
}

// ================== MAP MODAL EDIT ==================
let mapEdit, markerEdit;
const modalEditEl = document.getElementById('modalEdit');
if (modalEditEl) {
    modalEditEl.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        const id = btn.getAttribute('data-id');
        const nourut = btn.getAttribute('data-nourut');
        const nama = btn.getAttribute('data-nama');
        const alamat = btn.getAttribute('data-alamat');
        const lat = parseFloat(btn.getAttribute('data-lat'));
        const lng = parseFloat(btn.getAttribute('data-lng'));

        document.getElementById('editLaborId').value = id;
        document.getElementById('editNoUrut').value = nourut;
        document.getElementById('editNama').value = nama;
        document.getElementById('editAlamat').value = alamat;
        document.getElementById('editLat').value = lat;
        document.getElementById('editLng').value = lng;

        const center = (!isNaN(lat) && !isNaN(lng)) ? {lat, lng} : {lat: 0.5, lng: 101.4};

        if (!mapEdit) {
            mapEdit = new google.maps.Map(document.getElementById('mapEdit'), {
                center,
                zoom: 15,
            });
            markerEdit = new google.maps.Marker({
                position: center,
                map: mapEdit,
                draggable: true
            });

            google.maps.event.addListener(markerEdit, 'dragend', function (e) {
                document.getElementById('editLat').value = e.latLng.lat();
                document.getElementById('editLng').value = e.latLng.lng();
            });

            mapEdit.addListener('click', function (e) {
                markerEdit.setPosition(e.latLng);
                document.getElementById('editLat').value = e.latLng.lat();
                document.getElementById('editLng').value = e.latLng.lng();
            });
        } else {
            mapEdit.setCenter(center);
            markerEdit.setPosition(center);
        }
    });

    document.getElementById('btnEditSave').addEventListener('click', function () {
        const lat = document.getElementById('editLat').value;
        const lng = document.getElementById('editLng').value;

        if (!lat || !lng) {
            Swal.fire('Error', 'Koordinat belum dipilih.', 'error');
            return;
        }

        Swal.fire({
            title: 'Yakin simpan perubahan?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal'
        }).then(res => {
            if (res.isConfirmed) {
                document.getElementById('formEditLabor').submit();
            }
        });
    });
}

// ================== DELETE ==================
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('btnDelete')) {
        const id = e.target.getAttribute('data-id');

        Swal.fire({
            title: 'Hapus data ini?',
            text: 'Data tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(res => {
            if (res.isConfirmed) {
                const f = document.createElement('form');
                f.method = 'post';
                f.action = '<?= base_url('usaha/deleteLabor') ?>';

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '<?= csrf_token() ?>';
                csrf.value = '<?= csrf_hash() ?>';
                f.appendChild(csrf);

                const hid = document.createElement('input');
                hid.type = 'hidden';
                hid.name = 'laborId';
                hid.value = id;
                f.appendChild(hid);

                document.body.appendChild(f);
                f.submit();
            }
        });
    }
});
</script>

<?= $this->endSection() ?>
