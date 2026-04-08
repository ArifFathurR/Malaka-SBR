<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h4 class="mt-4">Form Pelaporan</h4>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif ?>

    <form id="formPelaporan" method="post" action="<?= base_url('pelaporan/simpan') ?>">
        <div class="mb-3">
            <label for="resume">Uraian Kegiatan</label>
            <textarea name="resume" class="form-control" rows="3" required></textarea>
        </div>

        <button type="button" class="btn btn-outline-primary mb-3" onclick="getLocation()">Ambil Koordinat</button>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Latitude</label>
                <input type="text" id="latitude" name="latitude" class="form-control" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label>Longitude</label>
                <input type="text" id="longitude" name="longitude" class="form-control" readonly>
            </div>
        </div>
        

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="idkec">Kecamatan</label>
                <select name="idkec" id="idkec" class="form-select" required></select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="iddesa">Desa</label>
                <select name="iddesa" id="iddesa" class="form-select" required></select>
            </div>
        </div>

        <div class="mb-3">
            <label>Foto Selfie</label>
            <video id="webcam" autoplay muted playsinline class="w-100 border"></video>
            <canvas id="canvas" style="display: none;"></canvas>
        </div>

        <input type="hidden" name="image" id="imageInput">
        <button type="submit" class="btn btn-primary" id="submitBtn">Selfie + Kirim</button>
    </form>
</div>

<script>
    let video = document.getElementById('webcam');
    let canvas = document.getElementById('canvas');
    let imageInput = document.getElementById('imageInput');
    let submitBtn = document.getElementById('submitBtn');

    async function initCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } });
            video.srcObject = stream;
        } catch (err) {
            alert("Kamera tidak bisa diakses: " + err.message);
        }
    }

    function captureImage() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        const imageData = canvas.toDataURL('image/jpeg', 0.3); // Compress quality 30%
        imageInput.value = imageData;
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                $('#latitude').val(position.coords.latitude);
                $('#longitude').val(position.coords.longitude);
            }, function(error) {
                alert("Gagal ambil lokasi: " + error.message);
            });
        } else {
            alert("Browser tidak mendukung geolokasi.");
        }
    }

    $(document).ready(function () {
        initCamera();

        // Isi kecamatan
        $.getJSON("<?= base_url('wilayah/kecamatan') ?>", function (data) {
            $('#idkec').append('<option value="">Pilih Kecamatan</option>');
            $.each(data, function (i, item) {
                $('#idkec').append('<option value="' + item.idkec + '">' + item.nmkec + '</option>');
            });
        });

        // Ganti desa sesuai kecamatan
        $('#idkec').change(function () {
            $.getJSON("<?= base_url('wilayah/desa') ?>", { kode_kec: $(this).val() }, function (data) {
                $('#iddesa').empty().append('<option value="">Pilih Desa</option>');
                $.each(data, function (i, item) {
                    $('#iddesa').append('<option value="' + item.iddesa + '">' + item.nmdesa + '</option>');
                });
            });
        });

        // Submit = capture lalu kirim
        $('#formPelaporan').on('submit', function (e) {
            captureImage();
            if (!imageInput.value.startsWith('data:image/jpeg;base64,')) {
                e.preventDefault();
                alert('Gambar belum berhasil diambil.');
            }
        });
    });
</script>

<?= $this->endSection() ?>
