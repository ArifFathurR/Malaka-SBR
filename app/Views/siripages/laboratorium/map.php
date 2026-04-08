<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laboratorium - Peta GeoJSON & Establishment</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body { font-family: system-ui, Arial, sans-serif; margin:0; padding:0; }
    #map { height: 80vh; width: 100%; }
    .topbar { padding:10px; background:#fff; border-bottom:1px solid #eee; display:flex; gap:8px; align-items:center;}
    .badge { background:#007bff;color:#fff;padding:4px 8px;border-radius:4px; font-size:0.9rem;}
    .small { color:#666; font-size:0.9rem; }
  </style>
</head>
<body>
  <div class="topbar">
    <div class="badge">Laboratorium Map</div>
    <div class="small">IDs: <strong id="ids-list"><?= esc($default_ids) ?></strong></div>
    <div style="margin-left:auto"><button id="btn-reload">Reload Layers</button></div>
  </div>

  <div id="map"></div>

  <script>
    // API key dikirim controller (hardcoded di controller file)
    const GMAPS_API_KEY = '<?= esc($google_maps_api_key) ?>';
  </script>

  <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= esc($google_maps_api_key) ?>&callback=initMap&v=weekly"></script>

  <script>
    let map;
    const defaultIds = (document.getElementById('ids-list').textContent || '').split(',').map(s=>s.trim()).filter(Boolean);
    let _markers = [];

    function initMap(){
      const center = { lat: -0.5453334, lng: 101.9901861 };
      map = new google.maps.Map(document.getElementById('map'), {
        center, zoom: 12
      });

      loadAllLayers();
      document.getElementById('btn-reload').addEventListener('click', loadAllLayers);
    }

    async function loadAllLayers(){
      if (!defaultIds.length) { alert('Tidak ada iddesa terkonfigurasi.'); return; }

      // 1) Load GeoJSON gabungan
      try {
        const resGeo = await fetch('/laboratorium/geojson?ids=' + encodeURIComponent(defaultIds.join(',')));
        if (!resGeo.ok) throw new Error('Gagal load geojson: ' + resGeo.status);
        const geojson = await resGeo.json();
        map.data.forEach(f => map.data.remove(f));
        map.data.addGeoJson(geojson);
        styleGeoJson();
      } catch (err) {
        console.error(err);
        alert('Error load geojson: ' + err.message);
      }

      // 2) Load establishment points terbatas
      try {
        const resPts = await fetch('/laboratorium/api/establishments?ids=' + encodeURIComponent(defaultIds.join(',')));
        if (!resPts.ok) throw new Error('Gagal load points: ' + resPts.status);
        const pts = await resPts.json();
        clearMarkers();
        placeMarkers(pts);
      } catch (err) {
        console.error(err);
        alert('Error load markers: ' + err.message);
      }
    }

    function styleGeoJson(){
      map.data.setStyle(function(feature){
        const iddesa = feature.getProperty('iddesa') || '';
        const color = iddesa === '1471040001' ? '#1f78b4' : (iddesa === '1471060005' ? '#33a02c' : '#6a3d9a');
        return {
          fillColor: color,
          strokeColor: color,
          strokeWeight: 1,
          fillOpacity: 0.25
        };
      });

      map.data.addListener('click', function(event) {
        const props = event.feature.getProperties ? event.feature.getProperties() : {};
        let content = '<div>';
        content += '<strong>' + (props.name || props.nmsls || 'Feature') + '</strong><br/>';
        content += 'iddesa: ' + (props.iddesa || '-') + '<br/>';
        content += '</div>';
        const infowindow = new google.maps.InfoWindow({ content });
        infowindow.setPosition(event.latLng);
        infowindow.open(map);
      });
    }

    function clearMarkers(){
      for (const m of _markers) m.setMap(null);
      _markers = [];
    }

    function placeMarkers(items){
      if (!items || !items.length) return;
      const bounds = new google.maps.LatLngBounds();
      for (const it of items) {
        const lat = parseFloat(it.lat);
        const lng = parseFloat(it.lng);
        if (isNaN(lat) || isNaN(lng)) continue;
        const marker = new google.maps.Marker({
          position: { lat, lng },
          map,
          title: it.placeName || it.placeId
        });
        const iw = new google.maps.InfoWindow({
          content: `<strong>${escapeHtml(it.placeName)}</strong><br/>${escapeHtml(it.placeAddress||'')}<br/>SLS: ${escapeHtml(it.kdsls||'')}`
        });
        marker.addListener('click', ()=> iw.open(map, marker));
        _markers.push(marker);
        bounds.extend(marker.getPosition());
      }
      if (_markers.length) map.fitBounds(bounds);
    }

    function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }
  </script>
</body>
</html>
