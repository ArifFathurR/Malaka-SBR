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

    .highlight-error {
        border: 2px solid red;
        animation: blink 0.3s ease-in-out 3;
    }

    @keyframes blink {
        0%, 100% { background-color: transparent; }
        50% { background-color: rgba(255, 0, 0, 0.1); }
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

<!-- Toast Notifications -->
<?php if (session()->has('success')): ?>
                <div class="toast-container position-fixed top-0 end-0 p-3">
                        <div class="toast show align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <?= session()->getFlashdata('success') ?>
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                </div>
            <?php endif; ?>
            <?php if (session()->has('error')): ?>
                <div class="toast-container position-fixed top-0 end-0 p-3">
                    <div class="toast show align-items-center text-bg-danger border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body"><?= session()->getFlashdata('error') ?></div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
            </div>
        <?php endif; ?>

<!-- Main page content-->
<div class="container-xl px-4">
    <div class="card mt-n10">
        <form id="kuesionerForm" action="<?= base_url('survey/proses_input') ?>" method="post"> 
            <!-- Section Identitas -->
            <div class="section active" id="section-identitas">
                <div class="card-header">IDENTITAS</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="nama">Nama :</label><br>
                        <input class="form-control" id="nama_survei_1" name="nama_survei_1" type="text" value ="<?= session('nama_user'); ?>" placeholder="<?= session('nama_user'); ?>" readonly />
                    </div>
                    <div class="mb-3">
                        <label for="nama">Jabatan :</label><br>
                        <input class="form-control" id="jabatan_survei_1" name="jabatan_survei_1" type="text" value ="<?= session('jabatan'); ?>" placeholder="<?= session('jabatan'); ?>" readonly />
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
                            <label for="nama">Masa Kerja di BPS Provinsi Riau :</label><br>
                            <select class="form-control" name="masakerja_survei_1" id="masakerja_survei_1" required>
                                <option value="">Pilih Masa Kerja</option>
                                <option value="<5">&lt; 5 tahun</option>
                                <option value="5-10">5-10 tahun</option>
                                <option value="10-20">10-20 tahun</option>
                                <option value=">20">&gt; 20 tahun</option>
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
                    <button class="btn btn-next btn-primary me-2 my-1"  style="float: right; margin-right: 20px;" type="button" onclick="nextSection()">Lanjut</button><br><br>
                </div>
            </div>

            <!-- Section ZI-->
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
                            <td><input type="radio" name="q1_1" value="1" required></td>
                            <td><input type="radio" name="q1_1" value="2"></td>
                            <td><input type="radio" name="q1_1" value="3"></td>
                            <td><input type="radio" name="q1_1" value="4"></td>
                            <td><input type="radio" name="q1_1" value="5"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Saya merasa paham tentang maksud dan tujuan dari pembangunan Zona Integritas (ZI).</td>
                            <td><input type="radio" name="q1_2" value="1" required></td>
                            <td><input type="radio" name="q1_2" value="2"></td>
                            <td><input type="radio" name="q1_2" value="3"></td>
                            <td><input type="radio" name="q1_2" value="4"></td>
                            <td><input type="radio" name="q1_2" value="5"></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Saya merasa pencanangan ZI berpengaruh/berdampak positif secara signifikan pada diri saya.</td>
                            <td><input type="radio" name="q1_3" value="1" required></td>
                            <td><input type="radio" name="q1_3" value="2"></td>
                            <td><input type="radio" name="q1_3" value="3"></td>
                            <td><input type="radio" name="q1_3" value="4"></td>
                            <td><input type="radio" name="q1_3" value="5"></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Saya merasa pencanangan ZI berpengaruh/berdampak positif secara signifikan pada lingkungan kerja saya.</td>
                            <td><input type="radio" name="q1_4" value="1" required></td>
                            <td><input type="radio" name="q1_4" value="2"></td>
                            <td><input type="radio" name="q1_4" value="3"></td>
                            <td><input type="radio" name="q1_4" value="4"></td>
                            <td><input type="radio" name="q1_4" value="5"></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Saya telah berupaya menerapkan ZI pada diri saya sendiri untuk menuju Wilayah Bebas Korupsi (WBK)/Wilayah Birokrasi Bersih Melayani (WBBM) di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q1_5" value="1" required></td>
                            <td><input type="radio" name="q1_5" value="2"></td>
                            <td><input type="radio" name="q1_5" value="3"></td>
                            <td><input type="radio" name="q1_5" value="4"></td>
                            <td><input type="radio" name="q1_5" value="5"></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Rekan kerja telah berupaya menerapkan ZI di lingkungan kerja untuk menuju Wilayah Bebas Korupsi (WBK)/Wilayah Birokrasi Bersih Melayani (WBBM) di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q1_6" value="1" required></td>
                            <td><input type="radio" name="q1_6" value="2"></td>
                            <td><input type="radio" name="q1_6" value="3"></td>
                            <td><input type="radio" name="q1_6" value="4"></td>
                            <td><input type="radio" name="q1_6" value="5"></td>
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
                            <td><input type="radio" name="q2_1" value="1" required></td>
                            <td><input type="radio" name="q2_1" value="2"></td>
                            <td><input type="radio" name="q2_1" value="3"></td>
                            <td><input type="radio" name="q2_1" value="4"></td>
                            <td><input type="radio" name="q2_1" value="5"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya perubahan pola pikir dan budaya kerja menuju Wilayah Bebas Korupsi (WBK)/Wilayah Birokrasi Bersih Melayani (WBBM) di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_2" value="1" required></td>
                            <td><input type="radio" name="q2_2" value="2"></td>
                            <td><input type="radio" name="q2_2" value="3"></td>
                            <td><input type="radio" name="q2_2" value="4"></td>
                            <td><input type="radio" name="q2_2" value="5"></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan penggunaan teknologi informasi dalam proses penyelenggaraan manajemen pemerintahan di Zona Integritas menuju WBK/WBBM di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_3" value="1" required></td>
                            <td><input type="radio" name="q2_3" value="2"></td>
                            <td><input type="radio" name="q2_3" value="3"></td>
                            <td><input type="radio" name="q2_3" value="4"></td>
                            <td><input type="radio" name="q2_3" value="5"></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan efisiensi dan efektivitas proses manajemen pemerintahan di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_4" value="1" required></td>
                            <td><input type="radio" name="q2_4" value="2"></td>
                            <td><input type="radio" name="q2_4" value="3"></td>
                            <td><input type="radio" name="q2_4" value="4"></td>
                            <td><input type="radio" name="q2_4" value="5"></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan ketaatan terhadap pengelolaan SDM aparatur di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_5" value="1" required></td>
                            <td><input type="radio" name="q2_5" value="2"></td>
                            <td><input type="radio" name="q2_5" value="3"></td>
                            <td><input type="radio" name="q2_5" value="4"></td>
                            <td><input type="radio" name="q2_5" value="5"></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan transparansi dan akuntabilitas pengelolaan SDM aparatur di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_6" value="1" required></td>
                            <td><input type="radio" name="q2_6" value="2"></td>
                            <td><input type="radio" name="q2_6" value="3"></td>
                            <td><input type="radio" name="q2_6" value="4"></td>
                            <td><input type="radio" name="q2_6" value="5"></td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan disiplin SDM aparatur di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_7" value="1" required></td>
                            <td><input type="radio" name="q2_7" value="2"></td>
                            <td><input type="radio" name="q2_7" value="3"></td>
                            <td><input type="radio" name="q2_7" value="4"></td>
                            <td><input type="radio" name="q2_7" value="5"></td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan efektivitas manajemen SDM aparatur di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_8" value="1" required></td>
                            <td><input type="radio" name="q2_8" value="2"></td>
                            <td><input type="radio" name="q2_8" value="3"></td>
                            <td><input type="radio" name="q2_8" value="4"></td>
                            <td><input type="radio" name="q2_8" value="5"></td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan profesionalisme SDM aparatur di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_9" value="1" required></td>
                            <td><input type="radio" name="q2_9" value="2"></td>
                            <td><input type="radio" name="q2_9" value="3"></td>
                            <td><input type="radio" name="q2_9" value="4"></td>
                            <td><input type="radio" name="q2_9" value="5"></td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan kinerja instansi pemerintah di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_10" value="1" required></td>
                            <td><input type="radio" name="q2_10" value="2"></td>
                            <td><input type="radio" name="q2_10" value="3"></td>
                            <td><input type="radio" name="q2_10" value="4"></td>
                            <td><input type="radio" name="q2_10" value="5"></td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan akuntabilitas instansi pemerintah di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_11" value="1" required></td>
                            <td><input type="radio" name="q2_11" value="2"></td>
                            <td><input type="radio" name="q2_11" value="3"></td>
                            <td><input type="radio" name="q2_11" value="4"></td>
                            <td><input type="radio" name="q2_11" value="5"></td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan kepatuhan terhadap pengelolaan keuangan negara di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_12" value="1" required></td>
                            <td><input type="radio" name="q2_12" value="2"></td>
                            <td><input type="radio" name="q2_12" value="3"></td>
                            <td><input type="radio" name="q2_12" value="4"></td>
                            <td><input type="radio" name="q2_12" value="5"></td>
                        </tr>
                        <tr>
                            <td>13</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan efektivitas pengelolaan keuangan negara di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_13" value="1" required></td>
                            <td><input type="radio" name="q2_13" value="2"></td>
                            <td><input type="radio" name="q2_13" value="3"></td>
                            <td><input type="radio" name="q2_13" value="4"></td>
                            <td><input type="radio" name="q2_13" value="5"></td>
                        </tr>
                        <tr>
                            <td>14</td>
                            <td>Setelah pencanangan ZI, Saya merasa sudah tidak ada praktek korupsi dan/atau gratifikasi di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_14" value="1" required></td>
                            <td><input type="radio" name="q2_14" value="2"></td>
                            <td><input type="radio" name="q2_14" value="3"></td>
                            <td><input type="radio" name="q2_14" value="4"></td>
                            <td><input type="radio" name="q2_14" value="5"></td>
                        </tr>
                        <tr>
                            <td>15</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya penurunan tingkat penyalahgunaan wewenang di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_15" value="1" required></td>
                            <td><input type="radio" name="q2_15" value="2"></td>
                            <td><input type="radio" name="q2_15" value="3"></td>
                            <td><input type="radio" name="q2_15" value="4"></td>
                            <td><input type="radio" name="q2_15" value="5"></td>
                        </tr>
                        <tr>
                            <td>16</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan kualitas pelayanan publik (lebih cepat, lebih murah, lebih aman, dan lebih mudah dijangkau) di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_16" value="1" required></td>
                            <td><input type="radio" name="q2_16" value="2"></td>
                            <td><input type="radio" name="q2_16" value="3"></td>
                            <td><input type="radio" name="q2_16" value="4"></td>
                            <td><input type="radio" name="q2_16" value="5"></td>
                        </tr>
                        <tr>
                            <td>17</td>
                            <td>Setelah pencanangan ZI, Saya merasa telah terjadi adanya peningkatan budaya pelayanan prima di BPS Provinsi Riau.</td>
                            <td><input type="radio" name="q2_17" value="1" required></td>
                            <td><input type="radio" name="q2_17" value="2"></td>
                            <td><input type="radio" name="q2_17" value="3"></td>
                            <td><input type="radio" name="q2_17" value="4"></td>
                            <td><input type="radio" name="q2_17" value="5"></td>
                        </tr>
                        <tr>
                            <td>18</td>
                            <td>Saya merasa BPS Provinsi Riau telah layak untuk mendapatkan predikat Wilayah Bebas Korupsi (WBK).</td>
                            <td><input type="radio" name="q2_18" value="1" required></td>
                            <td><input type="radio" name="q2_18" value="2"></td>
                            <td><input type="radio" name="q2_18" value="3"></td>
                            <td><input type="radio" name="q2_18" value="4"></td>
                            <td><input type="radio" name="q2_18" value="5"></td>
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
                        <td><input type="checkbox" name="inovasi1_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi1_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi1_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Buteb</td>
                        <td><input type="checkbox" name="inovasi2_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi2_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi2_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Siladu</td>
                        <td><input type="checkbox" name="inovasi3_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi3_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi3_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>KopiStat</td>
                        <td><input type="checkbox" name="inovasi4_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi4_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi4_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Spektral</td>
                        <td><input type="checkbox" name="inovasi5_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi5_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi5_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>SiPantau</td>
                        <td><input type="checkbox" name="inovasi6_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi6_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi6_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>E-Agenda</td>
                        <td><input type="checkbox" name="inovasi7_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi7_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi7_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>AsamPedas</td>
                        <td><input type="checkbox" name="inovasi8_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi8_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi8_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>AKUSTIK+</td>
                        <td><input type="checkbox" name="inovasi9_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi9_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi9_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>Go-Dinas</td>
                        <td><input type="checkbox" name="inovasi10_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi10_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi10_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td>E-Curhat</td>
                        <td><input type="checkbox" name="inovasi11_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi11_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi11_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>12</td>
                        <td>INTRA Terkini</td>
                        <td><input type="checkbox" name="inovasi12_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi12_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi12_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>13</td>
                        <td>Bebual Asyik</td>
                        <td><input type="checkbox" name="inovasi13_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi13_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi13_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>14</td>
                        <td>Sapa Pagi & Sore</td>
                        <td><input type="checkbox" name="inovasi14_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi14_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi14_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td>Employee Gathering</td>
                        <td><input type="checkbox" name="inovasi15_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi15_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi15_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>16</td>
                        <td>Step to an Eco Office</td>
                        <td><input type="checkbox" name="inovasi16_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi16_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi16_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>17</td>
                        <td>Internalisasi Berakhlak dan RB</td>
                        <td><input type="checkbox" name="inovasi17_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi17_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi17_dampak" value="1"></td>
                    </tr>
                    <tr>
                        <td>18</td>
                        <td>Lainnya: Sebutkan <br>
                            <input class="form-control" id="lain18" name="lain18" type="text"  />
                        </td>
                        <td><input type="checkbox" name="inovasi18_tahu" value="1"></td>
                        <td><input type="checkbox" name="inovasi18_guna" value="1"></td>
                        <td><input type="checkbox" name="inovasi18_dampak" value="1"></td>
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
                            <td><input type="radio" name="q4_1" value="1" required></td>
                            <td><input type="radio" name="q4_1" value="2"></td>
                            <td><input type="radio" name="q4_1" value="3"></td>
                            <td><input type="radio" name="q4_1" value="4"></td>
                            <td><input type="radio" name="q4_1" value="5"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Saya mendukung BPS Provinsi Riau untuk meraih predikat Wilayah Birokrasi Bersih Melayani (WBBM).</td>
                            <td><input type="radio" name="q4_2" value="1" required></td>
                            <td><input type="radio" name="q4_2" value="2"></td>
                            <td><input type="radio" name="q4_2" value="3"></td>
                            <td><input type="radio" name="q4_2" value="4"></td>
                            <td><input type="radio" name="q4_2" value="5"></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Bersama BPS Provinsi Riau, saya mau terlibat untuk meraih predikat Wilayah Birokrasi Bersih Melayani (WBBM).</td>
                            <td><input type="radio" name="q4_3" value="1" required></td>
                            <td><input type="radio" name="q4_3" value="2"></td>
                            <td><input type="radio" name="q4_3" value="3"></td>
                            <td><input type="radio" name="q4_3" value="4"></td>
                            <td><input type="radio" name="q4_3" value="5"></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Bersama BPS Provinsi Riau, saya mau berperan aktif untuk meraih predikat Wilayah Birokrasi Bersih Melayani (WBBM).</td>
                            <td><input type="radio" name="q4_4" value="1" required></td>
                            <td><input type="radio" name="q4_4" value="2"></td>
                            <td><input type="radio" name="q4_4" value="3"></td>
                            <td><input type="radio" name="q4_4" value="4"></td>
                            <td><input type="radio" name="q4_4" value="5"></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Saya merasa BPS Provinsi Riau layak mendapatkan predikat Wilayah Birokrasi Bersih Melayani (WBBM).</td>
                            <td><input type="radio" name="q4_5" value="1" required></td>
                            <td><input type="radio" name="q4_5" value="2"></td>
                            <td><input type="radio" name="q4_5" value="3"></td>
                            <td><input type="radio" name="q4_5" value="4"></td>
                            <td><input type="radio" name="q4_5" value="5"></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td colspan="6">Berapa lama kira-kira BPS Provinsi Riau bisa mendapatkan predikat Wilayah Birokrasi Bersih Melayani (WBBM)?<br>
                                &nbsp; &nbsp; &nbsp; <input type="radio" name="q4_6" value="1-2" required> 1-2 Tahun <br>
                                &nbsp; &nbsp; &nbsp; <input type="radio" name="q4_6" value="3-4"> 3-4 Tahun <br>
                                &nbsp; &nbsp; &nbsp; <input type="radio" name="q4_6" value="5lebih"> >= 5 Tahun <br>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr><br><br>
                <h4>Bagian 5. Peminatan Pilar ZI 2025 dan Pemilihan Calon Change Champion</h4><hr>
                    <div class="mb-3">
                        <label for="nama">Tahun 2025 ini, <b>Pilihan pertama</b> saya untuk masuk anggota pilar: </label><br>
                        <select class="form-control" name="minat_pilar_1" id="minat_pilar_1" required>
                            <option value="">-Pilih-</option>
                            <option value="1">Manajemen Perubahan</option>
                            <option value="2">Penataan Tata Laksana</option>
                            <option value="3">Penataan Sistem Manajemen SDM</option>
                            <option value="4">Penguatan Akuntabilitas</option>
                            <option value="5">Penguatan Pengawasan</option>
                            <option value="4">Peningkatan Kualitas Pelayanan Publik</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama">Tahun 2025 ini, <b>Pilihan kedua</b> saya untuk masuk anggota pilar: </label><br>
                        <select class="form-control" name="minat_pilar_2" id="minat_pilar_2" required>
                            <option value="">-Pilih-</option>
                            <option value="1">Manajemen Perubahan</option>
                            <option value="2">Penataan Tata Laksana</option>
                            <option value="3">Penataan Sistem Manajemen SDM</option>
                            <option value="4">Penguatan Akuntabilitas</option>
                            <option value="5">Penguatan Pengawasan</option>
                            <option value="4">Peningkatan Kualitas Pelayanan Publik</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nama"><b>Pilihan pertama</b> calon Change Champion (CC) tahun 2025 : </label><br>
                        <select name="cc_1" id="cc_1" class="form-control select2" required>
                            <option value="">-- Pilih Pegawai --</option>
                            <?php foreach ($pegawai_list as $pegawai): ?>
                                <option value="<?= $pegawai['nama_user'] ?>">
                                    <?= $pegawai['nama_user'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama"><b>Pilihan kedua</b> calon Change Champion (CC) Tahun 2025 : </label><br>
                        <select name="cc_2" id="cc_2" class="form-control select2" required>
                            <option value="">-- Pilih Pegawai --</option>
                            <?php foreach ($pegawai_list as $pegawai): ?>
                                <option value="<?= $pegawai['nama_user'] ?>">
                                    <?= $pegawai['nama_user'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button class="btn btn-next btn-primary me-2 my-1"  style="float: right; margin-right: 20px;" type="button" onclick="nextSection()">Lanjut</button>
                    <button class="btn btn-prev btn-secondary me-2 my-1"  style="float: right; margin-right: 60px;" type="button" onclick="prevSection()">Kembali</button><br><br>
                </div>
            </div>

            <!-- Section Individual Development -->
            <div class="section" id="section-idp">
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
                            <td>Jalur yang dipilih: <br> (<i>bisa pilih lebih dari satu</i>)<br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp3_tb" value="1"> Tugas Belajar <br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp3_ib" value="1"> Izin Belajar/Tugas Belajar Mandiri
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Rencana Pengembangan Kompetensi (diklat/pelatihan/workshop/seminar/dll): <br> (<i>bisa pilih lebih dari satu</i>)<br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp4_kepemimpinan" value="1"> Pelatihan Kepemimpinan <br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp4_public_speaking" value="1"> Pelatihan Public Speaking, Public Relation (Kehumasan), dan Keprotokoleran <br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp4_literasi_statistik" value="1"> Pelatihan Literasi Statistik dan Indikator Strategis <br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp4_pengadaan_barang" value="1"> Pelatihan Kompetensi Pengadaan Barang/Jasa Pemerintah <br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp4_manajemen_asn" value="1"> Pelatihan Manajemen ASN <br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp4_penulisan_ilmiah" value="1"> Pelatihan Teknis Penulisan Karya Tulis Ilmiah <br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp4_bahasa_inggris" value="1"> Pelatihan Kemampuan Berbahasa Inggris <br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp4_diklat_fungsional" value="1"> Pelatihan/Diklat Fungsional <br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp4_machine_learning" value="1"> Pelatihan Machine Learning, Data Science, Artificial Intelligence, Big Data <br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp4_visualisasi_data" value="1"> Pelatihan Visualisasi Data <br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp4_web_programming" value="1"> Pelatihan Web Programming <br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp4_training_trainer" value="1"> Pelatihan Training of Trainer (TOT) <br>
                                &nbsp; &nbsp; &nbsp; <input type="checkbox" name="idp4_geospasial" value="1"> Pelatihan Pengolahan Data dan Analisis Geospasial <br>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Saran Tema Pengembangan Kompetensi lainnya (pertanyaan terbuka):<br>
                            <input class="form-control" id="idp5_saran" name="idp5_saran" type="text"  />
                            </td>
                        </tr>

                    </tbody>
                </table>
                    <button class="btn btn-next btn-primary me-2 my-1"  style="float: right; margin-right: 20px;" type="button" onclick="nextSection()">Lanjut</button>
                    <button class="btn btn-prev btn-secondary me-2 my-1"  style="float: right; margin-right: 60px;" type="button" onclick="prevSection()">Kembali</button><br><br>
                </div>
            </div>

             <!-- Section Sarana Prasaran -->
             <div class="section" id="section-sarpras">
                <div class="card-header">SARANA PRASARANA</div>
                <div class="card-body">
                    <div class="small">
                    Skala Penilaian: (Untuk Pertanyaan pada Bagian Sarana Prasarana) <br>
                        &nbsp; &nbsp; &nbsp; 1.	Rusak Sekali <br>
                        &nbsp; &nbsp; &nbsp; 2.	Rusak <br>
                        &nbsp; &nbsp; &nbsp; 3.	Sedang <br>
                        &nbsp; &nbsp; &nbsp; 4.	Baik <br>
                        &nbsp; &nbsp; &nbsp; 5.	Baik Sekali
                        <br><br><hr>
                    </div>
                    <h4>Bagian 1. Kondisi umum</h4><hr>
                    
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
                            <td>Menurut Anda, bagaimana kondisi halaman?</td>
                            <td><input type="radio" name="sarpras1_1" value="1" required></td>
                            <td><input type="radio" name="sarpras1_1" value="2"></td>
                            <td><input type="radio" name="sarpras1_1" value="3"></td>
                            <td><input type="radio" name="sarpras1_1" value="4"></td>
                            <td><input type="radio" name="sarpras1_1" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai halaman? <br>
                                <textarea class="form-control" id="sarpras1_1_saran" name="sarpras1_1_saran" rows="3"></textarea>
                            </td>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Menurut Anda, bagaimana kondisi parkiran?</td>
                            <td><input type="radio" name="sarpras1_2" value="1" required></td> 
                            <td><input type="radio" name="sarpras1_2" value="2"></td> 
                            <td><input type="radio" name="sarpras1_2" value="3"></td> 
                            <td><input type="radio" name="sarpras1_2" value="4"></td> 
                            <td><input type="radio" name="sarpras1_2" value="5"></td> 
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai parkiran? <br>
                                <textarea class="form-control" id="sarpras1_2_saran" name="sarpras1_2_saran" rows="3"></textarea> 
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <td>Menurut Anda, bagaimana kondisi ruang Aula Lt.3?</td>
                            <td><input type="radio" name="sarpras1_3" value="1" required></td> 
                            <td><input type="radio" name="sarpras1_3" value="2"></td> 
                            <td><input type="radio" name="sarpras1_3" value="3"></td> 
                            <td><input type="radio" name="sarpras1_3" value="4"></td> 
                            <td><input type="radio" name="sarpras1_3" value="5"></td> 
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai ruang Aula Lt.3? <br>
                                <textarea class="form-control" id="sarpras1_3_saran" name="sarpras1_3_saran" rows="3"></textarea> 
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>4</td>
                            <td>Menurut Anda, bagaimana kondisi ruang laktasi (khusus pegawai perempuan)?</td>
                            <td><input type="radio" name="sarpras1_4" value="1"></td> 
                            <td><input type="radio" name="sarpras1_4" value="2"></td> 
                            <td><input type="radio" name="sarpras1_4" value="3"></td> 
                            <td><input type="radio" name="sarpras1_4" value="4"></td> 
                            <td><input type="radio" name="sarpras1_4" value="5"></td> 
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai ruang laktasi? <br>
                                <textarea class="form-control" id="sarpras1_4_saran" name="sarpras1_4_saran" rows="3"></textarea> 
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>5</td>
                            <td>Menurut Anda, bagaimana kinerja cleaning service?</td>
                            <td><input type="radio" name="sarpras1_5" value="1" required></td> 
                            <td><input type="radio" name="sarpras1_5" value="2"></td> 
                            <td><input type="radio" name="sarpras1_5" value="3"></td> 
                            <td><input type="radio" name="sarpras1_5" value="4"></td> 
                            <td><input type="radio" name="sarpras1_5" value="5"></td> 
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai cleaning service? <br>
                                <textarea class="form-control" id="sarpras1_5_saran" name="sarpras1_5_saran" rows="3"></textarea> 
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>6</td>
                            <td>Menurut Anda, bagaimana kondisi satpam/security?</td>
                            <td><input type="radio" name="sarpras1_6" value="1" required></td> 
                            <td><input type="radio" name="sarpras1_6" value="2"></td> 
                            <td><input type="radio" name="sarpras1_6" value="3"></td> 
                            <td><input type="radio" name="sarpras1_6" value="4"></td> 
                            <td><input type="radio" name="sarpras1_6" value="5"></td> 
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai satpam/security? <br>
                                <textarea class="form-control" id="sarpras1_6_saran" name="sarpras1_6_saran" rows="3"></textarea> 
                            </td>
                            <td colspan="5"></td>
                        </tr>
                    </tbody>
                </table>

                <hr><br><br>
                <h4>Bagian 2. Per Lantai </h4><hr>

                <div class="mb-3">
                    <label for="nama">Menempati lantai berapakah Anda saat ini?</label><br>
                    <select class="form-control" name="lantai" id="lantai" required>
                        <option value="">-Pilih-</option>
                        <option value="1">Lantai 1</option>
                        <option value="2">Lantai 2</option>
                        <option value="3">Lantai 3</option>
                    </select>
                </div>

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
                            <td>Menurut Anda, bagaimana kondisi plafon?</td>
                            <td><input type="radio" name="sarpras2_1" value="1" required></td>
                            <td><input type="radio" name="sarpras2_1" value="2"></td>
                            <td><input type="radio" name="sarpras2_1" value="3"></td>
                            <td><input type="radio" name="sarpras2_1" value="4"></td>
                            <td><input type="radio" name="sarpras2_1" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai plafon? <br>
                                <textarea class="form-control" id="sarpras2_1_saran" name="sarpras2_1_saran" rows="3"></textarea> 
                            </td>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Menurut Anda, bagaimana kondisi dinding?</td>
                            <td><input type="radio" name="sarpras2_2" value="1" required></td>
                            <td><input type="radio" name="sarpras2_2" value="2"></td>
                            <td><input type="radio" name="sarpras2_2" value="3"></td>
                            <td><input type="radio" name="sarpras2_2" value="4"></td>
                            <td><input type="radio" name="sarpras2_2" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai dinding? <br>
                                <textarea class="form-control" id="sarpras2_2_saran" name="sarpras2_2_saran" rows="3"></textarea>
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <td>Menurut Anda, bagaimana kondisi lantai ruangan?</td>
                            <td><input type="radio" name="sarpras2_3" value="1" required></td>
                            <td><input type="radio" name="sarpras2_3" value="2"></td>
                            <td><input type="radio" name="sarpras2_3" value="3"></td>
                            <td><input type="radio" name="sarpras2_3" value="4"></td>
                            <td><input type="radio" name="sarpras2_3" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai lantai ruangan? <br>
                                <textarea class="form-control" id="sarpras2_3_saran" name="sarpras2_3_saran" rows="3"></textarea>
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>4</td>
                            <td>Menurut Anda, bagaimana kondisi meja?</td>
                            <td><input type="radio" name="sarpras2_4" value="1" required></td>
                            <td><input type="radio" name="sarpras2_4" value="2"></td>
                            <td><input type="radio" name="sarpras2_4" value="3"></td>
                            <td><input type="radio" name="sarpras2_4" value="4"></td>
                            <td><input type="radio" name="sarpras2_4" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai meja? <br>
                                <textarea class="form-control" id="sarpras2_4_saran" name="sarpras2_4_saran" rows="3"></textarea>
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>5</td>
                            <td>Menurut Anda, bagaimana kondisi kursi?</td>
                            <td><input type="radio" name="sarpras2_5" value="1" required></td>
                            <td><input type="radio" name="sarpras2_5" value="2"></td>
                            <td><input type="radio" name="sarpras2_5" value="3"></td>
                            <td><input type="radio" name="sarpras2_5" value="4"></td>
                            <td><input type="radio" name="sarpras2_5" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai kursi? <br>
                                <textarea class="form-control" id="sarpras2_5_saran" name="sarpras2_5_saran" rows="3"></textarea>
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>6</td>
                            <td>Menurut Anda, bagaimana kondisi loker?</td>
                            <td><input type="radio" name="sarpras2_6" value="1" required></td>
                            <td><input type="radio" name="sarpras2_6" value="2"></td>
                            <td><input type="radio" name="sarpras2_6" value="3"></td>
                            <td><input type="radio" name="sarpras2_6" value="4"></td>
                            <td><input type="radio" name="sarpras2_6" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai loker? <br>
                                <textarea class="form-control" id="sarpras2_6_saran" name="sarpras2_6_saran" rows="3"></textarea>
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>7</td>
                            <td>Menurut Anda, bagaimana kondisi ruang rapat Agro?</td>
                            <td><input type="radio" name="sarpras2_7" value="1" required></td>
                            <td><input type="radio" name="sarpras2_7" value="2"></td>
                            <td><input type="radio" name="sarpras2_7" value="3"></td>
                            <td><input type="radio" name="sarpras2_7" value="4"></td>
                            <td><input type="radio" name="sarpras2_7" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai ruang rapat Agro? <br>
                                <textarea class="form-control" id="sarpras2_7_saran" name="sarpras2_7_saran" rows="3"></textarea>
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>8</td>
                            <td>Menurut Anda, bagaimana kondisi ruang pantry?</td>
                            <td><input type="radio" name="sarpras2_8" value="1" required></td>
                            <td><input type="radio" name="sarpras2_8" value="2"></td>
                            <td><input type="radio" name="sarpras2_8" value="3"></td>
                            <td><input type="radio" name="sarpras2_8" value="4"></td>
                            <td><input type="radio" name="sarpras2_8" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai ruang pantry? <br>
                                <textarea class="form-control" id="sarpras2_8_saran" name="sarpras2_8_saran" rows="3"></textarea>
                            </td>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Menurut Anda, bagaimana kondisi ruang penyimpanan dispenser (khusus lt.3)?</td>
                            <td><input type="radio" name="sarpras2_9" value="1"></td>
                            <td><input type="radio" name="sarpras2_9" value="2"></td>
                            <td><input type="radio" name="sarpras2_9" value="3"></td>
                            <td><input type="radio" name="sarpras2_9" value="4"></td>
                            <td><input type="radio" name="sarpras2_9" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai ruang penyimpanan dispenser? <br>
                                <textarea class="form-control" id="sarpras2_9_saran" name="sarpras2_9_saran" rows="3"></textarea>
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>10</td>
                            <td>Menurut Anda, bagaimana kondisi AC?</td>
                            <td><input type="radio" name="sarpras2_10" value="1" required></td>
                            <td><input type="radio" name="sarpras2_10" value="2"></td>
                            <td><input type="radio" name="sarpras2_10" value="3"></td>
                            <td><input type="radio" name="sarpras2_10" value="4"></td>
                            <td><input type="radio" name="sarpras2_10" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai AC? <br>
                                <textarea class="form-control" id="sarpras2_10_saran" name="sarpras2_10_saran" rows="3"></textarea>
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>11</td>
                            <td>Menurut Anda, bagaimana kondisi penerangan?</td>
                            <td><input type="radio" name="sarpras2_11" value="1" required></td>
                            <td><input type="radio" name="sarpras2_11" value="2"></td>
                            <td><input type="radio" name="sarpras2_11" value="3"></td>
                            <td><input type="radio" name="sarpras2_11" value="4"></td>
                            <td><input type="radio" name="sarpras2_11" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai penerangan? <br>
                                <textarea class="form-control" id="sarpras2_11_saran" name="sarpras2_11_saran" rows="3"></textarea>
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>12</td>
                            <td>Menurut Anda, bagaimana kondisi toilet?</td>
                            <td><input type="radio" name="sarpras2_12" value="1" required></td>
                            <td><input type="radio" name="sarpras2_12" value="2"></td>
                            <td><input type="radio" name="sarpras2_12" value="3"></td>
                            <td><input type="radio" name="sarpras2_12" value="4"></td>
                            <td><input type="radio" name="sarpras2_12" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai toilet? <br>
                                <textarea class="form-control" id="sarpras2_12_saran" name="sarpras2_12_saran" rows="3"></textarea>
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>13</td>
                            <td>Menurut Anda, bagaimana kondisi perangkat PC (komputer, CPU, UPS)?</td>
                            <td><input type="radio" name="sarpras2_13" value="1" required></td>
                            <td><input type="radio" name="sarpras2_13" value="2"></td>
                            <td><input type="radio" name="sarpras2_13" value="3"></td>
                            <td><input type="radio" name="sarpras2_13" value="4"></td>
                            <td><input type="radio" name="sarpras2_13" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai perangkat PC? <br>
                                <textarea class="form-control" id="sarpras2_13_saran" name="sarpras2_13_saran" rows="3"></textarea>
                            </td>
                            <td colspan="5"></td>
                        </tr>

                        <tr>
                            <td>14</td>
                            <td>Menurut Anda, bagaimana kondisi jaringan internet?</td>
                            <td><input type="radio" name="sarpras2_14" value="1" required></td>
                            <td><input type="radio" name="sarpras2_14" value="2"></td>
                            <td><input type="radio" name="sarpras2_14" value="3"></td>
                            <td><input type="radio" name="sarpras2_14" value="4"></td>
                            <td><input type="radio" name="sarpras2_14" value="5"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Saran Anda mengenai jaringan internet? <br>
                                <textarea class="form-control" id="sarpras2_14_saran" name="sarpras2_14_saran" rows="3"></textarea>
                            </td>
                            <td colspan="5"></td>
                        </tr>
                    </tbody>
                </table>
                
                <button class="btn btn-success me-2 my-1"  style="float: right; margin-right: 20px;" type="submit">Kirim</button>
                <button class="btn btn-prev btn-secondary me-2 my-1"  style="float: right; margin-right: 60px;" type="button" onclick="prevSection()">Kembali</button><br><br>           
                </div>
            </div>

        </form>
    </div>
</div>

    <script>
        const sections = document.querySelectorAll('.section');
        let currentSectionIndex = 0;

        // Fungsi untuk menampilkan section tertentu
        function showSection(index) {
            sections.forEach(section => section.classList.remove('active')); // Sembunyikan semua section
            currentSectionIndex = index;
            sections[currentSectionIndex].classList.add('active'); // Tampilkan section aktif
        }

        // Fungsi validasi elemen di section aktif
        function validateCurrentSection() {
            const currentSection = sections[currentSectionIndex];
            const requiredInputs = currentSection.querySelectorAll('[required]'); // Cari elemen required di section aktif
            let isValid = true;
            let firstInvalidElement = null;

            // Periksa semua elemen required
            requiredInputs.forEach(input => {
                const inputType = input.type;

                // Validasi khusus untuk radio button
                if (inputType === 'radio') {
                    const radioGroup = currentSection.querySelectorAll(`[name="${input.name}"]`);
                    const isAnyChecked = Array.from(radioGroup).some(radio => radio.checked);

                    if (!isAnyChecked) {
                        isValid = false; // Jika tidak ada yang dipilih, validasi gagal
                        radioGroup.forEach(radio => {
                            radio.closest('td').classList.add('highlight-error'); // Highlight pada <td>
                            setTimeout(() => radio.closest('td').classList.remove('highlight-error'), 2000);
                        });

                        if (!firstInvalidElement) {
                            firstInvalidElement = radioGroup[0]; // Simpan elemen pertama yang tidak valid
                        }
                    }
                } else if (!input.value) { // Validasi untuk input biasa
                    isValid = false; // Jika ada yang kosong, validasi gagal
                    input.classList.add('highlight-error'); // Tambahkan highlight
                    setTimeout(() => input.classList.remove('highlight-error'), 2000);

                    if (!firstInvalidElement) {
                        firstInvalidElement = input; // Simpan elemen pertama yang tidak valid
                    }
                }
            });

            // Scroll ke elemen pertama yang tidak valid
            if (firstInvalidElement) {
                firstInvalidElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalidElement.focus();
            }

            return isValid; // Kembalikan status validasi
        }


        // Fungsi untuk tombol "Lanjut"
        function nextSection() {
            if (validateCurrentSection()) {
                if (currentSectionIndex < sections.length - 1) {
                    showSection(currentSectionIndex + 1); // Pindah ke section berikutnya
                }
            } else {
                alert('Mohon lengkapi semua isian sebelum melanjutkan.');
            }
        }

        // Fungsi untuk tombol "Kembali"
        function prevSection() {
            if (currentSectionIndex > 0) {
                showSection(currentSectionIndex - 1); // Kembali ke section sebelumnya
            }
        }

        // Event listener untuk submit form
        const form = document.getElementById('kuesionerForm');
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Cegah submit form default

            if (validateCurrentSection()) {
                if (confirm('Apakah Anda yakin ingin mengirimkan kuesioner ini?')) {
                    form.submit(); // Submit form jika semua valid
                }
            } else {
                alert('Mohon lengkapi semua isian yang diperlukan sebelum mengirim formulir.');
            }
        });

        // Konfigurasi DataTables
        $('#datatablesSimple2').DataTable({
            paging: false,
            searching: false,
            info: false,
            lengthChange: false
        });

    </script>

<?= $this->endSection() ?>