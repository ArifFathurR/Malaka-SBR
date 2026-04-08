<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>MALAKA - BPS Riau Hebat</title>

        <link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet" />
        <link href="<?= base_url('public/sbadmin/dist/css/styles.css') ?>" rel="stylesheet">

        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Leaflet.js CDN -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


        <!-- jQuery dan Select2 JS -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Accordion -->
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
        
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous"></script>
        
        <!-- Datatables -->
        <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <link rel="icon" href="<?= base_url('public/file/micon.png') ?>" type="image/png">
    </head>

    <body class="nav-fixed">
        <?= $this->include('layouts/top_nav') ?>
        <?= $this->include('layouts/side_nav') ?>
        
        <div id="layoutSidenav_content">
        <main>
            <?= $this->renderSection('content') ?>
        </main>
        <?= $this->include('layouts/footer') ?>
        </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('public/sbadmin/dist/js/scripts.js') ?>"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('public/sbadmin/dist/assets/demo/chart-area-demo.js') ?>"></script>
        <script src="<?= base_url('public/sbadmin/dist/assets/demo/chart-bar-demo.js') ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('public/sbadmin/dist/js/litepicker.js') ?>"></script>

        
        <script>
            $(document).ready(function () {
                $('.select2').select2({
                    placeholder: "- Pilih -",
                    width: '100%', // Atur width ke 100%
                    allowClear: true
                });

                $('.datatable-export').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'csv', 'excel'
                    ],
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                    }
                });

            });
        </script>

        <script>
            $( function() {
                $( "#accordion" ).accordion({
                    collapsible: true, 
                    active: false
                });
            } );

            // Menjalankan fungsi setelah halaman siap
            document.addEventListener("DOMContentLoaded", function () {
                // Seleksi semua elemen toast yang memiliki class "toast"
                const toastElements = document.querySelectorAll('.toast');

                toastElements.forEach(function (toast) {
                    // Mengatur timer agar toast auto hilang setelah 5 detik (5000ms)
                    setTimeout(function () {
                        // Menambahkan animasi fade out dan menghapus elemen toast
                        toast.classList.remove('show'); // Menghapus class 'show' untuk menyembunyikan toast
                    }, 5000); // Waktu dalam milidetik (5 detik)
                });
            });
        </script>
    </body>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables Core + Bootstrap 5 Integration -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>

    <!-- Export Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

</html>