<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-user-edit"></i></div>
                        Import USER (Admin Only) <?= esc($user) ?>
                    
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

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
    
    <!-- Detail Table -->
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-table me-1"></i> Rincian</div>
        <div class="col-auto mb-3">
            <form action="<?= base_url('siri/import_siri_user_proses') ?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="file_csv" class="form-label">Upload CSV Siri User</label>
                    <input type="file" name="file_csv" class="form-control" required accept=".csv">
                </div>
                <button type="submit" class="btn btn-primary">Import</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tabelReport').DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            searching: true,
            pageLength: 10, // Default tampilkan 10
            lengthMenu: [5, 10, 25, 50, 100], // Pilihan dropdown

            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>

<?= $this->endSection() ?>
