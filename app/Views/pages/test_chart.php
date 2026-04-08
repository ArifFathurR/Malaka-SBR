<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Test Chart</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { padding: 2rem; }
    #loader { display: none; }
  </style>
</head>
<body>

<h3>Grafik Uji Coba</h3>

<select id="bulan">
  <option value="1">Januari</option>
  <option value="2" selected>Februari</option>
</select>
<select id="tahun">
  <option value="2024" selected>2024</option>
</select>

<div id="loader">⏳ Loading...</div>

<canvas id="wilayahChart" width="600" height="300"></canvas>

<script>
let chart = null;

function loadChart(bulan, tahun) {
  document.getElementById('loader').style.display = 'block';

  fetch(`<?= base_url('dashboard/getWilayahChartAjax') ?>?bulan=${bulan}&tahun=${tahun}`)
    .then(res => res.json())
    .then(data => {
      console.log('[DATA]', data);

      const ctx = document.getElementById('wilayahChart').getContext('2d');

      if (chart) chart.destroy();

      chart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
          responsive: true,
          plugins: {
            legend: { position: 'top' }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });

      document.getElementById('loader').style.display = 'none';
    })
    .catch(err => {
      console.error('[ERROR]', err);
      document.getElementById('loader').style.display = 'none';
    });
}

document.getElementById('bulan').addEventListener('change', () => {
  loadChart(document.getElementById('bulan').value, document.getElementById('tahun').value);
});
document.getElementById('tahun').addEventListener('change', () => {
  loadChart(document.getElementById('bulan').value, document.getElementById('tahun').value);
});

// First load
window.onload = function () {
  loadChart(document.getElementById('bulan').value, document.getElementById('tahun').value);
};
</script>

</body>
</html>
