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
                <table class="table table-bordered nowrap" id="tabelDashboard" style="width:100%;">
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
            <small class="text-muted d-block mt-2">
                Tips: scroll ke kanan untuk melihat tanggal yang jauh. Dua kolom kiri dibekukan supaya antum tidak tersesat.
            </small>
        </div>
    </div>
</div>

<style>
    /* Pastikan wrapper bisa scroll horizontal mulus */
    div.dataTables_wrapper {
        width: 100%;
        overflow-x: auto;
    }
    /* Biar sel kiri yang dibekukan terlihat */
    .dtfc-fixed-left {
        background: #fff;
        box-shadow: 2px 0 4px rgba(0,0,0,.05);
        z-index: 3;
    }
    /* Rapikan header tanggal yang mepet */
    #tabelDashboard thead th {
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
    }
    #tabelDashboard tbody td {
        white-space: nowrap;
        text-align: center;
    }
    /* Kolom Nama biar rata kiri */
    #tabelDashboard tbody td:nth-child(2),
    #tabelDashboard thead th:nth-child(2) {
        text-align: left;
    }
</style>

<script>
    $(document).ready(function () {
        function getFormattedTimestamp() {
            const now = new Date();
            const pad = (n) => n.toString().padStart(2, '0');
            return `${now.getFullYear()}${pad(now.getMonth()+1)}${pad(now.getDate())}-${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}`;
        }

        $('#tabelDashboard').DataTable({
            scrollX: true,          // scrollbar bawah untuk kanan-kiri
            responsive: false,      // matikan responsive biar kolom tidak di-wrap
            deferRender: true,
            autoWidth: false,
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
                    text: 'Export Excel',
                    className: 'btn btn-success btn-sm',
                    filename: function () {
                        return 'Rekap-Establishment-' + getFormattedTimestamp();
                    }
                }
                <?php endif; ?>
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            fixedColumns: {
                leftColumns: 2   // bekukan 2 kolom kiri: id_siri_user + Nama
            }
        });
    });
</script>

<?= $this->endSection() ?>
