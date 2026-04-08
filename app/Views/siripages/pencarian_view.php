<?php echo $this->extend('layouts/master') ?>
<?php echo $this->section('content') ?>

<div class="container mt-4">
    <h4>Pencarian Usaha</h4>

    <form id="formCari">
        <?php if ($idkab == '1400'): ?>
            <div class="mb-2">
                <select name="idkab" id="idkab" class="form-select" required>
                    <option value="">-- Pilih Kabupaten --</option>
                    <?php foreach ($kabupaten as $kab): ?>
                        <?php if ($kab['idkab'] != '1400'): ?>
                            <option value="<?= $kab['idkab'] ?>"><?= $kab['nmkab'] ?></option>
                        <?php endif ?>
                    <?php endforeach ?>
                </select>
            </div>
        <?php else: ?>
            <input type="hidden" name="idkab" value="<?= $idkab ?>" id="idkab">
        <?php endif ?>

        <div class="mb-2">
            <select name="idkec" id="idkec" class="form-select" required>
                <option value="">-- Pilih Kecamatan --</option>
                <?php foreach ($kecamatan as $kec): ?>
                    <option value="<?= is_object($kec) ? $kec->idkec : $kec['idkec'] ?>">
                        <?= is_object($kec) ? $kec->nmkec : $kec['nmkec'] ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>
        
        <div class="mb-2">
            <select name="iddesa" id="iddesa" class="form-select" required>
                <option value="all">SEMUA DESA/KELURAHAN</option>
                <?php if (isset($desa)): ?>
                    <?php foreach ($desa as $d): ?>
                        <option value="<?= $d['iddesa'] ?>"><?= $d['nmdesa'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        

        <div class="mb-2">
            <label for="keyword">Keyword</label>
            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Masukkan nama usaha..." required>
        </div>

        <button type="submit" class="btn btn-primary">Cari</button>
    </form>

    <div class="mt-3 text-center" id="spinnerWrapper" style="display:none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="mt-4" id="hasilPencarian"></div>
</div>

<!-- Modal Update -->
<div class="modal fade" id="updateModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= base_url('usaha/update') ?>">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Usaha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="mb-3 text-center mt-3">
                    <h5 id="namaUsahaModal" class="fw-semibold text-primary"></h5>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="placeId" id="placeIdInput">

                    <div class="mb-3">
                        <label>Nama Usaha</label>
                        <input type="text" class="form-control" name="placeName" id="updatePlaceName" required>
                    </div>

                    <div class="mb-3">
                        <label>Alamat Usaha</label>
                        <textarea class="form-control" name="placeAddress" id="updatePlaceAddress" rows="2" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Status Usaha</label>
                        <select name="status_usaha" id="statusUpdateSelect" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="1">Aktif</option>
                            <option value="2">Tidak Aktif</option>
                            <option value="3">Bukan Usaha</option>
                            <option value="4">Tidak Ditemukan</option>
                        </select>
                    </div>

                    <div class="mb-3" id="kategoriWrapperUpdate">
                        <label>Kategori (Optional)</label>
                        <select name="kategori" id="kategoriUpdate" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            <?php if (!empty($kategoriLabels)): foreach ($kategoriLabels as $k => $v): ?>
                                <option value="<?= esc($k) ?>"><?= esc($v) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="btnSaveUpdate">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Usaha (sederhana, seperti sebelumnya) -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="createUsaha" method="post" action="<?= base_url('usaha/insertUsaha') ?>">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Usaha Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <?php if (session('idkab') == '1400'): ?>
                        <!-- Pilih Kabupaten (hanya untuk user provinsi) -->
                        <div class="mb-3">
                            <label>Kabupaten</label>
                            <select name="idkab" id="modal_kabupaten" class="form-select" required>
                                <option value="">-- Pilih Kabupaten --</option>
                                <?php foreach ($kabupaten as $kab): ?>
                                    <?php if ($kab['idkab'] != '1400'): ?>
                                        <option value="<?= $kab['idkab'] ?>"><?= $kab['nmkab'] ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="idkab" value="<?= session('idkab') ?>">
                    <?php endif; ?>

                    <!-- Pilih Kecamatan -->
                    <div class="mb-3">
                        <label>Kecamatan</label>
                        <select name="idkec" id="modal_kecamatan" class="form-select" required>
                            <option value="">-- Pilih Kecamatan --</option>
                            <?php if (session('idkab') != '1400'): ?>
                                <?php foreach ($kecamatan as $kec): ?>
                                    <option value="<?= is_object($kec) ? $kec->idkec : $kec['idkec'] ?>">
                                        <?= is_object($kec) ? $kec->nmkec : $kec['nmkec'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Pilih Desa -->
                    <div class="mb-3">
                        <label>Desa/Kelurahan</label>
                        <select name="iddesa" id="modal_desa" class="form-select" required>
                            <option value="">-- Pilih Desa --</option>
                            <?php if (session('idkab') != '1400' && isset($desa)): ?>
                                <?php foreach ($desa as $d): ?>
                                    <option value="<?= $d['iddesa'] ?>"><?= $d['nmdesa'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Nama Usaha -->
                    <div class="mb-3">
                        <label>Nama Usaha</label>
                        <input type="text" name="placeName" class="form-control" required>
                    </div>

                    <!-- Alamat Usaha -->
                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="placeAddress" class="form-control" rows="2" required></textarea>
                    </div>

                    <!-- Status Usaha (disabled tampilan + value dikirim via hidden) -->
                    <div class="mb-3">
                        <label>Status Usaha</label>
                        <select class="form-select" disabled>
                            <option value="1" selected>Aktif</option>
                        </select>
                        <input type="hidden" name="status_usaha" value="1">
                    </div>

                    <!-- Kategori (optional) -->
                    <div class="mb-3" id="kategoriWrapperTambah">
                        <label>Kategori (Optional)</label>
                        <select name="kategori" id="kategoriTambah" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategoriLabels as $key => $label): ?>
                                <option value="<?= esc($key) ?>"><?= esc($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Koordinat + tombol Ambil Koordinat -->
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <button type="button" onclick="getCoordinates()" class="btn btn-outline-secondary btn-sm">
                            Ambil Koordinat
                        </button>
                        <input type="text" name="latitude" id="latInput" class="form-control form-control-sm w-50 me-2" placeholder="Latitude" required>
                        <input type="text" name="longitude" id="lngInput" class="form-control form-control-sm w-50" placeholder="Longitude" required>
                    </div>

                    <div id="mapTambah" style="height: 300px; display: none;" class="mt-3 rounded border"></div>

                    <!-- OPTIONAL: hidden fields to store kdsls/nmsls if you integrate checkGeojson on this modal -->
                    <input type="hidden" name="kdsls" id="create_kdsls">
                    <input type="hidden" name="nmsls" id="create_nmsls">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="button" id="btnInsertUsaha">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Dependencies: SweetAlert & Leaflet (if needed elsewhere) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($googleMapsKey ?? '')): ?>
<!-- not used in this view but keep optional -->
<?php endif; ?>

<script>
    let mapTambah = null;
    let markerTambah = null;

    function getCoordinates() {
        if (!navigator.geolocation) {
            Swal.fire({ icon: 'warning', title: 'Browser tidak mendukung Geolocation.' });
            return;
        }

        navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            document.getElementById('latInput').value = lat;
            document.getElementById('lngInput').value = lng;

            const mapDiv = document.getElementById('mapTambah');
            mapDiv.style.display = 'block';

            // Jika sudah ada map sebelumnya, hapus
            if (mapTambah) {
                mapTambah.remove();
                mapTambah = null;
                markerTambah = null;
            }

            // Inisialisasi Leaflet map
            mapTambah = L.map('mapTambah').setView([lat, lng], 17);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(mapTambah);

            markerTambah = L.marker([lat, lng], { draggable: true }).addTo(mapTambah);

            // Saat marker digeser, update input lat/lng
            markerTambah.on('dragend', function () {
                const pos = markerTambah.getLatLng();
                document.getElementById('latInput').value = pos.lat;
                document.getElementById('lngInput').value = pos.lng;
            });

            // Klik pada map juga mengubah posisi marker
            mapTambah.on('click', function (e) {
                if (markerTambah) markerTambah.setLatLng(e.latlng);
                else markerTambah = L.marker(e.latlng, { draggable: true }).addTo(mapTambah);

                document.getElementById('latInput').value = e.latlng.lat;
                document.getElementById('lngInput').value = e.latlng.lng;
            });

        }, function (err) {
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Gagal mengambil lokasi.' });
        }, {
            enableHighAccuracy: true,
            timeout: 10000
        });
    }

    // Hapus peta saat modal ditutup supaya tidak duplikat kalau dibuka lagi
    document.getElementById('createModal').addEventListener('hidden.bs.modal', function () {
        if (mapTambah) {
            mapTambah.remove();
            mapTambah = null;
            markerTambah = null;
        }
        document.getElementById('mapTambah').style.display = 'none';
    });

    // Tombol simpan -> validasi sederhana + konfirmasi SweetAlert
    document.getElementById('btnInsertUsaha').addEventListener('click', function () {
        const form = document.getElementById('createUsaha');

        const nama = form.querySelector('input[name="placeName"]').value.trim();
        const alamat = form.querySelector('textarea[name="placeAddress"]').value.trim();
        const lat = form.querySelector('input[name="latitude"]').value.trim();
        const lng = form.querySelector('input[name="longitude"]').value.trim();

        let missing = [];
        if (!nama) missing.push('Nama Usaha');
        if (!alamat) missing.push('Alamat Usaha');
        if (!lat || !lng) missing.push('Koordinat (Latitude/Longitude)');

        if (missing.length) {
            Swal.fire({
                icon: 'warning',
                title: 'Validasi Gagal',
                html: 'Field wajib diisi: <b>' + missing.join(', ') + '</b>'
            });
            return;
        }

        Swal.fire({
            title: 'Yakin simpan usaha baru?',
            text: 'Pastikan data sudah benar.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((res) => {
            if (res.isConfirmed) {
                form.submit();
            }
        });
    });

    // Jika ada picklist kab/kec/desa di modal: bind change untuk ambil kec/desa (fungsi ini sudah ada di view, tapi bila belum)
    <?php if ($idkab == '1400'): ?>
    document.getElementById('modal_kabupaten').addEventListener('change', function () {
        const idkab = this.value;
        const kecSelect = document.getElementById('modal_kecamatan');
        const desaSelect = document.getElementById('modal_desa');

        kecSelect.innerHTML = '<option value="">Loading...</option>';
        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

        fetch("<?= base_url('pencarian/kecamatan') ?>", {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `idkab=${encodeURIComponent(idkab)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const options = data.kecamatan.map(k => `<option value="${k.idkec}">${k.nmkec}</option>`).join('');
                kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>' + options;
            } else {
                kecSelect.innerHTML = '<option value="">-- Tidak ada data --</option>';
            }
        });
    });

    document.getElementById('modal_kecamatan').addEventListener('change', function () {
        const idkec = this.value;
        const desaSelect = document.getElementById('modal_desa');

        desaSelect.innerHTML = '<option value="">Loading...</option>';

        fetch("<?= base_url('pencarian/desa') ?>", {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `idkec=${encodeURIComponent(idkec)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const options = data.desa.map(d => `<option value="${d.iddesa}">${d.nmdesa}</option>`).join('');
                desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>' + options;
            } else {
                desaSelect.innerHTML = '<option value="">-- Tidak ada data --</option>';
            }
        });
    });
    <?php endif; ?>
</script>

<!-- Flash messages dengan Swal -->
<?php if (session()->getFlashdata('success')): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Sukses',
    text: <?= json_encode(session()->getFlashdata('success')) ?>,
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2500
});
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: <?= json_encode(session()->getFlashdata('error')) ?>,
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
</script>
<?php endif; ?>


<script>
document.getElementById('formCari').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = new FormData(this);
    const spinner = document.getElementById('spinnerWrapper');
    const hasil = document.getElementById('hasilPencarian');

    const sessionName = <?= json_encode((string)(session('nama_user') ?? '')) ?>;
    const sessionUserId = <?= json_encode((string)(session('id_user') ?? '')) ?>;

    hasil.innerHTML = '';
    spinner.style.display = 'block';

    fetch("<?= base_url('pencarian/cari') ?>", {
        method: 'POST',
        body: form
    })
    .then(r => r.json())
    .then(res => {
        spinner.style.display = 'none';

        if (!res || res.status === 'error') {
            alert(res?.message || 'Gagal memproses pencarian.');
            return;
        }

        // ambil dropdown selections for map link
        const selectedKab  = document.getElementById('idkab')?.value || '';
        const selectedKec  = document.getElementById('idkec')?.value || '';
        const selectedDesa = document.getElementById('iddesa')?.value || '';

        // base url mapView (sama seperti di tabel usaha)
        const mapViewBase = <?= json_encode(base_url('usaha/mapView')) ?>;

        // helper escape HTML
        function escHtml(s) {
            if (s === null || typeof s === 'undefined') return '';
            return String(s)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        // sort res.data berdasarkan timestamp di last_update_by (descending)
        function parseLastUpdateTime(str) {
            if (!str) return 0;
            // format expected: "YYYY-MM-DD HH:MM:SS oleh NAME"
            const m = str.match(/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/);
            if (!m) return 0;
            const dt = new Date(m[1].replace(' ', 'T') + 'Z'); // treat as UTC if stored as UTC; adjust if local
            if (isNaN(dt.getTime())) {
                // try local parse
                return Date.parse(m[1].replace(' ', 'T')) || 0;
            }
            return dt.getTime();
        }

        const arr = (res.data || []).slice(0);
        arr.sort((a,b) => {
            return parseLastUpdateTime(b.last_update_by) - parseLastUpdateTime(a.last_update_by);
        });

        const rows = arr.map((item, index) => {
            const statusVal = (item.status_usaha === null || item.status_usaha === '' || typeof item.status_usaha === 'undefined')
                ? '' : String(item.status_usaha);

            const lastUpdateBy = (item.last_update_by || '').toLowerCase();
            const me = (sessionName || '').toLowerCase();
            const updatedByYou = (lastUpdateBy && me) ? lastUpdateBy.includes(me) : false;

            // rules for showing update
            let showUpdateBtn = false;
            if (!statusVal) {
                showUpdateBtn = true;
            } else if (['2','3','4'].includes(statusVal)) {
                showUpdateBtn = true;
            } else if (statusVal === '1') {
                if (item.creator_id && String(item.creator_id) === String(sessionUserId)) {
                    showUpdateBtn = true;
                } else {
                    showUpdateBtn = updatedByYou;
                }
            }

            const btnClass = (statusVal === '1') ? 'btn-success' : 'btn-warning';

            // delete button: only if baru == 1 AND creator_id matches sessionUserId
            let deleteBtnHtml = '';
            if (Number(item.baru) === 1 && item.creator_id && String(item.creator_id) === String(sessionUserId)) {
                // create small form with CSRF tokens embedded
                const csrfName = <?= json_encode(csrf_token()) ?>;
                const csrfHash = <?= json_encode(csrf_hash()) ?>;
                deleteBtnHtml = `
                    <form method="post" action="<?= base_url('usaha/deleteUsaha') ?>" class="d-inline ms-1 form-delete-usaha">
                        <input type="hidden" name="${escHtml(csrfName)}" value="${escHtml(csrfHash)}">
                        <input type="hidden" name="placeId" value="${escHtml(item.placeId)}">
                        <button type="button" class="btn btn-danger btn-sm btnDelete">Hapus</button>
                    </form>
                `;
            }

            const actionCell = showUpdateBtn
                ? `<button class="btn btn-sm ${btnClass} btnUpdate"
                        data-placeid="${escHtml(item.placeId)}"
                        data-placename="${escHtml(item.placeName ?? '')}"
                        data-placeaddress="${escHtml(item.placeAddress ?? '')}"
                        data-status="${escHtml(item.status_usaha ?? '')}"
                        data-kategori="${escHtml(item.kategori ?? '')}"
                        data-idkab="${escHtml(item.idkab ?? '')}"
                        data-idkec="${escHtml(item.idkec ?? '')}"
                        data-iddesa="${escHtml(item.iddesa ?? '')}"
                        data-creatorid="${escHtml(item.creator_id ?? '')}"
                        data-bs-toggle="modal"
                        data-bs-target="#updateModal">
                        Update
                   </button>`
                : `<span class="text-success fw-bold">Telah Diupdate</span>`;

            // coordinates
            const latText = item.placeLatitude ? escHtml(item.placeLatitude) : '-';
            const lngText = item.placeLongitude ? escHtml(item.placeLongitude) : '-';

            // map url (same pattern as table usaha)
            const mapUrl = `${mapViewBase}?placeId=${encodeURIComponent(item.placeId || '')}&kabupaten=${encodeURIComponent(selectedKab)}&kecamatan=${encodeURIComponent(selectedKec)}&desa=${encodeURIComponent(selectedDesa)}`;

            const statusText = (statusVal === '')
                ? 'Belum diupdate'
                : ({'1':'1. Aktif','2':'2. Tidak Aktif','3':'3. Bukan Usaha','4':'4. Tidak Ditemukan'}[statusVal] ?? 'Belum diupdate');

            const kategoriText = (!statusVal || statusVal !== '1') ? '-' : escHtml(item.kategori ?? '-');

            const lastUpdateDisplay = item.last_update_by ? escHtml(item.last_update_by) : '-';

            // **PERUBAHAN: pisahkan kolom Desa dan Alamat**
            const nmdesaText = item.nmdesa ? escHtml(item.nmdesa) : '-';
            const alamatText = item.placeAddress ? escHtml(item.placeAddress) : '-';

            return `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        ${escHtml(item.placeName ?? '-')}<br>
                        ${actionCell}
                        ${deleteBtnHtml}
                    </td>
                    <td>${escHtml(item.nmkab ?? '-')}</td>
                    <td>${escHtml(item.nmkec ?? '-')}</td>

                    <!-- Desa dan Alamat terpisah -->
                    <td>${nmdesaText}</td>
                    <td>${alamatText}</td>

                    <td>
                        ${latText}, ${lngText}
                        <br>
                        <a href="${escHtml(mapUrl)}" target="_blank" class="btn btn-outline-info btn-sm mt-1">Lihat Map</a>
                    </td>
                    <td>${statusText}</td>
                    <td>${kategoriText}</td>
                    <td>${lastUpdateDisplay}</td>
                </tr>
            `;
        }).join('');

        hasil.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mt-4">
                <label>Hasil pencarian untuk <b>${escHtml(form.get('keyword') || '')}</b>:</label>
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="me-1" data-feather="plus"></i> Tambah Usaha
                </button>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped nowrap" id="tableHasil" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Tempat</th>
                            <th>Kabupaten</th>
                            <th>Kecamatan</th>
                            <th>Desa</th>
                            <th>Alamat</th>
                            <th>Koordinat</th>
                            <th>Status</th>
                            <th>Kategori</th>
                            <th>Update Terakhir</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>`;

        // render icons if feather available
        if (window.feather && typeof feather.replace === 'function') feather.replace();

        // init DataTable (destroy previous if exists)
        if ($.fn.DataTable.isDataTable('#tableHasil')) {
            $('#tableHasil').DataTable().destroy();
        }
        $("#tableHasil").DataTable({
            responsive: true,
            autoWidth: false,
            paging: true,
            ordering: true,
            searching: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" }
        });

        // Bind Update buttons (delegated)
        document.querySelectorAll('.btnUpdate').forEach(button => {
            button.addEventListener('click', function () {
                const placeId      = this.dataset.placeid || '';
                const placeName    = this.dataset.placename || '';
                const placeAddress = this.dataset.placeaddress || '';
                const status       = this.dataset.status || '';
                const kategori     = this.dataset.kategori || '';

                document.getElementById('placeIdInput').value = placeId;
                document.getElementById('updatePlaceName').value = placeName;
                document.getElementById('updatePlaceAddress').value = placeAddress;
                document.getElementById('statusUpdateSelect').value = status;
                document.getElementById('kategoriUpdate').value = kategori;
                document.getElementById('namaUsahaModal').innerText = placeName;

                // show/hide kategori wrapper depending status
                const kategoriWrapper = document.getElementById('kategoriWrapperUpdate');
                if (status === '1') {
                    kategoriWrapper.style.display = 'block';
                } else {
                    kategoriWrapper.style.display = 'none';
                }
            });
        });

        // Bind delete buttons (delegated)
        document.querySelectorAll('.btnDelete').forEach(btn => {
            btn.addEventListener('click', function () {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Yakin ingin menghapus data ini?',
                    text: 'Data usaha dan histori akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then(function (res2) {
                    if (res2.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

    })
    .catch(err => {
        spinner.style.display = 'none';
        console.error(err);
        alert('Terjadi kesalahan saat memuat data.');
    });

}); // end submit handler
</script>

<?php if ($idkab == '1400'): ?>
<script>
document.getElementById('idkab').addEventListener('change', function () {
    const idkab = this.value;
    const kecSelect = document.getElementById('idkec');
    kecSelect.innerHTML = '<option value="">Loading...</option>';
    fetch("<?= base_url('pencarian/kecamatan') ?>", {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `idkab=${encodeURIComponent(idkab)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const options = data.kecamatan.map(kec => `<option value="${kec.idkec}">${kec.nmkec}</option>`).join('');
            kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>' + options;
        } else {
            kecSelect.innerHTML = '<option value="">-- Tidak ada data --</option>';
        }
    });
});
</script>
<?php endif; ?>

<script>
    // Toggle kategori visibility in modals
    document.addEventListener('change', function (e) {
        if (e.target && e.target.matches('#statusUpdateSelect')) {
            const status = e.target.value;
            const kat = document.getElementById('kategoriWrapperUpdate');
            if (status === '1') kat.style.display = 'block'; else { kat.style.display = 'none'; document.getElementById('kategoriUpdate').value = ''; }
        }
    });

    // confirm update modal submit
    const formUpdate = document.querySelector('#updateModal form');
    formUpdate && formUpdate.addEventListener('submit', function (ev) {
        ev.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin ingin menyimpan perubahan?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(r => {
            if (r.isConfirmed) formUpdate.submit();
        });
    });

    // create modal submit confirmation (if used)
    const formCreate = document.querySelector('#createModal form');
    formCreate && formCreate.addEventListener('submit', function (ev) {
        ev.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin ingin menambahkan usaha ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tambahkan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(r => {
            if (r.isConfirmed) formCreate.submit();
        });
    });
</script>

<?php echo $this->endSection() ?>
