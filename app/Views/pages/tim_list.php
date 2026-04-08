<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="card mb-4">
        <div class="card-header">
            <h4>Daftar Tim</h4>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tim</th>
                        <th>Ketua Tim</th>
                        <th>Jumlah Anggota Subtim</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($teams as $team): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($team['nama_tim']) ?></td>
                        <td><?= esc($team['ketua_tim'] ?? 'Tidak ada') ?></td>
                        <td><?= esc($team['jumlah_anggota']) ?></td>
                        <td>
                            <!-- Ubah tombol detail agar mengarah ke read_timwakil -->
                            <a href="<?= base_url('mastercontroller/read_subtim/' . $team['id_tim']) ?>" class="btn btn-outline-warning btn-xs">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
