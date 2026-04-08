<?php $this->extend('layouts/master') ?>
<?php $this->section('content') ?>

<?php
$gmKey = isset($googleMapsKey) ? $googleMapsKey : '';
$baseUrl = base_url();
?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
  <div class="container-fluid px-4">
    <div class="page-header-content">
      <div class="row align-items-center justify-content-between pt-3">
        <div class="col-auto mb-3">
          <h1 class="page-header-title">
            <div class="page-header-icon"><i data-feather="map-pin"></i></div>
            Daftar Usaha Berdasarkan Lokasi
          </h1>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="container-fluid px-4">
  <div class="row mb-3">
    <div class="col-md-8">
      <form id="form-pilih-desa" class="d-flex" onsubmit="return false;">
        <div class="input-group">
          <label class="input-group-text" for="iddesa">Pilih Desa</label>
          <select id="iddesa" name="iddesa" class="form-select">
            <option value="">-- Pilih Desa --</option>
            <option value="1471040001">1471040001 — Cinta Raja</option>
            <option value="1471060005">1471060005 — Kedung Sari</option>
          </select>
          <button id="btn-tampil" class="btn btn-primary ms-2">Tampilkan</button>
        </div>
      </form>
    </div>

    <div class="col-md-4 text-end">
      <a href="<?= base_url('usaha') . '?kabupaten=' . esc($selected_kabupaten) . '&kecamatan=' . esc($selected_kecamatan) . '&desa=' . esc($selected_desa) ?>" class="btn btn-secondary">
        <i data-feather="list"></i> Kembali ke Tabel
      </a>
    </div>
  </div>

  <div id="mapAll" style="height:640px;border:1px solid #ccc;border-radius:8px;"></div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<?php if (!empty($gmKey)): ?>
  <script src="https://maps.googleapis.com/maps/api/js?key=<?= esc($gmKey) ?>&v=weekly"></script>
  <script>
    const baseUrl = "<?= $baseUrl ?>";
    const defaultCenter = { lat: 0.507904, lng: 101.449728 };
    const defaultZoom = 14;

    let map;
    let polygonOverlays = [];
    let lastBounds = null;
    window._markers = [];

    function initMap() {
      map = new google.maps.Map(document.getElementById('mapAll'), {
        center: defaultCenter,
        zoom: defaultZoom,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: true,
        gestureHandling: 'greedy'
      });
    }
    window.addEventListener('load', initMap);

    function clearPolygonsAndMarkers() {
      polygonOverlays.forEach(o => { try { o.setMap(null); } catch(e) {} });
      polygonOverlays = [];
      window._markers.forEach(m => { try { m.setMap(null); } catch(e) {} });
      window._markers = [];
      lastBounds = null;
    }

    function createPolygon(paths) {
      return new google.maps.Polygon({
        paths,
        strokeColor: '#000000',
        strokeWeight: 2.5,
        strokeOpacity: 1,
        fillColor: '#555555',
        fillOpacity: 0.35,
        map: map,
        zIndex: 1
      });
    }

    async function loadGeojsonsForDesa(iddesa) {
      // don't remove markers yet; but clear polygons so we redraw and compute bounds
      polygonOverlays.forEach(o => { try { o.setMap(null); } catch(e) {} });
      polygonOverlays = [];

      const res = await fetch(`${baseUrl}/laboratorium/getGeojsonFiles/${iddesa}`);
      if (!res.ok) { Swal.fire('Error', 'Gagal mengambil daftar file geojson (HTTP ' + res.status + ')','error'); return; }
      const j = await res.json();
      if (!j.success) { Swal.fire('Error', j.message || 'Gagal ambil geojson','error'); return; }
      if (!j.files || j.files.length === 0) { lastBounds = null; return; }

      const bounds = new google.maps.LatLngBounds();
      let hasBounds = false;

      for (const fobj of j.files) {
        const fileUrl = (typeof fobj === 'string') ? `${baseUrl}/lokasi/map/${fobj}` : (fobj.url || `${baseUrl}/lokasi/map/${fobj.file}`);
        try {
          const r = await fetch(fileUrl);
          if (!r.ok) { console.warn('GEOJSON fetch failed', fileUrl, r.status); continue; }
          const gj = await r.json();
          if (!gj || !gj.features) continue;

          for (const feature of gj.features) {
            const geom = feature.geometry;
            if (!geom) continue;

            if (geom.type === 'Polygon') {
              const paths = geom.coordinates.map(ring => ring.map(c => ({ lat: parseFloat(c[1]), lng: parseFloat(c[0]) })));
              const poly = createPolygon(paths);
              polygonOverlays.push(poly);
              paths.forEach(r => r.forEach(p => { bounds.extend(p); hasBounds = true; }));
            } else if (geom.type === 'MultiPolygon') {
              for (const polycoords of geom.coordinates) {
                const paths = polycoords.map(ring => ring.map(c => ({ lat: parseFloat(c[1]), lng: parseFloat(c[0]) })));
                const poly = createPolygon(paths);
                polygonOverlays.push(poly);
                paths.forEach(r => r.forEach(p => { bounds.extend(p); hasBounds = true; }));
              }
            } else if (geom.type === 'LineString' || geom.type === 'MultiLineString') {
              const lines = geom.type === 'LineString' ? [geom.coordinates] : geom.coordinates;
              for (const line of lines) {
                const path = line.map(c => ({ lat: parseFloat(c[1]), lng: parseFloat(c[0]) }));
                const polyline = new google.maps.Polyline({
                  path,
                  strokeColor: '#000',
                  strokeWeight: 2,
                  strokeOpacity: 0.9,
                  map,
                  zIndex: 2
                });
                polygonOverlays.push(polyline);
                path.forEach(p => { bounds.extend(p); hasBounds = true; });
              }
            }
          }
        } catch (err) {
          console.warn('Gagal load geojson', fileUrl, err);
        }
      }

      if (hasBounds) {
        lastBounds = bounds;
      } else lastBounds = null;
    }

    function getRedIcon() {
      return {
        url: 'https://chart.googleapis.com/chart?chst=d_map_pin_icon&chld=place|FF0000',
        scaledSize: new google.maps.Size(34,45),
        anchor: new google.maps.Point(17,45)
      };
    }

    async function loadUsahaForDesa(iddesa) {
      // clear previous markers only (keep polygons, we will use lastBounds)
      window._markers.forEach(m => { try { m.setMap(null); } catch(e) {} });
      window._markers = [];

      try {
        const r = await fetch(`${baseUrl}/laboratorium/getUsaha/${iddesa}`);
        if (!r.ok) { Swal.fire('Error', 'Gagal mengambil data usaha (HTTP ' + r.status + ')','error'); return; }
        const j = await r.json();
        if (!j.success) { Swal.fire('Error', j.message || 'Gagal mengambil data usaha','error'); return; }

        const rows = j.data || [];
        if (!rows.length) {
          if (lastBounds) { map.fitBounds(lastBounds, { maxZoom: 18 }); }
          return;
        }

        const icon = getRedIcon();
        const markerBounds = new google.maps.LatLngBounds();
        let placed = 0;

        for (const u of rows) {
          const lat = parseFloat(String(u.placeLatitude ?? u.latitude ?? u.lat ?? '').replace(',', '.'));
          const lng = parseFloat(String(u.placeLongitude ?? u.longitude ?? u.lng ?? '').replace(',', '.'));
          if (isNaN(lat) || isNaN(lng)) continue;

          const marker = new google.maps.Marker({
            position: { lat, lng },
            map: map,
            title: u.placeName ?? '',
            icon: icon,
            zIndex: 9999,
            optimized: false
          });

          // small hover infowindow
          const inf = new google.maps.InfoWindow({ content: `<div style="font-size:13px;"><strong>${escapeHtml(u.placeName ?? '-')}</strong><br/>${escapeHtml(u.placeAddress ?? '')}</div>`, disableAutoPan: true });
          marker.addListener('mouseover', () => inf.open(map, marker));
          marker.addListener('mouseout', () => inf.close());

          window._markers.push(marker);
          markerBounds.extend({ lat, lng });
          placed++;
        }

        // combine polygon bounds + marker bounds
        const combined = new google.maps.LatLngBounds();
        let any = false;
        if (lastBounds) {
          try {
            const sw = lastBounds.getSouthWest();
            const ne = lastBounds.getNorthEast();
            combined.extend(sw); combined.extend(ne);
            any = true;
          } catch(e) {}
        }
        if (placed) {
          // extend with marker bounds
          try {
            const mSw = markerBounds.getSouthWest();
            const mNe = markerBounds.getNorthEast();
            combined.extend(mSw); combined.extend(mNe);
            any = true;
          } catch(e) {}
        }

        if (any) {
          // fit and zoom in to max 18
          map.fitBounds(combined, { maxZoom: 18 });
        } else {
          map.setCenter(defaultCenter);
          map.setZoom(defaultZoom);
        }

        console.log('Markers placed (no cluster):', placed);

      } catch (err) {
        console.error('Error loadUsahaForDesa (google):', err);
        Swal.fire('Error', 'Terjadi error saat memuat data usaha.', 'error');
      }
    }

    function escapeHtml(s) {
      if (s === null || s === undefined) return '';
      return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    document.getElementById('btn-tampil').addEventListener('click', async function(e) {
      e.preventDefault();
      const iddesa = document.getElementById('iddesa').value;
      if (!iddesa) { Swal.fire('Pilih Desa','Pilih desa terlebih dahulu.','warning'); return; }
      clearPolygonsAndMarkers();
      await loadGeojsonsForDesa(iddesa); // polygon loaded, lastBounds set
      await loadUsahaForDesa(iddesa);    // markers added, then fitBounds
    });
  </script>

<?php else: ?>
  <!-- Leaflet fallback: NO CLUSTER, all markers directly added -->
  <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

  <script>
    const baseUrl = "<?= $baseUrl ?>";
    const defaultCenter = [0.507904,101.449728];
    const defaultZoom = 14;
    const map = L.map('mapAll', { zoomControl: true }).setView(defaultCenter, defaultZoom);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution:'&copy; OpenStreetMap', maxZoom:19 }).addTo(map);

    const polygonLayer = L.layerGroup().addTo(map);
    const markerLayer = L.layerGroup().addTo(map);

    async function loadGeojsonsForDesa(iddesa) {
      polygonLayer.clearLayers();
      const res = await fetch(`${baseUrl}/laboratorium/getGeojsonFiles/${iddesa}`);
      if (!res.ok) { Swal.fire('Error', 'Gagal mengambil daftar file geojson (HTTP ' + res.status + ')','error'); return; }
      const j = await res.json();
      if (!j.success) { Swal.fire('Error', j.message || 'Gagal ambil geojson','error'); return; }
      if (!j.files || j.files.length === 0) return;

      const bounds = L.latLngBounds();
      let hasBounds = false;

      for (const fobj of j.files) {
        const fileUrl = (typeof fobj === 'string') ? `${baseUrl}/lokasi/map/${fobj}` : (fobj.url || `${baseUrl}/lokasi/map/${fobj.file}`);
        try {
          const r = await fetch(fileUrl);
          if (!r.ok) continue;
          const gj = await r.json();
          const layer = L.geoJSON(gj, { style: { color:'#000', weight:2.5, fillColor:'#555', fillOpacity:0.35 } }).addTo(polygonLayer);
          const lb = layer.getBounds();
          if (lb && lb.isValid && lb.isValid()) { bounds.extend(lb); hasBounds = true; }
        } catch(e){ console.warn(e); }
      }
      if (hasBounds) map.fitBounds(bounds, { maxZoom:18 });
    }

    async function loadUsahaForDesa(iddesa) {
      markerLayer.clearLayers();
      try {
        const r = await fetch(`${baseUrl}/laboratorium/getUsaha/${iddesa}`);
        if (!r.ok) { Swal.fire('Error', 'Gagal mengambil data usaha (HTTP ' + r.status + ')','error'); return; }
        const j = await r.json();
        if (!j.success) { Swal.fire('Error', j.message || 'Gagal ambil usaha','error'); return; }
        const rows = j.data || [];
        if (!rows.length) return;

        const bounds = L.latLngBounds();
        let placed = 0;
        for (const u of rows) {
          const lat = parseFloat(String(u.placeLatitude ?? u.latitude ?? u.lat ?? '').replace(',', '.'));
          const lng = parseFloat(String(u.placeLongitude ?? u.longitude ?? u.lng ?? '').replace(',', '.'));
          if (isNaN(lat) || isNaN(lng)) continue;
          const m = L.marker([lat,lng]);
          m.bindTooltip(u.placeName ?? '-', { direction:'top', offset:[0,-6] });
          m.addTo(markerLayer);
          bounds.extend([lat,lng]);
          placed++;
        }

        // Also include polygon bounds if any for tighter fit
        const polyBounds = polygonLayer.getBounds ? polygonLayer.getBounds() : null;
        if (polyBounds && polyBounds.isValid && polyBounds.isValid()) {
          polyBounds.extend(bounds);
          map.fitBounds(polyBounds, { maxZoom: 18 });
        } else if (placed) {
          map.fitBounds(bounds, { maxZoom: 18 });
        }

        console.log('Markers placed (no cluster):', placed);
      } catch (err) { console.error(err); Swal.fire('Error', 'Terjadi error saat memuat data usaha.', 'error'); }
    }

    document.getElementById('btn-tampil').addEventListener('click', async function(e) {
      e.preventDefault();
      const iddesa = document.getElementById('iddesa').value;
      if (!iddesa) { Swal.fire('Pilih Desa','Pilih desa terlebih dahulu.','warning'); return; }
      await loadGeojsonsForDesa(iddesa);
      await loadUsahaForDesa(iddesa);
    });
  </script>
<?php endif; ?>

<?php $this->endSection() ?>
