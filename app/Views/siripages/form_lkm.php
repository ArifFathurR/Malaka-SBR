<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="container-fluid px-4 mt-3">
  <h4 class="mb-3">Entri LKM per Segmen</h4>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>

  <form id="formLKM" method="post" action="<?= base_url('lkm/simpan') ?>" novalidate>
    <?= csrf_field() ?>

    <div class="row g-3">
      <div class="col-md-4">
        <label for="kecamatan" class="form-label">Kecamatan</label>
        <select id="kecamatan" name="idkec" class="form-select" required>
          <option value="">-- Pilih Kecamatan --</option>
          <?php if (!empty($kecamatan)): foreach ($kecamatan as $k): ?>
            <option value="<?= esc($k['idkec']) ?>" <?= isset($selected_kecamatan) && $selected_kecamatan == $k['idkec'] ? 'selected' : '' ?>>
              <?= esc($k['nmkec']) ?>
            </option>
          <?php endforeach; endif; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label for="desa" class="form-label">Desa</label>
        <select id="desa" name="iddesa" class="form-select" required>
          <option value="">-- Pilih Desa --</option>
          <?php if (!empty($desa)): foreach ($desa as $d): ?>
            <option value="<?= esc($d['iddesa']) ?>" <?= isset($selected_desa) && $selected_desa == $d['iddesa'] ? 'selected' : '' ?>>
              <?= esc($d['nmdesa']) ?>
            </option>
          <?php endforeach; endif; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label for="kodesls" class="form-label">
          Kode SLS (Project)
          <span id="lkmLoader" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
        </label>
        <input type="text" inputmode="numeric" pattern="\d{4}" maxlength="4"
           class="form-control" id="kodesls" name="kodesls"
           placeholder="4 digit"
           value="<?= esc($prefill_kodesls ?? '') ?>" 
           required>
        <div class="form-text">Wajib 4 digit angka. Contoh: 0001</div>
      </div>
    </div>

    <hr class="my-4">

    <!-- Hint HP -->
    <div id="hpSwipeHint" class="small fst-italic text-muted d-none mb-2">
      geser ke kanan untuk tampilan HP (kecil)
    </div>

    <div class="table-responsive">
      <table class="table table-bordered align-middle lkm-table" id="tblSegmen">
        <colgroup>
          <col style="width:120px">  <!-- No Segmen -->
          <col style="width:150px">  <!-- KK(5) -->
          <col style="width:110px">  <!-- BTT(6) -->
          <col style="width:110px">  <!-- BTTK(7) -->
          <col style="width:110px">  <!-- BKU(8) -->
          <col style="width:150px">  <!-- BBTT non Usaha(9) -->
          <col style="width:170px">  <!-- Muatan Usaha(10) -->
          <col style="width:200px">  <!-- Total(11) -->
        </colgroup>
        <thead class="table-light text-center">
          <tr>
            <th>No Segmen (S01, S02, dst)</th>
            <th>Perkiraan Muatan KK (5)</th>
            <th>BTT (6)</th>
            <th>BTTK (7)</th>
            <th>BKU (8)</th>
            <th>BBTT non Usaha (9)</th>
            <th>Perkiraan Muatan Usaha (10)</th>
            <th>Total (11) = (maks{5,6}) + 7 + 9 + 10</th>
          </tr>
        </thead>
        <tbody>
          <?php for ($i=0; $i<8; $i++): ?>
          <tr data-row="<?= $i ?>">
            <td>
              <input type="text" class="form-control text-center segmen"
                     name="rows[<?= $i ?>][no_segmen]"
                     id="rows_<?= $i ?>_segmen"
                     pattern="^S\d{2}$" maxlength="3" autocomplete="off">
            </td>
            <td>
              <input type="number" min="0" inputmode="numeric" pattern="\d*"
                     class="form-control text-end jmlkk"
                     name="rows[<?= $i ?>][jmlkk]" id="rows_<?= $i ?>_jmlkk" autocomplete="off">
            </td>
            <td>
              <input type="number" min="0" inputmode="numeric" pattern="\d*"
                     class="form-control text-end btt"
                     name="rows[<?= $i ?>][btt]" id="rows_<?= $i ?>_btt" autocomplete="off">
            </td>
            <td>
              <input type="number" min="0" inputmode="numeric" pattern="\d*"
                     class="form-control text-end bttk"
                     name="rows[<?= $i ?>][bttk]" id="rows_<?= $i ?>_bttk" autocomplete="off">
            </td>
            <td>
              <input type="number" min="0" inputmode="numeric" pattern="\d*"
                     class="form-control text-end bku"
                     name="rows[<?= $i ?>][bku]" id="rows_<?= $i ?>_bku" autocomplete="off">
            </td>
            <td>
              <input type="number" min="0" inputmode="numeric" pattern="\d*"
                     class="form-control text-end bbtt_nu"
                     name="rows[<?= $i ?>][bbtt_nu]" id="rows_<?= $i ?>_bbtt_nu" autocomplete="off">
            </td>
            <td>
              <input type="number" min="0" inputmode="numeric" pattern="\d*"
                     class="form-control text-end perkiraan_usaha"
                     name="rows[<?= $i ?>][perkiraan_usaha]" id="rows_<?= $i ?>_perkiraan_usaha" autocomplete="off">
            </td>
            <td>
              <input type="number" min="0" inputmode="numeric" pattern="\d*"
                     class="form-control text-end total"
                     name="rows[<?= $i ?>][total_muatan]" id="rows_<?= $i ?>_total" readonly>
            </td>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>
    </div>

    <div id="infoUpdated" class="small mt-2 d-none"></div>

    <input type="hidden" name="id_siri_user" value="<?= esc(session('id_siri_user') ?? session('id_user')) ?>">

    <div class="d-flex gap-2 mt-3">
      <button type="submit" id="btnSimpan" class="btn btn-primary d-none">Simpan</button>
    </div>
  </form>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Simpan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <p>Pastikan data berikut sudah benar.</p>
        <table class="table table-sm mb-2">
          <tbody>
            <tr><th style="width:18%">Kecamatan</th><td id="pv_kec">-</td></tr>
            <tr><th>Desa</th><td id="pv_desa">-</td></tr>
            <tr><th>Kode SLS</th><td id="pv_sls">-</td></tr>
          </tbody>
        </table>
        <div class="table-responsive">
          <table class="table table-bordered table-sm" id="pv_table">
            <thead class="table-light">
              <tr>
                <th>No Segmen</th><th>KK(5)</th><th>BTT(6)</th><th>BTTK(7)</th>
                <th>BKU(8)</th><th>BBTT non Usaha(9)</th><th>Muatan Usaha(10)</th><th>Total(11)</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="alert alert-warning mt-2">Total = (maks{5,6}) + 7 + 9 + 10</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cek Lagi</button>
        <button type="button" id="btnConfirmSubmit" class="btn btn-primary">Yakin, Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- CSS -->
<style>
  .lkm-table { table-layout: fixed; }         /* patuhi width di <colgroup> */
  .lkm-table th{
    white-space: normal;                      /* header boleh wrap */
    word-break: break-word;
    overflow-wrap: anywhere;
    line-height: 1.2;
    padding: 10px 8px;
    font-size: 13px;
    vertical-align: middle;
  }
  .lkm-table td { vertical-align: middle; }
  .lkm-table input.form-control{
    width: 100%;
    height: 48px;
    font-size: 16px;                          /* cegah iOS auto-zoom */
  }
  .table-responsive { overflow-x: auto; }
</style>

<!-- JS -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const form    = document.getElementById('formLKM');
  const kec     = document.getElementById('kecamatan');
  const desa    = document.getElementById('desa');
  const kodesls = document.getElementById('kodesls');
  const loader  = document.getElementById('lkmLoader');
  const btn     = document.getElementById('btnSimpan');
  const info    = document.getElementById('infoUpdated');
  const hpHint  = document.getElementById('hpSwipeHint');

  let prefetchDone = false, ctrl = null, modalInst, confirmed = false;

  const rows = [...document.querySelectorAll('#tblSegmen tbody tr')].map(tr => {
    const i = tr.dataset.row;
    return {
      segmen: document.getElementById(`rows_${i}_segmen`),
      jmlkk:  document.getElementById(`rows_${i}_jmlkk`),
      btt:    document.getElementById(`rows_${i}_btt`),
      bttk:   document.getElementById(`rows_${i}_bttk`),
      bku:    document.getElementById(`rows_${i}_bku`),
      bbtt:   document.getElementById(`rows_${i}_bbtt_nu`),
      usaha:  document.getElementById(`rows_${i}_perkiraan_usaha`),
      total:  document.getElementById(`rows_${i}_total`),
    };
  });

  function toInt(v){ const n = parseInt(v, 10); return isNaN(n) ? 0 : n; }
  function recalcRow(r){
    const base = Math.max(toInt(r.jmlkk.value), toInt(r.btt.value));
    r.total.value = base + toInt(r.bttk.value) + toInt(r.bbtt.value) + toInt(r.usaha.value);
  }
  rows.forEach(r => ['input','change'].forEach(ev => {
    [r.jmlkk,r.btt,r.bttk,r.bbtt,r.usaha].forEach(el => el.addEventListener(ev, ()=>recalcRow(r)));
  }));

  function validKodesls(){ const ok=/^\d{4}$/.test(kodesls.value); kodesls.classList.toggle('is-invalid',!ok&&kodesls.value.length>0); return ok; }
  function validSeg(s){ return /^S\d{2}$/.test((s||'').toUpperCase()); }
  function hasAnyRowData(){
    return rows.some(r=>{
      const any = r.jmlkk.value||r.btt.value||r.bttk.value||r.bku.value||r.bbtt.value||r.usaha.value;
      return validSeg(r.segmen.value) || any;
    });
  }
  function toggleSave(show){ btn.classList.toggle('d-none',!show); btn.disabled = !show; }
  function guardSaveVisibility(){ toggleSave(!!desa.value && validKodesls() && prefetchDone && hasAnyRowData()); }
  function disableInputs(state){ rows.forEach(r=>[r.segmen,r.jmlkk,r.btt,r.bttk,r.bku,r.bbtt,r.usaha].forEach(el=>el.disabled=state)); btn.disabled = state || btn.classList.contains('d-none'); }

  function clearRows(){
    rows.forEach(r=>{ r.segmen.value=''; r.jmlkk.value=''; r.btt.value=''; r.bttk.value=''; r.bku.value=''; r.bbtt.value=''; r.usaha.value=''; r.total.value=''; });
    info.classList.add('d-none'); info.innerHTML='';
  }
  function fillRows(list){
    clearRows();
    (list||[]).forEach((row,idx)=>{
      if (idx >= rows.length) return;
      const r = rows[idx];
      r.segmen.value = row.no_segmen || '';
      r.jmlkk.value  = row.jmlkk ?? '';
      r.btt.value    = row.btt ?? '';
      r.bttk.value   = row.bttk ?? '';
      r.bku.value    = row.bku ?? '';
      r.bbtt.value   = row.bbtt_nu ?? '';
      r.usaha.value  = row.perkiraan_usaha ?? '';
      recalcRow(r);
      r.total.classList.add('bg-warning-subtle'); setTimeout(()=>r.total.classList.remove('bg-warning-subtle'), 500);
    });

    const updated = (list||[]).filter(x=>x.updated_by||x.tanggal_update)
      .map(x=>`<li><code>${x.no_segmen||'-'}</code> oleh <strong>${x.updated_by||'?'}</strong>${x.tanggal_update?` pada <strong>${x.tanggal_update}</strong>`:''}</li>`);
    if (updated.length){ info.classList.remove('d-none'); info.innerHTML = `<div class="text-success">Riwayat update:</div><ul class="mb-0">${updated.join('')}</ul>`; }
  }

  function updateHpHint(){
    const isMobile = window.matchMedia('(max-width: 768px)').matches;
    hpHint.classList.toggle('d-none', !(isMobile && /^\d{4}$/.test(kodesls.value)));
  }

  // SLS input
  kodesls.addEventListener('input', ()=>{
    kodesls.value = kodesls.value.replace(/\D/g,'').slice(0,4);
    if (desa.value && /^\d{4}$/.test(kodesls.value)) prefillIfAny();
    guardSaveVisibility(); updateHpHint();
  });
  kodesls.addEventListener('blur', ()=>{
    if (kodesls.value.length>0) kodesls.value = kodesls.value.padStart(4,'0');
    prefillIfAny(); updateHpHint();
  });

  // Kec -> desa
  if (kec) {
    kec.addEventListener('change', async ()=>{
      desa.innerHTML = '<option value="">Memuat...</option>';
      try{
        const res = await fetch(`<?= base_url('lkm/desa') ?>/${kec.value}`);
        const data = await res.json();
        desa.innerHTML = '<option value="">-- Pilih Desa --</option>';
        data.forEach(d=>{ const opt=document.createElement('option'); opt.value=d.iddesa; opt.textContent=d.nmdesa; desa.appendChild(opt); });
      }catch{ desa.innerHTML = '<option value="">Gagal memuat</option>'; }
      clearRows(); prefetchDone=false; toggleSave(false); updateHpHint();
    });
  }
  desa.addEventListener('change', ()=>{ clearRows(); prefetchDone=false; toggleSave(false); if (validKodesls()) prefillIfAny(); updateHpHint(); });

  // Segmen normalisasi
  rows.forEach(r=>{
    r.segmen.addEventListener('input', ()=>{ r.segmen.value = r.segmen.value.toUpperCase().replace(/\s+/g,'').slice(0,3); guardSaveVisibility(); });
    r.segmen.addEventListener('blur', ()=>{
      const m = r.segmen.value.toUpperCase().replace(/\s+/g,'').match(/^(?:S)?0*([0-9]{1,2})$/);
      if (m) r.segmen.value = 'S' + String(m[1]).padStart(2,'0');
      guardSaveVisibility();
    });
    [r.jmlkk,r.btt,r.bttk,r.bku,r.bbtt,r.usaha].forEach(el=>el.addEventListener('input', guardSaveVisibility));
  });

  // Prefill
  async function prefillIfAny(){
    if (!desa.value || !validKodesls()) { prefetchDone=false; guardSaveVisibility(); return; }
    if (ctrl) ctrl.abort(); ctrl = new AbortController();
    loader.classList.remove('d-none'); disableInputs(true);
    try{
      const url = `<?= base_url('lkm/get') ?>?iddesa=${encodeURIComponent(desa.value)}&kodesls=${encodeURIComponent(kodesls.value)}`;
      const res = await fetch(url, { signal: ctrl.signal });
      if (!res.ok) throw new Error('HTTP '+res.status);
      const list = await res.json();
      if (Array.isArray(list) && list.length) fillRows(list); else clearRows();
    }catch{ clearRows(); }
    finally{
      loader.classList.add('d-none'); disableInputs(false);
      prefetchDone = true; guardSaveVisibility(); updateHpHint();
    }
  }

  // Modal preview & submit
  const pv = {
    kec:  document.getElementById('pv_kec'),
    desa: document.getElementById('pv_desa'),
    sls:  document.getElementById('pv_sls'),
    tbody: document.querySelector('#pv_table tbody')
  };
  function collectRows(){
    return rows.map(r=>({
      seg: (r.segmen.value||'').toUpperCase(),
      j5: r.jmlkk.value||0, j6: r.btt.value||0, j7: r.bttk.value||0,
      j8: r.bku.value||0,   j9: r.bbtt.value||0, j10: r.usaha.value||0,
      j11: r.total.value||0
    })).filter(x=>{
      const any = x.j5||x.j6||x.j7||x.j8||x.j9||x.j10;
      return /^S\d{2}$/.test(x.seg) && any;
    });
  }
  function fillPreview(){
    pv.kec.textContent  = kec.options[kec.selectedIndex]?.text || '-';
    pv.desa.textContent = desa.options[desa.selectedIndex]?.text || '-';
    pv.sls.textContent  = kodesls.value || '-';
    const list = collectRows();
    pv.tbody.innerHTML = list.map(x=>`
      <tr>
        <td><code>${x.seg}</code></td>
        <td class="text-end">${x.j5}</td><td class="text-end">${x.j6}</td>
        <td class="text-end">${x.j7}</td><td class="text-end">${x.j8}</td>
        <td class="text-end">${x.j9}</td><td class="text-end">${x.j10}</td>
        <td class="text-end">${x.j11}</td>
      </tr>`).join('');
  }

  form.addEventListener('submit', (e)=>{
    if (confirmed) { confirmed=false; return; }
    e.preventDefault();
    if (!prefetchDone || btn.classList.contains('d-none')) return;
    const list = collectRows();
    if (!list.length) { alert('Isi minimal satu baris segmen yang valid.'); return; }
    fillPreview();
    modalInst = modalInst || new bootstrap.Modal(document.getElementById('confirmModal'));
    modalInst.show();
  });

  document.getElementById('btnConfirmSubmit').addEventListener('click', ()=>{
    rows.forEach(r=>{
      if (!r.segmen.value) return;
      const m = r.segmen.value.toUpperCase().replace(/\s+/g,'').match(/^(?:S)?0*([0-9]{1,2})$/);
      if (m) r.segmen.value = 'S' + String(m[1]).padStart(2,'0');
    });
    confirmed = true; form.submit();
  });

  window.addEventListener('resize', updateHpHint);
  if (desa.value && /^\d{4}$/.test(kodesls.value)) prefillIfAny(); else toggleSave(false);
});
</script>

<?= $this->endSection() ?>
