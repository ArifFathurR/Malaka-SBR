<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<!-- SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">

<style>
/* ───────────────────────── Design Tokens ───────────────────────── */
:root {
  --c-bg:        #f0f4fb;
  --c-surface:   #ffffff;
  --c-border:    #e2e8f4;
  --c-primary:   #1d4ed8;
  --c-primary-h: #1e40af;
  --c-accent:    #0ea5e9;
  --c-success:   #16a34a;
  --c-warning:   #d97706;
  --c-danger:    #dc2626;
  --c-dark:      #1e293b;
  --c-muted:     #64748b;
  --c-row-alt:   #f8faff;
  --radius:      .5rem;
  --shadow-sm:   0 1px 3px rgba(0,0,0,.08);
  --shadow-md:   0 4px 20px rgba(29,78,216,.13);
  --font:        'Plus Jakarta Sans', sans-serif;
  --mono:        'JetBrains Mono', monospace;
}
body { background: var(--c-bg); font-family: var(--font); color: var(--c-dark); }

/* ───────────────────────── Page Header ────────────────────────── */
.pg-header { display:flex; align-items:center; gap:.75rem; margin-bottom:1.5rem; }
.pg-header .icon-box {
  width:46px; height:46px; border-radius:var(--radius);
  background:linear-gradient(135deg,var(--c-primary),var(--c-accent));
  display:grid; place-items:center; color:#fff; font-size:1.2rem;
  box-shadow:var(--shadow-sm); flex-shrink:0;
}
.pg-header h4  { margin:0; font-weight:700; font-size:1.2rem; }
.pg-header small { color:var(--c-muted); font-size:.8rem; }

/* ───────────────────────── Filter Card ────────────────────────── */
.filter-card {
  background:var(--c-surface);
  border:1px solid var(--c-border);
  border-radius:var(--radius);
  padding:1rem 1.25rem 1.1rem;
  margin-bottom:1.2rem;
  box-shadow:var(--shadow-sm);
}
.filter-row { display:flex; flex-wrap:wrap; gap:.6rem; align-items:flex-end; }
.fg { display:flex; flex-direction:column; gap:.28rem; min-width:145px; }
.fg label {
  font-size:.7rem; font-weight:700;
  letter-spacing:.05em; text-transform:uppercase; color:var(--c-muted);
}
.fg select, .fg input {
  border:1px solid var(--c-border); border-radius:var(--radius);
  padding:.38rem .65rem; font-size:.85rem; font-family:var(--font);
  background:var(--c-bg); color:var(--c-dark);
  transition:border-color .18s, box-shadow .18s; height:37px;
}
.fg select:focus, .fg input:focus {
  outline:none; border-color:var(--c-primary);
  box-shadow:0 0 0 3px rgba(29,78,216,.1); background:#fff;
}
.fg select:disabled { opacity:.5; cursor:not-allowed; }

/* ───────────────────────── Buttons ────────────────────────── */
.btn-f {
  display:inline-flex; align-items:center; gap:.35rem;
  border:none; border-radius:var(--radius);
  font-size:.85rem; font-weight:600; cursor:pointer;
  transition:all .15s; height:37px; padding:0 1rem;
}
.btn-f.primary   { background:var(--c-primary); color:#fff; }
.btn-f.primary:hover { background:var(--c-primary-h); transform:translateY(-1px); }
.btn-f.ghost { background:transparent; color:var(--c-muted); border:1px solid var(--c-border); }
.btn-f.ghost:hover { border-color:var(--c-primary); color:var(--c-primary); background:#eff6ff; }

/* ───────────────────────── Table Card ─────────────────────── */
.tbl-card {
  background:var(--c-surface);
  border:1px solid var(--c-border);
  border-radius:var(--radius);
  box-shadow:var(--shadow-sm);
  overflow:hidden;
}
.tbl-card-head {
  display:flex; align-items:center; justify-content:space-between;
  flex-wrap:wrap; gap:.5rem;
  padding:.8rem 1.1rem;
  border-bottom:1px solid var(--c-border);
  background:linear-gradient(135deg,#eff6ff,#f8faff);
}
.tbl-card-head .title {
  font-weight:700; font-size:.92rem; color:var(--c-primary);
  display:flex; align-items:center; gap:.45rem;
}
.badge-count {
  background:var(--c-primary); color:#fff;
  font-size:.68rem; font-weight:700;
  padding:.15rem .55rem; border-radius:999px;
}
.per-page-wrap { display:flex; align-items:center; gap:.4rem; font-size:.78rem; color:var(--c-muted); }
.per-page-wrap select {
  border:1px solid var(--c-border); border-radius:var(--radius);
  padding:.2rem .5rem; font-size:.8rem; height:30px; background:var(--c-bg);
}

/* ───────────────────────── Table ───────────────────────────── */
.tbl-wrap { overflow-x:auto; }
table.gc-tbl { width:100%; border-collapse:collapse; font-size:.855rem; }
.gc-tbl thead th {
  position:sticky; top:0; z-index:2;
  background:var(--c-primary); color:#fff;
  font-weight:600; padding:.6rem .85rem;
  text-align:left; white-space:nowrap;
  font-size:.74rem; letter-spacing:.04em; text-transform:uppercase;
}
.gc-tbl thead th.center { text-align:center; }
.gc-tbl tbody tr { border-bottom:1px solid var(--c-border); transition:background .12s; }
.gc-tbl tbody tr:nth-child(even) { background:var(--c-row-alt); }
.gc-tbl tbody tr:hover { background:#dbeafe33; }
.gc-tbl td { padding:.55rem .85rem; vertical-align:middle; }
.gc-tbl td.no-col { color:var(--c-muted); font-size:.76rem; text-align:center; width:46px; }
.gc-tbl td.center { text-align:center; }

code.id-code {
  font-family:var(--mono); font-size:.77rem;
  background:#eff6ff; padding:.1rem .38rem;
  border-radius:.3rem; color:var(--c-primary-h);
}
.nama-cell { font-weight:600; }
.alamat-cell { color:var(--c-muted); font-size:.8rem; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

/* ───────────────────────── Status Badges ───────────────────── */
.sbadge {
  display:inline-flex; align-items:center; gap:.28rem;
  font-size:.72rem; font-weight:700;
  padding:.18rem .55rem; border-radius:999px; white-space:nowrap;
}
.sb-aktif   { background:#dcfce7; color:#15803d; }
.sb-tutup   { background:#fef9c3; color:#92400e; }
.sb-ganda   { background:#fee2e2; color:#991b1b; }
.sb-tdkdapat{ background:#e2e8f0; color:#475569; }
.sb-none    { background:#f1f5f9; color:#94a3b8; }

/* GC Coordinate chip */
.coord-chip {
  font-family:var(--mono); font-size:.72rem;
  background:#f0fdf4; color:#15803d;
  padding:.1rem .38rem; border-radius:.3rem;
  display:inline-block;
}
.coord-none { color:var(--c-muted); font-style:italic; font-size:.78rem; }

/* ───────────────────────── Action button ───────────────────── */
.btn-update {
  display:inline-flex; align-items:center; gap:.28rem;
  padding:.28rem .65rem; border-radius:var(--radius);
  font-size:.76rem; font-weight:600; cursor:pointer;
  border:none; background:#dcfce7; color:#15803d;
  transition:all .15s;
}
.btn-update:hover { background:var(--c-success); color:#fff; }

/* ───────────────────────── Empty / Loading ─────────────────── */
.state-box { text-align:center; padding:3.5rem 1rem; color:var(--c-muted); }
.state-box i { font-size:2.2rem; display:block; margin-bottom:.65rem; }
.spin-ring {
  width:36px; height:36px;
  border:3px solid var(--c-border); border-top-color:var(--c-primary);
  border-radius:50%; animation:spin .65s linear infinite;
  margin:0 auto .65rem;
}
@keyframes spin { to { transform:rotate(360deg); } }

/* ───────────────────────── Pagination ─────────────────────── */
.pg-bar {
  display:flex; align-items:center; justify-content:space-between;
  flex-wrap:wrap; gap:.5rem;
  padding:.7rem 1.1rem;
  border-top:1px solid var(--c-border);
  background:var(--c-row-alt); font-size:.8rem;
}
.pg-bar .info { color:var(--c-muted); }
.pg-controls { display:flex; gap:.28rem; }
.pg-btn {
  min-width:32px; height:32px;
  border:1px solid var(--c-border); border-radius:var(--radius);
  background:#fff; color:var(--c-dark);
  font-size:.8rem; font-weight:500;
  cursor:pointer; transition:all .13s; display:grid; place-items:center;
}
.pg-btn:hover:not(:disabled) { border-color:var(--c-primary); color:var(--c-primary); background:#eff6ff; }
.pg-btn.active { background:var(--c-primary); color:#fff; border-color:var(--c-primary); font-weight:700; }
.pg-btn:disabled { opacity:.35; cursor:not-allowed; }

/* ───────────────────────── Modal ───────────────────────────── */
#mapGCUpdate { height:300px; width:100%; border-radius:var(--radius); display:block; }
.modal-custom-header {
  background:linear-gradient(135deg,var(--c-success) 0%,#4ade80 100%);
  border-bottom:none; padding:1rem 1.25rem;
}
.modal-custom-header .modal-title { color:#fff; font-weight:700; }
.modal-custom-header .btn-close    { filter:invert(1) brightness(2); }
</style>

<div class="container-fluid px-3 px-md-4 py-4" style="max-width:1380px;">

  <!-- ── Flash Messages (hidden → SweetAlert) ── -->
  <div class="d-none">
    <?php if (session('success')): ?>
      <div id="flash-success" data-msg="<?= esc(session('success')) ?>"></div>
    <?php endif; ?>
    <?php if (session('error')): ?>
      <div id="flash-error" data-msg="<?= esc(session('error')) ?>"></div>
    <?php endif; ?>
  </div>

  <!-- ── Page Header ── -->
  <div class="pg-header">
    <div class="icon-box"><i class="bi bi-geo-alt-fill"></i></div>
    <div>
      <h4>Data GC Usaha</h4>
      <small>Ground Check — verifikasi koordinat dan status usaha</small>
    </div>
    <a href="<?= base_url('sbr/gcexport') ?>" class="btn-f primary ms-auto" style="text-decoration:none;">
      <i class="bi bi-download"></i> Export
    </a>
  </div>

  <!-- ── Filter Card ── -->
  <div class="filter-card">
    <div class="filter-row">

      <!-- ID SBR -->
      <div class="fg" style="min-width:165px;">
        <label><i class="bi bi-hash"></i> ID SBR</label>
        <input type="text" id="fIdsbr" placeholder="Cari ID SBR…">
      </div>

      <!-- Nama Usaha -->
      <div class="fg" style="min-width:180px;">
        <label><i class="bi bi-building"></i> Nama Usaha</label>
        <input type="text" id="fNama" placeholder="Cari nama usaha…">
      </div>

      <!-- Kecamatan (server-side list via JSON) -->
      <div class="fg">
        <label><i class="bi bi-map"></i> Kecamatan</label>
        <select id="fKec">
          <option value="">-- Semua Kecamatan --</option>
        </select>
      </div>

      <!-- Desa (lazy load) -->
      <div class="fg">
        <label><i class="bi bi-geo"></i> Desa / Kelurahan</label>
        <select id="fDesa" disabled>
          <option value="">-- Pilih kecamatan dulu --</option>
        </select>
      </div>

      <!-- Status GC -->
      <div class="fg">
        <label><i class="bi bi-flag"></i> Status GC</label>
        <select id="fStatus">
          <option value="">-- Semua Status --</option>
          <option value="1">Aktif</option>
          <option value="3">Tutup</option>
          <option value="4">Ganda</option>
          <option value="9">Tidak Ditemukan</option>
          <option value="0">Belum Diupdate</option>
        </select>
      </div>

      <!-- Actions -->
      <!-- Actions -->
<div style="display:flex;gap:.4rem;align-items:flex-end;">
    <button class="btn-f primary" id="btnFilter">
        <i class="bi bi-funnel-fill"></i> Filter
    </button>
    <button class="btn-f ghost" id="btnReset">
        <i class="bi bi-arrow-counterclockwise"></i> Reset
    </button>
    <!-- TAMBAHKAN INI -->
    <a href="<?= base_url('sbr/gc/create') ?>" class="btn-f" style="background:#16a34a;color:#fff;text-decoration:none;">
        <i class="bi bi-plus-circle-fill"></i> Tambah Data
    </a>
</div>
    </div>
  </div>

  <!-- ── Table Card ── -->
  <div class="tbl-card">
    <div class="tbl-card-head">
      <span class="title">
        <i class="bi bi-table"></i>
        Daftar Usaha GC
        <span class="badge-count" id="totalCount">0</span>
      </span>
      <div class="per-page-wrap">
        Per halaman:
        <select id="perPage">
          <option value="25">25</option>
          <option value="50" selected>50</option>
          <option value="100">100</option>
        </select>
      </div>
    </div>

    <div class="tbl-wrap">
      <table class="gc-tbl">
        <thead>
          <tr>
            <th class="center" style="width:46px;">No</th>
            <th style="width:150px;">ID SBR</th>
            <th>Nama Usaha</th>
            <th>Alamat</th>
            <th style="width:140px;">Kecamatan</th>
            <th style="width:140px;">Desa/Kel</th>
            <th style="width:135px;">Status GC</th>
            <th style="width:180px;">Koordinat GC</th>
            <th class="center" style="width:105px;">Aksi</th>
          </tr>
        </thead>
        <tbody id="tblBody">
          <tr><td colspan="9"><div class="state-box"><div class="spin-ring"></div><span>Memuat data…</span></div></td></tr>
        </tbody>
      </table>
    </div>

    <div class="pg-bar">
      <span class="info" id="pgInfo">Menampilkan 0 data</span>
      <div class="pg-controls" id="pgControls"></div>
    </div>
  </div>

</div><!-- /container -->


<!-- ══════════ MODAL UPDATE GC (sama seperti sebelumnya) ══════════ -->
<div class="modal fade" id="gcModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="post" action="" id="formGC">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">

      <div class="modal-content" style="border-radius:.65rem;overflow:hidden;border:none;box-shadow:var(--shadow-md);">

        <div class="modal-header modal-custom-header">
          <h5 class="modal-title">
            <i class="bi bi-geo-alt-fill"></i> Update Data GC
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body p-4">
          <!-- Info Usaha -->
          <div class="mb-3 p-3 rounded" style="background:var(--c-bg);border:1px solid var(--c-border);">
            <div style="font-weight:700;font-size:.95rem;color:var(--c-primary);" id="gc_nama_usaha"></div>
            <small class="text-muted" id="gc_alamat"></small>
          </div>

          <!-- STATUS -->
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.85rem;">
              Status Usaha <span class="text-danger">*</span>
            </label>
            <select name="gc_status" id="gc_status" class="form-select" required>
              <option value="">-- Pilih Status --</option>
              <option value="9">Tidak Ditemukan</option>
              <option value="1">Aktif</option>
              <option value="3">Tutup</option>
              <option value="4">Ganda</option>
            </select>
          </div>

          <!-- KOORDINAT + GPS -->
          <div class="mb-3">
            <label class="form-label fw-semibold mb-2" style="font-size:.85rem;">
              Koordinat GC <span class="text-danger">*</span>
            </label>
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
              <button type="button" class="btn btn-outline-primary btn-sm" id="btnGpsGC">
                <i class="bi bi-crosshair"></i> Ambil Lokasi Saya
              </button>
              <div class="input-group" style="max-width:230px;">
                <span class="input-group-text">Lat</span>
                <input type="text" class="form-control form-control-sm" id="gc_lat" name="gc_lat" placeholder="Latitude" required>
              </div>
              <div class="input-group" style="max-width:230px;">
                <span class="input-group-text">Lng</span>
                <input type="text" class="form-control form-control-sm" id="gc_lng" name="gc_lng" placeholder="Longitude" required>
              </div>
            </div>

            <div id="mapGCUpdate" class="border"></div>
            <small class="text-muted d-block mt-2">
              <i class="bi bi-info-circle"></i>
              Klik pada peta atau drag marker untuk menyesuaikan koordinat secara manual.
            </small>
          </div>
        </div>

        <div class="modal-footer" style="background:var(--c-row-alt);border-top:1px solid var(--c-border);">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Batal
          </button>
          <button type="submit" class="btn btn-success btn-sm">
            <i class="bi bi-save"></i> Simpan Koordinat
          </button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/* ════════════════════════════════════════════════════
   CONFIG
════════════════════════════════════════════════════ */
const BASE_URL    = '<?= base_url() ?>';
// Endpoint JSON yang perlu ditambah di SbrGC controller:
const API_TABLE   = BASE_URL + 'sbr/gc/tabledata';   // GET  → {data:[], total:N}
const API_KEC     = BASE_URL + 'sbr/gc/keclist';     // GET  → [{idkec,nmkec}]
const API_DESA    = BASE_URL + 'sbr/gc/desalist/';   // GET  /{idkec} → [{iddesa,nmdesa}]

/* ════════════════════════════════════════════════════
   STATE
════════════════════════════════════════════════════ */
let S = {
  page: 1, perPage: 50,
  total: 0, totalPages: 1,
  filters: { idsbr:'', nama:'', idkec:'', iddesa:'', status:'' },
};

/* ════════════════════════════════════════════════════
   LAZY LOAD KECAMATAN (on page load)
════════════════════════════════════════════════════ */
async function loadKec() {
  try {
    const res  = await fetch(API_KEC);
    const list = await res.json();
    const sel  = document.getElementById('fKec');
    list.forEach(k => {
      const o = document.createElement('option');
      o.value = k.idkec; o.textContent = k.nmkec;
      sel.appendChild(o);
    });
  } catch(e) { console.warn('Gagal load kecamatan'); }
}

/* ════════════════════════════════════════════════════
   LAZY LOAD DESA (on kecamatan change)
════════════════════════════════════════════════════ */
document.getElementById('fKec').addEventListener('change', async function() {
  const idkec = this.value;
  const selD  = document.getElementById('fDesa');
  selD.innerHTML = '<option value="">Memuat…</option>';
  selD.disabled  = true;

  if (!idkec) {
    selD.innerHTML = '<option value="">-- Pilih kecamatan dulu --</option>';
    return;
  }
  try {
    const res  = await fetch(API_DESA + idkec);
    const list = await res.json();
    selD.innerHTML = '<option value="">-- Semua Desa --</option>';
    list.forEach(d => {
      const o = document.createElement('option');
      o.value = d.iddesa; o.textContent = d.nmdesa;
      selD.appendChild(o);
    });
    selD.disabled = false;
  } catch(e) {
    selD.innerHTML = '<option value="">Gagal memuat</option>';
  }
});

/* ════════════════════════════════════════════════════
   FETCH DATA TABLE (AJAX)
════════════════════════════════════════════════════ */
async function fetchData() {
  showLoading();
  const p = new URLSearchParams({
    page    : S.page,
    perPage : S.perPage,
    idsbr   : S.filters.idsbr,
    nama    : S.filters.nama,
    idkec   : S.filters.idkec,
    iddesa  : S.filters.iddesa,
    status  : S.filters.status,
  });
  try {
    const res  = await fetch(API_TABLE + '?' + p.toString());
    const json = await res.json();
    S.total      = json.total || 0;
    S.totalPages = Math.ceil(S.total / S.perPage) || 1;
    renderTable(json.data || []);
    renderPagination();
    document.getElementById('totalCount').textContent = S.total.toLocaleString('id-ID');
  } catch(e) {
    showError('Gagal memuat data. Silakan coba lagi.');
  }
}

/* ════════════════════════════════════════════════════
   RENDER TABLE
════════════════════════════════════════════════════ */
function renderTable(rows) {
  const tbody = document.getElementById('tblBody');
  if (!rows.length) {
    tbody.innerHTML = `<tr><td colspan="9"><div class="state-box">
      <i class="bi bi-inbox"></i><span>Tidak ada data ditemukan</span>
    </div></td></tr>`;
    return;
  }
  const offset = (S.page - 1) * S.perPage;
  tbody.innerHTML = rows.map((r, i) => {
    const no      = offset + i + 1;
    const status  = statusBadge(r.gc_status);
    const coord   = (r.gc_latitude && r.gc_longitude)
      ? `<span class="coord-chip">${parseFloat(r.gc_latitude).toFixed(5)}, ${parseFloat(r.gc_longitude).toFixed(5)}</span>`
      : `<span class="coord-none">Belum diupdate</span>`;
    return `
    <tr>
      <td class="no-col">${no}</td>
      <td><code class="id-code">${esc(r.id_sbr)}</code></td>
      <td class="nama-cell">${esc(r.nama_usaha ?? '-')}</td>
      <td class="alamat-cell" title="${esc(r.alamat ?? '')}">${esc(r.alamat ?? '-')}</td>
      <td>${esc(r.nmkec ?? '-')}</td>
      <td>${esc(r.nmdesa ?? '-')}</td>
      <td>${status}</td>
      <td>${coord}</td>
      <td class="center">
        <button class="btn-update"
          data-id_sbr="${esc(r.id_sbr)}"
          data-nama="${esc(r.nama_usaha ?? '-')}"
          data-alamat="${esc(r.alamat ?? '-')}"
          data-status="${esc(r.gc_status ?? '')}"
          data-lat="${esc(r.gc_latitude ?? r.latitude ?? '')}"
          data-lng="${esc(r.gc_longitude ?? r.longitude ?? '')}"
          onclick="openGCModal(this)">
          <i class="bi bi-geo-alt-fill"></i> Update
        </button>
      </td>
    </tr>`;
  }).join('');
}

function statusBadge(s) {
  const map = {
    '1': ['sb-aktif',    'bi-check-circle-fill', 'Aktif'],
    '3': ['sb-tutup',    'bi-x-circle-fill',     'Tutup'],
    '4': ['sb-ganda',    'bi-copy',              'Ganda'],
    '9': ['sb-tdkdapat', 'bi-slash-circle',      'Tidak Ditemukan'],
  };
  const [cls, icon, label] = map[s] ?? ['sb-none', 'bi-dash-circle', 'Belum Diupdate'];
  return `<span class="sbadge ${cls}"><i class="bi ${icon}"></i>${label}</span>`;
}

function esc(v) {
  return String(v ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
}

function showLoading() {
  document.getElementById('tblBody').innerHTML = `<tr><td colspan="9">
    <div class="state-box"><div class="spin-ring"></div><span>Memuat data…</span></div>
  </td></tr>`;
  document.getElementById('pgInfo').textContent = 'Memuat…';
}

function showError(msg) {
  document.getElementById('tblBody').innerHTML = `<tr><td colspan="9">
    <div class="state-box"><i class="bi bi-exclamation-circle text-danger"></i><span>${msg}</span></div>
  </td></tr>`;
}

/* ════════════════════════════════════════════════════
   PAGINATION
════════════════════════════════════════════════════ */
function renderPagination() {
  const { page, totalPages, total, perPage } = S;
  const from = total === 0 ? 0 : (page - 1) * perPage + 1;
  const to   = Math.min(page * perPage, total);
  document.getElementById('pgInfo').textContent =
    `Menampilkan ${from.toLocaleString('id-ID')}–${to.toLocaleString('id-ID')} dari ${total.toLocaleString('id-ID')} data`;

  const ctrl = document.getElementById('pgControls');
  ctrl.innerHTML = '';

  const mkBtn = (label, disabled, fn, active=false) => {
    const b = document.createElement('button');
    b.className   = 'pg-btn' + (active ? ' active' : '');
    b.innerHTML   = label;
    b.disabled    = disabled;
    b.addEventListener('click', fn);
    return b;
  };

  ctrl.appendChild(mkBtn('‹', page <= 1, () => goPage(page - 1)));

  const pages = pageNums(page, totalPages);
  pages.forEach(p => {
    if (p === '…') {
      const s = document.createElement('span');
      s.textContent = '…'; s.style.cssText = 'line-height:32px;padding:0 .2rem;color:var(--c-muted);';
      ctrl.appendChild(s);
    } else {
      ctrl.appendChild(mkBtn(p, false, () => goPage(p), p === page));
    }
  });

  ctrl.appendChild(mkBtn('›', page >= totalPages, () => goPage(page + 1)));
}

function pageNums(cur, total) {
  if (total <= 7) return Array.from({length: total}, (_, i) => i + 1);
  if (cur <= 4)         return [1,2,3,4,5,'…',total];
  if (cur >= total - 3) return [1,'…',total-4,total-3,total-2,total-1,total];
  return [1,'…',cur-1,cur,cur+1,'…',total];
}

function goPage(p) {
  S.page = p;
  fetchData();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ════════════════════════════════════════════════════
   FILTER EVENTS
════════════════════════════════════════════════════ */
document.getElementById('btnFilter').addEventListener('click', () => {
  S.filters = {
    idsbr  : document.getElementById('fIdsbr').value.trim(),
    nama   : document.getElementById('fNama').value.trim(),
    idkec  : document.getElementById('fKec').value,
    iddesa : document.getElementById('fDesa').value,
    status : document.getElementById('fStatus').value,
  };
  S.page = 1;
  fetchData();
});

document.getElementById('btnReset').addEventListener('click', () => {
  ['fIdsbr','fNama'].forEach(id => document.getElementById(id).value = '');
  document.getElementById('fKec').value    = '';
  document.getElementById('fStatus').value = '';
  const selD = document.getElementById('fDesa');
  selD.innerHTML = '<option value="">-- Pilih kecamatan dulu --</option>';
  selD.disabled  = true;
  S.filters = { idsbr:'', nama:'', idkec:'', iddesa:'', status:'' };
  S.page    = 1;
  fetchData();
});

['fIdsbr','fNama'].forEach(id => {
  document.getElementById(id).addEventListener('keydown', e => {
    if (e.key === 'Enter') document.getElementById('btnFilter').click();
  });
});

document.getElementById('perPage').addEventListener('change', function() {
  S.perPage = parseInt(this.value);
  S.page    = 1;
  fetchData();
});

/* ════════════════════════════════════════════════════
   MODAL GC — sama persis logika dari gcview lama
════════════════════════════════════════════════════ */
let gcMap = null, gcMarker = null;

function openGCModal(btn) {
  document.getElementById('gc_nama_usaha').textContent = btn.dataset.nama;
  document.getElementById('gc_alamat').textContent     = btn.dataset.alamat;
  document.getElementById('gc_status').value           = btn.dataset.status || '';
  document.getElementById('gc_lat').value              = btn.dataset.lat || '';
  document.getElementById('gc_lng').value              = btn.dataset.lng || '';
  document.getElementById('formGC').action = BASE_URL + 'sbr/gc/' + btn.dataset.id_sbr;
  new bootstrap.Modal(document.getElementById('gcModal')).show();
}

document.getElementById('gcModal').addEventListener('shown.bs.modal', function() {
  const latInput = document.getElementById('gc_lat');
  const lngInput = document.getElementById('gc_lng');
  let lat = parseFloat(latInput.value);
  let lng = parseFloat(lngInput.value);
  if (isNaN(lat) || isNaN(lng)) {
    lat = 0.50316813; lng = 101.455435;
    latInput.value = lat; lngInput.value = lng;
  }
  if (!gcMap) {
    gcMap = L.map('mapGCUpdate').setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19, attribution: '© OpenStreetMap'
    }).addTo(gcMap);
    gcMarker = L.marker([lat, lng], { draggable: true, autoPan: true }).addTo(gcMap);

    const sync = pos => {
      latInput.value = pos.lat.toFixed(8);
      lngInput.value = pos.lng.toFixed(8);
    };
    gcMarker.on('dragend', () => sync(gcMarker.getLatLng()));
    gcMap.on('click', e => { gcMarker.setLatLng(e.latlng); sync(e.latlng); });
  } else {
    gcMap.setView([lat, lng], 15);
    gcMarker.setLatLng([lat, lng]);
  }
  setTimeout(() => gcMap.invalidateSize(true), 200);
});

document.getElementById('btnGpsGC').addEventListener('click', function() {
  if (!navigator.geolocation) {
    Swal.fire({ icon:'error', title:'Tidak Didukung', text:'Browser tidak mendukung Geolocation.' });
    return;
  }
  Swal.fire({ title:'Mengambil Lokasi…', html:'Mohon izinkan akses lokasi', allowOutsideClick:false, didOpen: () => Swal.showLoading() });
  navigator.geolocation.getCurrentPosition(
    pos => {
      const lat = pos.coords.latitude, lng = pos.coords.longitude;
      document.getElementById('gc_lat').value = lat.toFixed(8);
      document.getElementById('gc_lng').value = lng.toFixed(8);
      if (gcMap && gcMarker) { gcMarker.setLatLng([lat,lng]); gcMap.setView([lat,lng],17); }
      Swal.fire({ icon:'success', title:'Berhasil!', html:`Akurasi: ±${Math.round(pos.coords.accuracy)} meter`, timer:2000, showConfirmButton:false });
    },
    err => {
      const msgs = { 1:'Akses lokasi ditolak.', 2:'Informasi lokasi tidak tersedia.', 3:'Waktu permintaan habis.' };
      Swal.fire({ icon:'error', title:'Gagal', text: msgs[err.code] ?? err.message });
    },
    { enableHighAccuracy:true, timeout:15000, maximumAge:0 }
  );
});

document.getElementById('formGC').addEventListener('submit', function(e) {
  e.preventDefault();
  const lat    = parseFloat(document.getElementById('gc_lat').value);
  const lng    = parseFloat(document.getElementById('gc_lng').value);
  const status = document.getElementById('gc_status').value;

  if (isNaN(lat) || isNaN(lng)) {
    Swal.fire({ icon:'warning', title:'Peringatan', text:'Koordinat tidak valid.' }); return;
  }
  if (!status) {
    Swal.fire({ icon:'warning', title:'Peringatan', text:'Mohon pilih status usaha.' }); return;
  }

  const statusText = document.getElementById('gc_status').selectedOptions[0].text;
  Swal.fire({
    title: 'Konfirmasi Penyimpanan',
    html: `<div class="text-start">
      <strong>Status:</strong> ${statusText}<br>
      <strong>Latitude:</strong> ${lat.toFixed(8)}<br>
      <strong>Longitude:</strong> ${lng.toFixed(8)}<br><br>
      Apakah data sudah benar?</div>`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#16a34a',
    cancelButtonColor: '#6c757d',
    confirmButtonText: '<i class="bi bi-check-circle"></i> Ya, Simpan',
    cancelButtonText: 'Periksa Kembali',
    showLoaderOnConfirm: true,
    preConfirm: () => new Promise(resolve => { this.submit(); resolve(); })
  });
});

/* ════════════════════════════════════════════════════
   FLASH + INIT
════════════════════════════════════════════════════ */
function showFlash() {
  const s = document.getElementById('flash-success');
  const e = document.getElementById('flash-error');
  if (s) Swal.fire({ icon:'success', title:'Berhasil!', text:s.dataset.msg, timer:3000, timerProgressBar:true, confirmButtonColor:'#16a34a' });
  if (e) Swal.fire({ icon:'error',   title:'Gagal!',    text:e.dataset.msg, confirmButtonColor:'#dc2626' });
}

document.addEventListener('DOMContentLoaded', () => {
  showFlash();
  loadKec();    // load dropdown kecamatan
  fetchData();  // load tabel awal
});
</script>

<?= $this->endSection() ?>