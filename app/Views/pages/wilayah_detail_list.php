<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="map-pin"></i></div>
                        Detail Kegiatan per Wilayah
                    </h1>
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
    <div class="card mb-4">
        <div class="card-header">Daftar Kegiatan</div>
        <div class="card-body table-responsive">
            <table id="datatablesSimple" class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Uraian Proses</th>
                        <th>Tanggal</th>
                        <th>Target</th>
                        <th>Realisasi</th>
                        <th>Progres</th>
                        <th>Status</th>
                        <th>Update Terakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($kegiatan_list as $row): ?>
                        <?php if ($row->target > 0): 
                            $persen = round(($row->realisasi / $row->target) * 100);
                            $barClass = 'bg-danger';
                            if ($persen >= 100) $barClass = 'bg-success';
                            elseif ($persen >= 80) $barClass = 'bg-primary';
                            elseif ($persen >= 50) $barClass = 'bg-warning';

                            $last_display = $row->last_update && $row->nama_user
                                ? $row->last_update . "<br>oleh {$row->nama_user} (" . ($idkab == 1400 ? "Provinsi" : "Kabupaten") . ")"
                                : '-';
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($row->nama_kegiatan_detail) ?></td>
                            <td><?= esc($row->uraian_proses) ?></td>
                            <td><?= date('d M Y', strtotime($row->tanggal_mulai)) ?> - <?= date('d M Y', strtotime($row->tanggal_selesai)) ?></td>
                            <td><?= $row->target ?></td>
                            <td><?= $row->realisasi ?></td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar <?= $barClass ?>" role="progressbar"
                                         style="width: <?= $persen ?>%;" aria-valuenow="<?= $persen ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?= $persen ?>%
                                    </div>
                                </div>
                            </td>
                            <td style="font-size: 13px;">
                                <?= $row->status === null ? '-' : (
                                    $row->status == 1 ? '<span class="text-warning">Menunggu Verifikasi Provinsi</span>' :
                                    ($row->status == 2 ? 'Diupdate oleh Provinsi' :
                                    ($row->status == 3 ? 'Terverifikasi oleh Provinsi' :
                                    ($row->status == 9 ? (
                                        !empty($row->nama_user_selesai) 
                                            ? '<span class="text-success">' . esc($row->nama_user_selesai) . '</span>' 
                                            : '<span class="text-success">Selesai</span>'
                                    ) : '-')))
                                ) ?>
                                
                                <?php if ($row->status == 1 && session('idkab') == 1400): ?>
                                    <br><button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#verifStatus_<?= $row->id_kegiatan_wilayah ?>">
                                        Verifikasi
                                    </button>
                                <?php endif ?>

                                <!-- Modal VERIFIKASI Status (khusus idkab 1400 dan status = 1) -->
                                <?php if ($row->status == 1 && session('idkab') == 1400): ?>
                                <div class="modal fade" id="verifStatus_<?= $row->id_kegiatan_wilayah ?>" tabindex="-1" aria-labelledby="verifLabel_<?= $row->id_kegiatan_wilayah ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="post" action="<?= base_url('mastercontroller/verifikasi_status') ?>">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="verifLabel_<?= $row->id_kegiatan_detail_proses ?>">Verifikasi Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    <input type="hidden" name="id_kegiatan_wilayah" value="<?= $row->id_kegiatan_wilayah ?>">
                                                    <input type="hidden" name="statuse" value="3">
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin memverifikasi data ini sebagai <b>Terverifikasi oleh Provinsi</b>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-warning">Ya, Verifikasi</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php endif; ?>

                            </td>
                            <td style="font-size: 13px;"><?= $last_display ?></td>
                            <td>
                                <?php if (session('idkab') == 1400 || session('idkab') == $idkab): ?>
                                    <?php if ($row->status != 9): ?>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal_<?= $row->id_kegiatan_detail_proses ?>">
                                            Update
                                        </button>
                                    <?php endif; ?>    

                                    <?php if (session('idkab') == 1400 && $row->status != 9): ?>
                                            <br><br><!-- Tombol open modal -->
                                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#confirmSelesaiModal_<?= $row->id_kegiatan_wilayah ?>">
                                                Tandai Selesai
                                            </button>

                                            <!-- Modal Konfirmasi dengan Rating -->
                                            <div class="modal fade" id="confirmSelesaiModal_<?= $row->id_kegiatan_wilayah ?>" tabindex="-1" aria-labelledby="confirmLabel_<?= $row->id_kegiatan_wilayah ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form method="post" action="<?= base_url('mastercontroller/verifikasi_status_selesai') ?>">
                                                        <input type="hidden" name="id_kegiatan_wilayah" value="<?= $row->id_kegiatan_wilayah ?>">
                                                        <input type="hidden" name="idkab" value="<?= $idkab ?>">

                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Konfirmasi Selesai</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <p>Apakah Anda yakin ingin menandai kegiatan ini sebagai <strong>Selesai</strong>?</p>
                                                                <p><strong>Berikan Penilaian:</strong></p>

                                                                <div class="rating text-center">
                                                                    <?php for ($i = 10; $i >= 1; $i--): ?>
                                                                        <input type="radio" id="star<?= $i ?>_<?= $row->id_kegiatan_wilayah ?>" name="rating" value="<?= $i ?>" required>
                                                                        <label for="star<?= $i ?>_<?= $row->id_kegiatan_wilayah ?>" title="<?= $i ?> bintang">★</label>
                                                                    <?php endfor; ?>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer justify-content-between">
                                                                <button type="submit" class="btn btn-success">Ya, Tandai Selesai</button>
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <style>
                                            .rating {
                                                direction: rtl;
                                                unicode-bidi: bidi-override;
                                                font-size: 24px;
                                            }
                                            .rating input[type="radio"] {
                                                display: none;
                                            }
                                            .rating label {
                                                color: #ccc;
                                                cursor: pointer;
                                            }
                                            .rating input[type="radio"]:checked ~ label,
                                            .rating label:hover,
                                            .rating label:hover ~ label {
                                                color: gold;
                                            }
                                            </style>


                                    <?php endif; ?>

                                    <!-- Modal -->
                                    <div class="modal fade" id="editModal_<?= $row->id_kegiatan_detail_proses ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <form method="post" action="<?= base_url('mastercontroller/insert_transaksi_2') ?>">
                                                <input type="hidden" name="id_kegiatan_detail_proses" value="<?= $row->id_kegiatan_detail_proses ?>">
                                                <input type="hidden" name="idkab" value="<?= $idkab ?>">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><?= $row->nama_kegiatan_detail ?> - <?= $row->uraian_proses ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-bordered table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>Pelapor</th>
                                                                    <th>Tanggal</th>
                                                                    <th>Target</th>
                                                                    <th>Realisasi</th>
                                                                    <th>Link Bukti Dukung</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($row->histori)): ?>
                                                                    <?php foreach ($row->histori as $h): ?>
                                                                        <tr>
                                                                            <td><?= esc($h['nama_user']) ?></td>
                                                                            <td><?= date('d-m-Y H:i:s', strtotime($h['tanggal_input'])) ?></td>
                                                                            <td><?= $row->target ?></td>
                                                                            <td><?= $h['transaksi_realisasi'] ?></td>
                                                                            <td>
                                                                                <?php if (!empty($h['bukti_dukung'])): ?>
                                                                                    <a href="<?= esc($h['bukti_dukung']) ?>" target="_blank">
                                                                                        <?= esc($h['bukti_dukung']) ?>
                                                                                    </a>
                                                                                <?php else: ?> - <?php endif ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach ?>
                                                                <?php else: ?>
                                                                    <tr><td colspan="5" class="text-muted text-center">Belum ada histori</td></tr>
                                                                <?php endif ?>
                                                                <tr>
                                                                    <td><?= session('nama_user') ?></td>
                                                                    <td><?= date('d-m-Y H:i:s') ?></td>
                                                                    <td><?= $row->target ?></td>
                                                                    <td><input type="number" name="transaksi_realisasi" class="form-control" required></td>
                                                                    <td><input type="text" name="bukti_dukung" class="form-control"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted" style="font-size: 13px;"> - </span>
                                <?php endif ?>
                            </td>
                        </tr>
                        <?php endif ?>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
