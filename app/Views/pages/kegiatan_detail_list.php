<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="layers"></i></div>
                        Master Kegiatan - Detail Periode
                    </h1>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <button class="btn btn-sm btn-light text-primary" type="button" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="me-1" data-feather="plus"></i>
                        Tambah Kegiatan Detail (Periode)
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
                <div class="toast-container position-fixed top-0 end-0 p-3">
                    <div class="toast show align-items-center text-bg-danger border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body"><?= session()->getFlashdata('error') ?></div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
            </div>
        <?php endif; ?>

<div class="container-fluid px-4">
    <!-- Filter Output -->
    <form method="post" action="<?= base_url('mastercontroller/read_kegiatan_detail') ?>">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="id_output_detail" class="form-label">Pilih Output</label>
                <select name="id_output_detail" id="id_output_detail" class="form-control select2" required>
                    <option value="">-- Semua Output --</option>
                    <?php foreach ($outputs as $output): ?>
                        <option value="<?= $output['id_output'] ?>" 
                            <?= isset($selected_output) && $selected_output == $output['id_output'] ? 'selected' : '' ?>>
                            <?= $output['nama_output'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 mt-4">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Tabel Data -->
    <div class="card mb-4">
        <div class="card-body">
            <table id="datatablesSimple" class="table">
                <thead>
                    <tr>
                        <th>ID Detail</th>
                        <th>Nama Kegiatan</th>
                        <th>Nama Kegiatan Detail</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Periode</th>
                        <th>Keterangan</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($kegiatan_details)): ?>
                        <?php foreach ($kegiatan_details as $detail): ?>
                            <tr>
                                <td><?= $detail['id_kegiatan_detail'] ?></td>
                                <td><?= $detail['nama_kegiatan'] ?></td>
                                <td><?= $detail['nama_kegiatan_detail'] ?></td>
                                <td><?= date('d M Y', strtotime($detail['tanggal_mulai'])) ?></td>
                                <td><?= date('d M Y', strtotime($detail['tanggal_selesai'])) ?></td>
                                <td><?= $detail['bulan'] ?></td>
                                <td><?= $detail['keterangan'] ?></td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-datatable btn-icon btn-transparent-dark me-2" data-bs-toggle="modal" data-bs-target="#editModal"
                                            onclick="editKegiatanDetail(
                                                '<?= $detail['id_kegiatan_detail'] ?>',
                                                '<?= $detail['nama_kegiatan_detail'] ?>',
                                                '<?= $detail['tanggal_mulai'] ?>',
                                                '<?= $detail['tanggal_selesai'] ?>',
                                                '<?= $detail['bulan'] ?>',
                                                '<?= $detail['keterangan'] ?>'
                                            )">
                                        <i data-feather="edit"></i>
                                    </button>

                                    <button class="btn btn-outline-warning btn-xs" type="button">Wilayah</button>

                                    <!-- Tombol Delete 
                                                    <a class="btn btn-datatable btn-icon btn-transparent-dark" data-bs-toggle="modal" 
                                                    data-bs-target="#confirmDeleteModal" data-id="zz //$proses['id_kegiatan_detail_proses'] ?>">
                                                        <i data-feather="trash-2"></i>
                                                    </a>
                                                    -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= base_url('mastercontroller/create_kegiatan_detail') ?>" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Tambah Detail Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Dropdown untuk memilih kegiatan -->
                        <div class="mb-3">
                            <label for="id_kegiatan" class="form-label">Pilih Kegiatan</label>
                            <select name="id_kegiatan" id="id_output" class="form-control select2" required>
                                <option value="">-- Pilih Kegiatan --</option>
                                <?php foreach ($kegiatan_list as $kegiatan): ?>
                                    <option value="<?= $kegiatan['id_kegiatan'] ?>">
                                        <?= $kegiatan['nama_kegiatan'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Input nama kegiatan detail -->
                        <div class="mb-3">
                            <label for="nama_kegiatan_detail" class="form-label">Nama Kegiatan Detail</label>
                            <input type="text" name="nama_kegiatan_detail" id="nama_kegiatan_detail" class="form-control" required>
                        </div>

                        <!-- Input tanggal mulai -->
                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
                        </div>

                        <!-- Input tanggal selesai -->
                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required>
                        </div>

                        <!-- Input bulan -->
                        <div class="mb-3">
                            <label for="bulan" class="form-label">Bulan</label>
                            <input type="text" name="bulan" id="bulan" class="form-control">
                        </div>

                        <!-- Input keterangan -->
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
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
                        <h5 class="modal-title" id="editModalLabel">Edit Detail Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama_kegiatan_detail" class="form-label">Nama Kegiatan Detail</label>
                            <input type="text" name="nama_kegiatan_detail" id="edit_nama_kegiatan_detail" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="edit_tanggal_selesai" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_bulan" class="form-label">Bulan</label>
                            <input type="text" name="bulan" id="edit_bulan" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit_keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="edit_keterangan" class="form-control"></textarea>
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
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">Apakah Anda yakin ingin menghapus data ini?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>


</div>

<script>
    function editKegiatanDetail(id, nama_kegiatan_detail, tanggal_mulai, tanggal_selesai, bulan, keterangan) {
        // Mengisi input form di modal Edit
        document.getElementById('edit_nama_kegiatan_detail').value = nama_kegiatan_detail;
        document.getElementById('edit_tanggal_mulai').value = tanggal_mulai;
        document.getElementById('edit_tanggal_selesai').value = tanggal_selesai;
        document.getElementById('edit_bulan').value = bulan;
        document.getElementById('edit_keterangan').value = keterangan;

        // Update action form untuk submit data dengan ID yang dipilih
        document.getElementById('editForm').action = "<?= base_url('mastercontroller/update_kegiatan_detail/') ?>" + id;
    }

    // Script untuk Delete Modal
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('[data-bs-target="#confirmDeleteModal"]');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                confirmDeleteBtn.href = "<?= base_url('mastercontroller/delete_kegiatan_detail/') ?>" + id;
            });
        });
    });
</script>


<?= $this->endSection() ?>
