<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="layers"></i></div>
                        Master Kegiatan
                    </h1>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <button class="btn btn-sm btn-light text-primary" type="button" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="me-1" data-feather="plus"></i>
                        Tambah Kegiatan
                    </button>
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
    <div class="card mb-4">
        <div class="card-body">
            <table id="datatablesSimple" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kegiatan</th>
                        <th>Fungsi</th>
                        <th>Alias</th>
                        <th>Output</th>
                        <th>Keterangan</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kegiatans as $kegiatan): ?>
                        <tr>
                            <td><?= $kegiatan['id_kegiatan'] ?></td>
                            <td><?= $kegiatan['nama_kegiatan'] ?></td>
                            <td><?= $kegiatan['fungsi'] ?></td>
                            <td><?= $kegiatan['alias'] ?></td>
                            <td><?= $kegiatan['nama_output'] ?></td>
                            <td><?= $kegiatan['keterangan'] ?? '-' ?></td>
                            <td>
                                <button class="btn btn-datatable btn-icon btn-transparent-dark me-2" type="button" data-bs-toggle="modal" data-bs-target="#editModal" 
                                    onclick="editKegiatan(
                                        '<?= $kegiatan['id_kegiatan'] ?>',
                                        '<?= $kegiatan['nama_kegiatan'] ?>',
                                        '<?= $kegiatan['fungsi'] ?>',
                                        '<?= $kegiatan['alias'] ?>', 
                                        '<?= $kegiatan['keterangan'] ?? '' ?>',
                                        '<?= $kegiatan['periode'] ?>',
                                        '<?= $kegiatan['nama_output'] ?>'
                                    )">
                                    <i data-feather="edit"></i>
                                </button>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="<?= $kegiatan['id_kegiatan'] ?>">
                                    <i data-feather="trash-2"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('mastercontroller/create_kegiatan') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="mb-3">
                        <label for="id_output" class="form-label">Pilih Output</label>
                        <select name="id_output" id="id_output" class="form-control select2" style="width: 100% !important;" required>
                            <option value="" selected disabled>Pilih Output</option>
                            <?php foreach ($outputs as $output): ?>
                                <option value="<?= $output['id_output'] ?>">
                                    <?= $output['nama_output'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="fungsi" class="form-label">Fungsi</label>
                        <input type="text" name="fungsi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="alias" class="form-label">Alias</label>
                        <input type="text" name="alias" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="periode" class="form-label">Jangka Waktu</label>
                        <select name="periode" id="periode" class="form-control" style="width: 100% !important;" required>
                            <option value="" selected disabled>-- Pilih --</option>
                            <?php 
                                $outputs = ['Tahunan', 'Semesteran', 'Triwulanan', 'Subround', 'Bulanan', 'Lainnya']; // Array output
                                foreach ($outputs as $output): 
                            ?>
                                <option value="<?= $output ?>">
                                    <?= $output ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pelaksana" class="form-label">Pelaksana</label>
                        <input type="number" name="pelaksana" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editForm" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">  
                    <div class="mb-3">
                        <label for="edit_nama_output" class="form-label">Nama Output</label>
                        <input type="text" name="nama_output" id="edit_nama_output" class="form-control" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama_kegiatan" class="form-label">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" id="edit_nama_kegiatan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_fungsi" class="form-label">Fungsi</label>
                        <input type="text" name="fungsi" id="edit_fungsi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_alias" class="form-label">Alias</label>
                        <input type="text" name="alias" id="edit_alias" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_periode" class="form-label">Periode</label>
                        <input type="text" name="periode" id="edit_periode" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Apakah Anda yakin ingin menghapus data ini?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<script>
    function editKegiatan(id, nama, fungsi, alias, keterangan, periode, nama_output) {
        document.getElementById('edit_nama_kegiatan').value = nama;
        document.getElementById('edit_fungsi').value = fungsi;
        document.getElementById('edit_alias').value = alias;
        document.getElementById('edit_keterangan').value = keterangan;
        document.getElementById('edit_periode').value = periode;
        document.getElementById('edit_nama_output').value = nama_output;

        document.getElementById('editForm').action = "<?= base_url('mastercontroller/update_kegiatan/') ?>" + id;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('[data-bs-target="#confirmDeleteModal"]');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                confirmDeleteBtn.href = "<?= base_url('mastercontroller/delete_kegiatan/') ?>" + id;
            });
        });
    });

</script>

<?= $this->endSection() ?>
