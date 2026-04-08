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
                    <div class="page-header-subtitle">SURVEI SHP</div>
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
        <form id="kuesionerForm" action="<?= base_url('surveyshp/proses_input_shp') ?>" method="post"> 
            <!-- Section Identitas -->
            <div class="section active" id="section-identitas">
                <div class="card-header">IDENTITAS</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="nama">Nama :</label><br>
                        <input class="form-control" id="nama_survei_1" name="nama_survei_1" type="text" value ="<?= session('nama_user'); ?>" placeholder="<?= session('nama_user'); ?>" readonly />
                    </div>
                    <div class="mb-3">
                        <label for="jabatan">Jabatan :</label><br>
                        <input class="form-control" id="jabatan_survei_1" name="jabatan_survei_1" type="text" value ="KEPALA BPS KABUPATEN/KOTA" readonly />
                    </div>
                    <div class="mb-3">
                        <label for="usia">Usia :</label><br>
                        <input class="form-control" id="usia" name="usia" type="text"  required />
                    </div>
                    <div class="mb-3">
                            <label for="nama">Jenis Kelamin :</label><br>
                            <select class="form-control" name="jk_survei_1" id="jk_survei_1" required >
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                    </div>
                    <div class="mb-3">
                            <label for="nama">Pendidikan :</label><br>
                            <select class="form-control" name="pendidikan_survei_1" id="pendidikan_survei_1" required >
                                <option value="">Pilih Pendidikan</option>
                                <option value="SMA">SMA/se-derajat</option>
                                <option value="Diploma">Diploma</option>
                                <option value="D4S1">D-IV/S-1</option>
                                <option value="S2S3">S-2/S-3</option>
                            </select>
                    </div>
                    <div class="mb-3">
                            <label for="nama">Lama Jabatan (tahun) di Satker saat ini :</label><br>
                            <input class="form-control" id="lama_jabatan" name="lama_jabatan" type="text" required/>
                    </div>
                    <div class="mb-3">
                            <label for="nama">Jika lama jabatan Anda saat ini kurang dari 6 bulan, isikan 4 digit kode satker sebelumnya (misal 1401) :</label><br>
                            <input class="form-control" id="kode_satker_sebelumnya" name="kode_satker_sebelumnya" type="text" />
                    </div>
                    <div class="mb-3">
                        <label for="keterangan">
                            Keterangan: <br> 
                            (Jika masa kerja anda di Sakter saat ini < 1 tahun, isikan tanggal TMT Anda untuk jabatan saat ini dan isikan Jabatan Anda sebelumnya, <br>
                            Misal: TMT 1 Oktober 2024 sebagai Kepala BPS Kabupaten Y, jabatan sebelumnya: Statistisi Ahli Muda BPS Provinsi X)
                        </label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" />
                    </div>
                    
                    <button class="btn btn-next btn-primary me-2 my-1"  style="float: right; margin-right: 20px;" type="button" onclick="nextSection()">Lanjut</button><br><br>
                </div>
            </div>

            <!-- Section Capaian -->
            <div class="section" id="section-capaian">
                <div class="card-header">CAPAIAN</div>
                <div class="card-body">
                    <div class="mb-3">
                        <table id="datatablesSimple2" class="table">
                            <thead>
                                <tr colspan="2">
                                    <label for="nilaisakip">Berapa Nilai SAKIP dari satuan kerja yang saudara/i pimpin saat ini /  sebelumnya (bagi yang menjabat < 6 bulan) ? </label><br>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2021: </td>
                                    <td></label><input class="form-control" id="sakip2021" name="sakip2021" type="text" required ></td>
                                </tr>
                                <tr>
                                    <td>2022: </td>
                                    <td></label><input class="form-control" id="sakip2022" name="sakip2022" type="text" required ></td>
                                </tr>
                                <tr>
                                    <td>2023: </td>
                                    <td></label><input class="form-control" id="sakip2023" name="sakip2023" type="text" required ></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-3">
                        <table id="datatablesSimple2" class="table">
                            <thead>
                                <tr colspan="2">
                                    <label for="nilaiikpa">Berapa Nilai IKPA dari satuan kerja yang saudara/i pimpin saat ini / sebelumnya (bagi yang menjabat < 6 bulan) ? </label><br>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2021: </td>
                                    <td></label><input class="form-control" id="ikpa2021" name="ikpa2021" type="text" required ></td>
                                </tr>
                                <tr>
                                    <td>2022: </td>
                                    <td></label><input class="form-control" id="ikpa2022" name="ikpa2022" type="text" required ></td>
                                </tr>
                                <tr>
                                    <td>2023: </td>
                                    <td></label><input class="form-control" id="ikpa2023" name="ikpa2023" type="text" required ></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-3">
                        <table id="datatablesSimple2" class="table">
                            <tbody>
                                <tr>
                                    <td>Apakah Unit Kerja yang dipimpin saat ini / sebelumnya bagi yang menjabat < 6 bulan mendapat predikat WBK/WBBM? (Baik Internal BPS RI maupun dari MENPAN RB)</td>
                                    <td><input type="radio" name="wbkwbbm" value="ya" required>Ya</td>
                                    <td><input type="radio" name="wbkwbbm" value="tidak">Tidak</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-3">
                        <label for="penghargaan_eksternal">Apakah di Tahun 2021-2023 Unit Kerja ini menerima penghargaan dari Eksternal? (isikan jika ada) </label><br>
                        <label for="penghargaan_eksternal1">Penghargaan 1: </label><input class="form-control" length = "3" id="penghargaan_eksternal1" name="penghargaan_eksternal1" type="text" ><br>
                        <label for="penghargaan_eksternal2">Penghargaan 2: </label><input class="form-control" id="penghargaan_eksternal2" name="penghargaan_eksternal2" type="text" ><br>
                        <label for="penghargaan_eksternal3">Penghargaan 3: </label><input class="form-control" id="penghargaan_eksternal3" name="penghargaan_eksternal3" type="text" ><br>
                    </div>
                    
                    <button class="btn btn-next btn-primary me-2 my-1"  style="float: right; margin-right: 20px;" type="button" onclick="nextSection()">Lanjut</button>
                    <button class="btn btn-prev btn-secondary me-2 my-1"  style="float: right; margin-right: 60px;" type="button" onclick="prevSection()">Kembali</button><br><br>
                </div>
            </div>

            <!-- Section ZI-->
            <div class="section" id="section-kepemimpinan-melayani">
                <div class="card-header">PENGUKURAN KEPEMIMPINAN MELAYANI</div>
                <div class="card-body">
                    <div class="small">
                        Skala Penilaian: (Untuk Pertanyaan pada Bagian 1,2, dan 4) <br>
                        1.	Sangat Tidak Setuju <br>
                        2.	Tidak Setuju <br>
                        3.	Ragu-ragu <br>
                        4.	Setuju <br>
                        5.	Sangat Setuju <br><br><hr>
                    </div>
                <h4>A. Dukungan</h4><hr>
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
                            <td>Pemimpin saya memberikan dukungan emosional ketika menghadapi kesulitan.</td>
                            <td><input type="radio" name="q1_1" value="1" required></td>
                            <td><input type="radio" name="q1_1" value="2"></td>
                            <td><input type="radio" name="q1_1" value="3"></td>
                            <td><input type="radio" name="q1_1" value="4"></td>
                            <td><input type="radio" name="q1_1" value="5"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Pemimpin saya menunjukkan empati melalui dukungan nyata.</td>
                            <td><input type="radio" name="q1_2" value="1" required></td>
                            <td><input type="radio" name="q1_2" value="2"></td>
                            <td><input type="radio" name="q1_2" value="3"></td>
                            <td><input type="radio" name="q1_2" value="4"></td>
                            <td><input type="radio" name="q1_2" value="5"></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Pemimpin saya memastikan saya mendapatkan dukungan dan sumber daya yang cukup untuk mencapai kesuksesan.</td>
                            <td><input type="radio" name="q1_3" value="1" required></td>
                            <td><input type="radio" name="q1_3" value="2"></td>
                            <td><input type="radio" name="q1_3" value="3"></td>
                            <td><input type="radio" name="q1_3" value="4"></td>
                            <td><input type="radio" name="q1_3" value="5"></td>
                        </tr>
                    </tbody>
                </table>
                <hr><br><br>
                <h4>B. Kepercayaan (Trust)</h4><hr>
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
                            <td>4</td>
                            <td>Pemimpin saya yakin bahwa saya mematuhi aturan dan nilai-nilai organisasi.</td>
                            <td><input type="radio" name="q1_4" value="1" required></td>
                            <td><input type="radio" name="q1_4" value="2"></td>
                            <td><input type="radio" name="q1_4" value="3"></td>
                            <td><input type="radio" name="q1_4" value="4"></td>
                            <td><input type="radio" name="q1_4" value="5"></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Pemimpin saya percaya bahwa saya mampu menyelesaikan pekerjaan dengan baik.</td>
                            <td><input type="radio" name="q1_5" value="1" required></td>
                            <td><input type="radio" name="q1_5" value="2"></td>
                            <td><input type="radio" name="q1_5" value="3"></td>
                            <td><input type="radio" name="q1_5" value="4"></td>
                            <td><input type="radio" name="q1_5" value="5"></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Pemimpin saya mempercayai saya untuk menyelesaikan tugas tepat waktu.</td>
                            <td><input type="radio" name="q1_6" value="1" required></td>
                            <td><input type="radio" name="q1_6" value="2"></td>
                            <td><input type="radio" name="q1_6" value="3"></td>
                            <td><input type="radio" name="q1_6" value="4"></td>
                            <td><input type="radio" name="q1_6" value="5"></td>
                        </tr>
                    </tbody>
                </table>

                <hr><br><br>
                <h4>C. Kerendahan Hati (Humility)</h4><hr>

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
                            <td>7</td>
                            <td>Pemimpin saya menunjukkan sikap terbuka tentang pengembangan individu untuk meningkatkan kompetensi.</td>
                            <td><input type="radio" name="q1_7" value="1" required></td>
                            <td><input type="radio" name="q1_7" value="2"></td>
                            <td><input type="radio" name="q1_7" value="3"></td>
                            <td><input type="radio" name="q1_7" value="4"></td>
                            <td><input type="radio" name="q1_7" value="5"></td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>Pemimpin saya sering memuji kerja keras dan dedikasi bawahannya.</td>
                            <td><input type="radio" name="q1_8" value="1" required></td>
                            <td><input type="radio" name="q1_8" value="2"></td>
                            <td><input type="radio" name="q1_8" value="3"></td>
                            <td><input type="radio" name="q1_8" value="4"></td>
                            <td><input type="radio" name="q1_8" value="5"></td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Pemimpin saya selalu bersikap sederhana dan tidak arogan dalam setiap situasi.</td>
                            <td><input type="radio" name="q1_9" value="1" required></td>
                            <td><input type="radio" name="q1_9" value="2"></td>
                            <td><input type="radio" name="q1_9" value="3"></td>
                            <td><input type="radio" name="q1_9" value="4"></td>
                            <td><input type="radio" name="q1_9" value="5"></td>
                        </tr>
                    </tbody>
                </table>
                <hr><br><br>

                <h4>D. Visi (Vision)</h4><hr>
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
                            <td>10</td>
                            <td>Pemimpin saya membangun rasa memiliki, terhadap tujuan organisasi.</td>
                            <td><input type="radio" name="q1_10" value="1" required></td>
                            <td><input type="radio" name="q1_10" value="2"></td>
                            <td><input type="radio" name="q1_10" value="3"></td>
                            <td><input type="radio" name="q1_10" value="4"></td>
                            <td><input type="radio" name="q1_10" value="5"></td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>Pemimpin saya memberikan arahan yang jelas tentang tujuan jangka panjang organisasi.</td>
                            <td><input type="radio" name="q1_11" value="1" required></td>
                            <td><input type="radio" name="q1_11" value="2"></td>
                            <td><input type="radio" name="q1_11" value="3"></td>
                            <td><input type="radio" name="q1_11" value="4"></td>
                            <td><input type="radio" name="q1_11" value="5"></td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>Pemimpin saya memastikan sumber daya yang diperlukan tersedia untuk mencapai visi.</td>
                            <td><input type="radio" name="q1_12" value="1" required></td>
                            <td><input type="radio" name="q1_12" value="2"></td>
                            <td><input type="radio" name="q1_12" value="3"></td>
                            <td><input type="radio" name="q1_12" value="4"></td>
                            <td><input type="radio" name="q1_12" value="5"></td>
                        </tr>
                    </tbody>
                </table>

                <hr><br><br>
                <h4>E. Kepedulian (Altruistic)</h4><hr>
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
                            <td>13</td>
                            <td>Pemimpin saya sering menunjukkan tindakan yang memprioritaskan tim daripada kepentingan dirinya sendiri.</td>
                            <td><input type="radio" name="q1_13" value="1" required></td>
                            <td><input type="radio" name="q1_13" value="2"></td>
                            <td><input type="radio" name="q1_13" value="3"></td>
                            <td><input type="radio" name="q1_13" value="4"></td>
                            <td><input type="radio" name="q1_13" value="5"></td>
                        </tr>
                        <tr>
                            <td>14</td>
                            <td>Pemimpin saya menunjukkan perhatian yang tulus terhadap kesejahteraan saya.</td>
                            <td><input type="radio" name="q1_14" value="1" required></td>
                            <td><input type="radio" name="q1_14" value="2"></td>
                            <td><input type="radio" name="q1_14" value="3"></td>
                            <td><input type="radio" name="q1_14" value="4"></td>
                            <td><input type="radio" name="q1_14" value="5"></td>
                        </tr>
                        <tr>
                            <td>15</td>
                            <td>Pemimpin saya mendukung saya dalam mengikuti pelatihan untuk meningkatkan kompetensi saya.</td>
                            <td><input type="radio" name="q1_15" value="1" required></td>
                            <td><input type="radio" name="q1_15" value="2"></td>
                            <td><input type="radio" name="q1_15" value="3"></td>
                            <td><input type="radio" name="q1_15" value="4"></td>
                            <td><input type="radio" name="q1_15" value="5"></td>
                        </tr>
                    </tbody>
                </table>

                
                <hr><br><br>
                <h4>F. Pelayanan (Serving)</h4><hr>
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
                            <td>16</td>
                            <td>Pemimpin saya sering memberikan arahan yang jelas untuk memastikan pekerjaan saya berhasil.</td>
                            <td><input type="radio" name="q1_16" value="1" required></td>
                            <td><input type="radio" name="q1_16" value="2"></td>
                            <td><input type="radio" name="q1_16" value="3"></td>
                            <td><input type="radio" name="q1_16" value="4"></td>
                            <td><input type="radio" name="q1_16" value="5"></td>
                        </tr>
                        <tr>
                            <td>17</td>
                            <td>Pemimpin saya memberikan motivasi untuk membantu saya melewati masa sulit.</td>
                            <td><input type="radio" name="q1_17" value="1" required></td>
                            <td><input type="radio" name="q1_17" value="2"></td>
                            <td><input type="radio" name="q1_17" value="3"></td>
                            <td><input type="radio" name="q1_17" value="4"></td>
                            <td><input type="radio" name="q1_17" value="5"></td>
                        </tr>
                        <tr>
                            <td>18</td>
                            <td>Pemimpin saya sering berdiskusi secara langsung dengan saya untuk memahami kebutuhan.</td>
                            <td><input type="radio" name="q1_18" value="1" required></td>
                            <td><input type="radio" name="q1_18" value="2"></td>
                            <td><input type="radio" name="q1_18" value="3"></td>
                            <td><input type="radio" name="q1_18" value="4"></td>
                            <td><input type="radio" name="q1_18" value="5"></td>
                        </tr>
                    </tbody>
                </table><hr><br><br>
                <h4>G. Pemberdayaan (Empower)</h4><hr>
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
                            <td>19</td>
                            <td>Pemimpin saya mendorong saya untuk memiliki ide-ide baru dalam pekerjaan.</td>
                            <td><input type="radio" name="q1_19" value="1" required></td>
                            <td><input type="radio" name="q1_19" value="2"></td>
                            <td><input type="radio" name="q1_19" value="3"></td>
                            <td><input type="radio" name="q1_19" value="4"></td>
                            <td><input type="radio" name="q1_19" value="5"></td>
                        </tr>
                        <tr>
                            <td>20</td>
                            <td>Pemimpin saya mendorong saya untuk mencoba cara baru dalam menyelesaikan pekerjaan.</td>
                            <td><input type="radio" name="q1_20" value="1" required></td>
                            <td><input type="radio" name="q1_20" value="2"></td>
                            <td><input type="radio" name="q1_20" value="3"></td>
                            <td><input type="radio" name="q1_20" value="4"></td>
                            <td><input type="radio" name="q1_20" value="5"></td>
                        </tr>
                        <tr>
                            <td>21</td>
                            <td>Pemimpin saya memberikan motivasi yang membuat saya yakin terhadap kompetensi.</td>
                            <td><input type="radio" name="q1_21" value="1" required></td>
                            <td><input type="radio" name="q1_21" value="2"></td>
                            <td><input type="radio" name="q1_21" value="3"></td>
                            <td><input type="radio" name="q1_21" value="4"></td>
                            <td><input type="radio" name="q1_21" value="5"></td>
                        </tr>
                    </tbody>
                </table>
                <br>

                    <button class="btn btn-next btn-primary me-2 my-1"  style="float: right; margin-right: 20px;" type="button" onclick="nextSection()">Lanjut</button>
                    <button class="btn btn-prev btn-secondary me-2 my-1"  style="float: right; margin-right: 60px;" type="button" onclick="prevSection()">Kembali</button><br><br>
                </div>
            </div>

            <div class="section" id="section-modal-intelektual">
                <div class="card-header">PENGUKURAN MODAL INTELEKTUAL</div>
                <div class="card-body">
                    <div class="small">
                        Skala Penilaian: <br>
                        1. Sangat Tidak Setuju <br>
                        2. Tidak Setuju <br>
                        3. Ragu-ragu <br>
                        4. Setuju <br>
                        5. Sangat Setuju <br><br><hr>
                    </div>

                    <h4>A. Pengembangan Modal Manusia (Human Capital)</h4><hr>
                    <table id="datatablesSimple3" class="table">
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
                                <td>Saya memiliki kualifikasi pendidikan yang relevan dengan kebutuhan satuan kerja untuk mengembangkan organisasi.</td>
                                <td><input type="radio" name="q2_1" value="1" required></td>
                                <td><input type="radio" name="q2_1" value="2"></td>
                                <td><input type="radio" name="q2_1" value="3"></td>
                                <td><input type="radio" name="q2_1" value="4"></td>
                                <td><input type="radio" name="q2_1" value="5"></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Saya memiliki pengalaman kerja yang relevan, termasuk pengalaman di sektor publik yang berkontribusi pada pengetahuan praktis dan keahlian.</td>
                                <td><input type="radio" name="q2_2" value="1" required></td>
                                <td><input type="radio" name="q2_2" value="2"></td>
                                <td><input type="radio" name="q2_2" value="3"></td>
                                <td><input type="radio" name="q2_2" value="4"></td>
                                <td><input type="radio" name="q2_2" value="5"></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Saya selalu mengambil kesempatan untuk meningkatkan keterampilan dan kompetensi sebagai bentuk investasi melalui pelatihan dan pengembangan.</td>
                                <td><input type="radio" name="q2_3" value="1" required></td>
                                <td><input type="radio" name="q2_3" value="2"></td>
                                <td><input type="radio" name="q2_3" value="3"></td>
                                <td><input type="radio" name="q2_3" value="4"></td>
                                <td><input type="radio" name="q2_3" value="5"></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Saya memiliki kemampuan untuk berinovasi dan berpikir kreatif dalam menyelesaikan masalah dan mengembangkan solusi baru.</td>
                                <td><input type="radio" name="q2_4" value="1" required></td>
                                <td><input type="radio" name="q2_4" value="2"></td>
                                <td><input type="radio" name="q2_4" value="3"></td>
                                <td><input type="radio" name="q2_4" value="4"></td>
                                <td><input type="radio" name="q2_4" value="5"></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Saya memiliki kemampuan untuk berkomunikasi secara efektif, baik secara lisan maupun tertulis, yang penting untuk kerja sama dan koordinasi.</td>
                                <td><input type="radio" name="q2_5" value="1" required></td>
                                <td><input type="radio" name="q2_5" value="2"></td>
                                <td><input type="radio" name="q2_5" value="3"></td>
                                <td><input type="radio" name="q2_5" value="4"></td>
                                <td><input type="radio" name="q2_5" value="5"></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Saya memiliki kemampuan untuk bekerja dalam tim dan berkolaborasi dengan berbagai pemangku kepentingan.</td>
                                <td><input type="radio" name="q2_6" value="1" required></td>
                                <td><input type="radio" name="q2_6" value="2"></td>
                                <td><input type="radio" name="q2_6" value="3"></td>
                                <td><input type="radio" name="q2_6" value="4"></td>
                                <td><input type="radio" name="q2_6" value="5"></td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Saya memiliki motivasi dan komitmen yang tinggi terhadap pekerjaan dan organisasi.</td>
                                <td><input type="radio" name="q2_7" value="1" required></td>
                                <td><input type="radio" name="q2_7" value="2"></td>
                                <td><input type="radio" name="q2_7" value="3"></td>
                                <td><input type="radio" name="q2_7" value="4"></td>
                                <td><input type="radio" name="q2_7" value="5"></td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Saya memiliki kemampuan kepemimpinan dan manajemen dalam melakukan pengawasan atau manajerial.</td>
                                <td><input type="radio" name="q2_8" value="1" required></td>
                                <td><input type="radio" name="q2_8" value="2"></td>
                                <td><input type="radio" name="q2_8" value="3"></td>
                                <td><input type="radio" name="q2_8" value="4"></td>
                                <td><input type="radio" name="q2_8" value="5"></td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Saya memiliki sistem penilaian kinerja yang efektif yang selalu memberikan umpan balik konstruktif kepada Pegawai untuk membantu mereka tumbuh dan berkembang.</td>
                                <td><input type="radio" name="q2_9" value="1" required></td>
                                <td><input type="radio" name="q2_9" value="2"></td>
                                <td><input type="radio" name="q2_9" value="3"></td>
                                <td><input type="radio" name="q2_9" value="4"></td>
                                <td><input type="radio" name="q2_9" value="5"></td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Saya selalu mendorong keterlibatan Pegawai sebagai hasil dari pengakuan dan penghargaan atas kontribusi mereka.</td>
                                <td><input type="radio" name="q2_10" value="1" required></td>
                                <td><input type="radio" name="q2_10" value="2"></td>
                                <td><input type="radio" name="q2_10" value="3"></td>
                                <td><input type="radio" name="q2_10" value="4"></td>
                                <td><input type="radio" name="q2_10" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>B. Membentuk Modal Struktural (Structural Capital)</h4><hr>
                    <table id="datatablesSimple4" class="table">
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
                                <td>11</td>
                                <td>Saya sudah dan selalu mengimplementasikan sistem manajemen pengetahuan untuk mendokumentasikan, menyimpan, dan membagikan pengetahuan ke pegawai.</td>
                                <td><input type="radio" name="q2_11" value="1" required></td>
                                <td><input type="radio" name="q2_11" value="2"></td>
                                <td><input type="radio" name="q2_11" value="3"></td>
                                <td><input type="radio" name="q2_11" value="4"></td>
                                <td><input type="radio" name="q2_11" value="5"></td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Saya sudah membangun dan selalu meningkatkan kualitas dan efektivitas proses internal, seperti prosedur pengadaan barang dan jasa, manajemen proyek survei dan sensus.</td>
                                <td><input type="radio" name="q2_12" value="1" required></td>
                                <td><input type="radio" name="q2_12" value="2"></td>
                                <td><input type="radio" name="q2_12" value="3"></td>
                                <td><input type="radio" name="q2_12" value="4"></td>
                                <td><input type="radio" name="q2_12" value="5"></td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Saya selalu mendorong seluruh pegawai untuk berinovasi dan belajar dari pengalaman untuk meningkatkan kinerja baik aspek teknis, administratif dan organisasi.</td>
                                <td><input type="radio" name="q2_13" value="1" required></td>
                                <td><input type="radio" name="q2_13" value="2"></td>
                                <td><input type="radio" name="q2_13" value="3"></td>
                                <td><input type="radio" name="q2_13" value="4"></td>
                                <td><input type="radio" name="q2_13" value="5"></td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Saya sudah membangun dan selalu mengembangkan sistem proses organisasi, infrastruktur teknologi informasi, dan komunikasi yang mendukung operasi organisasi satuan kerja.</td>
                                <td><input type="radio" name="q2_14" value="1" required></td>
                                <td><input type="radio" name="q2_14" value="2"></td>
                                <td><input type="radio" name="q2_14" value="3"></td>
                                <td><input type="radio" name="q2_14" value="4"></td>
                                <td><input type="radio" name="q2_14" value="5"></td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Saya selalu berhasil beradaptasi dengan perubahan lingkungan dan memiliki fleksibilitas yang tinggi dalam struktur dan proses organisasi.</td>
                                <td><input type="radio" name="q2_15" value="1" required></td>
                                <td><input type="radio" name="q2_15" value="2"></td>
                                <td><input type="radio" name="q2_15" value="3"></td>
                                <td><input type="radio" name="q2_15" value="4"></td>
                                <td><input type="radio" name="q2_15" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>C. Membangun Modal Relasional (Reational Capital)</h4><hr>
                    <table id="datatablesSimple5" class="table">
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
                                <td>16</td>
                                <td>Saya mampu membangun dan meningkatkan kualitas hubungan dengan para stakeholder, termasuk masyarakat, sektor swasta, dan organisasi lainnya.</td>
                                <td><input type="radio" name="q2_16" value="1" required></td>
                                <td><input type="radio" name="q2_16" value="2"></td>
                                <td><input type="radio" name="q2_16" value="3"></td>
                                <td><input type="radio" name="q2_16" value="4"></td>
                                <td><input type="radio" name="q2_16" value="5"></td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Saya selalu berusaha untuk meningkatkan kepercayaan publik terhadap organisasi pemerintah.</td>
                                <td><input type="radio" name="q2_17" value="1" required></td>
                                <td><input type="radio" name="q2_17" value="2"></td>
                                <td><input type="radio" name="q2_17" value="3"></td>
                                <td><input type="radio" name="q2_17" value="4"></td>
                                <td><input type="radio" name="q2_17" value="5"></td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Selama menjadi kepala satker, Saya berhasil meningkatkan dan memperkuat jaringan kerjasama dengan organisasi lain, termasuk sesama pemerintah, sektor swasta, dan organisasi non-profit dibanding pimpinan satker terdahulu.</td>
                                <td><input type="radio" name="q2_18" value="1" required></td>
                                <td><input type="radio" name="q2_18" value="2"></td>
                                <td><input type="radio" name="q2_18" value="3"></td>
                                <td><input type="radio" name="q2_18" value="4"></td>
                                <td><input type="radio" name="q2_18" value="5"></td>
                            </tr>
                            <tr>
                                <td>19</td>
                                <td>Saya memiliki keterampilan dalam menegosiasikan dan menyelesaikan konflik dengan pemangku kepentingan, yang penting untuk menjaga hubungan yang harmonis.</td>
                                <td><input type="radio" name="q2_19" value="1" required></td>
                                <td><input type="radio" name="q2_19" value="2"></td>
                                <td><input type="radio" name="q2_19" value="3"></td>
                                <td><input type="radio" name="q2_19" value="4"></td>
                                <td><input type="radio" name="q2_19" value="5"></td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>Satuan kerja yang saya pimpin selalu terlibat dalam dialog dan aktivitas yang mendukung kepentingan masyarakat.</td>
                                <td><input type="radio" name="q2_20" value="1" required></td>
                                <td><input type="radio" name="q2_20" value="2"></td>
                                <td><input type="radio" name="q2_20" value="3"></td>
                                <td><input type="radio" name="q2_20" value="4"></td>
                                <td><input type="radio" name="q2_20" value="5"></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>

                    <button class="btn btn-next btn-primary me-2 my-1"  style="float: right; margin-right: 20px;" type="button" onclick="nextSection()">Lanjut</button>
                    <button class="btn btn-prev btn-secondary me-2 my-1"  style="float: right; margin-right: 60px;" type="button" onclick="prevSection()">Kembali</button><br><br>
                </div>
            </div>

            <div class="section" id="section-budaya-kerja">
                <div class="card-header">PENGUKURAN BUDAYA KERJA</div>
                <div class="card-body">
                    <div class="small">
                        Skala Penilaian: <br>
                        1. Sangat Tidak Setuju <br>
                        2. Tidak Setuju <br>
                        3. Ragu-ragu <br>
                        4. Setuju <br>
                        5. Sangat Setuju <br><br><hr>
                    </div>

                    <h4>A. Berorientasi Pelayanan</h4><hr>
                    <table id="datatablesSimple6" class="table">
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
                                <td>Saya secara rutin mengidentifikasi kebutuhan masyarakat untuk meningkatkan pelayanan.</td>
                                <td><input type="radio" name="q3_1" value="1" required></td>
                                <td><input type="radio" name="q3_1" value="2"></td>
                                <td><input type="radio" name="q3_1" value="3"></td>
                                <td><input type="radio" name="q3_1" value="4"></td>
                                <td><input type="radio" name="q3_1" value="5"></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Saya selalu memberikan solusi yang cepat dan dapat diandalkan kepada masyarakat.</td>
                                <td><input type="radio" name="q3_2" value="1" required></td>
                                <td><input type="radio" name="q3_2" value="2"></td>
                                <td><input type="radio" name="q3_2" value="3"></td>
                                <td><input type="radio" name="q3_2" value="4"></td>
                                <td><input type="radio" name="q3_2" value="5"></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Saya secara konsisten mengevaluasi dan meningkatkan proses pelayanan.</td>
                                <td><input type="radio" name="q3_3" value="1" required></td>
                                <td><input type="radio" name="q3_3" value="2"></td>
                                <td><input type="radio" name="q3_3" value="3"></td>
                                <td><input type="radio" name="q3_3" value="4"></td>
                                <td><input type="radio" name="q3_3" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>B. Akuntabilitas</h4><hr>
                    <table id="datatablesSimple7" class="table">
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
                                <td>4</td>
                                <td>Saya selalu bekerja dengan jujur dan bertanggung jawab dalam setiap tugas.</td>
                                <td><input type="radio" name="q3_4" value="1" required></td>
                                <td><input type="radio" name="q3_4" value="2"></td>
                                <td><input type="radio" name="q3_4" value="3"></td>
                                <td><input type="radio" name="q3_4" value="4"></td>
                                <td><input type="radio" name="q3_4" value="5"></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Saya memastikan penggunaan barang milik negara selalu efisien dan bertanggung jawab.</td>
                                <td><input type="radio" name="q3_5" value="1" required></td>
                                <td><input type="radio" name="q3_5" value="2"></td>
                                <td><input type="radio" name="q3_5" value="3"></td>
                                <td><input type="radio" name="q3_5" value="4"></td>
                                <td><input type="radio" name="q3_5" value="5"></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Saya sangat memperhatikan batasan kewenangan dalam setiap keputusan.</td>
                                <td><input type="radio" name="q3_6" value="1" required></td>
                                <td><input type="radio" name="q3_6" value="2"></td>
                                <td><input type="radio" name="q3_6" value="3"></td>
                                <td><input type="radio" name="q3_6" value="4"></td>
                                <td><input type="radio" name="q3_6" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>C. Kompeten</h4><hr>
                    <table id="datatablesSimple8" class="table">
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
                                <td>7</td>
                                <td>Saya rutin mengikuti pelatihan untuk meningkatkan kompetensi.</td>
                                <td><input type="radio" name="q3_7" value="1" required></td>
                                <td><input type="radio" name="q3_7" value="2"></td>
                                <td><input type="radio" name="q3_7" value="3"></td>
                                <td><input type="radio" name="q3_7" value="4"></td>
                                <td><input type="radio" name="q3_7" value="5"></td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Saya sering membantu rekan kerja untuk memahami tugas mereka dengan lebih baik.</td>
                                <td><input type="radio" name="q3_8" value="1" required></td>
                                <td><input type="radio" name="q3_8" value="2"></td>
                                <td><input type="radio" name="q3_8" value="3"></td>
                                <td><input type="radio" name="q3_8" value="4"></td>
                                <td><input type="radio" name="q3_8" value="5"></td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Saya selalu memastikan tugas diselesaikan dengan kualitas terbaik.</td>
                                <td><input type="radio" name="q3_9" value="1" required></td>
                                <td><input type="radio" name="q3_9" value="2"></td>
                                <td><input type="radio" name="q3_9" value="3"></td>
                                <td><input type="radio" name="q3_9" value="4"></td>
                                <td><input type="radio" name="q3_9" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>D. Harmonis</h4><hr>
                    <table id="datatablesSimple9" class="table">
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
                                <td>10</td>
                                <td>Saya selalu menghormati perbedaan latar belakang dalam tim.</td>
                                <td><input type="radio" name="q3_10" value="1" required></td>
                                <td><input type="radio" name="q3_10" value="2"></td>
                                <td><input type="radio" name="q3_10" value="3"></td>
                                <td><input type="radio" name="q3_10" value="4"></td>
                                <td><input type="radio" name="q3_10" value="5"></td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Saya sering mengambil inisiatif untuk membantu rekan kerja yang membutuhkan.</td>
                                <td><input type="radio" name="q3_11" value="1" required></td>
                                <td><input type="radio" name="q3_11" value="2"></td>
                                <td><input type="radio" name="q3_11" value="3"></td>
                                <td><input type="radio" name="q3_11" value="4"></td>
                                <td><input type="radio" name="q3_11" value="5"></td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Saya berupaya menciptakan suasana kerja yang nyaman dan produktif.</td>
                                <td><input type="radio" name="q3_12" value="1" required></td>
                                <td><input type="radio" name="q3_12" value="2"></td>
                                <td><input type="radio" name="q3_12" value="3"></td>
                                <td><input type="radio" name="q3_12" value="4"></td>
                                <td><input type="radio" name="q3_12" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>E. Loyal</h4><hr>
                    <table id="datatablesSimple10" class="table">
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
                                <td>13</td>
                                <td>Saya selalu menjalankan tugas sesuai dengan nilai-nilai Pancasila.</td>
                                <td><input type="radio" name="q3_13" value="1" required></td>
                                <td><input type="radio" name="q3_13" value="2"></td>
                                <td><input type="radio" name="q3_13" value="3"></td>
                                <td><input type="radio" name="q3_13" value="4"></td>
                                <td><input type="radio" name="q3_13" value="5"></td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Saya selalu menjaga nama baik instansi dan rekan kerja Saya.</td>
                                <td><input type="radio" name="q3_14" value="1" required></td>
                                <td><input type="radio" name="q3_14" value="2"></td>
                                <td><input type="radio" name="q3_14" value="3"></td>
                                <td><input type="radio" name="q3_14" value="4"></td>
                                <td><input type="radio" name="q3_14" value="5"></td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Saya sangat menjaga kerahasiaan terkait jabatan dan negara.</td>
                                <td><input type="radio" name="q3_15" value="1" required></td>
                                <td><input type="radio" name="q3_15" value="2"></td>
                                <td><input type="radio" name="q3_15" value="3"></td>
                                <td><input type="radio" name="q3_15" value="4"></td>
                                <td><input type="radio" name="q3_15" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>F. Adaptif</h4><hr>
                    <table id="datatablesSimple11" class="table">
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
                                <td>16</td>
                                <td>Saya cepat beradaptasi dengan kebijakan baru di lingkungan kerja.</td>
                                <td><input type="radio" name="q3_16" value="1" required></td>
                                <td><input type="radio" name="q3_16" value="2"></td>
                                <td><input type="radio" name="q3_16" value="3"></td>
                                <td><input type="radio" name="q3_16" value="4"></td>
                                <td><input type="radio" name="q3_16" value="5"></td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Saya secara aktif mencari cara inovatif untuk menyelesaikan masalah.</td>
                                <td><input type="radio" name="q3_17" value="1" required></td>
                                <td><input type="radio" name="q3_17" value="2"></td>
                                <td><input type="radio" name="q3_17" value="3"></td>
                                <td><input type="radio" name="q3_17" value="4"></td>
                                <td><input type="radio" name="q3_17" value="5"></td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Saya selalu bertindak proaktif dalam mengidentifikasi masalah yang mungkin muncul.</td>
                                <td><input type="radio" name="q3_18" value="1" required></td>
                                <td><input type="radio" name="q3_18" value="2"></td>
                                <td><input type="radio" name="q3_18" value="3"></td>
                                <td><input type="radio" name="q3_18" value="4"></td>
                                <td><input type="radio" name="q3_18" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>G. Kolaboratif</h4><hr>
                    <table id="datatablesSimple12" class="table">
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
                                <td>19</td>
                                <td>Saya selalu memberikan kesempatan kepada semua pihak untuk berkontribusi.</td>
                                <td><input type="radio" name="q3_19" value="1" required></td>
                                <td><input type="radio" name="q3_19" value="2"></td>
                                <td><input type="radio" name="q3_19" value="3"></td>
                                <td><input type="radio" name="q3_19" value="4"></td>
                                <td><input type="radio" name="q3_19" value="5"></td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>Saya sangat terbuka dalam berbagi informasi untuk mendukung kolaborasi.</td>
                                <td><input type="radio" name="q3_20" value="1" required></td>
                                <td><input type="radio" name="q3_20" value="2"></td>
                                <td><input type="radio" name="q3_20" value="3"></td>
                                <td><input type="radio" name="q3_20" value="4"></td>
                                <td><input type="radio" name="q3_20" value="5"></td>
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>Saya secara efektif memanfaatkan berbagai sumber daya untuk tujuan bersama.</td>
                                <td><input type="radio" name="q3_21" value="1" required></td>
                                <td><input type="radio" name="q3_21" value="2"></td>
                                <td><input type="radio" name="q3_21" value="3"></td>
                                <td><input type="radio" name="q3_21" value="4"></td>
                                <td><input type="radio" name="q3_21" value="5"></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>

                    <button class="btn btn-next btn-primary me-2 my-1"  style="float: right; margin-right: 20px;" type="button" onclick="nextSection()">Lanjut</button>
                    <button class="btn btn-prev btn-secondary me-2 my-1"  style="float: right; margin-right: 60px;" type="button" onclick="prevSection()">Kembali</button><br><br>

                </div>
            </div>

            <div class="section" id="section-komitmen-pegawai">
                <div class="card-header">PENGUKURAN KOMITMEN PEGAWAI</div>
                <div class="card-body">
                    <div class="small">
                        Skala Penilaian: <br>
                        1. Sangat Tidak Setuju <br>
                        2. Tidak Setuju <br>
                        3. Ragu-ragu <br>
                        4. Setuju <br>
                        5. Sangat Setuju <br><br><hr>
                    </div>

                    <h4>A. Komitmen Afektif</h4><hr>
                    <table id="datatablesSimple13" class="table">
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
                                <td>Saya merasa bangga menjadi bagian dari BPS.</td>
                                <td><input type="radio" name="q4_1" value="1" required></td>
                                <td><input type="radio" name="q4_1" value="2"></td>
                                <td><input type="radio" name="q4_1" value="3"></td>
                                <td><input type="radio" name="q4_1" value="4"></td>
                                <td><input type="radio" name="q4_1" value="5"></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Lingkungan kerja saya mendukung kolaborasi dan produktivitas.</td>
                                <td><input type="radio" name="q4_2" value="1" required></td>
                                <td><input type="radio" name="q4_2" value="2"></td>
                                <td><input type="radio" name="q4_2" value="3"></td>
                                <td><input type="radio" name="q4_2" value="4"></td>
                                <td><input type="radio" name="q4_2" value="5"></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Saya merasa nyaman dengan peran dan tanggung jawab saya di BPS.</td>
                                <td><input type="radio" name="q4_3" value="1" required></td>
                                <td><input type="radio" name="q4_3" value="2"></td>
                                <td><input type="radio" name="q4_3" value="3"></td>
                                <td><input type="radio" name="q4_3" value="4"></td>
                                <td><input type="radio" name="q4_3" value="5"></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Nilai-nilai pribadi saya sangat sesuai dengan nilai-nilai BPS.</td>
                                <td><input type="radio" name="q4_4" value="1" required></td>
                                <td><input type="radio" name="q4_4" value="2"></td>
                                <td><input type="radio" name="q4_4" value="3"></td>
                                <td><input type="radio" name="q4_4" value="4"></td>
                                <td><input type="radio" name="q4_4" value="5"></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Saya merasa setia kepada BPS dalam segala situasi.</td>
                                <td><input type="radio" name="q4_5" value="1" required></td>
                                <td><input type="radio" name="q4_5" value="2"></td>
                                <td><input type="radio" name="q4_5" value="3"></td>
                                <td><input type="radio" name="q4_5" value="4"></td>
                                <td><input type="radio" name="q4_5" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>B. Komitmen Berkelanjutan</h4><hr>
                    <table id="datatablesSimple14" class="table">
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
                                <td>6</td>
                                <td>Saya merasa meninggalkan BPS ini akan merugikan karier saya.</td>
                                <td><input type="radio" name="q4_6" value="1" required></td>
                                <td><input type="radio" name="q4_6" value="2"></td>
                                <td><input type="radio" name="q4_6" value="3"></td>
                                <td><input type="radio" name="q4_6" value="4"></td>
                                <td><input type="radio" name="q4_6" value="5"></td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Waktu dan usaha yang saya habiskan di BPS ini memberikan nilai yang signifikan.</td>
                                <td><input type="radio" name="q4_7" value="1" required></td>
                                <td><input type="radio" name="q4_7" value="2"></td>
                                <td><input type="radio" name="q4_7" value="3"></td>
                                <td><input type="radio" name="q4_7" value="4"></td>
                                <td><input type="radio" name="q4_7" value="5"></td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Keterampilan saya sangat sesuai dengan kebutuhan BPS.</td>
                                <td><input type="radio" name="q4_8" value="1" required></td>
                                <td><input type="radio" name="q4_8" value="2"></td>
                                <td><input type="radio" name="q4_8" value="3"></td>
                                <td><input type="radio" name="q4_8" value="4"></td>
                                <td><input type="radio" name="q4_8" value="5"></td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Program pensiun di BPS menjadi faktor penting dalam keputusan saya untuk tetap tinggal.</td>
                                <td><input type="radio" name="q4_9" value="1" required></td>
                                <td><input type="radio" name="q4_9" value="2"></td>
                                <td><input type="radio" name="q4_9" value="3"></td>
                                <td><input type="radio" name="q4_9" value="4"></td>
                                <td><input type="radio" name="q4_9" value="5"></td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Saya merasa bahwa meninggalkan BPS akan mengakibatkan kerugian finansial bagi saya.</td>
                                <td><input type="radio" name="q4_10" value="1" required></td>
                                <td><input type="radio" name="q4_10" value="2"></td>
                                <td><input type="radio" name="q4_10" value="3"></td>
                                <td><input type="radio" name="q4_10" value="4"></td>
                                <td><input type="radio" name="q4_10" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>C. Komitmen Normatif</h4><hr>
                    <table id="datatablesSimple15" class="table">
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
                                <td>11</td>
                                <td>Saya merasa memiliki tanggung jawab moral untuk tetap berkontribusi di organisasi ini.</td>
                                <td><input type="radio" name="q4_11" value="1" required></td>
                                <td><input type="radio" name="q4_11" value="2"></td>
                                <td><input type="radio" name="q4_11" value="3"></td>
                                <td><input type="radio" name="q4_11" value="4"></td>
                                <td><input type="radio" name="q4_11" value="5"></td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Saya merasa norma sosial dan budaya organisasi ini mendukung loyalitas karyawan.</td>
                                <td><input type="radio" name="q4_12" value="1" required></td>
                                <td><input type="radio" name="q4_12" value="2"></td>
                                <td><input type="radio" name="q4_12" value="3"></td>
                                <td><input type="radio" name="q4_12" value="4"></td>
                                <td><input type="radio" name="q4_12" value="5"></td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Saya merasa dihargai atas kontribusi yang saya berikan di organisasi ini.</td>
                                <td><input type="radio" name="q4_13" value="1" required></td>
                                <td><input type="radio" name="q4_13" value="2"></td>
                                <td><input type="radio" name="q4_13" value="3"></td>
                                <td><input type="radio" name="q4_13" value="4"></td>
                                <td><input type="radio" name="q4_13" value="5"></td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Proses pengenalan nilai-nilai organisasi membantu saya merasa terintegrasi.</td>
                                <td><input type="radio" name="q4_14" value="1" required></td>
                                <td><input type="radio" name="q4_14" value="2"></td>
                                <td><input type="radio" name="q4_14" value="3"></td>
                                <td><input type="radio" name="q4_14" value="4"></td>
                                <td><input type="radio" name="q4_14" value="5"></td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Saya merasa memiliki kewajiban moral untuk tetap bekerja di organisasi ini.</td>
                                <td><input type="radio" name="q4_15" value="1" required></td>
                                <td><input type="radio" name="q4_15" value="2"></td>
                                <td><input type="radio" name="q4_15" value="3"></td>
                                <td><input type="radio" name="q4_15" value="4"></td>
                                <td><input type="radio" name="q4_15" value="5"></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>

                    <button class="btn btn-next btn-primary me-2 my-1"  style="float: right; margin-right: 20px;" type="button" onclick="nextSection()">Lanjut</button>
                    <button class="btn btn-prev btn-secondary me-2 my-1"  style="float: right; margin-right: 60px;" type="button" onclick="prevSection()">Kembali</button><br><br>

                </div>
            </div>

            <div class="section" id="section-kinerja-pegawai">
                <div class="card-header">PENGUKURAN KINERJA PEGAWAI</div>
                <div class="card-body">
                    <div class="small">
                        Skala Penilaian: <br>
                        1. Sangat Tidak Setuju <br>
                        2. Tidak Setuju <br>
                        3. Ragu-ragu <br>
                        4. Setuju <br>
                        5. Sangat Setuju <br><br><hr>
                    </div>

                    <h4>A. Kecakapan tugas spesifik /Job-specific Task Proficiency</h4><hr>
                    <table id="datatablesSimple16" class="table">
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
                                <td>Saya pastikan pemahaman terhadap tugas dan kewenangan dalam tugas teknis survei dan sensus, administrasi dan pengelolaan organisasi sudah optimal.</td>
                                <td><input type="radio" name="q5_1" value="1" required></td>
                                <td><input type="radio" name="q5_1" value="2"></td>
                                <td><input type="radio" name="q5_1" value="3"></td>
                                <td><input type="radio" name="q5_1" value="4"></td>
                                <td><input type="radio" name="q5_1" value="5"></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Saya pastikan implementasi pekerjaan teknis survei dan sensus, administrasi dan organisasi sudah optimal.</td>
                                <td><input type="radio" name="q5_2" value="1" required></td>
                                <td><input type="radio" name="q5_2" value="2"></td>
                                <td><input type="radio" name="q5_2" value="3"></td>
                                <td><input type="radio" name="q5_2" value="4"></td>
                                <td><input type="radio" name="q5_2" value="5"></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Saya konsisten berupaya mencapai target yang sudah direncanakan dalam kualitas dan kuantitas pekerjaan.</td>
                                <td><input type="radio" name="q5_3" value="1" required></td>
                                <td><input type="radio" name="q5_3" value="2"></td>
                                <td><input type="radio" name="q5_3" value="3"></td>
                                <td><input type="radio" name="q5_3" value="4"></td>
                                <td><input type="radio" name="q5_3" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>B. Kecakapan tugas Non spesifik /Non Job-specific Task Proficiency</h4><hr>
                    <table id="datatablesSimple17" class="table">
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
                                <td>4</td>
                                <td>Saya selalu berbagi pengalaman baik dan membantu satker lain mencapai tujuan organisasi.</td>
                                <td><input type="radio" name="q5_4" value="1" required></td>
                                <td><input type="radio" name="q5_4" value="2"></td>
                                <td><input type="radio" name="q5_4" value="3"></td>
                                <td><input type="radio" name="q5_4" value="4"></td>
                                <td><input type="radio" name="q5_4" value="5"></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Saya sudah optimal membangun harmoni dengan pihak internal dan eksternal dalam pelaksanaan tugas.</td>
                                <td><input type="radio" name="q5_5" value="1" required></td>
                                <td><input type="radio" name="q5_5" value="2"></td>
                                <td><input type="radio" name="q5_5" value="3"></td>
                                <td><input type="radio" name="q5_5" value="4"></td>
                                <td><input type="radio" name="q5_5" value="5"></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Saya selalu berinisiatif membantu rekan kerja tanpa diminta.</td>
                                <td><input type="radio" name="q5_6" value="1" required></td>
                                <td><input type="radio" name="q5_6" value="2"></td>
                                <td><input type="radio" name="q5_6" value="3"></td>
                                <td><input type="radio" name="q5_6" value="4"></td>
                                <td><input type="radio" name="q5_6" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>C. Kecakapan komunikasi tertulis dan lisan/Oral and Written Communication Proficiency</h4><hr>
                    <table id="datatablesSimple18" class="table">
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
                                <td>7</td>
                                <td>Saya selalu membuat arahan lisan dan instruksi tertulis yang efektif ditindaklanjuti semua pegawai.</td>
                                <td><input type="radio" name="q5_7" value="1" required></td>
                                <td><input type="radio" name="q5_7" value="2"></td>
                                <td><input type="radio" name="q5_7" value="3"></td>
                                <td><input type="radio" name="q5_7" value="4"></td>
                                <td><input type="radio" name="q5_7" value="5"></td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Saya selau menyampaikan informasi dengan sederhana, jelas, dan mudah dipahami dan Efektif.</td>
                                <td><input type="radio" name="q5_8" value="1" required></td>
                                <td><input type="radio" name="q5_8" value="2"></td>
                                <td><input type="radio" name="q5_8" value="3"></td>
                                <td><input type="radio" name="q5_8" value="4"></td>
                                <td><input type="radio" name="q5_8" value="5"></td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Saya memperhatikan informasi yang berkembang dan segera merespon secara terukur urgensinya.</td>
                                <td><input type="radio" name="q5_9" value="1" required></td>
                                <td><input type="radio" name="q5_9" value="2"></td>
                                <td><input type="radio" name="q5_9" value="3"></td>
                                <td><input type="radio" name="q5_9" value="4"></td>
                                <td><input type="radio" name="q5_9" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>D. Unjuk Usaha/Demonstration Effort</h4><hr>
                    <table id="datatablesSimple19" class="table">
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
                                <td>10</td>
                                <td>Saya berupaya menyelesaikan tugas meskipun dalam kondisi keterbatasan sumberdaya dan berbagai hambatan.</td>
                                <td><input type="radio" name="q5_10" value="1" required></td>
                                <td><input type="radio" name="q5_10" value="2"></td>
                                <td><input type="radio" name="q5_10" value="3"></td>
                                <td><input type="radio" name="q5_10" value="4"></td>
                                <td><input type="radio" name="q5_10" value="5"></td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Dalam kondisi apapun, target yang sudah disepakati diupayakan tercapai bahkan bisa melampaui taget semula.</td>
                                <td><input type="radio" name="q5_11" value="1" required></td>
                                <td><input type="radio" name="q5_11" value="2"></td>
                                <td><input type="radio" name="q5_11" value="3"></td>
                                <td><input type="radio" name="q5_11" value="4"></td>
                                <td><input type="radio" name="q5_11" value="5"></td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Saya meyakini bahwa masalah dapat diselesaikan dengan, pro aktif, keterbukaan dan kolaborasi.</td>
                                <td><input type="radio" name="q5_12" value="1" required></td>
                                <td><input type="radio" name="q5_12" value="2"></td>
                                <td><input type="radio" name="q5_12" value="3"></td>
                                <td><input type="radio" name="q5_12" value="4"></td>
                                <td><input type="radio" name="q5_12" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>E. Pemeliharaan Disiplin Pribadi/Maintanance personal discipline</h4><hr>
                    <table id="datatablesSimple20" class="table">
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
                                <td>13</td>
                                <td>Saya sebagai pimpinan konsisten mengikuti peraturan dan kebijakan organisasi tanpa terkecuali.</td>
                                <td><input type="radio" name="q5_13" value="1" required></td>
                                <td><input type="radio" name="q5_13" value="2"></td>
                                <td><input type="radio" name="q5_13" value="3"></td>
                                <td><input type="radio" name="q5_13" value="4"></td>
                                <td><input type="radio" name="q5_13" value="5"></td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Saya konsisten menjaga waktu penyelesaiaan tugas.</td>
                                <td><input type="radio" name="q5_14" value="1" required></td>
                                <td><input type="radio" name="q5_14" value="2"></td>
                                <td><input type="radio" name="q5_14" value="3"></td>
                                <td><input type="radio" name="q5_14" value="4"></td>
                                <td><input type="radio" name="q5_14" value="5"></td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Saya konsisten secara pribadi dan menjaga seluruh pegawai agar taat atas kebijakan dan peraturan yang berlaku.</td>
                                <td><input type="radio" name="q5_15" value="1" required></td>
                                <td><input type="radio" name="q5_15" value="2"></td>
                                <td><input type="radio" name="q5_15" value="3"></td>
                                <td><input type="radio" name="q5_15" value="4"></td>
                                <td><input type="radio" name="q5_15" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>F. Fasilitasi kinerja rekan dan tim/Facilitation of Peer and Team Performance</h4><hr>
                    <table id="datatablesSimple21" class="table">
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
                                <td>16</td>
                                <td>Saya menginstruksikan kerja kolaboratif antar tim dan antar Satker untuk mencapai target organisasi.</td>
                                <td><input type="radio" name="q5_16" value="1" required></td>
                                <td><input type="radio" name="q5_16" value="2"></td>
                                <td><input type="radio" name="q5_16" value="3"></td>
                                <td><input type="radio" name="q5_16" value="4"></td>
                                <td><input type="radio" name="q5_16" value="5"></td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Saya mampu menyelesaikan konflik dalam tim dengan cara yang konstruktif.</td>
                                <td><input type="radio" name="q5_17" value="1" required></td>
                                <td><input type="radio" name="q5_17" value="2"></td>
                                <td><input type="radio" name="q5_17" value="3"></td>
                                <td><input type="radio" name="q5_17" value="4"></td>
                                <td><input type="radio" name="q5_17" value="5"></td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Saya mendorong kolaborasi antar tim bahkan lintas satker.</td>
                                <td><input type="radio" name="q5_18" value="1" required></td>
                                <td><input type="radio" name="q5_18" value="2"></td>
                                <td><input type="radio" name="q5_18" value="3"></td>
                                <td><input type="radio" name="q5_18" value="4"></td>
                                <td><input type="radio" name="q5_18" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>G. Supervisi/kepemimpinan (Supervision/Leadership)</h4><hr>
                    <table id="datatablesSimple22" class="table">
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
                                <td>19</td>
                                <td>Saya selalu memberikan arahan yang jelas dan mudah dipahami oleh bawahan.</td>
                                <td><input type="radio" name="q5_19" value="1" required></td>
                                <td><input type="radio" name="q5_19" value="2"></td>
                                <td><input type="radio" name="q5_19" value="3"></td>
                                <td><input type="radio" name="q5_19" value="4"></td>
                                <td><input type="radio" name="q5_19" value="5"></td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>Saya memberikan umpan balik yang konstruktif dan membangun kepada bawahan.</td>
                                <td><input type="radio" name="q5_20" value="1" required></td>
                                <td><input type="radio" name="q5_20" value="2"></td>
                                <td><input type="radio" name="q5_20" value="3"></td>
                                <td><input type="radio" name="q5_20" value="4"></td>
                                <td><input type="radio" name="q5_20" value="5"></td>
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>Saya selalu memotivasi staf untuk tetap bersemangat dan fokus pada tujuan pekerjaan.</td>
                                <td><input type="radio" name="q5_21" value="1" required></td>
                                <td><input type="radio" name="q5_21" value="2"></td>
                                <td><input type="radio" name="q5_21" value="3"></td>
                                <td><input type="radio" name="q5_21" value="4"></td>
                                <td><input type="radio" name="q5_21" value="5"></td>
                            </tr>
                        </tbody>
                    </table><hr><br><br>

                    <h4>H. Manajemen/administrasi (Management/Administration)</h4><hr>
                    <table id="datatablesSimple23" class="table">
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
                                <td>22</td>
                                <td>Saya mampu menyusun rencana kerja yang sistematis sesuai dengan prioritas organisasi.</td>
                                <td><input type="radio" name="q5_22" value="1" required></td>
                                <td><input type="radio" name="q5_22" value="2"></td>
                                <td><input type="radio" name="q5_22" value="3"></td>
                                <td><input type="radio" name="q5_22" value="4"></td>
                                <td><input type="radio" name="q5_22" value="5"></td>
                            </tr>
                            <tr>
                                <td>23</td>
                                <td>Saya membuat sistem pemantauan, penggunaan dan evaluasi sumberdaya manusia, anggaran dengan efisien.</td>
                                <td><input type="radio" name="q5_23" value="1" required></td>
                                <td><input type="radio" name="q5_23" value="2"></td>
                                <td><input type="radio" name="q5_23" value="3"></td>
                                <td><input type="radio" name="q5_23" value="4"></td>
                                <td><input type="radio" name="q5_23" value="5"></td>
                            </tr>
                            <tr>
                                <td>24</td>
                                <td>Saya memastikan setiap pelaporan administrasi dilakukan dengan akurat, akuntabel dan tepat waktu.</td>
                                <td><input type="radio" name="q5_24" value="1" required></td>
                                <td><input type="radio" name="q5_24" value="2"></td>
                                <td><input type="radio" name="q5_24" value="3"></td>
                                <td><input type="radio" name="q5_24" value="4"></td>
                                <td><input type="radio" name="q5_24" value="5"></td>
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