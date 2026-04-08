<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
  --c-bg:      #f0f4fb;
  --c-surface: #ffffff;
  --c-border:  #e2e8f4;
  --c-primary: #1d4ed8;
  --c-primary-h:#1e40af;
  --c-success: #16a34a;
  --c-muted:   #64748b;
  --c-dark:    #1e293b;
  --c-row-alt: #f8faff;
  --radius:    .5rem;
  --shadow-sm: 0 1px 3px rgba(0,0,0,.08);
  --shadow-md: 0 4px 20px rgba(29,78,216,.13);
  --font:      'Plus Jakarta Sans', sans-serif;
}
body { background: var(--c-bg); font-family: var(--font); color: var(--c-dark); }

.pg-header { display:flex; align-items:center; gap:.75rem; margin-bottom:1.5rem; }
.pg-header .icon-box {
  width:46px; height:46px; border-radius:var(--radius);
  background:linear-gradient(135deg,var(--c-success),#4ade80);
  display:grid; place-items:center; color:#fff; font-size:1.2rem;
  box-shadow:var(--shadow-sm); flex-shrink:0;
}
.pg-header h4  { margin:0; font-weight:700; font-size:1.2rem; }
.pg-header small { color:var(--c-muted); font-size:.8rem; }

.form-card {
  background: var(--c-surface);
  border: 1px solid var(--c-border);
  border-radius: var(--radius);
  box-shadow: var(--shadow-sm);
  overflow: hidden;
  max-width: 780px;
}
.form-card-header {
  padding: .85rem 1.25rem;
  background: linear-gradient(135deg,#f0fdf4,#f8faff);
  border-bottom: 1px solid var(--c-border);
  font-weight: 700; font-size: .92rem; color: var(--c-success);
  display: flex; align-items: center; gap: .45rem;
}
.form-card-body { padding: 1.5rem 1.25rem; }

.fg {
  display: flex; flex-direction: column; gap: .3rem;
  margin-bottom: 1.1rem;
}
.fg label {
  font-size: .72rem; font-weight: 700;
  letter-spacing: .05em; text-transform: uppercase;
  color: var(--c-muted);
}
.fg input, .fg select, .fg textarea {
  border: 1px solid var(--c-border); border-radius: var(--radius);
  padding: .48rem .75rem; font-size: .875rem; font-family: var(--font);
  background: var(--c-bg); color: var(--c-dark);
  transition: border-color .18s, box-shadow .18s;
}
.fg input:focus, .fg select:focus, .fg textarea:focus {
  outline: none; border-color: var(--c-primary);
  box-shadow: 0 0 0 3px rgba(29,78,216,.1); background: #fff;
}
.fg select:disabled { opacity: .5; cursor: not-allowed; }
.fg textarea { resize: vertical; min-height: 80px; }
.fg .hint { font-size: .73rem; color: var(--c-muted); margin-top: .15rem; }
.req { color: #dc2626; margin-left: 2px; }

.form-card-footer {
  padding: .85rem 1.25rem;
  background: var(--c-row-alt);
  border-top: 1px solid var(--c-border);
  display: flex; gap: .5rem; justify-content: flex-end;
}

.btn-f {
  display: inline-flex; align-items: center; gap: .35rem;
  border: none; border-radius: var(--radius);
  font-size: .85rem; font-weight: 600; cursor: pointer;
  transition: all .15s; height: 38px; padding: 0 1.1rem;
  text-decoration: none;
}
.btn-f.success { background: var(--c-success); color: #fff; }
.btn-f.success:hover { background: #15803d; }
.btn-f.ghost { background: transparent; color: var(--c-muted); border: 1px solid var(--c-border); }
.btn-f.ghost:hover { border-color: var(--c-primary); color: var(--c-primary); background: #eff6ff; }

.alert-err {
  background: #fef2f2; border: 1px solid #fecaca;
  border-radius: var(--radius); padding: .75rem 1rem;
  margin-bottom: 1.2rem; font-size: .85rem; color: #991b1b;
}
.alert-err ul { margin: .35rem 0 0 1rem; padding: 0; }
.alert-err li { margin-bottom: .2rem; }

.divider {
  grid-column: 1 / -1;
  border: none; border-top: 1px dashed var(--c-border);
  margin: .35rem 0 .85rem;
}
.section-label {
  font-size: .72rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: .07em;
  color: var(--c-primary); margin-bottom: .75rem;
  display: flex; align-items: center; gap: .4rem;
}
.section-label::after {
  content: ''; flex: 1; height: 1px; background: var(--c-border);
}
</style>

<div class="container-fluid px-3 px-md-4 py-4" style="max-width:1380px;">

  <!-- Flash Error dari session (redirect back) -->
  <div class="d-none">
    <?php if (session('error')): ?>
      <div id="flash-error" data-msg="<?= esc(session('error')) ?>"></div>
    <?php endif; ?>
    <?php if (session('success')): ?>
      <div id="flash-success" data-msg="<?= esc(session('success')) ?>"></div>
    <?php endif; ?>
  </div>

  <!-- Page Header -->
  <div class="pg-header">
    <div class="icon-box"><i class="bi bi-plus-circle-fill"></i></div>
    <div>
      <h4>Tambah Data Usaha</h4>
      <small>Input data usaha baru untuk Ground Check</small>
    </div>
    <a href="<?= base_url('sbr/gc') ?>" class="btn-f ghost ms-auto">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>
  </div>

  <!-- Validation Errors -->
  <?php if (!empty(session('errors'))): ?>
  <div class="alert-err" style="max-width:780px;">
    <strong><i class="bi bi-exclamation-triangle-fill"></i> Mohon perbaiki kesalahan berikut:</strong>
    <ul>
      <?php foreach (session('errors') as $err): ?>
        <li><?= esc($err) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <!-- Form Card -->
  <div class="form-card">
    <div class="form-card-header">
      <i class="bi bi-building-add"></i> Form Data Usaha Baru
    </div>

    <form action="<?= base_url('sbr/gc/store') ?>" method="post" id="formCreate">
      <?= csrf_field() ?>

      <div class="form-card-body">

        <!-- Kabupaten (hanya tampil untuk admin 1400) -->
        <?php if ($userKab === '1400'): ?>
        <div class="section-label"><i class="bi bi-map-fill"></i> Wilayah Admin</div>
        <div class="fg">
          <label>Kabupaten / Kota <span class="req">*</span></label>
          <select name="idkab" id="fKabCreate" required>
            <option value="">-- Pilih Kabupaten/Kota --</option>
            <?php foreach ($kabList as $k): ?>
              <option value="<?= esc($k['idkab']) ?>"
                <?= old('idkab') === $k['idkab'] ? 'selected' : '' ?>>
                <?= esc($k['nmkab']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <?php endif; ?>

        <!-- Informasi Usaha -->
        <div class="section-label"><i class="bi bi-building"></i> Informasi Usaha</div>

        <div class="fg">
          <label>Nama Usaha <span class="req">*</span></label>
          <input type="text" name="nama_usaha" id="nama_usaha"
                 placeholder="Contoh: Toko Maju Jaya"
                 value="<?= old('nama_usaha') ?>" required>
        </div>

        <div class="fg">
          <label>Alamat <span class="req">*</span></label>
          <textarea name="alamat" id="alamat"
                    placeholder="Jl. Sudirman No. 12, RT 01/RW 03"
                    required><?= old('alamat') ?></textarea>
        </div>

        <div class="fg">
  <label>Keberadaan Usaha <span class="req">*</span></label>
  <select name="keberadaan_usaha" required>
    <option value="">-- Pilih Status Keberadaan Usaha --</option>
    <option value="1" <?= old('keberadaan_usaha') === '1' ? 'selected' : '' ?>>Ada</option>
    <option value="0" <?= old('keberadaan_usaha') === '0' ? 'selected' : '' ?>>Tidak Ada</option>
  </select>
  <span class="hint">Pilih apakah usaha tersebut ada atau tidak ada</span>
</div>
        <!-- Wilayah -->
        <div class="section-label"><i class="bi bi-geo-alt-fill"></i> Wilayah</div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 1rem;">
          <!-- Kecamatan -->
          <div class="fg">
            <label>Kecamatan <span class="req">*</span></label>
            <select name="idkec" id="fKecCreate" required>
              <option value="">-- Pilih Kecamatan --</option>
              <?php foreach ($kecList as $k): ?>
                <option value="<?= esc($k['idkec']) ?>"
                  <?= old('idkec') === $k['idkec'] ? 'selected' : '' ?>>
                  <?= esc($k['nmkec']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Desa -->
          <div class="fg">
            <label>Desa / Kelurahan <span class="req">*</span></label>
            <select name="iddesa" id="fDesaCreate" required disabled>
              <option value="">-- Pilih kecamatan dulu --</option>
            </select>
          </div>
        </div>

      </div><!-- /form-card-body -->

      <div class="form-card-footer">
        <a href="<?= base_url('sbr/gc') ?>" class="btn-f ghost">
          <i class="bi bi-x-circle"></i> Batal
        </a>
        <button type="submit" class="btn-f success" id="btnSubmit">
          <i class="bi bi-save-fill"></i> Simpan Data
        </button>
      </div>

    </form>
  </div>

</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const BASE_URL  = '<?= base_url() ?>';
const API_DESA  = BASE_URL + 'sbr/gc/desalist/';
const OLD_IDDESA = '<?= old('iddesa') ?>';
const USER_KAB  = '<?= esc($userKab) ?>';

/* ── Flash Messages ── */
document.addEventListener('DOMContentLoaded', () => {
  const s = document.getElementById('flash-success');
  const e = document.getElementById('flash-error');
  if (s) Swal.fire({ icon:'success', title:'Berhasil!', text:s.dataset.msg, timer:3000, timerProgressBar:true, confirmButtonColor:'#16a34a' });
  if (e) Swal.fire({ icon:'error',   title:'Gagal!',    text:e.dataset.msg, confirmButtonColor:'#dc2626' });
});

/* ── Lazy load Desa saat Kecamatan berubah ── */
async function loadDesa(idkec, selectedDesa = '') {
  const selD = document.getElementById('fDesaCreate');
  selD.innerHTML = '<option value="">Memuat…</option>';
  selD.disabled  = true;

  if (!idkec) {
    selD.innerHTML = '<option value="">-- Pilih kecamatan dulu --</option>';
    return;
  }
  try {
    const res  = await fetch(API_DESA + idkec);
    const list = await res.json();
    selD.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
    list.forEach(d => {
      const o = document.createElement('option');
      o.value = d.iddesa;
      o.textContent = d.nmdesa;
      if (d.iddesa === selectedDesa) o.selected = true;
      selD.appendChild(o);
    });
    selD.disabled = false;
  } catch(e) {
    selD.innerHTML = '<option value="">Gagal memuat</option>';
  }
}

document.getElementById('fKecCreate').addEventListener('change', function() {
  loadDesa(this.value);
});

/* ── Restore old('iddesa') setelah redirect back ── */
const oldKec = '<?= old('idkec') ?>';
if (oldKec) {
  loadDesa(oldKec, OLD_IDDESA);
}

/* ── Untuk admin 1400: reload kecamatan saat kabupaten berubah ── */
<?php if ($userKab === '1400'): ?>
const kabSel = document.getElementById('fKabCreate');
if (kabSel) {
  kabSel.addEventListener('change', async function() {
    const idkab = this.value;
    const selKec = document.getElementById('fKecCreate');
    const selDes = document.getElementById('fDesaCreate');
    selKec.innerHTML = '<option value="">Memuat…</option>';
    selDes.innerHTML = '<option value="">-- Pilih kecamatan dulu --</option>';
    selDes.disabled  = true;

    if (!idkab) {
      selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
      return;
    }
    try {
      // Gunakan endpoint keclist yang sudah ada, tapi perlu endpoint khusus per kab
      // Workaround: fetch dari desalist via kecamatan
      // Tambahkan endpoint GET sbr/gc/keclistbykab/{idkab} di controller
      const res  = await fetch(BASE_URL + 'sbr/gc/keclistbykab/' + idkab);
      const list = await res.json();
      selKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
      list.forEach(k => {
        const o = document.createElement('option');
        o.value = k.idkec; o.textContent = k.nmkec;
        selKec.appendChild(o);
      });
      selKec.disabled = false;
    } catch(e) {
      selKec.innerHTML = '<option value="">Gagal memuat</option>';
    }
  });
}
<?php endif; ?>

/* ── Konfirmasi submit ── */
document.getElementById('formCreate').addEventListener('submit', function(e) {
  const nama  = document.getElementById('nama_usaha').value.trim();
  const kec   = document.getElementById('fKecCreate').value;
  const desa  = document.getElementById('fDesaCreate').value;

  if (!nama || !kec || !desa) return; // biarkan validasi HTML5

  e.preventDefault();
  Swal.fire({
    title: 'Konfirmasi Penyimpanan',
    html: `Tambah usaha <strong>${nama}</strong>?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#16a34a',
    cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="bi bi-save-fill"></i> Ya, Simpan',
    cancelButtonText: 'Periksa Kembali',
    showLoaderOnConfirm: true,
    preConfirm: () => new Promise(resolve => { this.submit(); resolve(); })
  });
});
</script>

<?= $this->endSection() ?>