<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>


<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="map-pin"></i></div>
                        Laporan Kegiatan Wilkerstat Saya
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif ?>

<div class="container mt-4">

<!--  if (isset($error)) 
    <!-- <div style="color:red;">Error: /div> -->


<?php if (!empty($laporan)) : ?>

    <div style="overflow-x: auto;">
        <table id="tabel-sipantau" class="display" style="width:100%; min-width: 700px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Resume</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($laporan as $i => $row) : ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($row['tanggal_transaksi']) ?></td>
                        <td><?= esc($row['resume']) ?></td>
                        <td><?= esc($row['latitude']) ?></td>
                        <td><?= esc($row['longitude']) ?></td>
                        <td>
                            <?php if (!empty($row['imagepath'])) : ?>
                                <img src="https://webapps.bps.go.id/riau/sipantau/<?= esc($row['imagepath']) ?>" width="100">
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Init DataTables -->
    <script>
        $(document).ready(function() {
            $('#tabel-sipantau').DataTable({
                responsive: true,
                paging: true,
                searching: false,
                ordering: true,
                info: false,
                lengthChange: false
            });
        });
    </script>

<?php else : ?>
    <p>Tidak ada data laporan untuk saat ini.</p>
<?php endif; ?>


<?= $this->endSection() ?>

