<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-user-edit"></i></div>
                        Laporan Aktivitas: <?= esc($user) ?>
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- pastikan Swal2 sudah terload; kalau belum tambahkan CDN di <head> atau sebelum skrip ini -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (session()->getFlashdata('success')): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: <?= json_encode(session()->getFlashdata('success')) ?>,
        timer: 2500,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: <?= json_encode(session()->getFlashdata('error')) ?>,
        timer: 3500,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
</script>
<?php endif; ?>


<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">Total Usaha: <?= $summary['total_update'] ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">Usaha Existing Diupdate: <?= $summary['existing_update'] ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">Usaha Baru Ditambahkan: <?= $summary['baru_update'] ?></div>
            </div>
        </div>
    </div>

    <!-- Detail Table -->
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-table me-1"></i> Rincian</div>
        <div class="card-body table-responsive">
            <table id="tabelReport" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Establishment</th>
                        <th>Alamat</th>
                        <th>Status Usaha</th>
                        <th>Kondisi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sessionUserId = (string)(session('id_user') ?? '');
                        $sessionUserName = (string)(session('nama_user') ?? '');
                        $no = 1;
                    ?>
                    <?php foreach ($report as $row): ?>
                        <?php
                            $placeId = $row['placeId'];
                            $status = $row['status_usaha'] ?? null;
                            $creator = $creators[$placeId] ?? null; // object or null
                            $isNew = isset($row['baru']) && intval($row['baru']) === 1;

                            // showUpdate logic:
                            // - jika status in [2,3,4] => everyone can see Update
                            // - jika status == 1 => only creator (first history id_siri_user) can see Update
                            // - jika status empty => allow update
                            $showUpdate = false;
                            if (in_array(intval($status), [2,3,4], true)) {
                                $showUpdate = true;
                            } elseif (intval($status) === 1) {
                                if ($creator && ((string)$creator->id_siri_user === $sessionUserId)) {
                                    $showUpdate = true;
                                }
                            } else {
                                // status kosong atau null
                                $showUpdate = true;
                            }

                            // showDelete logic: only creator (first history) can delete AND record must be "baru"
                            $showDelete = ($isNew && $creator && ((string)$creator->id_siri_user === $sessionUserId));
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($row['placeName']) ?></td>
                            <td><?= esc($row['placeAddress']) ?></td>
                            <td><?= isset($statusLabels[$status]) ? $statusLabels[$status] : 'Belum diupdate' ?></td>
                            <td><?= $isNew ? 'Baru' : 'Existing Update' ?></td>
                            <td>
                                <?php if ($showUpdate): ?>
                                    <button
                                        class="btn btn-sm btn-primary btnUpdate"
                                        data-placeid="<?= esc($placeId) ?>"
                                        data-placename="<?= esc($row['placeName']) ?>"
                                        data-placeaddress="<?= esc($row['placeAddress']) ?>"
                                        data-status="<?= esc($row['status_usaha']) ?>"
                                        data-kategori="<?= esc($row['kategori'] ?? '') ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#updateModal">
                                        Update
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted small">Tidak bisa update</span>
                                <?php endif; ?>

                                <?php if ($showDelete): ?>
                                    <form method="post" action="<?= base_url('report/deleteUsaha') ?>" class="d-inline ms-1 form-delete-usaha">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="placeId" value="<?= esc($placeId) ?>">
                                        <button type="button" class="btn btn-danger btn-sm btnDelete">Hapus</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Update Modal (sama seperti sebelumnya) -->
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
                        <select name="status_usaha" id="statusUsahaModal" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="1">Aktif</option>
                            <option value="2">Tidak Aktif</option>
                            <option value="3">Bukan Usaha</option>
                            <option value="4">Tidak Ditemukan</option>
                        </select>
                    </div>
                    <div class="mb-3" id="kategoriWrapperUpdate">
                        <label>Kategori (optional)</label>
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

<script>
    // DataTables
    $(document).ready(function () {
        $('#tabelReport').DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            searching: true,
            pageLength: 10,
            lengthMenu: [5,10,25,50,100],
            language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" }
        });
    });

    // bind Update buttons (delegated)
    document.addEventListener('click', function (e) {
        const t = e.target;
        if (t.matches('.btnUpdate')) {
            const btn = t;
            const placeId = btn.dataset.placeid || '';
            const placeName = btn.dataset.placename || '';
            const placeAddress = btn.dataset.placeaddress || '';
            const status = btn.dataset.status || '';
            const kategori = btn.dataset.kategori || '';

            document.getElementById('placeIdInput').value = placeId;
            document.getElementById('namaUsahaModal').innerText = placeName;
            document.getElementById('updatePlaceName').value = placeName;
            document.getElementById('updatePlaceAddress').value = placeAddress;
            document.getElementById('statusUsahaModal').value = status;
            document.getElementById('kategoriUpdate').value = kategori;

            // show/hide kategori wrapper depending status
            const katWrap = document.getElementById('kategoriWrapperUpdate');
            if (status === '1') katWrap.style.display = 'block'; else { katWrap.style.display = 'none'; document.getElementById('kategoriUpdate').value = ''; }
        }

        // bind delete button (delegated)
        if (t.matches('.btnDelete')) {
            const form = t.closest('form');
            Swal.fire({
                title: 'Yakin ingin menghapus data ini?',
                text: 'Data usaha dan histori akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function (res) {
                if (res.isConfirmed) {
                    form.submit();
                }
            });
        }
    });
</script>

<?= $this->endSection() ?>
