<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<?php
$googleMapsKey = $googleMapsKey ?? '';
$desaList = $desaList ?? [];
$selectedDesa = $selectedDesa ?? '';
$selectedSls  = $selectedSls ?? 'all';
$creators = $creators ?? [];
$statusLabels = [1=>'1. Aktif',2=>'2. Tidak Aktif',3=>'3. Bukan Usaha',4=>'4. Tidak Ditemukan',5=>'5. Duplikat',6=>'6. Tidak Relevan',7=>'7. Baru'];
$currentUserId = (string)(session('id_user') ?? '');
?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
  <div class="container-fluid px-4">
    <div class="page-header-content">
      <div class="row align-items-center justify-content-between pt-3">
        <div class="col-auto mb-3">
          <h1 class="page-header-title">
            <div class="page-header-icon"><i data-feather="map-pin"></i></div>
            Indepth - Kaki Lima
          </h1>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="container-fluid px-4">
  <form method="get" action="<?= base_url('usaha/kakilima-dropdown') ?>" class="mb-4" id="formFilterKaki">
    <div class="row g-2 align-items-end">
      <div class="col-md-5">
        <label class="form-label">Desa</label>
        <select name="desa" id="desa" class="form-select" required>
          <option value="">-- Pilih Desa/Kel --</option>
          <?php foreach ($desaList as $d): ?>
            <option value="<?= esc($d['iddesa']) ?>" <?= $selectedDesa == $d['iddesa'] ? 'selected' : '' ?>>
              <?= esc($d['nmdesa']) ?> (<?= esc($d['iddesa']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">SLS</label>
        <select name="sls" id="sls" class="form-select" <?= empty($selectedDesa) ? 'disabled' : '' ?>>
          <option value="all">— Semua SLS —</option>
          <?php if (!empty($slsOptions)): foreach ($slsOptions as $s): 
            $kds = str_pad($s['kdsls'],4,'0',STR_PAD_LEFT); ?>
            <option value="<?= esc($kds) ?>" <?= ($selectedSls===$kds?'selected':'') ?>>
              <?= esc($s['nmsls'] . ' (' . $kds . ')') ?>
            </option>
          <?php endforeach; endif; ?>
        </select>
      </div>

      <div class="col-auto">
        <button class="btn btn-primary" type="submit">Tampilkan</button>
      </div>

      <div class="col-12 mt-2">
        <button type="button" class="btn btn-success" id="btnTambahKaki" data-bs-toggle="modal" data-bs-target="#createKakiModal"
          style="<?= empty($selectedDesa) ? 'display:none;' : '' ?>">
          <i data-feather="plus"></i> Tambah Usaha Kaki Lima
        </button>
      </div>
    </div>
  </form>

  <?php if (!empty($usaha)): ?>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table id="kakiTable" class="table table-sm table-bordered table-striped">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Nama Usaha</th>
              <th>Alamat Usaha</th>
              <th>Lihat Map</th>
              <th>Alamat Tempat Tinggal</th>
              <th>Status Indepth</th>
              <th>Lokasi Usaha</th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; foreach ($usaha as $u):
              $placeId   = is_array($u)?($u['placeId']??''):$u->placeId;
              $name      = is_array($u)?($u['placeName']??''):$u->placeName;
              $addr      = is_array($u)?($u['placeAddress']??''):$u->placeAddress;
              $loklain   = is_array($u)?($u['kakilima_address']??''):$u->kakilima_address;
              $kakilima_iddesa = is_array($u)?($u['kakilima_iddesa']??''):$u->kakilima_iddesa;
              $jenis_lokasi = is_array($u)?($u['jenis_usaha_lokasi']??null):($u->jenis_usaha_lokasi ?? null);
              $indepth_flag = is_array($u)?($u['indepth_flag']??null):($u->indepth_flag ?? null);
              $btnClass = ($indepth_flag !== null && (string)$indepth_flag === '1') ? 'btn-success' : 'btn-warning';
              $kab = esc($selected_kabupaten ?? ''); $kec = esc($selected_kecamatan ?? ''); $des = esc($selectedDesa ?? '');
              $mapUrl = base_url("usaha/mapView?placeId=".urlencode($placeId)."&kabupaten={$kab}&kecamatan={$kec}&desa={$des}");
              $creatorId = $creators[$placeId] ?? null;
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td>
                <div class="fw-semibold"><?= esc($name) ?></div>

                <button
                  type="button"
                  class="btn <?= $btnClass ?> btn-sm mt-2 btnKakiUpdate"
                  data-bs-toggle="modal"
                  data-bs-target="#updateKakiModal"
                  data-placeid="<?= esc($placeId) ?>"
                  data-name="<?= esc($name) ?>"
                  data-address="<?= esc($addr) ?>"
                  data-kakilima-address="<?= esc($loklain) ?>"
                  data-kakilima-iddesa="<?= esc($kakilima_iddesa) ?>"
                >Update</button>

                <?php if ($creatorId !== null && (string)$creatorId === $currentUserId): ?>
                  <form action="<?= base_url('usaha/deleteUsaha') ?>" method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus usaha ini?')">
                      <?= csrf_field() ?>
                      <input type="hidden" name="placeId" value="<?= esc($placeId) ?>">
                      <input type="hidden" name="return_desa" value="<?= esc($selectedDesa ?? '') ?>">
                      <input type="hidden" name="return_sls" value="<?= esc($selectedSls ?? 'all') ?>">
                      <button type="submit" class="btn btn-danger btn-sm ms-1 mt-2">Hapus</button>
                  </form>
                <?php endif; ?>

              </td>
              <td><?= esc($addr) ?></td>
              <td><a href="<?= $mapUrl ?>" target="_blank" class="btn btn-outline-info btn-sm">Lihat Map</a></td>
              <td><?= $loklain ? esc($loklain) : '-' ?></td>
              <td><?= ($indepth_flag !== null && (string)$indepth_flag === '1') ? 'Sudah indepth' : 'Belum' ?></td>
              <td>
                <?php
                  if ($jenis_lokasi === null || $jenis_lokasi === '') {
                    echo '-';
                  } else {
                    $map = [1=>'1. bangunan ini', 2=>'2. keliling', 3=>'3. bangunan tetap di lokasi lain'];
                    echo isset($map[(int)$jenis_lokasi]) ? $map[(int)$jenis_lokasi] : esc($jenis_lokasi);
                  }
                ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php else: ?>
    <div class="alert alert-info">Silakan pilih desa/kelurahan untuk menampilkan usaha kaki lima.</div>
  <?php endif; ?>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createKakiModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form id="formCreateKaki" method="post" action="<?= base_url('usaha/insertKakilima') ?>">
      <?= csrf_field() ?>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Usaha Kaki Lima</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="iddesa" id="create_iddesa" value="<?= esc($selectedDesa ?? '') ?>">
          <input type="hidden" id="create_kdsls" name="kdsls" value="">
          <input type="hidden" id="create_nmsls" name="nmsls" value="">
          <input type="hidden" name="indepth_flag" value="1">
          <input type="hidden" name="jenis_usaha_lokasi" value="2"> <!-- kaki lima default -->
          <input type="hidden" name="return_desa" value="<?= esc($selectedDesa ?? '') ?>">
          <input type="hidden" name="return_sls" value="<?= esc($selectedSls ?? 'all') ?>">

          <div class="mb-3">
            <label class="form-label">Nama Usaha</label>
            <input type="text" name="placeName" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat Usaha</label>
            <textarea name="placeAddress" class="form-control" rows="2" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Titik Lokasi (klik/drag marker)</label>
            <div id="createMap" style="height:420px;border:1px solid #ddd;"></div>
            <small class="text-muted">Jika titik berada dalam polygon SLS, kdsls & nmsls otomatis terisi.</small>
          </div>
          <div class="mb-3 d-flex gap-2">
            <input type="text" id="create_lat" name="latitude" class="form-control" placeholder="Latitude" required readonly>
            <input type="text" id="create_lng" name="longitude" class="form-control" placeholder="Longitude" required readonly>
          </div>
          <div class="mb-3 small text-muted">
            kdsls: <span id="kdslsValue">-</span> | nmsls: <span id="nmslsValue">-</span>
          </div>

          <hr>
          <h6 class="mb-2">Alamat Tempat Tinggal Pengusaha</h6>
          <div class="row g-2">
            <div class="col-md-4">
              <label class="form-label">Kabupaten</label>
              <select id="kaki_kab" class="form-select"><option value="">-- Pilih Kabupaten --</option></select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Kecamatan</label>
              <select id="kaki_kec" class="form-select"><option value="">-- Pilih Kecamatan --</option></select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Desa</label>
              <select id="kaki_desa" class="form-select"><option value="">-- Pilih Desa/Kel --</option></select>
              <input type="hidden" name="kakilima_iddesa" id="kakilima_iddesa">
            </div>
          </div>
          <div class="mt-2">
            <label class="form-label">Alamat Rumah</label>
            <textarea name="kakilima_address" id="kakilima_address" class="form-control" rows="2" required></textarea>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" id="btnCreateKakiSubmit" class="btn btn-success">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateKakiModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" action="<?= base_url('usaha/updateKakilima') ?>" id="formUpdateKaki" class="modal-content">
      <?= csrf_field() ?>
      <div class="modal-header">
        <h5 class="modal-title">Update Usaha Kaki Lima</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="placeId" id="upd_placeId">
        <input type="hidden" name="indepth_flag" value="1">
        <input type="hidden" name="return_desa" value="<?= esc($selectedDesa ?? '') ?>">
        <input type="hidden" name="return_sls" value="<?= esc($selectedSls ?? 'all') ?>">

        <div class="mb-3">
          <label class="form-label">Nama Usaha</label>
          <input type="text" class="form-control" name="placeName" id="upd_placeName" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Alamat Usaha</label>
          <textarea class="form-control" name="placeAddress" id="upd_placeAddress" rows="2" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Alamat Tempat Tinggal</label>
          <textarea class="form-control" name="kakilima_address" id="upd_loklain" rows="2" placeholder="Alamat tempat tinggal pengusaha"></textarea>
        </div>

        <input type="hidden" name="kakilima_iddesa" id="upd_kakilima_iddesa">
        <input type="hidden" name="kakilima_latitude" id="upd_kakilima_lat">
        <input type="hidden" name="kakilima_longitude" id="upd_kakilima_lng">

        <h6 class="mb-2">Ubah Alamat Tempat Tinggal (Kab/Kec/Desa)</h6>
        <div class="row g-2">
          <div class="col-md-4">
            <label class="form-label">Kabupaten</label>
            <select id="upd_kab" class="form-select"><option value="">-- Pilih Kabupaten --</option></select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Kecamatan</label>
            <select id="upd_kec" class="form-select"><option value="">-- Pilih Kecamatan --</option></select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Desa</label>
            <select id="upd_desa" class="form-select"><option value="">-- Pilih Desa/Kel --</option></select>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Simpan</button>
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($googleMapsKey)): ?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= esc($googleMapsKey) ?>&libraries=geometry"></script>
<?php endif; ?>

<script>
(function(){
  if (typeof jQuery !== 'undefined') {
    $(function(){ if ($('#kakiTable').length && !$.fn.dataTable.isDataTable('#kakiTable')) $('#kakiTable').DataTable({responsive:true,pageLength:10,language:{url:"//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"}}); });
  }

  document.addEventListener('DOMContentLoaded', function(){
    <?php if (session()->getFlashdata('success')): ?>
      Swal.fire({icon:'success',title:'Berhasil',text:<?= json_encode(session()->getFlashdata('success')) ?>});
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      Swal.fire({icon:'error',title:'Gagal',text:<?= json_encode(session()->getFlashdata('error')) ?>});
    <?php endif; ?>
  });

  // filter SLS dynamic
  (function(){
    var desaSel = document.getElementById('desa'), slsSel = document.getElementById('sls');
    function resetSls(){ if(slsSel) slsSel.innerHTML = '<option value="all">— Semua SLS —</option>'; }
    function enableSls(b){ if(slsSel) slsSel.disabled = !b; }
    if (desaSel && slsSel) {
      desaSel.addEventListener('change', function(){
        var iddesa = this.value || ''; if(!iddesa){ resetSls(); enableSls(false); return; }
        enableSls(true); slsSel.innerHTML = '<option>Memuat...</option>';
        fetch('<?= base_url('usaha/get-sls-by-desa/') ?>'+encodeURIComponent(iddesa)).then(r=>r.json()).then(rows=>{ resetSls(); (rows||[]).forEach(function(row){ var k=(row.kdsls||'').toString().padStart(4,'0'); var opt=document.createElement('option'); opt.value=k; opt.textContent=(row.nmsls||'')+' ('+k+')'; slsSel.appendChild(opt); }); }).catch(()=>resetSls());
      });
    }
  })();

  // helper wilayah fetch
  function fetchKab(sel){ return fetch('<?= base_url('usaha/getAllKabupaten') ?>').then(r=>r.json()).then(rows=>{ var s=document.querySelector(sel); if(!s) return; s.innerHTML='<option value=\"\">-- Pilih Kabupaten --</option>'; (rows||[]).forEach(k=>{ var opt=document.createElement('option'); opt.value=k.idkab; opt.textContent=k.nmkab; s.appendChild(opt); }); }); }
  function fetchKec(sel,idkab){ if(!idkab) return Promise.resolve(); return fetch('<?= base_url('usaha/getKecamatan/') ?>'+encodeURIComponent(idkab)).then(r=>r.json()).then(rows=>{ var s=document.querySelector(sel); if(!s) return; s.innerHTML='<option value=\"\">-- Pilih Kecamatan --</option>'; (rows||[]).forEach(k=>{ var opt=document.createElement('option'); opt.value=k.idkec; opt.textContent=k.nmkec; s.appendChild(opt); }); }); }
  function fetchDesa(sel,idkec){ if(!idkec) return Promise.resolve(); return fetch('<?= base_url('usaha/getDesa/') ?>'+encodeURIComponent(idkec)).then(r=>r.json()).then(rows=>{ var s=document.querySelector(sel); if(!s) return; s.innerHTML='<option value=\"\">-- Pilih Desa/Kel --</option>'; (rows||[]).forEach(d=>{ var opt=document.createElement('option'); opt.value=d.iddesa; opt.textContent=d.nmdesa; s.appendChild(opt); }); }); }

  // Create modal select cascade + map
  (function(){
    var createEl = document.getElementById('createKakiModal');
    if (createEl) {
      createEl.addEventListener('shown.bs.modal', function(){ fetchKab('#kaki_kab'); initCreateMap({lat:0.5074,lng:101.4478}); });
      var selKab=document.getElementById('kaki_kab'), selKec=document.getElementById('kaki_kec'), selDes=document.getElementById('kaki_desa');
      if (selKab) selKab.addEventListener('change', function(){ fetchKec('#kaki_kec', this.value).then(()=>{ selDes.innerHTML='<option value=\"\">-- Pilih Desa/Kel --</option>'; document.getElementById('kakilima_iddesa').value=''; }); });
      if (selKec) selKec.addEventListener('change', function(){ fetchDesa('#kaki_desa', this.value).then(()=>document.getElementById('kakilima_iddesa').value=''); });
      if (selDes) selDes.addEventListener('change', function(){ document.getElementById('kakilima_iddesa').value = this.value || ''; if(!document.getElementById('kakilima_address').value.trim() && this.selectedOptions[0]) document.getElementById('kakilima_address').value = this.selectedOptions[0].textContent; });
    }

    document.getElementById('btnCreateKakiSubmit')?.addEventListener('click', function(){
      var form = document.getElementById('formCreateKaki');
      var miss=[];
      if (!form.placeName.value.trim()) miss.push('Nama usaha');
      if (!form.placeAddress.value.trim()) miss.push('Alamat usaha');
      if (!document.getElementById('create_iddesa').value) miss.push('Desa usaha');
      if (!document.getElementById('create_lat').value || !document.getElementById('create_lng').value) miss.push('Koordinat usaha');
      if (!document.getElementById('kakilima_iddesa').value) miss.push('Desa tempat tinggal pengusaha');
      if (!document.getElementById('kakilima_address').value.trim()) miss.push('Alamat tempat tinggal pengusaha');
      if (miss.length){ Swal.fire({icon:'warning',title:'Validasi Gagal',html:'Field wajib: <b>'+miss.join(', ')+'</b>'}); return; }
      Swal.fire({title:'Simpan data?',icon:'question',showCancelButton:true,confirmButtonText:'Ya, simpan',cancelButtonText:'Batal'}).then(function(res){ if(res.isConfirmed) form.submit(); });
    });

    // minimal map init + geojson check
    var createMap=null, createMarker=null;
    function initCreateMap(center){
      if (!center) center={lat:0.5074,lng:101.4478};
      var mapDiv = document.getElementById('createMap');
      if (!mapDiv || typeof google === 'undefined') return;
      if (!createMap) createMap = new google.maps.Map(mapDiv,{center:center,zoom:16,streetViewControl:false});
      else { createMap.setCenter(center); google.maps.event.trigger(createMap,'resize'); }
      if (!createMarker) {
        createMarker = new google.maps.Marker({position:center,map:createMap,draggable:true});
        createMarker.addListener('dragend', function(){ var p=createMarker.getPosition(); setCreateLatLng(p.lat(),p.lng()); checkGeojsonAndSetKdsls(p.lat(),p.lng()); });
        createMap.addListener('click', function(e){ createMarker.setPosition(e.latLng); setCreateLatLng(e.latLng.lat(), e.latLng.lng()); checkGeojsonAndSetKdsls(e.latLng.lat(), e.latLng.lng()); });
      } else createMarker.setPosition(center);
      setCreateLatLng(center.lat, center.lng);
      checkGeojsonAndSetKdsls(center.lat, center.lng);
    }
    function setCreateLatLng(lat,lng){ var a=document.getElementById('create_lat'); var b=document.getElementById('create_lng'); if(a) a.value=lat; if(b) b.value=lng; }

    function checkGeojsonAndSetKdsls(lat,lng){
      var iddesa = document.getElementById('create_iddesa')?.value || '';
      if (!iddesa) return;
      var fd = new FormData();
      fd.append('lat', lat); fd.append('lng', lng); fd.append('iddesa', iddesa);
      fd.append('<?= csrf_token() ?>','<?= csrf_hash() ?>');
      fetch('<?= base_url('usaha/checkGeojson') ?>', { method:'POST', body: fd, credentials: 'same-origin' })
        .then(r=>r.json()).then(json=>{
          if (json && json.success) {
            document.getElementById('create_kdsls').value = json.kdsls || '';
            document.getElementById('create_nmsls').value = json.nmsls || '';
            document.getElementById('kdslsValue').textContent = json.kdsls || '-';
            document.getElementById('nmslsValue').textContent = json.nmsls || '-';
          } else {
            document.getElementById('create_kdsls').value = '';
            document.getElementById('create_nmsls').value = '';
            document.getElementById('kdslsValue').textContent = '-';
            document.getElementById('nmslsValue').textContent = '-';
          }
        }).catch(err => {
          console.error('checkGeojson failed', err);
          document.getElementById('create_kdsls').value = '';
          document.getElementById('create_nmsls').value = '';
          document.getElementById('kdslsValue').textContent = '-';
          document.getElementById('nmslsValue').textContent = '-';
        });
    }

  })();

  // Update modal: isi fields + pra-pilih kakilima wilayah
  (function(){
    document.addEventListener('click', function(e){
      var btn = e.target.closest('.btnKakiUpdate'); if(!btn) return;
      var placeId = btn.getAttribute('data-placeid') || '';
      var name    = btn.getAttribute('data-name') || '';
      var address = btn.getAttribute('data-address') || '';
      var kakAddr = btn.getAttribute('data-kakilima-address') || '';
      var kakIdDesa = btn.getAttribute('data-kakilima-iddesa') || '';

      document.getElementById('upd_placeId').value = placeId;
      document.getElementById('upd_placeName').value = name;
      document.getElementById('upd_placeAddress').value = address;
      document.getElementById('upd_loklain').value = kakAddr;
      document.getElementById('upd_kakilima_iddesa').value = kakIdDesa || '';
      document.getElementById('upd_kakilima_lat').value = btn.getAttribute('data-kakilima-lat') || '';
      document.getElementById('upd_kakilima_lng').value = btn.getAttribute('data-kakilima-lng') || '';

      var iddesaNow = kakIdDesa || '';
      var idkabNow = iddesaNow ? iddesaNow.substring(0,4) : '';
      var idkecNow = iddesaNow ? iddesaNow.substring(0,7) : '';

      var selKab = document.getElementById('upd_kab');
      var selKec = document.getElementById('upd_kec');
      var selDes = document.getElementById('upd_desa');
      if (selKec) selKec.innerHTML = '<option value=\"\">-- Pilih Kecamatan --</option>';
      if (selDes) selDes.innerHTML = '<option value=\"\">-- Pilih Desa/Kel --</option>';

      if (!iddesaNow) {
        fetchKab('#upd_kab').catch(()=>{});
        return;
      }

      fetchKab('#upd_kab').then(function(){
        if (idkabNow && selKab) selKab.value = idkabNow;
        return fetchKec('#upd_kec', idkabNow);
      }).then(function(){
        if (idkecNow && selKec) selKec.value = idkecNow;
        return fetchDesa('#upd_desa', idkecNow);
      }).then(function(){
        if (iddesaNow && selDes) selDes.value = iddesaNow;
      }).catch(function(err){ console.error('Pra-pilih wilayah kakilima error', err); });
    });

    var uKab = document.getElementById('upd_kab');
    var uKec = document.getElementById('upd_kec');
    var uDes = document.getElementById('upd_desa');
    if (uKab) uKab.addEventListener('change', function(){ var idkab=this.value; if(!idkab){ if(uKec) uKec.innerHTML='<option value=\"\">-- Pilih Kecamatan --</option>'; if(uDes) uDes.innerHTML='<option value=\"\">-- Pilih Desa/Kel --</option>'; document.getElementById('upd_kakilima_iddesa').value=''; return; } fetchKec('#upd_kec', idkab).then(function(){ if(uDes) uDes.innerHTML='<option value=\"\">-- Pilih Desa/Kel --</option>'; document.getElementById('upd_kakilima_iddesa').value=''; }); });
    if (uKec) uKec.addEventListener('change', function(){ var idkec=this.value; if(!idkec){ if(uDes) uDes.innerHTML='<option value=\"\">-- Pilih Desa/Kel --</option>'; document.getElementById('upd_kakilima_iddesa').value=''; return; } fetchDesa('#upd_desa', idkec).then(function(){ document.getElementById('upd_kakilima_iddesa').value=''; }); });
    if (uDes) uDes.addEventListener('change', function(){ document.getElementById('upd_kakilima_iddesa').value = this.value || ''; });

  })();

})();
</script>

<style>
  div.dataTables_length { float:left; margin-right:20px; }
  div.dataTables_filter { float:right; }
  @media (max-width:768px){ div.dataTables_length, div.dataTables_filter { float:none; text-align:center; margin-bottom:10px; } }
</style>

<?= $this->endSection() ?>
