<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

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

<!-- Toast Notifications -->
<?php if (session()->has('success')): ?>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div class="toast show align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?= session()->getFlashdata('success') ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if (session()->has('error')): ?>
    <div class="toast show align-items-center text-bg-danger border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"><?= session()->getFlashdata('error') ?></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

<div class="container-fluid px-4">
    <form method="get" action="<?= base_url('usaha') ?>">
        <div class="row mb-4">
            <?php if (session('idkab') == '1400'): ?>
                <div class="col-md-6">
                    <label for="kabupaten">Pilih Kabupaten</label>
                    <select id="kabupaten" name="kabupaten" class="form-select">
                        <option value="">-- Pilih Kabupaten --</option>
                        <?php foreach ($kabupaten as $kab): ?>
                            <?php if ($kab['idkab'] != '1400'): ?>
                                <option value="<?= $kab['idkab'] ?>" <?= ($selected_kabupaten ?? '') == $kab['idkab'] ? 'selected' : '' ?>>
                                    <?= $kab['nmkab'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="col-md-6">
                <label for="kecamatan">Pilih Kecamatan</label>
                <select id="kecamatan" name="kecamatan" class="form-select">
                    <option value="">-- Pilih Kecamatan --</option>
                    <?php foreach ($kecamatan as $kec): ?>
                        <option value="<?= $kec->idkec ?>" <?= ($selected_kecamatan ?? '') == $kec->idkec ? 'selected' : '' ?>>
                            <?= $kec->nmkec ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="desa">Pilih Desa/Kelurahan</label>
                <select id="desa" name="desa" class="form-select">
                    <option value="">-- Pilih Desa --</option>
                    <?php foreach ($desa as $d): ?>
                        <option value="<?= $d->iddesa ?>" <?= ($selected_desa ?? '') == $d->iddesa ? 'selected' : '' ?>>
                            <?= $d->nmdesa ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="sls">Pilih SLS</label>
                <select id="sls" name="sls" class="form-select">
                    <option value="">-- Pilih SLS --</option>
                    <option value="999" <?= ($selected_sls ?? '') === '999' ? 'selected' : '' ?>>
                        Penambahan Baru
                    </option>
                    <?php foreach ($sls as $s): ?>
                        <option value="<?= $s->idsls ?>" <?= ($selected_sls ?? '') === $s->idsls ? 'selected' : '' ?>>
                            <?= $s->nmsls ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2 mt-4" id="tampilkan-wrapper" style="display: none;">
                <button type="submit" class="btn btn-primary">Tampilkan Data</button>
            </div>

        </div>
    </form>

    <?php
        $statusLabels = [
            1  => '1. Aktif',
            2  => '2. Tidak Aktif',
            3  => '3. Bukan Usaha',
            4  => '4. Tidak Ditemukan',
        ];

        $kategoriLabels = [
            'A' => 'A. Pertanian, Kehutanan, dan Perikanan',
            'B' => 'B. Pertambangan dan Penggalian',
            'C' => 'C. Industri Pengolahan',
            'D' => 'D. Pengadaan Listrik dan Gas',
            'E' => 'E. Pengadaan Air, Pengelolaan Sampah, Limbah dan Daur Ulang',
            'F' => 'F. Konstruksi',
            'G' => 'G. Perdagangan Besar dan Eceran; Reparasi Mobil dan Sepeda Motor',
            'H' => 'H. Transportasi dan Pergudangan',
            'I' => 'I. Penyediaan Akomodasi dan Makan Minum',
            'J' => 'J. Informasi dan Komunikasi',
            'K' => 'K. Jasa Keuangan dan Asuransi',
            'L' => 'L. Real Estate',
            'M' => 'M. Aktivitas Profesional, Ilmiah, dan Teknis',
            'N' => 'N. Aktivitas Penyewaan dan Sewa Guna Usaha Tanpa Opsi',
            'O' => 'O. Administrasi Pemerintahan, Pertahanan dan Jaminan Sosial Wajib',
            'P' => 'P. Jasa Pendidikan',
            'Q' => 'Q. Jasa Kesehatan dan Kegiatan Sosial',
            'R' => 'R. Kesenian, Hiburan, dan Rekreasi',
            'S' => 'S. Aktivitas Jasa Lainnya',
            'T' => 'T. Aktivitas Sebagai Pemberi Kerja, dll',
            'U' => 'U. Aktivitas Badan Internasional Lainnya',
        ];
    ?>

    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelUsaha" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Usaha</th>
                            <th>Alamat</th>
                            <th>SLS</th>
                            <th>Koordinat</th>
                            <th>Status</th>
                            <th>Kategori</th>
                            <th>Update Terakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($usaha as $u): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <?= esc($u['placeName']) ?><br>
                                    <?php
                                        // ambil status sebagai string agar aman
                                        $statusVal     = isset($u['status_usaha']) ? (string)$u['status_usaha'] : '';
                                        $statusAda     = $statusVal !== '' && $statusVal !== '0' && $statusVal !== null;
                                        $lastBy        = (string)($u['last_update_by'] ?? '');
                                        $sessionNama   = (string)(session('nama_user') ?? '');
                                        $updatedByYou  = $statusAda && $sessionNama !== '' && strpos($lastBy, $sessionNama) !== false;

                                        // aturan baru:
                                        // - jika status kosong => semua boleh update
                                        // - jika status in [2,3,4] => semua boleh update
                                        // - jika status == 1 => hanya yang meng-update terakhir (updatedByYou) boleh update
                                        $showUpdateBtn = false;
                                        if (!$statusAda) {
                                            $showUpdateBtn = true;
                                        } else {
                                            if (in_array($statusVal, ['2','3','4'], true)) {
                                                $showUpdateBtn = true;
                                            } elseif ($statusVal === '1') {
                                                $showUpdateBtn = $updatedByYou;
                                            } else {
                                                // fallback: jangan tampilkan
                                                $showUpdateBtn = false;
                                            }
                                        }
                                    ?>

                                    <?php if ($showUpdateBtn): ?>
                                        <button
                                            class="btn btn-sm <?= ($statusAda && $statusVal === '1') ? 'btn-success' : 'btn-warning' ?> btnUpdate"
                                            data-placeid="<?= esc($u['placeId']) ?>"
                                            data-placename="<?= esc($u['placeName']) ?>"
                                            data-placeaddress="<?= esc($u['placeAddress']) ?>"
                                            data-status="<?= esc($u['status_usaha']) ?>"
                                            data-kategori="<?= esc($u['kategori']) ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#updateModal">
                                            Update
                                        </button>
                                    <?php else: ?>
                                        <span class="text-success fw-bold">Telah Diupdate</span>
                                    <?php endif; ?>

                                    <?php
                                        // Tombol hapus tetap sesuai creator pertama (opsional)
                                        $creator = $creators[$u['placeId']] ?? null;
                                        $currentUserId = session('id_user');
                                        if ($creator && isset($creator->id_siri_user) && (string)$creator->id_siri_user === (string)$currentUserId):
                                    ?>
                                        <form action="<?= base_url('usaha/deleteUsaha') ?>" method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus usaha ini?')">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="placeId" value="<?= $u['placeId'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm ms-1">Hapus</button>
                                        </form>
                                    <?php endif; ?>
                                </td>

                                <td><?= esc($u['placeAddress']) ?></td>
                                <td><?= esc($u['nmsls']) ?></td>
                                <td>
                                    <?= !empty($u['placeLatitude']) ? esc($u['placeLatitude']) : '-' ?>, <?= !empty($u['placeLongitude']) ? esc($u['placeLongitude']) : '-' ?>
                                    <br>
                                    <?php
                                        $kab = esc($selected_kabupaten ?? '');
                                        $kec = esc($selected_kecamatan ?? '');
                                        $des = esc($selected_desa ?? '');
                                        $mapUrl = base_url("usaha/mapView?placeId=" . urlencode($u['placeId']) . "&kabupaten={$kab}&kecamatan={$kec}&desa={$des}");
                                    ?>
                                    <a href="<?= $mapUrl ?>" target="_blank" class="btn btn-sm btn-outline-info mt-1">
                                        Lihat Map
                                    </a>
                                </td>

                                <td><?= isset($statusLabels[$u['status_usaha']]) ? $statusLabels[$u['status_usaha']] : 'Belum diupdate' ?></td>
                                <td>
                                    <?php
                                        if (empty($u['status_usaha'])) {
                                            echo 'Belum diupdate';
                                        } elseif ($u['status_usaha'] != 1) {
                                            echo '-';
                                        } else {
                                            echo isset($kategoriLabels[$u['kategori']]) ? $kategoriLabels[$u['kategori']] : '-';
                                        }
                                    ?>
                                </td>

                                <td><?php
                                    if (!empty($u['last_update_by']) && strpos($u['last_update_by'], 'oleh') !== false) {
                                        list($tanggalWaktu, $oleh) = explode(' oleh ', $u['last_update_by'], 2);
                                        try {
                                            $date = new DateTime($tanggalWaktu);
                                            $formatted = $date->format('d M Y H:i');
                                            $bulanIndonesia = [
                                                'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar',
                                                'Apr' => 'Apr', 'May' => 'Mei', 'Jun' => 'Jun',
                                                'Jul' => 'Jul', 'Aug' => 'Agu', 'Sep' => 'Sep',
                                                'Oct' => 'Okt', 'Nov' => 'Nov', 'Dec' => 'Des'
                                            ];
                                            $indobulan = strtr($formatted, $bulanIndonesia);
                                            echo $indobulan . '<br>oleh ' . $oleh;
                                        } catch (\Exception $e) {
                                            echo esc($u['last_update_by']);
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>

<div class="container-fluid px-4" id="wrapper-cari" style="display: none;">
    <form method="get" action="<?= base_url('usaha') ?>" id="formCari">
        <input type="hidden" name="kabupaten" id="hidden_kabupaten">
        <input type="hidden" name="kecamatan" id="hidden_kecamatan">
        <input type="hidden" name="desa" id="hidden_desa">
        <input type="hidden" name="sls" id="hidden_sls">
    </form>
</div>

<div class="container-fluid px-4">
    <div class="page-header-content">
        <?php if (
            (!empty($selected_kecamatan) && !empty($selected_desa)) ||
            (!empty($_GET['keyword']))
        ): ?>
            <div class="d-flex justify-content-end pt-3 mb-3">
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="me-1" data-feather="plus"></i>
                    Tambah Usaha
                </button>
            </div>
        <?php endif; ?>
    </div>
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
                </div><br>
                <div class="mb-3 text-center">
                    <h5 id="namaUsahaModal" class="fw-semibold"></h5>
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
                        <select name="status_usaha" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="1">Aktif</option>
                            <option value="2">Tidak Aktif</option>
                            <option value="3">Bukan Usaha</option>
                            <option value="4">Tidak Ditemukan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Kategori (optional)</label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="A">A. Pertanian, Kehutanan, dan Perikanan</option>
                            <option value="B">B. Pertambangan dan Penggalian</option>
                            <option value="C">C. Industri Pengolahan</option>
                            <option value="D">D. Pengadaan Listrik dan Gas</option>
                            <option value="E">E. Pengadaan Air, Pengelolaan Sampah, Limbah dan Daur Ulang</option>
                            <option value="F">F. Konstruksi</option>
                            <option value="G">G. Perdagangan Besar dan Eceran; Reparasi Mobil dan Sepeda Motor</option>
                            <option value="H">H. Transportasi dan Pergudangan</option>
                            <option value="I">I. Penyediaan Akomodasi dan Makan Minum</option>
                            <option value="J">J. Informasi dan Komunikasi</option>
                            <option value="K">K. Jasa Keuangan dan Asuransi</option>
                            <option value="L">L. Real Estate</option>
                            <option value="M">M. Aktivitas Profesional, Ilmiah, dan Teknis</option>
                            <option value="N">N. Aktivitas Penyewaan dan Sewa Guna Usaha Tanpa Opsi</option>
                            <option value="O">O. Administrasi Pemerintahan, Pertahanan dan Jaminan Sosial Wajib</option>
                            <option value="P">P. Jasa Pendidikan</option>
                            <option value="Q">Q. Jasa Kesehatan dan Kegiatan Sosial</option>
                            <option value="R">R. Kesenian, Hiburan, dan Rekreasi</option>
                            <option value="S">S. Aktivitas Jasa Lainnya</option>
                            <option value="T">T. Aktivitas Sebagai Pemberi Kerja, dll</option>
                            <option value="U">U. Aktivitas Badan Internasional Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between align-items-center flex-wrap w-100">
                    <button type="button" class="btn btn-success" id="btnConfirmSubmit">Simpan</button>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Usaha (tetap seperti sebelumnya) -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= base_url('usaha/insertUsaha') ?>">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Usaha Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="iddesa" value="<?= $selected_desa ?? '' ?>">

                    <div class="mb-3">
                        <label>Nama Desa/Kelurahan</label>
                        <input type="text" class="form-control" value="<?= $selected_desa ? ($desa[array_search($selected_desa, array_column($desa, 'iddesa'))]->nmdesa ?? '-') : '' ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Nama Usaha</label>
                        <input type="text" class="form-control" name="placeName" required>
                    </div>

                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea class="form-control" name="placeAddress" rows="2" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Kategori (Optional)</label>
                        <select name="kategori" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="A">A. Pertanian, Kehutanan, dan Perikanan</option>
                            <option value="B">B. Pertambangan dan Penggalian</option>
                            <option value="C">C. Industri Pengolahan</option>
                            <option value="D">D. Pengadaan Listrik dan Gas</option>
                            <option value="E">E. Pengadaan Air, Pengelolaan Sampah, Limbah dan Daur Ulang</option>
                            <option value="F">F. Konstruksi</option>
                            <option value="G">G. Perdagangan Besar dan Eceran; Reparasi Mobil dan Sepeda Motor</option>
                            <option value="H">H. Transportasi dan Pergudangan</option>
                            <option value="I">I. Penyediaan Akomodasi dan Makan Minum</option>
                            <option value="J">J. Informasi dan Komunikasi</option>
                            <option value="K">K. Jasa Keuangan dan Asuransi</option>
                            <option value="L">L. Real Estate</option>
                            <option value="M">M. Aktivitas Profesional, Ilmiah, dan Teknis</option>
                            <option value="N">N. Aktivitas Penyewaan dan Sewa Guna Usaha Tanpa Opsi</option>
                            <option value="O">O. Administrasi Pemerintahan, Pertahanan dan Jaminan Sosial Wajib</option>
                            <option value="P">P. Jasa Pendidikan</option>
                            <option value="Q">Q. Jasa Kesehatan dan Kegiatan Sosial</option>
                            <option value="R">R. Kesenian, Hiburan, dan Rekreasi</option>
                            <option value="S">S. Aktivitas Jasa Lainnya</option>
                            <option value="T">T. Aktivitas Sebagai Pemberi Kerja, dll</option>
                            <option value="U">U. Aktivitas Badan Internasional Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3 d-flex justify-content-between">
                        <button type="button" onclick="getCoordinates()" class="btn btn-outline-secondary btn-sm">
                            Ambil Koordinat
                        </button>
                        <input type="text" name="latitude" id="latInput" class="form-control form-control-sm w-50 me-2" placeholder="Latitude" required>
                        <input type="text" name="longitude" id="lngInput" class="form-control form-control-sm w-50" placeholder="Longitude" required>
                    </div>

                    <div id="mapTambah" style="height: 300px; display: none;" class="mt-3 rounded border"></div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="button" id="btnInsertUsaha">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
    // Inisialisasi: Tangani event saat kabupaten dipilih (khusus untuk user provinsi)
    document.getElementById('kabupaten')?.addEventListener('change', function () {
        const idkab = this.value;
        if (!idkab) return;

        // Fetch kecamatan berdasarkan idkab
        fetch("<?= base_url('usaha/getKecamatan/') ?>" + idkab)
            .then(res => res.json())
            .then(data => {
                const kecSelect = document.getElementById('kecamatan');
                kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.idkec;
                    option.text = item.nmkec;
                    kecSelect.appendChild(option);
                });

                // Reset dropdown desa
                document.getElementById('desa').innerHTML = '<option value="">-- Pilih Desa --</option>';
            });
    });

    // Tangani event saat kecamatan dipilih â†’ isi desa
    document.getElementById('kecamatan')?.addEventListener('change', function () {
        const idkec = this.value;
        if (!idkec) return;

        // Fetch desa berdasarkan idkec
        fetch("<?= base_url('usaha/getDesa/') ?>" + idkec)
            .then(res => res.json())
            .then(data => {
                const desaSelect = document.getElementById('desa');
                desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.iddesa;
                    option.text = item.nmdesa;
                    desaSelect.appendChild(option);
                });
            });
    });
    
    document.addEventListener('DOMContentLoaded', function () {
        const desaSelect = document.getElementById('desa');
        const slsSelect = document.getElementById('sls');
    
        if (!desaSelect || !slsSelect) return;
    
        desaSelect.addEventListener('change', function () {
            const desaId = this.value;
            console.log('Desa dipilih:', desaId);
    
            // Kosongkan dropdown terlebih dahulu
            slsSelect.innerHTML = '';
    
            // (1) Tambahkan option default "-- Pilih SLS --"
            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = '-- Pilih SLS --';
            slsSelect.appendChild(defaultOpt);
    
            // Jika tidak ada desa, cukup tampilkan default dan berhenti
            if (!desaId) return;
    
            // Fetch data SLS dari server
            fetch("<?= base_url('usaha/getSLS/') ?>" + desaId)
                .then(res => res.json())
                .then(data => {
                    // (2) Tambahkan "Penambahan Baru"
                    const newOpt = document.createElement('option');
                    newOpt.value = '999';
                    newOpt.textContent = 'Penambahan Baru';
                    slsSelect.appendChild(newOpt);
    
                    // (3) Tambahkan data SLS dari server
                    data.forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = item.idsls;
                        opt.textContent = item.nmsls;
                        slsSelect.appendChild(opt);
                    });
                })
                .catch(err => {
                    console.error('Gagal mengambil data SLS:', err);
                });
        });
    });
    
    document.querySelectorAll('.btnUpdate').forEach(button => {
        button.addEventListener('click', function () {
            const placeId = this.dataset.placeid;
            const placeName = this.dataset.placename;
            const placeAddress = this.dataset.placeaddress;
            const status = this.dataset.status;
            const kategori = this.dataset.kategori;
    
            document.getElementById('placeIdInput').value = placeId;
            document.getElementById('namaUsahaModal').textContent = placeName;
            document.getElementById('updatePlaceName').value = placeName;
            document.getElementById('updatePlaceAddress').value = placeAddress;
            document.querySelector('select[name="status_usaha"]').value = status;
            document.querySelector('select[name="kategori"]').value = kategori;
    
            // trigger visibilitas kategori
            const event = new Event('change');
            document.querySelector('select[name="status_usaha"]').dispatchEvent(event);
        });
    });


</script>

<script>
    $(document).ready(function () {
        $('#tabelUsaha').DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            searching: false,
            pageLength: 10, // Default tampilkan 10
            lengthMenu: [5, 10, 25, 50, 100], // Pilihan dropdown

            dom: 'lBfrtip', // âœ… ini penting: 'l' = length menu, 'B' = buttons, 'f' = filter, etc.
            buttons: [
                <?php if (session('id_role') == 1): ?>
                {
                    extend: 'excelHtml5',
                    text: '📥 Export Excel',
                    className: 'btn btn-success btn-sm'
                }
                <?php endif; ?>
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>

<style>
    /* Jarak antara length dropdown dan tombol */
    div.dataTables_length {
        float: left;
        margin-right: 20px;
    }

    /* Jarak tombol export */
    div.dt-buttons {
        float: left;
        margin-bottom: 10px;
    }

    /* Search input ke kanan */
    div.dataTables_filter {
        float: right;
    }

    /* Biar tidak saling tabrakan di layar kecil */
    @media screen and (max-width: 768px) {
        div.dataTables_length,
        div.dt-buttons,
        div.dataTables_filter {
            float: none;
            text-align: center;
            margin-bottom: 10px;
        }
    }
</style>


<script>
    document.getElementById('btnConfirmSubmit').addEventListener('click', function () {
        const form = this.closest('form'); // ambil elemen <form> terdekat
    
        const namaUsaha = form.querySelector('input[name="placeName"]').value.trim();
        const alamatUsaha = form.querySelector('textarea[name="placeAddress"]').value.trim();
        const statusUsaha = form.querySelector('select[name="status_usaha"]').value;
        const kategori = form.querySelector('select[name="kategori"]').value;
    
        let errorMessages = [];
    
        if (!namaUsaha) {
            errorMessages.push('Nama usaha');
        }
        if (!alamatUsaha) {
            errorMessages.push('Alamat usaha');
        }
        if (!statusUsaha) {
            errorMessages.push('Status usaha');
        }
    
        if (errorMessages.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Validasi Gagal',
                html: 'Field wajib diisi: <b>' + errorMessages.join(', ') + '</b>.',
            });
            return;
        }
    
        // Konfirmasi jika semua valid
        Swal.fire({
            title: 'Yakin simpan?',
            text: "Pastikan semua data sudah benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    document.getElementById('btnInsertUsaha').addEventListener('click', function () {
        const form = this.closest('form'); // pastikan ambil form yang benar
    
        const namaUsaha = form.querySelector('input[name="placeName"]').value.trim();
        const alamatUsaha = form.querySelector('textarea[name="placeAddress"]').value.trim();
        const kategori = form.querySelector('select[name="kategori"]').value;
    
        if (!namaUsaha || !alamatUsaha) {
            Swal.fire({
                icon: 'warning',
                title: 'Validasi Gagal',
                text: 'Field wajib diisi: Nama usaha, Alamat.',
            });
            return;
        }
    
        Swal.fire({
            title: 'Yakin simpan usaha baru?',
            text: "Pastikan semua data sudah terisi dengan benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Submit form jika valid
            }
        });
    });
</script>

<script>

    let mapTambah, markerTambah;

    function getCoordinates() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
    
                document.getElementById('latInput').value = lat;
                document.getElementById('lngInput').value = lng;
    
                const mapDiv = document.getElementById('mapTambah');
                mapDiv.style.display = 'block'; // Tampilkan peta
    
                // Jika sudah ada map sebelumnya, hapus
                if (mapTambah) {
                    mapTambah.remove();
                }
    
                mapTambah = L.map('mapTambah').setView([lat, lng], 17);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(mapTambah);
    
                markerTambah = L.marker([lat, lng], { draggable: true }).addTo(mapTambah);
    
                // Saat marker digeser, update input lat/lng
                markerTambah.on('dragend', function (e) {
                    const pos = markerTambah.getLatLng();
                    document.getElementById('latInput').value = pos.lat;
                    document.getElementById('lngInput').value = pos.lng;
                });
    
                // Jika user klik map, juga pindahkan marker
                mapTambah.on('click', function (e) {
                    markerTambah.setLatLng(e.latlng);
                    document.getElementById('latInput').value = e.latlng.lat;
                    document.getElementById('lngInput').value = e.latlng.lng;
                });
    
            }, function () {
                alert("Gagal mengambil lokasi.");
            });
        } else {
            alert("Browser tidak mendukung Geolocation.");
        }
    }
    
    document.getElementById('createModal').addEventListener('hidden.bs.modal', function () {
        if (mapTambah) {
            mapTambah.remove();
            mapTambah = null;
        }
        document.getElementById('mapTambah').style.display = 'none';
    });



</script>

<script>
    const kabupaten = document.getElementById('kabupaten');
    const kecamatan = document.getElementById('kecamatan');
    const desa = document.getElementById('desa');
    const sls = document.getElementById('sls'); // Gunakan 'sls', bukan 'slsVal'
    const tampilkanWrapper = document.getElementById('tampilkan-wrapper');
    const tampilkanWrapperCari = document.getElementById('wrapper-cari');

    function checkDropdowns() {
        const kab = kabupaten ? kabupaten.value : 'selected'; 
        const kec = kecamatan ? kecamatan.value : '';
        const des = desa ? desa.value : '';
        const slsVal = sls ? sls.value : '';

        // Tampilkan tombol hanya jika semua dropdown terisi (sls boleh '999')
        if (kab && kec && des && slsVal !== '') {
            tampilkanWrapper.style.display = 'block';
        } else {
            tampilkanWrapper.style.display = 'none';
        }
        
        // Tampilkan tombol hanya jika semua dropdown terisi (sls boleh '999')
        if (kab && kec && des) {
            tampilkanWrapperCari.style.display = 'block';
        } else {
            tampilkanWrapperCari.style.display = 'none';
        }
    }


    if (kabupaten) kabupaten.addEventListener('change', checkDropdowns);
    if (kecamatan) kecamatan.addEventListener('change', checkDropdowns);
    if (desa) desa.addEventListener('change', checkDropdowns);
    if (sls) sls.addEventListener('change', checkDropdowns);

    // Trigger on page load
    window.addEventListener('DOMContentLoaded', checkDropdowns);
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusSelect = document.querySelector('select[name="status_usaha"]');
        const kategoriDiv = document.querySelector('select[name="kategori"]').closest('.mb-3');
    
        function toggleKategoriVisibility() {
            if (statusSelect.value && statusSelect.value !== '1') {
                kategoriDiv.style.display = 'none';
                document.querySelector('select[name="kategori"]').value = '';
            } else {
                kategoriDiv.style.display = 'block';
            }
        }
    
        // Trigger saat buka modal
        statusSelect.addEventListener('change', toggleKategoriVisibility);
        toggleKategoriVisibility(); // Saat pertama modal terbuka
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formCari = document.querySelector('form[action*="usaha"]#formCari'); // pastikan form punya id="formCari"
        if (formCari) {
            formCari.addEventListener('submit', function () {
                // Ambil nilai dari dropdown yang ada di atas (form pertama)
                document.getElementById('hidden_kabupaten').value = document.getElementById('kabupaten')?.value || '';
                document.getElementById('hidden_kecamatan').value = document.getElementById('kecamatan')?.value || '';
                document.getElementById('hidden_desa').value = document.getElementById('desa')?.value || '';
                document.getElementById('hidden_sls').value = document.getElementById('sls')?.value || '';
            });
        }
    });
    
    document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('keyword')) {
            const inputKeyword = document.getElementById('keyword');
            if (inputKeyword) {
                inputKeyword.value = '';
            }
        }
    });
    
</script>



<?= $this->endSection() ?>
