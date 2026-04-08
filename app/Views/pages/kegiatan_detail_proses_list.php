<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="layers"></i></div>
                        Master Kegiatan - Detail Proses
                    </h1>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <button class="btn btn-sm btn-light text-primary" type="button" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="me-1" data-feather="plus"></i>
                        Tambah Kegiatan Detail (Proses)
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

    <!-- Tabel Data -->
    <div class="card mb-4">
        <div class="card-body">
            <div id="accordion">
                <?php if (!empty($kegiatan_details)): ?>
                    <?php foreach ($kegiatan_details as $index => $detail): ?>
                        <!-- Header Accordion (Klik untuk Expand) -->
                        <h3 class="accordion-header">
                            <span class="toggle-icon">&#9656;</span> <!-- Panah kanan -->
                            <?= $detail['nama_kegiatan_detail'] ?> (<?= $detail['bulan'] ?>)
                        </h3>

                        <!-- Konten Accordion -->
                        <div class="accordion-content">
                            <p><strong>Periode:</strong> <?= $detail['bulan'] ?></p>
                            <p><strong>Tanggal Mulai:</strong> <?= date('d M Y', strtotime($detail['tanggal_mulai'])) ?></p>
                            <p><strong>Tanggal Selesai:</strong> <?= date('d M Y', strtotime($detail['tanggal_selesai'])) ?></p>
                            <p><strong>Keterangan:</strong> <?= $detail['keterangan'] ?></p>

                            <!-- Table Proses -->
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Uraian Proses</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Target</th>
                                        <th>Realisasi</th>
                                        <th>Satuan</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($detail['proses'])): ?>
                                        <?php foreach ($detail['proses'] as $proses): ?>
                                            <tr>
                                                <td><?= $proses['uraian_proses'] ?></td>
                                                <td><?= date('d M Y', strtotime($proses['tanggal_mulai'])) ?></td>
                                                <td><?= date('d M Y', strtotime($proses['tanggal_selesai'])) ?></td>
                                                <td><?= $proses['target'] ?></td>
                                                <td><?= $proses['realisasi'] ?></td>
                                                <td><?= $proses['satuan'] ?></td>
                                                <td><?= $proses['status'] == 1 ? "Selesai" : "Belum Selesai" ?></td>
                                                <td><?= $proses['keterangan'] ?></td>
                                                <td>
                                                <?php $btnClass = $proses['is_assigned'] ? 'btn-outline-success' : 'btn-outline-warning'; ?>
                                                    <button class="btn <?= $btnClass ?> btn-xs" type="button" 
                                                            onclick="openWilayahModal('<?= $proses['id_kegiatan_detail_proses'] ?>')">
                                                        Assign Wilayah
                                                    </button>
                                                    &nbsp;&nbsp;

                                                    <!-- Tombol Edit -->
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark me-2" data-bs-toggle="modal" data-bs-target="#editModal"
                                                    onclick="editKegiatanDetail(
                                                                '<?= $proses['id_kegiatan_detail_proses'] ?>',
                                                                '<?= esc($proses['uraian_proses']) ?>',
                                                                '<?= $proses['tanggal_mulai'] ?>',
                                                                '<?= $proses['tanggal_selesai'] ?>',
                                                                '<?= $proses['target'] ?>',
                                                                '<?= $proses['satuan'] ?>',
                                                                '<?= esc($proses['keterangan']) ?>'
                                                            )"
                                                            >
                                                        <i data-feather="edit"></i>
                                                    </button>

                                                    <!-- Tombol Delete -->
                                                    <a class="btn btn-datatable btn-icon btn-transparent-dark" data-bs-toggle="modal" 
                                                    data-bs-target="#confirmDeleteModal" data-id="<?= $proses['id_kegiatan_detail_proses'] ?>">
                                                        <i data-feather="trash-2"></i>
                                                    </a>

                                                    

                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data proses</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">Tidak ada data ditemukan</p>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= base_url('mastercontroller/create_kegiatan_detail_proses') ?>" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Tambah Proses</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Dropdown untuk memilih Tim -->
                        <div class="mb-3">
                            <label for="id_tim" class="form-label">Pilih Tim</label>
                            <select name="id_tim" id="id_tim" class="form-control" required>
                                <option value="">-- Pilih Tim --</option>
                                <?php foreach ($tim_list as $tim): ?>
                                    <option value="<?= $tim['id_tim'] ?>"><?= $tim['nama_tim'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Dropdown untuk memilih kegiatan -->
                        <div class="mb-3">
                            <label for="id_kegiatan_detail" class="form-label">Pilih Kegiatan (Periode)</label>
                            <select name="id_kegiatan_detail" id="id_output" class="form-control select2" required>
                                <option value="">-- Pilih --</option>
                                <?php foreach ($kegdetail as $kegiatan): ?>
                                    <option value="<?= $kegiatan['id_kegiatan_detail'] ?>">
                                        <?= $kegiatan['nama_kegiatan_detail'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Input nama kegiatan detail -->
                        <div class="mb-3">
                            <label for="uraian_proses" class="form-label">Uraian Proses</label>
                            <input type="text" name="uraian_proses" id="uraian_proses" class="form-control" required>
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

                        <!-- Input target -->
                        <div class="mb-3">
                            <label for="target" class="form-label">Target</label>
                            <input type="text" name="target" id="target" class="form-control">
                        </div>

                        <!-- Input satuan -->
                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" name="satuan" id="satuan" class="form-control">
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
                        <h5 class="modal-title" id="editModalLabel">Edit Kegiatan Proses</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_uraian_proses" class="form-label">Uraian Proses</label>
                            <input type="text" name="uraian_proses" id="edit_uraian_proses" class="form-control" required>
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
                            <label for="edit_target" class="form-label">Target</label>
                            <input type="text" name="target" id="edit_target" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit_satuan" class="form-label">Satuan</label>
                            <input type="text" name="satuan" id="edit_satuan" class="form-control">
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

    <!-- Wilayah Modal -->
    <div class="modal fade" id="wilayahModal" tabindex="-1" aria-labelledby="wilayahModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="wilayahForm" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="wilayahModalLabel">Wilayah - <span id="nama_kegiatan_detail"></span> (<span id="uraian_prosez"></span>)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="id_tim_wakil_select" class="form-label"><b>Pilih Subtim</b></label>
                            <select name="id_tim_wakil" id="id_tim_wakil_select" class="form-select" required>
                                <!-- Opsi akan diisi lewat JS -->
                            </select>
                        </div>
                    </div>

                    <div class="modal-body">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID Kabupaten</th>
                                    <th>Nama Kabupaten</th>
                                    <th>Target</th>
                                </tr>
                            </thead>
                            <tbody id="wilayahTableBody">
                                <!-- Data dinamis akan dimasukkan di sini -->
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>


<script>
   function editKegiatanDetail(id, uraian_proses, tanggal_mulai, tanggal_selesai, target, satuan, keterangan) {
        document.getElementById('edit_uraian_proses').value = uraian_proses;
        document.getElementById('edit_tanggal_mulai').value = tanggal_mulai;
        document.getElementById('edit_tanggal_selesai').value = tanggal_selesai;
        document.getElementById('edit_target').value = target;
        document.getElementById('edit_satuan').value = satuan;
        document.getElementById('edit_keterangan').value = keterangan;

        document.getElementById('editForm').action = "<?= base_url('mastercontroller/update_kegiatan_detail_proses/') ?>" + id;
    }


    // Script untuk Delete Modal
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('[data-bs-target="#confirmDeleteModal"]');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                confirmDeleteBtn.href = "<?= base_url('mastercontroller/delete_kegiatan_detail_proses/') ?>" + id;
            });
        });
    });
</script>

<script>
    function openWilayahModal(id_kegiatan_detail_proses) {
        $('#wilayahTableBody').empty();

        $.ajax({
            url: "<?= base_url('mastercontroller/get_wilayah_data') ?>",
            type: "POST",
            data: { id_kegiatan_detail_proses: id_kegiatan_detail_proses },
            dataType: "json",
            success: function(response) {
                console.log(response); // Tambahkan ini dulu

                $('#nama_kegiatan_detail').text(response.nama_kegiatan_detail);
                $('#uraian_prosez').text(response.uraian_proses);

                // Set dropdown tim wakil
                let defaultTimWakil = response.id_tim_wakil || '99';

                $('#id_tim_wakil_select').empty();
                $('#id_tim_wakil_select').append('<option value="99">Pilih SUbtim</option>');
                $.each(response.timwakil_list, function(i, tim) {
                    let selected = (defaultTimWakil == tim.id_tim_wakil) ? 'selected' : '';
                    $('#id_tim_wakil_select').append(
                        `<option value="${tim.id_tim_wakil}" ${selected}>${tim.nama_tim_wakil}</option>`
                    );
                });

                // Isi data wilayah ke tabel
                $.each(response.wilayah, function(index, wilayah) {
                    var row = `<tr>
                        <td><input type="text" class="form-control" value="${wilayah.idkab}" readonly></td>
                        <td><input type="text" class="form-control" value="${wilayah.nmkab}" readonly></td>
                        <td><input type="number" class="form-control" name="target[${wilayah.idkab}]" value="${wilayah.target}" min="0"></td>
                    </tr>`;
                    $('#wilayahTableBody').append(row);
                });

                $('#wilayahForm').attr('action', "<?= base_url('mastercontroller/update_wilayah') ?>/" + id_kegiatan_detail_proses);
                $('#wilayahModal').modal('show');
            },
            error: function() {
                alert("Gagal mengambil data wilayah.");
            }
        });
    }

    $('#wilayahForm').on('submit', function(e) {
        e.preventDefault();

        let selectedTimWakil = $('#id_tim_wakil_select').val();
        if (selectedTimWakil === '99') {
            alert('Silakan pilih Subtim terlebih dahulu.');
            return;
        }

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#wilayahModal').modal('hide');
                alert("Data wilayah berhasil disimpan!");
                location.reload();
            },
            error: function() {
                alert("Gagal menyimpan data wilayah.");
            }
        });
    });

</script>


<?= $this->endSection() ?>
