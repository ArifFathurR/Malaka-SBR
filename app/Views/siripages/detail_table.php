<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="bar-chart"></i></div>
                        <?= $title ?>
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4">
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tabelDashboard" >
                    <thead>
                        <tr>
                            <?php foreach ($columns as $col): ?>
                                <th><?= esc($col) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <?php foreach ($row as $cell): ?>
                                    <td><?= esc($cell) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#dataTable').DataTable({
            responsive: true,
            pageLength: 25,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Buat format timestamp lokal saat ini
        function getFormattedTimestamp() {
            const now = new Date();
            const pad = (n) => n.toString().padStart(2, '0');
            const year = now.getFullYear();
            const month = pad(now.getMonth() + 1);
            const day = pad(now.getDate());
            const hour = pad(now.getHours());
            const minute = pad(now.getMinutes());
            const second = pad(now.getSeconds());

            return `${year}${month}${day}-${hour}${minute}${second}`;
        }

        $('#tabelDashboard').DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            searching: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            dom: 'lBfrtip',
            buttons: [
                <?php if (session('id_role') == 1): ?>
                {
                    extend: 'excelHtml5',
                    text: '📥 Export Excel',
                    className: 'btn btn-success btn-sm',
                    filename: function () {
                        return 'MALAKA-Export-' + getFormattedTimestamp();
                    }
                }
                <?php endif; ?>
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>


<?= $this->endSection() ?>
