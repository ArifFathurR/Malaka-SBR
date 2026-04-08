<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-user-edit"></i></div>
                        Panduan Pengisian Usaha
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4">
   
    <!-- Detail Table -->
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-table me-1"></i> Rincian <strong> Keberadaan Perusahaan</strong></div>
        <div class="card-body table-responsive">
Identifikasi kondisi keberadaan perusahaan/usaha dengan memilih salah satu kode berikut:
<br><br>
<table border="1" cellspacing="0" cellpadding="8">
    <tr>
        <td align="center" valign="top" width="40">
            1
        </td>
        <td>
            = Aktif : perusahaan/usaha ditemukan dan sesuai baik nama dan alamat (jika nama dan alamat berubah, tuliskan di kolom catatan)
        </td>
    </tr>
    <tr>
        <td align="center" valign="top" width="40">
            2
        </td>
        <td>
            = Tidak Aktif: perusahaan/usaha telah menghentikan kegiatan produksi maupun administrasi.
        </td>
    </tr>
    <tr>
        <td align="center" valign="top" width="40">
            3
        </td>
        <td>
            = Bukan Usaha: bangunan yang tidak memiliki tujuan memperoleh laba atau tidak menghasilkan
        </td>
    </tr>
    <tr>
        <td align="center" valign="top" width="40">
            
        </td>
        <td>
            barang/jasa secara komersial, termasuk yang bergerak di bidang pertanian (kategori A) karena bukan
        </td>
    </tr>
    <tr>
        <td align="center" valign="top" width="40">
            
        </td>
        <td>
            merupakan usaha yang dicakup dalam Sensus Ekonomi. (misalnya: tempat ibadah, kantor pemerintah, pemakaman, dan usaha pertanian)
        </td>
    </tr>
    <tr>
        <td align="center" valign="top" width="40">
            4
        </td>
        <td>
            = Tidak Ditemukan : perusahaan/usaha tidak ditemukan di sekitar alamat tersebut.
        </td>
    </tr>
</table>
        </div>
    </div>
</div>

<script>
   
</script>

<?= $this->endSection() ?>
