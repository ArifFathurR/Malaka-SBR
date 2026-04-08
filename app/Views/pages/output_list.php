<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                                Master Output
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <button class="btn btn-sm btn-light text-primary" type="button" data-bs-toggle="modal"  data-bs-target="#createModal">
                            <i class="me-1" data-feather="plus"></i>
                                Create Output
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

        <!-- Toast Notifications -->
        <  <?php if (session()->has('success')): ?>
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
            <div class="card-header">
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Output</th>
                            <th>Alias</th>
                            <th>Fungsi</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($outputs as $output): ?>
                        <tr>
                            <td><?= $output['id_output'] ?></td>
                            <td><?= $output['nama_output'] ?></td>
                            <td><?= $output['alias'] ?></td>
                            <td><?= $output['fungsi'] ?></td>
                            <td><?= $output['status'] ?></td>
                            <td>
                                <button class="btn btn-datatable btn-icon btn-transparent-dark me-2" type="button" data-bs-toggle="modal" data-bs-target="#editModal" 
                                    onclick="editOutput('<?= $output['id_output'] ?>', '<?= $output['nama_output'] ?>', '<?= $output['alias'] ?>', '<?= $output['fungsi'] ?>', '<?= $output['status'] ?>')">
                                    <i data-feather="edit"></i>
                                </button>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#confirmDeleteModal" 
                                    data-id="<?= $output['id_output'] ?>">
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
            <form action="<?= base_url('mastercontroller/create_output') ?>" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Add New Output</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_output" class="form-label">Nama Output</label>
                            <input type="text" name="nama_output" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="alias" class="form-label">Alias</label>
                            <input type="text" name="alias" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="fungsi" class="form-label">Fungsi</label>
                            <input type="text" name="fungsi" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <input type="text" name="status" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
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
                        <h5 class="modal-title" id="editModalLabel">Edit Output</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama_output" class="form-label">Nama Output</label>
                            <input type="text" name="nama_output" id="edit_nama_output" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_alias" class="form-label">Alias</label>
                            <input type="text" name="alias" id="edit_alias" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_fungsi" class="form-label">Fungsi</label>
                            <input type="text" name="fungsi" id="edit_fungsi" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <input type="text" name="status" id="edit_status" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Delete -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editOutput(id, nama, alias, fungsi, status) {
            document.getElementById('edit_nama_output').value = nama;
            document.getElementById('edit_alias').value = alias;
            document.getElementById('edit_fungsi').value = fungsi;
            document.getElementById('edit_status').value = status;

            document.getElementById('editForm').action = "<?= base_url('mastercontroller/update_output/') ?>" + id;
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Tangkap elemen modal dan tombol konfirmasi delete
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            // Event delegation untuk menangkap tombol delete secara dinamis
            document.body.addEventListener('click', function (event) {
                // Cek apakah yang diklik adalah tombol delete
                if (event.target.closest('[data-bs-target="#confirmDeleteModal"]')) {
                    const button = event.target.closest('[data-bs-target="#confirmDeleteModal"]');
                    const id = button.getAttribute('data-id');

                    // Set href tombol konfirmasi delete
                    confirmDeleteBtn.setAttribute('href', '<?= base_url("mastercontroller/delete_output") ?>/' + id);
                }
            });
        });
    </script>

<?= $this->endSection() ?>