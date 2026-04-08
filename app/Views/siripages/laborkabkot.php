<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="map-pin"></i></div>
                        Update Daftar Prelist Usaha (Dokumen L)
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4 mt-4">

    <!-- Notifikasi -->
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success"><?= session('success') ?></div>
    <?php elseif (session()->has('error')): ?>
        <div class="alert alert-danger"><?= session('error') ?></div>
    <?php endif; ?>

    <form method="get" class="row mb-4">
        <div class="col-md-5">
            <label>Pilih Desa</label>
            <select name="iddesa" class="form-select" onchange="showLoadingThenSubmit(this.form)">
                <option value="">-- Pilih Desa --</option>
                <?php foreach ($targetDesa as $id => $nama): ?>
                    <option value="<?= $id ?>" <?= ($iddesa == $id) ? 'selected' : '' ?>>
                        <?= esc($nama) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (!empty($iddesa)): ?>
        <div class="col-md-5">
            <label>Pilih SLS</label>
            <select name="kdsls" class="form-select" onchange="showLoadingThenSubmit(this.form)">
                <option value="">-- Pilih SLS --</option>
                <option value="999" <?= ($kdsls === '999') ? 'selected' : '' ?>>Belum masuk SLS manapun</option>
                <?php foreach ($slsList as $sls): ?>
                    <option value="<?= esc($sls['kdsls']) ?>" <?= ($kdsls == $sls['kdsls']) ? 'selected' : '' ?>>
                        <?= esc($sls['nmsls']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
    </form>
    
    <?php
    $statusAwalLabels = [
      1 => '1. Aktif',
      2 => '2. Tidak Aktif',
      3 => '3. Bukan Usaha',
      4 => '4. Tidak Ditemukan',
    ];
    ?>

    <?php if (!empty($usahaList)): ?>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                      <tr>
                        <th>No</th>
                        <th>Nama Usaha</th>
                        <th>Alamat</th>
                        <th>Status Awal</th>
                        <th>Status Labor</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php $no=1; foreach($usahaList as $u): ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($u['placeName']) ?></td>
                        <td><?= esc($u['placeAddress']) ?></td>
                    
                        <!-- Status awal dari kolom status_usaha -->
                        <td>
                          <?= esc($statusAwalLabels[(int)($u['status_usaha'] ?? 0)] ?? 'Belum diupdate') ?>
                        </td>
                    
                        <!-- Status labor dari kolom status_usaha_labor -->
                        <td>
                          <?php
                            $labor = (int)($u['status_usaha_labor'] ?? 0);
                            $laborLabels = [
                              1 => '1. Aktif',
                              2 => '2. Tidak Aktif',
                              3 => '3. Bukan Usaha',
                              4 => '4. Tidak Ditemukan',
                              5 => '5. Duplikat',
                              6 => '6. Tidak Relevan (Kategori A,O,T)'
                            ];

                            if ($labor > 0) {
                                echo '<span class="text-success fw-semibold">'
                                   . esc($laborLabels[$labor])
                                   . '</span>';
                            } else {
                                echo '<span class="text-warning fw-semibold">Belum diupdate</span>';
                            }
                          ?>
                        </td>
                    
                        <td>
                          <button type="button" class="btn btn-sm btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#updateModal"
                            data-placeid="<?= $u['placeId'] ?>"
                            data-placename="<?= esc($u['placeName']) ?>"
                            data-statuslabor="<?= esc($u['status_usaha_labor']) ?>">
                            Update
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php elseif ($iddesa && $kdsls): ?>
        <div class="alert alert-info mt-3">Belum ada data usaha untuk kombinasi ini.</div>
    <?php endif; ?>
</div>

<!-- Modal Update -->
<div class="modal fade" id="updateModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= base_url('usaha/updatelabor') ?>">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Hasil Indepth</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="placeId" id="placeIdInput">
                    <p><b id="namaUsahaModal"></b></p>
                    <div class="mb-3">
                        <label>Status Usaha Hasil Indepth</label>
                        <select class="form-select" name="status_usaha_labor" required>
                            <option value="">-- Pilih --</option>
                            <option value="1">1. Aktif</option>
                            <option value="2">2. Tidak Aktif</option>
                            <option value="3">3. Bukan Usaha</option>
                            <option value="4">4. Tidak Ditemukan</option>
                            <option value="5">5. Duplikat</option>
                            <option value="6">6. Tidak Relevan (Kategori A,O,T)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Overlay Loading -->
<div id="loadingOverlay" 
     style="display:none; 
            position:fixed; 
            top:0; left:0; 
            width:100%; height:100%; 
            background:rgba(255,255,255,0.8); 
            z-index:9999; 
            text-align:center; 
            backdrop-filter: blur(2px);">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
        <div class="spinner-border text-primary" role="status" style="width:3rem; height:3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="mt-3 fw-semibold text-dark">Memuat data...</div>
    </div>
</div>

<script>
function showLoadingThenSubmit(form) {
    const overlay = document.getElementById('loadingOverlay');
    overlay.style.display = 'block';
    setTimeout(() => form.submit(), 150);
}

document.addEventListener('DOMContentLoaded', () => {
    const updateModal = document.getElementById('updateModal');
    updateModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const placeId = button.getAttribute('data-placeid');
        const placeName = button.getAttribute('data-placename');
        const statusLabor = button.getAttribute('data-statuslabor');
        document.getElementById('placeIdInput').value = placeId;
        document.getElementById('namaUsahaModal').textContent = placeName;
        document.querySelector('select[name="status_usaha_labor"]').value = statusLabor;
    });

    // tampilkan overlay saat submit form POST (modal Simpan)
    const postForm = document.querySelector('form[method="post"]');
    if (postForm) {
        postForm.addEventListener('submit', () => {
            document.getElementById('loadingOverlay').style.display = 'block';
        });
    }

    // sembunyikan overlay kalau page sudah siap
    window.addEventListener('pageshow', () => {
        document.getElementById('loadingOverlay').style.display = 'none';
    });
});
</script>

<?= $this->endSection() ?>
