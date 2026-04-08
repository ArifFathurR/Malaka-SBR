<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<style>
    .section {
        display: none;
    }
    .section.active {
        display: block;
    }
    .buttons {
        margin-top: 20px;
        text-align: right;
    }
    .buttons button {
        margin-left: 10px;
    }
</style>

<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
    <div class="container-xl px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="layout"></i></div>
                        BPS RIAU HEBAT
                    </h1>
                    <div class="page-header-subtitle">SURVEI TAHUNAN TERINTEGRASI BPS PROVINSI RIAU</div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main page content-->
<div class="container-xl px-4">
    <div class="card mt-n10">
        <form id="kuesionerForm">
            <!-- Section Identitas -->
            <div class="section active" id="section-identitas">
                <div class="card-header">IDENTITAS</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="nama">Nama :</label><br>
                        <input class="form-control" id="nama_survei_1" name="nama_survei_1" type="text" placeholder="<?= session('nama_user'); ?>" readonly />
                    </div>
                    <div class="mb-3">
                        <label for="nama">Jabatan :</label><br>
                        <input class="form-control" id="jabatan_survei_1" name="jabatan_survei_1" type="text" placeholder="<?= session('jabatan'); ?>" readonly />
                    </div>
                    <div class="mb-3">
                        <label for="nama">Fungsi :</label><br>
                        <select class="form-control" name="fungsi_survei_1" id="fungsi_survei_1" required>
                            <option value="">Pilih Fungsi</option>
                            <option value="Umum">Bagian Umum</option>
                            <option value="Sosial">Sosial</option>
                            <option value="Produksi">Produksi</option>
                            <option value="Distribusi">Distribusi</option>
                            <option value="Nerwilis">Nerwilis</option>
                            <option value="IPDS">IPDS</option>
                        </select>
                    </div>
                    <div class="mb-3">
                            <label for="nama">Masa Kerja :</label><br>
                            <select class="form-control" name="masakerja_survei_1" id="masakerja_survei_1" required>
                                <option value="">Pilih Masa Kerja</option>
                                <option value="1">&lt; 5 tahun</option>
                                <option value="2">5-10 tahun</option>
                                <option value="3">10-20 tahun</option>
                                <option value="4">&gt; 20 tahun</option>
                            </select>
                    </div>
                    <div class="mb-3">
                            <label for="nama">Pendidikan :</label><br>
                            <select class="form-control" name="pendidikan_survei_1" id="pendidikan_survei_1" required>
                                <option value="">Pilih Pendidikan</option>
                                <option value="SMA">SMA/se-derajat</option>
                                <option value="Diploma">Diploma</option>
                                <option value="D4S1">D-IV/S-1</option>
                                <option value="S2S3">S-2/S-3</option>
                            </select>
                    </div>
                    <div class="mb-3">
                            <label for="nama">Jenis Kelamin :</label><br>
                            <select class="form-control" name="jk_survei_1" id="jk_survei_1" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                    </div>
                    <button class="btn btn-primary me-2 my-1"  style="float: right; margin-right: 20px;" onclick="nextSection()">Lanjut</button><br><br>
                </div>
            </div>

            <!-- Section Identitas -->
            <div class="section" id="section-zi">
                <div class="card-header">IMPLEMENTASI ZONA INTEGRITAS (ZI)</div>
                <div class="card-body">
                    <div class="small">
                        Skala Penilaian: (Untuk Pertanyaan pada Bagian 1,2, dan 4) <br>
                        1.	Sangat Tidak Setuju <br>
                        2.	Tidak Setuju <br>
                        3.	Ragu-ragu <br>
                        4.	Setuju <br>
                        5.	Sangat Setuju <br><br><hr>
                    </div>
                <h4>Bagian 1. Pemahaman dan Implementasi ZI</h4><hr>
                <table id="datatablesSimple2" class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Pertanyaan</th>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Saya mengetahui bahwa BPS Provinsi Riau telah melakukan pencanangan Zona Integritas (ZI).</td>
                            <td><input type="radio" name="q1.1" value="1" required></td>
                            <td><input type="radio" name="q1.1" value="2"></td>
                            <td><input type="radio" name="q1.1" value="3"></td>
                            <td><input type="radio" name="q1.1" value="4"></td>
                            <td><input type="radio" name="q1.1" value="5"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Saya merasa paham tentang maksud dan tujuan dari pembangunan Zona Integritas (ZI).</td>
                            <td><input type="radio" name="q1.2" value="1" required></td>
                            <td><input type="radio" name="q1.2" value="2"></td>
                            <td><input type="radio" name="q1.2" value="3"></td>
                            <td><input type="radio" name="q1.2" value="4"></td>
                            <td><input type="radio" name="q1.2" value="5"></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Saya merasa pencanangan ZI berpengaruh/berdampak positif secara signifikan pada diri saya.</td>
                            <td><input type="radio" name="q1.3" value="1" required></td>
                            <td><input type="radio" name="q1.3" value="2"></td>
                            <td><input type="radio" name="q1.3" value="3"></td>
                            <td><input type="radio" name="q1.3" value="4"></td>
                            <td><input type="radio" name="q1.3" value="5"></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Saya merasa pencanangan ZI berpengaruh/berdampak positif secara signifikan pada lingkungan kerja saya.</td>
                            <td><input type="radio" name="q1.4" value="1" required></td>
                            <td><input type="radio" name="q1.4" value="2"></td>
                            <td><input type="radio" name="q1.4" value="3"></td>
                            <td><input type="radio" name="q1.4" value="4"></td>
                            <td><input type="radio" name="q1.4" value="5"></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Saya telah berupaya menerapkan ZI pada diri saya sendiri untuk menuju Wilayah Bebas Korupsi (WBK)/Wilayah Birokrasi Bersih Melayani (WBBM) di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q1.5" value="1" required></td>
                            <td><input type="radio" name="q1.5" value="2"></td>
                            <td><input type="radio" name="q1.5" value="3"></td>
                            <td><input type="radio" name="q1.5" value="4"></td>
                            <td><input type="radio" name="q1.5" value="5"></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Rekan kerja telah berupaya menerapkan ZI di lingkungan kerja untuk menuju Wilayah Bebas Korupsi (WBK)/Wilayah Birokrasi Bersih Melayani (WBBM) di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q1.6" value="1" required></td>
                            <td><input type="radio" name="q1.6" value="2"></td>
                            <td><input type="radio" name="q1.6" value="3"></td>
                            <td><input type="radio" name="q1.6" value="4"></td>
                            <td><input type="radio" name="q1.6" value="5"></td>
                        </tr>
                    </tbody>
                </table>
                <hr><br><br>
                <h4>Bagian 2. Persepsi Capaian Pembangunan ZI</h4><hr>
                <table id="datatablesSimple2" class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Pertanyaan</th>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Saya merasa seluruh jajaran pimpinan dan pegawai sudah komitmen dalam membangun Zona Integritas menuju Wilayah Bebas Korupsi (WBK)/Wilayah Birokrasi Bersih Melayani (WBBM) di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.1" value="1" required></td>
                            <td><input type="radio" name="q2.1" value="2"></td>
                            <td><input type="radio" name="q2.1" value="3"></td>
                            <td><input type="radio" name="q2.1" value="4"></td>
                            <td><input type="radio" name="q2.1" value="5"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya perubahan pola pikir dan budaya kerja menuju Wilayah Bebas Korupsi (WBK)/Wilayah Birokrasi Bersih Melayani (WBBM) di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.2" value="1" required></td>
                            <td><input type="radio" name="q2.2" value="2"></td>
                            <td><input type="radio" name="q2.2" value="3"></td>
                            <td><input type="radio" name="q2.2" value="4"></td>
                            <td><input type="radio" name="q2.2" value="5"></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan penggunaan teknologi informasi dalam proses penyelenggaraan manajemen pemerintahan di Zona Integritas menuju WBK/WBBM di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.3" value="1" required></td>
                            <td><input type="radio" name="q2.3" value="2"></td>
                            <td><input type="radio" name="q2.3" value="3"></td>
                            <td><input type="radio" name="q2.3" value="4"></td>
                            <td><input type="radio" name="q2.3" value="5"></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan efisiensi dan efektivitas proses manajemen pemerintahan di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.4" value="1" required></td>
                            <td><input type="radio" name="q2.4" value="2"></td>
                            <td><input type="radio" name="q2.4" value="3"></td>
                            <td><input type="radio" name="q2.4" value="4"></td>
                            <td><input type="radio" name="q2.4" value="5"></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan ketaatan terhadap pengelolaan SDM aparatur di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.5" value="1" required></td>
                            <td><input type="radio" name="q2.5" value="2"></td>
                            <td><input type="radio" name="q2.5" value="3"></td>
                            <td><input type="radio" name="q2.5" value="4"></td>
                            <td><input type="radio" name="q2.5" value="5"></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan transparansi dan akuntabilitas pengelolaan SDM aparatur di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.6" value="1" required></td>
                            <td><input type="radio" name="q2.6" value="2"></td>
                            <td><input type="radio" name="q2.6" value="3"></td>
                            <td><input type="radio" name="q2.6" value="4"></td>
                            <td><input type="radio" name="q2.6" value="5"></td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan disiplin SDM aparatur di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.7" value="1" required></td>
                            <td><input type="radio" name="q2.7" value="2"></td>
                            <td><input type="radio" name="q2.7" value="3"></td>
                            <td><input type="radio" name="q2.7" value="4"></td>
                            <td><input type="radio" name="q2.7" value="5"></td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan efektivitas manajemen SDM aparatur di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.8" value="1" required></td>
                            <td><input type="radio" name="q2.8" value="2"></td>
                            <td><input type="radio" name="q2.8" value="3"></td>
                            <td><input type="radio" name="q2.8" value="4"></td>
                            <td><input type="radio" name="q2.8" value="5"></td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan profesionalisme SDM aparatur di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.9" value="1" required></td>
                            <td><input type="radio" name="q2.9" value="2"></td>
                            <td><input type="radio" name="q2.9" value="3"></td>
                            <td><input type="radio" name="q2.9" value="4"></td>
                            <td><input type="radio" name="q2.9" value="5"></td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan kinerja instansi pemerintah di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.10" value="1" required></td>
                            <td><input type="radio" name="q2.10" value="2"></td>
                            <td><input type="radio" name="q2.10" value="3"></td>
                            <td><input type="radio" name="q2.10" value="4"></td>
                            <td><input type="radio" name="q2.10" value="5"></td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan akuntabilitas instansi pemerintah di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.11" value="1" required></td>
                            <td><input type="radio" name="q2.11" value="2"></td>
                            <td><input type="radio" name="q2.11" value="3"></td>
                            <td><input type="radio" name="q2.11" value="4"></td>
                            <td><input type="radio" name="q2.11" value="5"></td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan kepatuhan terhadap pengelolaan keuangan negara di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.12" value="1" required></td>
                            <td><input type="radio" name="q2.12" value="2"></td>
                            <td><input type="radio" name="q2.12" value="3"></td>
                            <td><input type="radio" name="q2.12" value="4"></td>
                            <td><input type="radio" name="q2.12" value="5"></td>
                        </tr>
                        <tr>
                            <td>13</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan efektivitas pengelolaan keuangan negara di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.13" value="1" required></td>
                            <td><input type="radio" name="q2.13" value="2"></td>
                            <td><input type="radio" name="q2.13" value="3"></td>
                            <td><input type="radio" name="q2.13" value="4"></td>
                            <td><input type="radio" name="q2.13" value="5"></td>
                        </tr>
                        <tr>
                            <td>14</td>
                            <td>Setelah pencanangan ZI, Saya merasa sudah tidak ada praktek korupsi dan/atau gratifikasi di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.14" value="1" required></td>
                            <td><input type="radio" name="q2.14" value="2"></td>
                            <td><input type="radio" name="q2.14" value="3"></td>
                            <td><input type="radio" name="q2.14" value="4"></td>
                            <td><input type="radio" name="q2.14" value="5"></td>
                        </tr>
                        <tr>
                            <td>15</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya penurunan tingkat penyalahgunaan wewenang di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.15" value="1" required></td>
                            <td><input type="radio" name="q2.15" value="2"></td>
                            <td><input type="radio" name="q2.15" value="3"></td>
                            <td><input type="radio" name="q2.15" value="4"></td>
                            <td><input type="radio" name="q2.15" value="5"></td>
                        </tr>
                        <tr>
                            <td>16</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan kualitas pelayanan publik (lebih cepat, lebih murah, lebih aman, dan lebih mudah dijangkau) di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.16" value="1" required></td>
                            <td><input type="radio" name="q2.16" value="2"></td>
                            <td><input type="radio" name="q2.16" value="3"></td>
                            <td><input type="radio" name="q2.16" value="4"></td>
                            <td><input type="radio" name="q2.16" value="5"></td>
                        </tr>
                        <tr>
                            <td>17</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan budaya pelayanan prima di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2.17" value="1" required></td>
                            <td><input type="radio" name="q2.17" value="2"></td>
                            <td><input type="radio" name="q2.17" value="3"></td>
                            <td><input type="radio" name="q2.17" value="4"></td>
                            <td><input type="radio" name="q2.17" value="5"></td>
                        </tr>
                        <tr>
                            <td>18</td>
                            <td>Saya merasa BPS Provinsi Riau telah layak untuk mendapatkan predikat Wilayah Bebas Korupsi (WBK).</td>
                            <td><input type="radio" name="q2.18" value="1" required></td>
                            <td><input type="radio" name="q2.18" value="2"></td>
                            <td><input type="radio" name="q2.18" value="3"></td>
                            <td><input type="radio" name="q2.18" value="4"></td>
                            <td><input type="radio" name="q2.18" value="5"></td>
                        </tr>
                    </tbody>

                </table>

                <hr><br><br>
                <h4>Bagian 3. Inovasi Kegiatan</h4><hr>

                <table id="datatablesSimple2" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Inovasi</th>
                            <th>Saya mengetahui inovasi ini</th>
                            <th>Saya menggunakan inovasi ini</th>
                            <th>Menurut saya, inovasi ini memiliki dampak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>SiJebat</td>
                            <td><input type="checkbox" name="sijebat" value="1"></td>
                            <td><input type="checkbox" name="inovasi1" value="2"></td>
                            <td><input type="checkbox" name="inovasi1" value="3"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Buteb</td>
                            <td><input type="checkbox" name="inovasi2" value="1"></td>
                            <td><input type="checkbox" name="inovasi2" value="2"></td>
                            <td><input type="checkbox" name="inovasi2" value="3"></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Siladu</td>
                            <td><input type="checkbox" name="inovasi3" value="1"></td>
                            <td><input type="checkbox" name="inovasi3" value="2"></td>
                            <td><input type="checkbox" name="inovasi3" value="3"></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>KopiStat</td>
                            <td><input type="checkbox" name="inovasi4" value="1"></td>
                            <td><input type="checkbox" name="inovasi4" value="2"></td>
                            <td><input type="checkbox" name="inovasi4" value="3"></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Spektral</td>
                            <td><input type="checkbox" name="inovasi5" value="1"></td>
                            <td><input type="checkbox" name="inovasi5" value="2"></td>
                            <td><input type="checkbox" name="inovasi5" value="3"></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>SiPantau</td>
                            <td><input type="checkbox" name="inovasi6" value="1"></td>
                            <td><input type="checkbox" name="inovasi6" value="2"></td>
                            <td><input type="checkbox" name="inovasi6" value="3"></td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>E-Agenda</td>
                            <td><input type="checkbox" name="inovasi7" value="1"></td>
                            <td><input type="checkbox" name="inovasi7" value="2"></td>
                            <td><input type="checkbox" name="inovasi7" value="3"></td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>AsamPedas</td>
                            <td><input type="checkbox" name="inovasi8" value="1"></td>
                            <td><input type="checkbox" name="inovasi8" value="2"></td>
                            <td><input type="checkbox" name="inovasi8" value="3"></td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>AKUSTIK+</td>
                            <td><input type="checkbox" name="inovasi9" value="1"></td>
                            <td><input type="checkbox" name="inovasi9" value="2"></td>
                            <td><input type="checkbox" name="inovasi9" value="3"></td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>Go-Dinas</td>
                            <td><input type="checkbox" name="inovasi10" value="1"></td>
                            <td><input type="checkbox" name="inovasi10" value="2"></td>
                            <td><input type="checkbox" name="inovasi10" value="3"></td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>E-Curhat</td>
                            <td><input type="checkbox" name="inovasi11" value="1"></td>
                            <td><input type="checkbox" name="inovasi11" value="2"></td>
                            <td><input type="checkbox" name="inovasi11" value="3"></td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>INTRA Terkini</td>
                            <td><input type="checkbox" name="inovasi12" value="1"></td>
                            <td><input type="checkbox" name="inovasi12" value="2"></td>
                            <td><input type="checkbox" name="inovasi12" value="3"></td>
                        </tr>
                        <tr>
                            <td>13</td>
                            <td>Bebual Asyik</td>
                            <td><input type="checkbox" name="inovasi13" value="1"></td>
                            <td><input type="checkbox" name="inovasi13" value="2"></td>
                            <td><input type="checkbox" name="inovasi13" value="3"></td>
                        </tr>
                        <tr>
                            <td>14</td>
                            <td>Sapa Pagi & Sore</td>
                            <td><input type="checkbox" name="inovasi14" value="1"></td>
                            <td><input type="checkbox" name="inovasi14" value="2"></td>
                            <td><input type="checkbox" name="inovasi14" value="3"></td>
                        </tr>
                        <tr>
                            <td>15</td>
                            <td>Employee Gathering</td>
                            <td><input type="checkbox" name="inovasi15" value="1"></td>
                            <td><input type="checkbox" name="inovasi15" value="2"></td>
                            <td><input type="checkbox" name="inovasi15" value="3"></td>
                        </tr>
                        <tr>
                            <td>16</td>
                            <td>Step to an Eco Office</td>
                            <td><input type="checkbox" name="inovasi16" value="1"></td>
                            <td><input type="checkbox" name="inovasi16" value="2"></td>
                            <td><input type="checkbox" name="inovasi16" value="3"></td>
                        </tr>
                        <tr>
                            <td>17</td>
                            <td>Internalisasi Berakhlak dan RB</td>
                            <td><input type="checkbox" name="inovasi17" value="1"></td>
                            <td><input type="checkbox" name="inovasi17" value="2"></td>
                            <td><input type="checkbox" name="inovasi17" value="3"></td>
                        </tr>
                        <tr>
                            <td>18</td>
                            <td>Lainnya: Sebutkan</td>
                            <td><input type="checkbox" name="inovasi18" value="1"></td>
                            <td><input type="checkbox" name="inovasi18" value="2"></td>
                            <td><input type="checkbox" name="inovasi18" value="3"></td>
                        </tr>
                    </tbody>
                </table><br><br>
                <label for="terbuka1">Tuliskan usulan inovasi atau kegiatan yang menurut Anda 
                    dapat membuat perubahan yang lebih baik di lingkungan kerja BPS Provinsi Riau:</label>
                <textarea class="form-control" id="terbuka_q1" name="terbuka_q1" rows="3"></textarea><br>

                <label for="terbuka2">Tuliskan usulan inovasi atau kegiatan yang menurut Anda 
                    dapat meningkatkan kualitas layanan publik BPS Provinsi Riau:</label>
                <textarea class="form-control" id="terbuka_q2" name="terbuka_q2" rows="3"></textarea><br>

                <label for="terbuka3">Tuliskan permasalahan atau sesuatu yang tidak disukai 
                    yang Anda temukan di BPS Provinsi Riau yang harus segera dibenahi:</label>
                <textarea class="form-control" id="terbuka_q3" name="terbuka_q3" rows="3"></textarea><br><br>

                <hr><br><br>
                <h4>Bagian 4. Wilayah Birokrasi Bersih dan Melayani (WBBM)</h4><hr>
                <table id="datatablesSimple2" class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Pertanyaan</th>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Saya mengetahui bahwa setelah meraih predikat Wilayah Bebas Korupsi (WBK), dilanjutkan dengan predikat Wilayah Birokrasi Bersih Melayani (WBBM).</td>
                            <td><input type="radio" name="q4.1" value="1" required></td>
                            <td><input type="radio" name="q4.1" value="2"></td>
                            <td><input type="radio" name="q4.1" value="3"></td>
                            <td><input type="radio" name="q4.1" value="4"></td>
                            <td><input type="radio" name="q4.1" value="5"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Saya mendukung BPS Provinsi Riau untuk meraih predikat Wilayah Birokrasi Bersih Melayani (WBBM).</td>
                            <td><input type="radio" name="q4.2" value="1" required></td>
                            <td><input type="radio" name="q4.2" value="2"></td>
                            <td><input type="radio" name="q4.2" value="3"></td>
                            <td><input type="radio" name="q4.2" value="4"></td>
                            <td><input type="radio" name="q4.2" value="5"></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Bersama BPS Provinsi Riau, saya mau terlibat untuk meraih predikat Wilayah Birokrasi Bersih Melayani (WBBM).</td>
                            <td><input type="radio" name="q4.3" value="1" required></td>
                            <td><input type="radio" name="q4.3" value="2"></td>
                            <td><input type="radio" name="q4.3" value="3"></td>
                            <td><input type="radio" name="q4.3" value="4"></td>
                            <td><input type="radio" name="q4.3" value="5"></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Bersama BPS Provinsi Riau, saya mau berperan aktif untuk meraih predikat Wilayah Birokrasi Bersih Melayani (WBBM).</td>
                            <td><input type="radio" name="q4.4" value="1" required></td>
                            <td><input type="radio" name="q4.4" value="2"></td>
                            <td><input type="radio" name="q4.4" value="3"></td>
                            <td><input type="radio" name="q4.4" value="4"></td>
                            <td><input type="radio" name="q4.4" value="5"></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Saya merasa BPS Provinsi Riau layak mendapatkan predikat Wilayah Birokrasi Bersih Melayani (WBBM).</td>
                            <td><input type="radio" name="q4.5" value="1" required></td>
                            <td><input type="radio" name="q4.5" value="2"></td>
                            <td><input type="radio" name="q4.5" value="3"></td>
                            <td><input type="radio" name="q4.5" value="4"></td>
                            <td><input type="radio" name="q4.5" value="5"></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td colspan="6">Berapa lama kira-kira BPS Provinsi Riau bisa mendapatkan predikat Wilayah Birokrasi Bersih Melayani (WBBM)?<br>
                                <input type="radio" name="q4.6" value="5"> 1-2 Tahun <br>
                                <input type="radio" name="q4.6" value="5"> 3-4 Tahun <br>
                                <input type="radio" name="q4.6" value="5"> >= 5 Tahun <br>
                            </td>
                        </tr>
                    </tbody>
                </table>

                    <button class="btn btn-primary me-2 my-1"  style="float: right; margin-right: 20px;" onclick="nextSection()">Lanjut</button>
                    <button class="btn btn-secondary me-2 my-1"  style="float: right; margin-right: 60px;" onclick="prevSection()">Kembali</button><br><br>
                </div>
            </div>

            <!-- Section Individual Development -->
             <!-- Section Identitas -->
            <div class="section" id="section-zi">
                <div class="card-header">INDIVIDUAL DEVELOPMENT PLAN</div>
                <div class="card-body">
                <table id="datatablesSimple2" class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>uraian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><label for="idp_1">Saya rencana mengikuti studi/pendidikan pada jenjang: </label><br>
                                <select class="form-control" name="idp_1" id="idp_1" required>
                                    <option value="">-Pilih-</option>
                                    <option value="D3">D-III</option>
                                    <option value="D4S1">D-IV / S-1</option>
                                    <option value="S2">S-2</option>
                                    <option value="S3">S-3</option>
                                    <option value="tidak">Tidak ada/Belum Bisa</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><label for="idp_2">Tahun rencana saya mengikuti studi/pendidikan: </label><br>
                                <select class="form-control" name="idp_2" id="idp_2" required>
                                    <option value="">-Pilih-</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="tidak">Tidak ada/Belum Bisa</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Jurusan/Program Studi yang Diminati</td>
                            <td><input type="checkbox" name="idp_3" value="1"></td>
                            <td><input type="checkbox" name="idp_" value="2"></td>
                            <td><input type="checkbox" name="inovasi18" value="3"></td>
                        </tr>
                    </tbody>
                </table>
                    <button class="btn btn-primary me-2 my-1"  style="float: right; margin-right: 20px;" onclick="nextSection()">Lanjut</button>
                    <button class="btn btn-secondary me-2 my-1"  style="float: right; margin-right: 60px;" onclick="prevSection()">Kembali</button><br><br>
                </div>
            </div>
        </form>
    </div>
</div>

    <script>
        const sections = document.querySelectorAll('.section');
        let currentSectionIndex = 0;

        function showSection(index) {
            sections[currentSectionIndex].classList.remove('active');
            currentSectionIndex = index;
            sections[currentSectionIndex].classList.add('active');
        }

        function nextSection() {
            if (currentSectionIndex < sections.length - 1) {
                showSection(currentSectionIndex + 1);
            }
        }

        function prevSection() {
            if (currentSectionIndex > 0) {
                showSection(currentSectionIndex - 1);
            }
        }

        document.getElementById('kuesionerForm').addEventListener('submit', function (event) {
            event.preventDefault();
            if (confirm('Apakah Anda yakin ingin mengirimkan kuesioner ini?')) {
                alert('Kuesioner berhasil dikirim. Terima kasih atas partisipasi Anda!');
                this.submit();
            }
        });
    </script>

    <script>
        $('#datatablesSimple2').DataTable({
            paging: false,
            searching: false,
            info: false,
            lengthChange: false
        });
    </script>

<?= $this->endSection() ?>