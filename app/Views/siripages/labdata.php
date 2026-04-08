<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<?php
$googleMapsKey = $googleMapsKey ?? '';
$desaList = $desaList ?? [
    ['iddesa' => '1471040001', 'nmdesa' => 'Cinta Raja'],
    ['iddesa' => '1471060005', 'nmdesa' => 'Kedungsari']
];

$statusLabels = [
    1 => '1. Aktif',
    2 => '2. Tidak Aktif',
    3 => '3. Bukan Usaha',
    4 => '4. Tidak Ditemukan',
    5 => '5. Duplikat',
    6 => '6. Tidak Relevan',
    7 => '7. Baru',
];

$creators = $creators ?? []; // pastikan ada supaya tombol Hapus bisa dicek
$selectedDesa = $selectedDesa ?? '';
$selectedSls = $selectedSls ?? 'all';

// Hitung dulu berapa yang akan terlihat (exclude kakilima_flag == 1)
$visibleCount = 0;
if (!empty($usaha) && is_array($usaha)) {
    foreach ($usaha as $u) {
        $kflag = is_array($u) ? ($u['kakilima_flag'] ?? null) : ($u->kakilima_flag ?? null);
        if ((string)$kflag === '1') continue;
        $visibleCount++;
    }
}
?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="map-pin"></i></div>
                        Indepth - Door to Door
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4">

    <form method="get" action="<?= base_url('usaha/lab-dropdown') ?>" class="mb-4" id="formFilter">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label for="desa" class="form-label">Desa</label>
                <select name="desa" id="desa" class="form-select" required>
                    <option value="">-- Pilih Desa/Kelurahan --</option>
                    <?php foreach ($desaList as $d): ?>
                        <option value="<?= esc($d['iddesa']) ?>"
                            <?= (!empty($selectedDesa) && $selectedDesa == $d['iddesa']) ? 'selected' : '' ?>>
                            <?= esc($d['nmdesa']) ?> (<?= esc($d['iddesa']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label for="sls" class="form-label">SLS</label>
                <select name="sls" id="sls" class="form-select" <?= empty($selectedDesa) ? 'disabled' : '' ?>>
                    <option value="all">— Semua SLS —</option>
                    <?php if (!empty($slsOptions)): ?>
                        <?php foreach ($slsOptions as $s): ?>
                            <?php $val = str_pad($s['kdsls'], 4, '0', STR_PAD_LEFT); ?>
                            <option value="<?= esc($val) ?>"
                                <?= (isset($selectedSls) && $selectedSls === $val) ? 'selected' : '' ?>>
                                <?= esc($s['nmsls'] . ' (' . $val . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="col-auto">
                <button class="btn btn-primary" type="submit">Tampilkan</button>
            </div>

            <div class="col-md-12 mt-2">
                <button type="button" id="btnTambahUsaha" class="btn btn-success"
                    data-bs-toggle="modal" data-bs-target="#createModal"
                    style="<?= empty($selectedDesa) ? 'display:none;' : '' ?>">
                    <i data-feather="plus"></i> Tambah Usaha
                </button>
            </div>
        </div>
    </form>


    <?php if ($visibleCount > 0): ?>
        <div class="card">
            <div class="card-body">
                <?php if (!empty($selectedSls) && $selectedSls !== 'all'): ?>
                    <div class="mb-2">
                        <span class="badge bg-secondary">Filter SLS: <?= esc($selectedSls) ?></span>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table id="labTable" class="table table-sm table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Usaha</th>
                                <th>Alamat</th>
                                <th>Desa</th>
                                <th>SLS</th>
                                <th>Koordinat</th>
                                <th>Status Usaha</th>
                                <th>Status Indepth</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; foreach ($usaha as $u):
                            $kflag = is_array($u) ? ($u['kakilima_flag'] ?? null) : ($u->kakilima_flag ?? null);
                            // Lewati baris jika kakilima_flag == 1
                            if ((string)$kflag === '1') continue;

                            $placeId      = is_array($u) ? ($u['placeId'] ?? '') : ($u->placeId ?? '');
                            $placeName    = is_array($u) ? ($u['placeName'] ?? '') : ($u->placeName ?? '');
                            $placeAddress = is_array($u) ? ($u['placeAddress'] ?? '') : ($u->placeAddress ?? '');
                            $nmdesa       = is_array($u) ? ($u['nmdesa'] ?? ($u['iddesa'] ?? '')) : ($u->nmdesa ?? $u->iddesa ?? '');
                            $lat          = is_array($u) ? ($u['placeLatitude'] ?? '') : ($u->placeLatitude ?? '');
                            $lng          = is_array($u) ? ($u['placeLongitude'] ?? '') : ($u->placeLongitude ?? '');
                            $status       = is_array($u) ? ($u['status_usaha'] ?? '') : ($u->status_usaha ?? '');
                            // SLS
                            $nmsls        = is_array($u) ? ($u['nmsls'] ?? '') : ($u->nmsls ?? '');
                            $kdsls        = is_array($u) ? ($u['kdsls'] ?? '') : ($u->kdsls ?? '');
                            $kdsls4       = $kdsls ? str_pad(preg_replace('/\D/','', (string)$kdsls), 4, '0', STR_PAD_LEFT) : '';

                            // indepth flag
                            $indepth = is_array($u) ? ($u['indepth_flag'] ?? null) : ($u->indepth_flag ?? null);
                            $isIndepth = ($indepth == 1);

                            // jenis lokasi usaha (1,2,3 or null) — sumber data: jenis_usaha_lokasi
                            $jenisLok = is_array($u) ? ($u['jenis_usaha_lokasi'] ?? null) : ($u->jenis_usaha_lokasi ?? null);
                            $jenisLabel = '-';
                            if ($jenisLok !== null && $jenisLok !== '') {
                                // normalize as string/number
                                $jl = (string)$jenisLok;
                                if ($jl === '1') $jenisLabel = '1. Bangunan ini';
                                else if ($jl === '2') $jenisLabel = '2. Keliling / kaki lima';
                                else if ($jl === '3') $jenisLabel = '3. Bangunan tetap di lokasi lain';
                                else $jenisLabel = esc($jenisLok);
                            }
                            $statusIndepthLabel = $isIndepth ? 'Sudah Indepth' : 'Belum';
                        ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td>
                                    <?= esc($placeName) ?><br>

                                    <?php
                                        // tombol update (warna tergantung indepth_flag)
                                        $btnClass = $isIndepth ? 'btn-success' : 'btn-warning';
                                    ?>
                                    <button
                                        type="button"
                                        class="btn btn-sm <?= $btnClass ?> mt-2 btnUpdateMinimal"
                                        data-bs-toggle="modal"
                                        data-bs-target="#updateMinimalModal"
                                        data-placeid="<?= esc($placeId) ?>"
                                        data-placename="<?= esc($placeName) ?>"
                                        data-status="<?= esc($status) ?>"
                                        data-address="<?= esc($placeAddress) ?>"
                                        data-iddesa="<?= esc($selectedDesa ?? '') ?>"
                                        data-sls="<?= esc(is_array($u) ? ($u['kdsls'] ?? '') : ($u->kdsls ?? '')) ?>"
                                    >
                                        <?= $isIndepth ? 'Sudah Indepth' : 'Update' ?>
                                    </button>

                                    <?php
                                        $creator = $creators[$placeId] ?? null;
                                        $currentUserId = session('id_user');
                                        if ($creator && isset($creator->id_siri_user) && (string)$creator->id_siri_user === (string)$currentUserId):
                                    ?>
                                        <form action="<?= base_url('usaha/deleteUsaha') ?>" method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus usaha ini?')">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="placeId" value="<?= esc($placeId) ?>">
                                            <!-- return context supaya controller bisa redirect kembali -->
                                            <input type="hidden" name="return_desa" value="<?= esc($selectedDesa ?? '') ?>">
                                            <input type="hidden" name="return_sls" value="<?= esc($selectedSls ?? 'all') ?>">
                                            <button type="submit" class="btn btn-danger btn-sm ms-1 mt-2">Hapus</button>
                                        </form>
                                    <?php endif; ?>
                                </td>

                                <td><?= esc($placeAddress) ?></td>
                                <td><?= esc($nmdesa) ?></td>

                                <td>
                                    <?= $nmsls ? esc($nmsls) : '-' ?>
                                    <?php if ($kdsls4): ?>
                                        <div class="small text-muted">(<?= esc($kdsls4) ?>)</div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= esc($lat) ?>, <?= esc($lng) ?>
                                    <br>
                                    <?php
                                        $kab = esc($selected_kabupaten ?? '');
                                        $kec = esc($selected_kecamatan ?? '');
                                        $des = esc($selected_desa ?? '');
                                        $mapUrl = base_url("usaha/mapView?placeId=" . urlencode($placeId) . "&kabupaten={$kab}&kecamatan={$kec}&desa={$des}");
                                    ?>
                                    <a href="<?= $mapUrl ?>" target="_blank" class="btn btn-sm btn-outline-info mt-1">Lihat Map</a>
                                </td>

                                <td><?= empty($status) ? 'Belum diupdate' : (isset($statusLabels[$status]) ? $statusLabels[$status] : esc($status)) ?></td>

                                <!-- NEW: Status Indepth -->
                                <td><?= esc($statusIndepthLabel) ?></td>

                                <!-- NEW: Lokasi Usaha (mapping dari jenis_usaha_lokasi) jenis_label -->
                            </tr>
                        <?php
                            $no++;
                            endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-info">Silakan pilih wilayah.</div>
    <?php endif; ?>

    <!-- Update Minimal Modal (tidak diubah) -->
    <div class="modal fade" id="updateMinimalModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <form method="post" action="<?= base_url('usaha/update-minimal') ?>" id="formUpdateMinimal" class="modal-content">
          <?= csrf_field() ?>
          <div class="modal-header">
            <h5 class="modal-title">Update Status & Lokasi Usaha</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            <input type="hidden" name="placeId" id="upd_placeId">
            <input type="hidden" name="iddesa" id="upd_iddesa">
            <input type="hidden" name="kdsls" id="upd_kdsls">
            <input type="hidden" name="indepth_flag" id="upd_indepth_flag" value="1">

            <div class="mb-3">
              <label class="form-label fw-semibold">Nama Usaha</label>
              <input type="text" id="upd_placeName"  name="placeName_Usaha" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Status Usaha</label>
              <select name="status_usaha" id="upd_status_usaha" class="form-select" required>
                <option value="">-- Pilih Status --</option>
                <option value="1">1. Aktif</option>
                <option value="2">2. Tidak Aktif</option>
                <option value="3">3. Bukan Usaha</option>
                <option value="4">4. Tidak Ditemukan</option>
                <option value="5">5. Duplikat</option>
                <option value="6">6. Tidak Relevan</option>
                <option value="7">7. Baru</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Alamat Usaha</label>
              <textarea name="placeAddress" id="upd_placeAddress" class="form-control" rows="2" required></textarea>
            </div>

            <div class="mb-3" id="upd_blok_jenis_lokasi" style="display:none;">
              <label class="form-label d-block mb-1">Lokasi usaha berada di</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="upd_jenis_usaha_lokasi" id="upd_lokasi_1" value="1">
                <label class="form-check-label" for="upd_lokasi_1">Bangunan ini</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="upd_jenis_usaha_lokasi" id="upd_lokasi_2" value="2">
                <label class="form-check-label" for="upd_lokasi_2">Keliling / kaki lima</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="upd_jenis_usaha_lokasi" id="upd_lokasi_3" value="3">
                <label class="form-check-label" for="upd_lokasi_3">Bangunan tetap di lokasi lain</label>
              </div>
            </div>

            <div class="mb-3" id="upd_blok_lokasilain" style="display:none;">
              <label for="upd_lokasilain_address" class="form-label">Alamat lengkap usaha di lokasi lain</label>
              <textarea id="upd_lokasilain_address" name="lokasilain_address" class="form-control" rows="2"
                        placeholder="Tulis alamat lengkap usaha tetap di tempat lain"></textarea>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Create Modal (tidak diubah) -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="formCreateLab" method="post" action="<?= base_url('usaha/insertUsahaLab') ?>">
                <?= csrf_field() ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Usaha Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="iddesa" id="create_iddesa" value="<?= esc($selectedDesa ?? '') ?>">
                        <input type="hidden" name="indepth_flag" id="create_indepth_flag" value="1">
                        <input type="hidden" id="create_kdsls" name="kdsls" value="">
                        <input type="hidden" id="create_nmsls" name="nmsls" value="">

                        <div class="mb-3">
                            <label>Nama Usaha</label>
                            <input type="text" class="form-control" name="placeName" required>
                        </div>

                        <div class="mb-3">
                            <label>Alamat</label>
                            <textarea class="form-control" name="placeAddress" rows="2" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Status Usaha</label>
                            <select name="status_usaha" id="status_usaha" class="form-select" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="1">1. Aktif</option>
                                <option value="2">2. Tidak Aktif</option>
                                <option value="3">3. Bukan Usaha</option>
                                <option value="4">4. Tidak Ditemukan</option>
                                <option value="5">5. Duplikat</option>
                                <option value="6">6. Tidak Relevan</option>
                                <option value="7">7. Baru</option>
                            </select>
                            <small class="text-muted">Jika memilih 1 atau 7, tentukan lokasi usaha di bawah.</small>
                        </div>

                        <div class="mb-3" id="blok_jenis_lokasi" style="display:none;">
                            <label class="form-label d-block mb-1">Lokasi usaha berada di</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_usaha_lokasi" id="lokasi_1" value="1">
                                <label class="form-check-label" for="lokasi_1">Bangunan ini</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_usaha_lokasi" id="lokasi_2" value="2">
                                <label class="form-check-label" for="lokasi_2">Keliling / kaki lima</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_usaha_lokasi" id="lokasi_3" value="3">
                                <label class="form-check-label" for="lokasi_3">Bangunan tetap di lokasi lain</label>
                            </div>
                        </div>

                        <div class="mb-3" id="blok_lokasilain" style="display:none;">
                            <label for="lokasilain_address" class="form-label">Alamat lengkap usaha</label>
                            <textarea id="lokasilain_address" name="lokasilain_address" class="form-control" rows="2"
                                      placeholder="Tulis alamat lengkap lokasi usaha (tetap) di tempat lain"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" class="form-control" rows="2"
                                      placeholder="Catatan tambahan (opsional)"></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Geser Peta untuk memilih koordinat (klik / drag marker)</label>
                            <div id="createMap" style="height:420px;border:1px solid #ddd;"></div>
                            <small class="text-muted d-block mt-2">
                                Jika titik masuk ke salah satu polygon SLS (geojson), sistem akan mengisi otomatis
                                <code>kdsls</code> & <code>nmsls</code>.
                            </small>
                        </div>

                        <div class="mb-3 d-flex gap-2">
                            <input type="text" id="create_lat" name="latitude" class="form-control" placeholder="Latitude" required readonly>
                            <input type="text" id="create_lng" name="longitude" class="form-control" placeholder="Longitude" required readonly>
                        </div>

                        <div class="mb-3">
                            <div id="kdslsInfo" class="small text-muted">
                                kdsls: <span id="kdslsValue">-</span>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                nmsls: <span id="nmslsValue">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="btnCreateSubmit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($googleMapsKey)): ?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= esc($googleMapsKey) ?>&libraries=geometry"></script>
<?php endif; ?>

<script>
// ---------- blok jenis lokasi (create) ----------
(function() {
    var statusSel = document.getElementById('status_usaha');
    var blokJenis = document.getElementById('blok_jenis_lokasi');
    var blokLain  = document.getElementById('blok_lokasilain');
    var radio3    = document.getElementById('lokasi_3');
    var radios    = document.querySelectorAll('input[name="jenis_usaha_lokasi"]');
    var addrLain  = document.getElementById('lokasilain_address');

    function resetJenis() {
        if (radios) radios.forEach(function(r){ r.checked = false; });
        if (addrLain) addrLain.value = '';
        if (blokLain) blokLain.style.display = 'none';
    }

    function refreshJenisVisibility() {
        var val = statusSel ? (statusSel.value || '') : '';
        var show = (val === '1' || val === '7');
        if (blokJenis) blokJenis.style.display = show ? 'block' : 'none';
        if (!show) resetJenis();
    }

    if (statusSel) {
        statusSel.addEventListener('change', refreshJenisVisibility);
        // inisialisasi saat modal dibuka (JS lain akan set status saat modal shown)
        refreshJenisVisibility();
    }

    if (radios && radio3) {
        radios.forEach(function(r){
            r.addEventListener('change', function() {
                if (blokLain) blokLain.style.display = (radio3.checked ? 'block' : 'none');
                if (!radio3.checked && addrLain) addrLain.value = '';
            });
        });
    }
})();

// ---------- main scripts ----------
(function () {
    // DataTable init
    if (typeof jQuery !== 'undefined') {
        $(document).ready(function () {
            if ($('#labTable').length && !$.fn.dataTable.isDataTable('#labTable')) {
                $('#labTable').DataTable({
                    responsive: true,
                    paging: true,
                    ordering: true,
                    searching: true,
                    pageLength: 10,
                    lengthMenu: [5,10,25,50,100],
                    language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" }
                });
            }
        });
    }

    // SweetAlert flashdata
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({ icon: 'success', title: 'Berhasil', text: <?= json_encode(session()->getFlashdata('success')) ?> });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({ icon: 'error', title: 'Gagal', text: <?= json_encode(session()->getFlashdata('error')) ?> });
        <?php endif; ?>
    });

    // ---------- Create map (Google Maps) ----------
    var createMap = null, createMarker = null;
    var defaultCenter = { lat: 0.5074, lng: 101.4478 };
    var selectedDesa = '<?= esc($selectedDesa ?? '') ?>';

    var createModalEl = document.getElementById('createModal');
    if (createModalEl) {
        createModalEl.addEventListener('shown.bs.modal', function () {
            var fallback = defaultCenter;
            <?php if (!empty($usaha) && !empty($usaha[0])):
                $first = $usaha[0];
                $fLat = is_array($first) ? ($first['placeLatitude'] ?? null) : ($first->placeLatitude ?? null);
                $fLng = is_array($first) ? ($first['placeLongitude'] ?? null) : ($first->placeLongitude ?? null);
                if ($fLat && $fLng): ?>
                    fallback = { lat: <?= (float)$fLat ?>, lng: <?= (float)$fLng ?> };
            <?php endif; endif; ?>

            setTimeout(function () {
                initCreateMap(fallback);
            }, 50);

            // set default status ke 7 (Baru) supaya blok jenis lokasi muncul
            var st = document.getElementById('status_usaha');
            if (st) {
                st.value = '7';
                st.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    }

    function initCreateMap(center) {
        center = center || defaultCenter;
        var mapDiv = document.getElementById('createMap');
        if (!mapDiv) return;

        if (typeof google === 'undefined' || !google.maps) {
            console.warn('Google Maps JS belum ter-load (periksa API key).');
            return;
        }

        if (!createMap) {
            createMap = new google.maps.Map(mapDiv, {
                center: center,
                zoom: 16,
                streetViewControl: false,
            });
        } else {
            createMap.setCenter(center);
            google.maps.event.trigger(createMap, 'resize');
        }

        if (!createMarker) {
            createMarker = new google.maps.Marker({
                position: center,
                map: createMap,
                draggable: true
            });

            createMarker.addListener('dragend', function () {
                var pos = createMarker.getPosition();
                setCreateLatLng(pos.lat(), pos.lng());
                checkGeojsonAndSetKdsls(pos.lat(), pos.lng());
            });

            createMap.addListener('click', function (e) {
                var ll = e.latLng;
                createMarker.setPosition(ll);
                setCreateLatLng(ll.lat(), ll.lng());
                checkGeojsonAndSetKdsls(ll.lat(), ll.lng());
            });
        } else {
            createMarker.setPosition(center);
        }

        setCreateLatLng(center.lat, center.lng);
        checkGeojsonAndSetKdsls(center.lat, center.lng);
    }

    function setCreateLatLng(lat, lng) {
        var elLat = document.getElementById('create_lat');
        var elLng = document.getElementById('create_lng');
        if (elLat) elLat.value = lat;
        if (elLng) elLng.value = lng;
    }

    function checkGeojsonAndSetKdsls(lat, lng) {
        var iddesa = (document.getElementById('create_iddesa') && document.getElementById('create_iddesa').value) || selectedDesa;
        var elKdslsVal = document.getElementById('kdslsValue');
        var elNmslsVal = document.getElementById('nmslsValue');
        var elKdslsHidden = document.getElementById('create_kdsls');
        var elNmslsHidden = document.getElementById('create_nmsls');

        if (!elKdslsVal || !elKdslsHidden || !elNmslsVal || !elNmslsHidden) return;
        if (!iddesa) {
            elKdslsVal.textContent = '-';
            elNmslsVal.textContent = '-';
            elKdslsHidden.value = '';
            elNmslsHidden.value = '';
            return;
        }

        elKdslsVal.textContent = 'Memeriksa...';
        elNmslsVal.textContent = 'Memeriksa...';

        var fd = new FormData();
        fd.append('lat', lat);
        fd.append('lng', lng);
        fd.append('iddesa', iddesa);
        fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url('usaha/checkGeojson') ?>', {
            method: 'POST',
            body: fd,
            credentials: 'same-origin'
        })
        .then(function (res) { return res.json(); })
        .then(function (json) {
            if (json && json.success) {
                elKdslsVal.textContent = json.kdsls || '-';
                elKdslsHidden.value = json.kdsls || '';

                elNmslsVal.textContent = json.nmsls || '-';
                elNmslsHidden.value = json.nmsls || '';
            } else {
                elKdslsVal.textContent = '-';
                elNmslsVal.textContent = '-';
                elKdslsHidden.value = '';
                elNmslsHidden.value = '';
            }
        })
        .catch(function (err) {
            console.error('checkGeojson error', err);
            elKdslsVal.textContent = '-';
            elNmslsVal.textContent = '-';
            elKdslsHidden.value = '';
            elNmslsHidden.value = '';
        });
    }

    // --------- Create submit: validation + final geojson check + confirmation ----------
    var btnCreate = document.getElementById('btnCreateSubmit');
    if (btnCreate) {
        btnCreate.addEventListener('click', function () {
            var form = document.getElementById('formCreateLab');
            if (!form) return;

            // deklarasikan missing di awal, jangan bikin error JS
            var missing = [];

            var name = (form.querySelector('input[name="placeName"]') && form.querySelector('input[name="placeName"]').value || '').trim();
            var address = (form.querySelector('textarea[name="placeAddress"]') && form.querySelector('textarea[name="placeAddress"]').value || '').trim();
            var lat = parseFloat(document.getElementById('create_lat').value || '');
            var lng = parseFloat(document.getElementById('create_lng').value || '');
            var iddesa = document.getElementById('create_iddesa').value || selectedDesa;

            var statusUsaha = (form.querySelector('select[name="status_usaha"]')?.value || '').trim();
            if (!statusUsaha) missing.push('Status Usaha');

            var needJenis = (statusUsaha === '1' || statusUsaha === '7');
            var jenisLok  = needJenis ? (form.querySelector('input[name="jenis_usaha_lokasi"]:checked')?.value || '') : '';
            if (needJenis && !jenisLok) missing.push('Lokasi usaha');

            var needAddrLain = (needJenis && jenisLok === '3');
            var addrLain = (form.querySelector('#lokasilain_address')?.value || '').trim();
            if (needAddrLain && !addrLain) missing.push('Alamat lengkap usaha (lokasi lain)');

            if (!name) missing.push('Nama usaha');
            if (!address) missing.push('Alamat');
            if (!iddesa) missing.push('Desa');
            if (isNaN(lat) || isNaN(lng)) missing.push('Koordinat');

            if (missing.length) {
                Swal.fire({ icon: 'warning', title: 'Validasi Gagal', html: 'Field wajib diisi: <b>' + missing.join(', ') + '</b>.' });
                return;
            }

            // final check via server to ensure kdsls/nmsls accurate
            var fd = new FormData();
            fd.append('lat', lat);
            fd.append('lng', lng);
            fd.append('iddesa', iddesa);
            fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url('usaha/checkGeojson') ?>', { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(r => r.json())
                .then(function (json) {
                    if (json && json.success) {
                        document.getElementById('create_kdsls').value = json.kdsls || '';
                        document.getElementById('kdslsValue').textContent = json.kdsls || '-';
                        document.getElementById('create_nmsls').value = json.nmsls || '';
                        document.getElementById('nmslsValue').textContent = json.nmsls || '-';
                    } else {
                        document.getElementById('create_kdsls').value = '';
                        document.getElementById('create_nmsls').value = '';
                    }

                    // set kakilima hidden values jika ada blok kaki lima (tetap kompatibel)
                    var chk = document.getElementById('kakilima_flag');
                    var hidFlag = document.getElementById('kakilima_flag_hidden');
                    var hidIddesa = document.getElementById('kakilima_iddesa');
                    if (hidFlag) hidFlag.value = chk && chk.checked ? '1' : '0';
                    if (hidIddesa && !hidIddesa.value && document.getElementById('kakilima_desa') && document.getElementById('kakilima_desa').value) {
                        hidIddesa.value = document.getElementById('kakilima_desa').value;
                    }

                    // konfirmasi akhir sebelum submit
                    Swal.fire({
                        title: 'Yakin akan menyimpan?',
                        text: "Pastikan semua data sudah benar.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, simpan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then(function (res) {
                        if (res.isConfirmed) {
                            form.submit();
                        }
                    });
                }).catch(function (err) {
                    console.error('checkGeojson failed', err);
                    // masih tampilkan konfirmasi walau check gagal
                    Swal.fire({
                        title: 'Yakin akan menyimpan?',
                        text: "Check geojson gagal — tetap lanjut menyimpan?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, simpan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then(function (res) {
                        if (res.isConfirmed) form.submit();
                    });
                });
        });
    }

    // ------------------- KAKI LIMA UI LOGIC (jaga kompatibilitas jika ada) -------------------
    (function () {
        // existing kaki lima logic preserved (tidak diubah di sini)
    })();

})();
</script>

<script>
    (function () {
        var desaSel = document.getElementById('desa');
        var slsSel  = document.getElementById('sls');

        function clearSls() {
            if (!slsSel) return;
            slsSel.innerHTML = '<option value="all">— Semua SLS —</option>';
        }

        function enableSls(enabled) {
            if (!slsSel) return;
            slsSel.disabled = !enabled;
        }

        if (desaSel && slsSel) {
            desaSel.addEventListener('change', function () {
                var iddesa = this.value || '';
                if (!iddesa) {
                    clearSls();
                    enableSls(false);
                    return;
                }
                enableSls(true);
                slsSel.innerHTML = '<option value="all">Memuat...</option>';

                fetch('<?= base_url('usaha/get-sls-by-desa/') ?>' + encodeURIComponent(iddesa), {
                    method: 'GET',
                    credentials: 'same-origin'
                })
                .then(function (r) { return r.json(); })
                .then(function (rows) {
                    clearSls();
                    if (Array.isArray(rows) && rows.length) {
                        rows.forEach(function (row) {
                            var val = (row.kdsls || '').toString().padStart(4, '0');
                            var opt = document.createElement('option');
                            opt.value = val;
                            opt.textContent = (row.nmsls || '') + ' (' + val + ')';
                            slsSel.appendChild(opt);
                        });
                    }
                })
                .catch(function () {
                    clearSls(); // fallback ke "Semua SLS"
                });
            });
        }
    })();
</script>

<script>
    (function(){
      var modal = document.getElementById('updateMinimalModal');
      if (!modal) return;

      // Saat tombol Update ditekan -> isi form modal
      document.addEventListener('click', function(e){
        var btn = e.target.closest('.btnUpdateMinimal');
        if(!btn) return;

        // Ambil data dari tombol (pastikan tombol di table menyertakan data-placename)
        var placeId = btn.getAttribute('data-placeid') || '';
        var placeName = btn.getAttribute('data-placename') || '';
        var address = btn.getAttribute('data-address') || '';
        var iddesa = btn.getAttribute('data-iddesa') || '';
        var kdsls = (btn.getAttribute('data-sls') || '').toString().padStart(4,'0');
        var status = btn.getAttribute('data-status') || '';

        document.getElementById('upd_placeId').value      = placeId;
        document.getElementById('upd_placeName').value    = placeName;
        document.getElementById('upd_placeAddress').value = address;
        document.getElementById('upd_iddesa').value       = iddesa;
        document.getElementById('upd_kdsls').value        = kdsls;

        // Pilih status di select
        var sel = document.getElementById('upd_status_usaha');
        if(sel){
          Array.from(sel.options).forEach(opt => opt.selected = (opt.value === status));
          // trigger change untuk menampilkan blok jenis lokasi jika perlu
          sel.dispatchEvent(new Event('change', { bubbles: true }));
        }

        // reset jenis lokasi & lokasi lain (jika ada)
        document.querySelectorAll('input[name="upd_jenis_usaha_lokasi"]').forEach(r => r.checked = false);
        document.getElementById('upd_lokasilain_address').value = '';
        document.getElementById('upd_blok_jenis_lokasi').style.display = 'none';
        document.getElementById('upd_blok_lokasilain').style.display = 'none';
      });

      // Logic tampil radio/textarea seperti di modal tambah
      var selStatus = document.getElementById('upd_status_usaha');
      var blokJenis = document.getElementById('upd_blok_jenis_lokasi');
      var blokLain  = document.getElementById('upd_blok_lokasilain');
      var radio3    = document.getElementById('upd_lokasi_3');
      var radios    = document.querySelectorAll('input[name="upd_jenis_usaha_lokasi"]');
      var addrLain  = document.getElementById('upd_lokasilain_address');

      function refreshJenisVisibility(){
        var val = selStatus ? selStatus.value : '';
        var show = (val === '1' || val === '7');
        if (blokJenis) blokJenis.style.display = show ? 'block' : 'none';
        if(!show){
          radios.forEach(r => r.checked = false);
          if (blokLain) blokLain.style.display = 'none';
          if (addrLain) addrLain.value = '';
        }
      }

      function refreshLainVisibility(){
        if (!blokLain) return;
        blokLain.style.display = radio3 && radio3.checked ? 'block' : 'none';
        if(!radio3.checked && addrLain) addrLain.value = '';
      }

      if(selStatus) selStatus.addEventListener('change', refreshJenisVisibility);
      radios.forEach(r => r.addEventListener('change', refreshLainVisibility));

      modal.addEventListener('shown.bs.modal', function(){
        refreshJenisVisibility();
        refreshLainVisibility();
      });
    })();
</script>

<style>
    div.dataTables_length { float:left; margin-right:20px; }
    div.dt-buttons { float:left; margin-bottom:10px; }
    div.dataTables_filter { float:right; }
    @media screen and (max-width:768px) {
        div.dataTables_length, div.dt-buttons, div.dataTables_filter { float:none; text-align:center; margin-bottom:10px; }
    }
</style>

<?= $this->endSection() ?>
