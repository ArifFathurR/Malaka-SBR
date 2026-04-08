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
        <form id="kuesionerForm" action="<?= base_url('surveyshp/proses_edit_shp') ?>" method="post"> 
            <!-- Section Identitas -->
            <div class="section active" id="section-identitas">
                <div class="card-header">IDENTITAS</div>
                <div class="card-body">
                    <input type="hidden" name="id_user" value="<?= $survey_data['id_user'] ?>" />

                    <div class="mb-3">
                        <label for="nama_survei_1">Nama Survei:</label>
                        <input type="text" class="form-control" id="nama_survei_1" name="nama_survei_1" value="<?= $survey_data['nama_survei_1'] ?>" readonly />
                    </div>

                    <div class="mb-3">
                        <label for="jabatan_survei_1">Jabatan:</label>
                        <input type="text" class="form-control" id="jabatan_survei_1" name="jabatan_survei_1" value="<?= $survey_data['jabatan_survei_1'] ?>" readonly />
                    </div>

                    <div class="mb-3">
                        <label for="usia">Usia:</label>
                        <input type="number" class="form-control" id="usia" name="usia" value="<?= $survey_data['usia'] ?>" />
                    </div>

                    <div class="mb-3">
                        <label for="jk_survei_1">Jenis Kelamin:</label>
                        <select class="form-control" id="jk_survei_1" name="jk_survei_1">
                            <option value="Laki-laki" <?= $survey_data['jk_survei_1'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="Perempuan" <?= $survey_data['jk_survei_1'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="pendidikan_survei_1">Pendidikan:</label>
                        <select class="form-control" id="pendidikan_survei_1" name="pendidikan_survei_1">
                            <option value="SMA" <?= $survey_data['pendidikan_survei_1'] == 'SMA' ? 'selected' : '' ?>>SMA</option>
                            <option value="Diploma" <?= $survey_data['pendidikan_survei_1'] == 'Diploma' ? 'selected' : '' ?>>Diploma</option>
                            <option value="D4S1" <?= $survey_data['pendidikan_survei_1'] == 'D4S1' ? 'selected' : '' ?>>D4/S1</option>
                            <option value="S2S3" <?= $survey_data['pendidikan_survei_1'] == 'S2S3' ? 'selected' : '' ?>>S2/S3</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="lama_jabatan">Lama Jabatan:</label>
                        <input type="text" class="form-control" id="lama_jabatan" name="lama_jabatan" value="<?= $survey_data['lama_jabatan'] ?>" />
                    </div>
                    
                    <div class="mb-3">
                        <label for="keterangan">
                            Keterangan: <br> 
                            (Jika masa kerja anda di Sakter saat ini < 1 tahun, isikan tanggal TMT Anda untuk jabatan saat ini dan isikan Jabatan Anda sebelumnya, <br>
                            Misal: TMT 1 Oktober 2024 sebagai Kepala BPS Kabupaten Y, jabatan sebelumnya: Statistisi Ahli Muda BPS Provinsi X)
                        </label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= $survey_data['keterangan'] ?>" />
                    </div>

                    <div class="mb-3">
                        <label for="kode_satker_sebelumnya">Kode Satker Sebelumnya:</label>
                        <input type="text" class="form-control" id="kode_satker_sebelumnya" name="kode_satker_sebelumnya" value="<?= $survey_data['kode_satker_sebelumnya'] ?>" />
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
                                    <label for="nilaisakip">
                                        Berapa Nilai SAKIP dari satuan kerja yang saudara/i pimpin saat ini / sebelumnya (bagi yang menjabat < 6 bulan)?
                                    </label>
                                    <br>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2021: </td>
                                    <td>
                                        <input class="form-control" id="sakip2021" name="sakip2021" type="text" 
                                            value="<?= $survey_data['sakip2021'] ?? '' ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2022: </td>
                                    <td>
                                        <input class="form-control" id="sakip2022" name="sakip2022" type="text" 
                                            value="<?= $survey_data['sakip2022'] ?? '' ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2023: </td>
                                    <td>
                                        <input class="form-control" id="sakip2023" name="sakip2023" type="text" 
                                            value="<?= $survey_data['sakip2023'] ?? '' ?>" required>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-3">
                        <table id="datatablesSimple2" class="table">
                            <thead>
                                <tr colspan="2">
                                    <label for="nilaiikpa">
                                        Berapa Nilai IKPA dari satuan kerja yang saudara/i pimpin saat ini / sebelumnya (bagi yang menjabat < 6 bulan)?
                                    </label>
                                    <br>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2021: </td>
                                    <td>
                                        <input class="form-control" id="ikpa2021" name="ikpa2021" type="text" 
                                            value="<?= $survey_data['ikpa2021'] ?? '' ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2022: </td>
                                    <td>
                                        <input class="form-control" id="ikpa2022" name="ikpa2022" type="text" 
                                            value="<?= $survey_data['ikpa2022'] ?? '' ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2023: </td>
                                    <td>
                                        <input class="form-control" id="ikpa2023" name="ikpa2023" type="text" 
                                            value="<?= $survey_data['ikpa2023'] ?? '' ?>" required>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-3">
                        <table id="datatablesSimple2" class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        Apakah Unit Kerja yang dipimpin saat ini / sebelumnya bagi yang menjabat < 6 bulan mendapat predikat WBK/WBBM? 
                                        (Baik Internal BPS RI maupun dari MENPAN RB)
                                    </td>
                                    <td>
                                        <input type="radio" name="wbkwbbm" value="ya" 
                                            <?= $survey_data['wbkwbbm'] == 'ya' ? 'checked' : '' ?> required> Ya
                                    </td>
                                    <td>
                                        <input type="radio" name="wbkwbbm" value="tidak" 
                                            <?= $survey_data['wbkwbbm'] == 'tidak' ? 'checked' : '' ?>> Tidak
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-3">
                        <label for="penghargaan_eksternal">
                            Apakah di Tahun 2021-2023 Unit Kerja ini menerima penghargaan dari Eksternal? (isikan jika ada)
                        </label>
                        <br>
                        <label for="penghargaan_eksternal1">Penghargaan 1: </label>
                        <input class="form-control" id="penghargaan_eksternal1" name="penghargaan_eksternal1" type="text" 
                            value="<?= $survey_data['penghargaan_eksternal1'] ?? '' ?>"><br>
                        <label for="penghargaan_eksternal2">Penghargaan 2: </label>
                        <input class="form-control" id="penghargaan_eksternal2" name="penghargaan_eksternal2" type="text" 
                            value="<?= $survey_data['penghargaan_eksternal2'] ?? '' ?>"><br>
                        <label for="penghargaan_eksternal3">Penghargaan 3: </label>
                        <input class="form-control" id="penghargaan_eksternal3" name="penghargaan_eksternal3" type="text" 
                            value="<?= $survey_data['penghargaan_eksternal3'] ?? '' ?>"><br>
                    </div>

                    <button class="btn btn-next btn-primary me-2 my-1" style="float: right; margin-right: 20px;" 
                            type="button" onclick="nextSection()">Lanjut</button>
                    <button class="btn btn-prev btn-secondary me-2 my-1" style="float: right; margin-right: 60px;" 
                            type="button" onclick="prevSection()">Kembali</button>
                    <br><br>
                </div>
            </div>

            <!-- Section ZI-->
            <div class="section" id="section-kepemimpinan-melayani">
                <div class="card-header">PENGUKURAN KEPEMIMPINAN MELAYANI</div>
                <div class="card-body">
                    <div class="small">
                        Skala Penilaian: (Untuk Pertanyaan pada Bagian 1,2, dan 4) <br>
                        1. Sangat Tidak Setuju <br>
                        2. Tidak Setuju <br>
                        3. Ragu-ragu <br>
                        4. Setuju <br>
                        5. Sangat Setuju <br><br><hr>
                    </div>
                    <hr>
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
                            <?php for ($i = 1; $i <= 21; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    // Pertanyaan dinamis berdasarkan indeks
                                    $questions = [
                                        1 => "Pemimpin saya memberikan dukungan emosional ketika menghadapi kesulitan.",
                                        2 => "Pemimpin saya menunjukkan empati melalui dukungan nyata.",
                                        3 => "Pemimpin saya memastikan saya mendapatkan dukungan dan sumber daya yang cukup untuk mencapai kesuksesan.",
                                        4 => "Pemimpin saya yakin bahwa saya mematuhi aturan dan nilai-nilai organisasi.",
                                        5 => "Pemimpin saya percaya bahwa saya mampu menyelesaikan pekerjaan dengan baik.",
                                        6 => "Pemimpin saya mempercayai saya untuk menyelesaikan tugas tepat waktu.",
                                        7 => "Pemimpin saya menunjukkan sikap terbuka tentang pengembangan individu untuk meningkatkan kompetensi.",
                                        8 => "Pemimpin saya sering memuji kerja keras dan dedikasi bawahannya.",
                                        9 => "Pemimpin saya selalu bersikap sederhana dan tidak arogan dalam setiap situasi.",
                                        10 => "Pemimpin saya membangun rasa memiliki, terhadap tujuan organisasi.",
                                        11 => "Pemimpin saya memberikan arahan yang jelas tentang tujuan jangka panjang organisasi.",
                                        12 => "Pemimpin saya memastikan sumber daya yang diperlukan tersedia untuk mencapai visi.",
                                        13 => "Pemimpin saya sering menunjukkan tindakan yang memprioritaskan tim daripada kepentingan dirinya sendiri.",
                                        14 => "Pemimpin saya menunjukkan perhatian yang tulus terhadap kesejahteraan saya.",
                                        15 => "Pemimpin saya mendukung saya dalam mengikuti pelatihan untuk meningkatkan kompetensi saya.",
                                        16 => "Pemimpin saya sering memberikan arahan yang jelas untuk memastikan pekerjaan saya berhasil.",
                                        17 => "Pemimpin saya memberikan motivasi untuk membantu saya melewati masa sulit.",
                                        18 => "Pemimpin saya sering berdiskusi secara langsung dengan saya untuk memahami kebutuhan.",
                                        19 => "Pemimpin saya mendorong saya untuk memiliki ide-ide baru dalam pekerjaan.",
                                        20 => "Pemimpin saya mendorong saya untuk mencoba cara baru dalam menyelesaikan pekerjaan.",
                                        21 => "Pemimpin saya memberikan motivasi yang membuat saya yakin terhadap kompetensi.",
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php
                                // Ambil nilai survey_data
                                $answer = $survey_data["q1_$i"] ?? '';
                                ?>
                                <td><input type="radio" name="q1_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q1_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q1_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q1_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q1_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                    <hr><br><br>
                    <button class="btn btn-next btn-primary me-2 my-1" style="float: right; margin-right: 20px;" type="button" onclick="nextSection()">Lanjut</button>
                    <button class="btn btn-prev btn-secondary me-2 my-1" style="float: right; margin-right: 60px;" type="button" onclick="prevSection()">Kembali</button><br><br>
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
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        1 => "Saya memiliki kualifikasi pendidikan yang relevan dengan kebutuhan satuan kerja untuk mengembangkan organisasi.",
                                        2 => "Saya memiliki pengalaman kerja yang relevan, termasuk pengalaman di sektor publik yang berkontribusi pada pengetahuan praktis dan keahlian.",
                                        3 => "Saya selalu mengambil kesempatan untuk meningkatkan keterampilan dan kompetensi sebagai bentuk investasi melalui pelatihan dan pengembangan.",
                                        4 => "Saya memiliki kemampuan untuk berinovasi dan berpikir kreatif dalam menyelesaikan masalah dan mengembangkan solusi baru.",
                                        5 => "Saya memiliki kemampuan untuk berkomunikasi secara efektif, baik secara lisan maupun tertulis, yang penting untuk kerja sama dan koordinasi.",
                                        6 => "Saya memiliki kemampuan untuk bekerja dalam tim dan berkolaborasi dengan berbagai pemangku kepentingan.",
                                        7 => "Saya memiliki motivasi dan komitmen yang tinggi terhadap pekerjaan dan organisasi.",
                                        8 => "Saya memiliki kemampuan kepemimpinan dan manajemen dalam melakukan pengawasan atau manajerial.",
                                        9 => "Saya memiliki sistem penilaian kinerja yang efektif yang selalu memberikan umpan balik konstruktif kepada Pegawai untuk membantu mereka tumbuh dan berkembang.",
                                        10 => "Saya selalu mendorong keterlibatan Pegawai sebagai hasil dari pengakuan dan penghargaan atas kontribusi mereka."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q2_$i"] ?? ''; ?>
                                <td><input type="radio" name="q2_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q2_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q2_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q2_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q2_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 11; $i <= 15; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        11 => "Saya sudah dan selalu mengimplementasikan sistem manajemen pengetahuan untuk mendokumentasikan, menyimpan, dan membagikan pengetahuan ke pegawai.",
                                        12 => "Saya sudah membangun dan selalu meningkatkan kualitas dan efektivitas proses internal, seperti prosedur pengadaan barang dan jasa, manajemen proyek survei dan sensus.",
                                        13 => "Saya selalu mendorong seluruh pegawai untuk berinovasi dan belajar dari pengalaman untuk meningkatkan kinerja baik aspek teknis, administratif dan organisasi.",
                                        14 => "Saya sudah membangun dan selalu mengembangkan sistem proses organisasi, infrastruktur teknologi informasi, dan komunikasi yang mendukung operasi organisasi satuan kerja.",
                                        15 => "Saya selalu berhasil beradaptasi dengan perubahan lingkungan dan memiliki fleksibilitas yang tinggi dalam struktur dan proses organisasi."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q2_$i"] ?? ''; ?>
                                <td><input type="radio" name="q2_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q2_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q2_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q2_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q2_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table><hr><br><br>

                    <h4>C. Membangun Modal Relasional (Relational Capital)</h4><hr>
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
                            <?php for ($i = 16; $i <= 20; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        16 => "Saya mampu membangun dan meningkatkan kualitas hubungan dengan para stakeholder, termasuk masyarakat, sektor swasta, dan organisasi lainnya.",
                                        17 => "Saya selalu berusaha untuk meningkatkan kepercayaan publik terhadap organisasi pemerintah.",
                                        18 => "Selama menjadi kepala satker, Saya berhasil meningkatkan dan memperkuat jaringan kerjasama dengan organisasi lain, termasuk sesama pemerintah, sektor swasta, dan organisasi non-profit dibanding pimpinan satker terdahulu.",
                                        19 => "Saya memiliki keterampilan dalam menegosiasikan dan menyelesaikan konflik dengan pemangku kepentingan, yang penting untuk menjaga hubungan yang harmonis.",
                                        20 => "Satuan kerja yang saya pimpin selalu terlibat dalam dialog dan aktivitas yang mendukung kepentingan masyarakat."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q2_$i"] ?? ''; ?>
                                <td><input type="radio" name="q2_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q2_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q2_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q2_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q2_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 1; $i <= 3; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        1 => "Saya secara rutin mengidentifikasi kebutuhan masyarakat untuk meningkatkan pelayanan.",
                                        2 => "Saya selalu memberikan solusi yang cepat dan dapat diandalkan kepada masyarakat.",
                                        3 => "Saya secara konsisten mengevaluasi dan meningkatkan proses pelayanan."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q3_$i"] ?? ''; ?>
                                <td><input type="radio" name="q3_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 4; $i <= 6; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        4 => "Saya selalu bekerja dengan jujur dan bertanggung jawab dalam setiap tugas.",
                                        5 => "Saya memastikan penggunaan barang milik negara selalu efisien dan bertanggung jawab.",
                                        6 => "Saya sangat memperhatikan batasan kewenangan dalam setiap keputusan."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q3_$i"] ?? ''; ?>
                                <td><input type="radio" name="q3_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 7; $i <= 9; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        7 => "Saya rutin mengikuti pelatihan untuk meningkatkan kompetensi.",
                                        8 => "Saya sering membantu rekan kerja untuk memahami tugas mereka dengan lebih baik.",
                                        9 => "Saya selalu memastikan tugas diselesaikan dengan kualitas terbaik."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q3_$i"] ?? ''; ?>
                                <td><input type="radio" name="q3_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 10; $i <= 12; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        10 => "Saya selalu menghormati perbedaan latar belakang dalam tim.",
                                        11 => "Saya sering mengambil inisiatif untuk membantu rekan kerja yang membutuhkan.",
                                        12 => "Saya berupaya menciptakan suasana kerja yang nyaman dan produktif."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q3_$i"] ?? ''; ?>
                                <td><input type="radio" name="q3_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 13; $i <= 15; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        13 => "Saya selalu menjalankan tugas sesuai dengan nilai-nilai Pancasila.",
                                        14 => "Saya selalu menjaga nama baik instansi dan rekan kerja Saya.",
                                        15 => "Saya sangat menjaga kerahasiaan terkait jabatan dan negara."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q3_$i"] ?? ''; ?>
                                <td><input type="radio" name="q3_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 16; $i <= 18; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        16 => "Saya cepat beradaptasi dengan kebijakan baru di lingkungan kerja.",
                                        17 => "Saya secara aktif mencari cara inovatif untuk menyelesaikan masalah.",
                                        18 => "Saya selalu bertindak proaktif dalam mengidentifikasi masalah yang mungkin muncul."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q3_$i"] ?? ''; ?>
                                <td><input type="radio" name="q3_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 19; $i <= 21; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        19 => "Saya selalu memberikan kesempatan kepada semua pihak untuk berkontribusi.",
                                        20 => "Saya sangat terbuka dalam berbagi informasi untuk mendukung kolaborasi.",
                                        21 => "Saya secara efektif memanfaatkan berbagai sumber daya untuk tujuan bersama."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q3_$i"] ?? ''; ?>
                                <td><input type="radio" name="q3_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q3_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        1 => "Saya merasa bangga menjadi bagian dari BPS.",
                                        2 => "Lingkungan kerja saya mendukung kolaborasi dan produktivitas.",
                                        3 => "Saya merasa nyaman dengan peran dan tanggung jawab saya di BPS.",
                                        4 => "Nilai-nilai pribadi saya sangat sesuai dengan nilai-nilai BPS.",
                                        5 => "Saya merasa setia kepada BPS dalam segala situasi."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q4_$i"] ?? ''; ?>
                                <td><input type="radio" name="q4_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q4_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q4_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q4_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q4_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 6; $i <= 10; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        6 => "Saya merasa meninggalkan BPS ini akan merugikan karier saya.",
                                        7 => "Waktu dan usaha yang saya habiskan di BPS ini memberikan nilai yang signifikan.",
                                        8 => "Keterampilan saya sangat sesuai dengan kebutuhan BPS.",
                                        9 => "Program pensiun di BPS menjadi faktor penting dalam keputusan saya untuk tetap tinggal.",
                                        10 => "Saya merasa bahwa meninggalkan BPS akan mengakibatkan kerugian finansial bagi saya."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q4_$i"] ?? ''; ?>
                                <td><input type="radio" name="q4_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q4_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q4_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q4_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q4_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 11; $i <= 15; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        11 => "Saya merasa memiliki tanggung jawab moral untuk tetap berkontribusi di organisasi ini.",
                                        12 => "Saya merasa norma sosial dan budaya organisasi ini mendukung loyalitas karyawan.",
                                        13 => "Saya merasa dihargai atas kontribusi yang saya berikan di organisasi ini.",
                                        14 => "Proses pengenalan nilai-nilai organisasi membantu saya merasa terintegrasi.",
                                        15 => "Saya merasa memiliki kewajiban moral untuk tetap bekerja di organisasi ini."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q4_$i"] ?? ''; ?>
                                <td><input type="radio" name="q4_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q4_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q4_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q4_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q4_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 1; $i <= 3; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        1 => "Saya pastikan pemahaman terhadap tugas dan kewenangan dalam tugas teknis survei dan sensus, administrasi dan pengelolaan organisasi sudah optimal.",
                                        2 => "Saya pastikan implementasi pekerjaan teknis survei dan sensus, administrasi dan organisasi sudah optimal.",
                                        3 => "Saya konsisten berupaya mencapai target yang sudah direncanakan dalam kualitas dan kuantitas pekerjaan."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q5_$i"] ?? ''; ?>
                                <td><input type="radio" name="q5_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 4; $i <= 6; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        4 => "Saya selalu berbagi pengalaman baik dan membantu satker lain mencapai tujuan organisasi.",
                                        5 => "Saya sudah optimal membangun harmoni dengan pihak internal dan eksternal dalam pelaksanaan tugas.",
                                        6 => "Saya selalu berinisiatif membantu rekan kerja tanpa diminta."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q5_$i"] ?? ''; ?>
                                <td><input type="radio" name="q5_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 7; $i <= 9; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        7 => "Saya selalu membuat arahan lisan dan instruksi tertulis yang efektif ditindaklanjuti semua pegawai.",
                                        8 => "Saya selalu menyampaikan informasi dengan sederhana, jelas, dan mudah dipahami dan efektif.",
                                        9 => "Saya memperhatikan informasi yang berkembang dan segera merespon secara terukur urgensinya."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q5_$i"] ?? ''; ?>
                                <td><input type="radio" name="q5_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 10; $i <= 12; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        10 => "Saya berupaya menyelesaikan tugas meskipun dalam kondisi keterbatasan sumberdaya dan berbagai hambatan.",
                                        11 => "Dalam kondisi apapun, target yang sudah disepakati diupayakan tercapai bahkan bisa melampaui target semula.",
                                        12 => "Saya meyakini bahwa masalah dapat diselesaikan dengan, pro aktif, keterbukaan dan kolaborasi."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q5_$i"] ?? ''; ?>
                                <td><input type="radio" name="q5_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table><hr><br><br>

                    <h4>E. Pemeliharaan Disiplin Pribadi/Maintenance personal discipline</h4><hr>
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
                            <?php for ($i = 13; $i <= 15; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        13 => "Saya sebagai pimpinan konsisten mengikuti peraturan dan kebijakan organisasi tanpa terkecuali.",
                                        14 => "Saya konsisten menjaga waktu penyelesaian tugas.",
                                        15 => "Saya konsisten secara pribadi dan menjaga seluruh pegawai agar taat atas kebijakan dan peraturan yang berlaku."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q5_$i"] ?? ''; ?>
                                <td><input type="radio" name="q5_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 16; $i <= 18; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        16 => "Saya menginstruksikan kerja kolaboratif antar tim dan antar Satker untuk mencapai target organisasi.",
                                        17 => "Saya mampu menyelesaikan konflik dalam tim dengan cara yang konstruktif.",
                                        18 => "Saya mendorong kolaborasi antar tim bahkan lintas satker."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q5_$i"] ?? ''; ?>
                                <td><input type="radio" name="q5_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 19; $i <= 21; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        19 => "Saya selalu memberikan arahan yang jelas dan mudah dipahami oleh bawahan.",
                                        20 => "Saya memberikan umpan balik yang konstruktif dan membangun kepada bawahan.",
                                        21 => "Saya selalu memotivasi staf untuk tetap bersemangat dan fokus pada tujuan pekerjaan."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q5_$i"] ?? ''; ?>
                                <td><input type="radio" name="q5_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
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
                            <?php for ($i = 22; $i <= 24; $i++): ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <?php
                                    $questions = [
                                        22 => "Saya mampu menyusun rencana kerja yang sistematis sesuai dengan prioritas organisasi.",
                                        23 => "Saya membuat sistem pemantauan, penggunaan dan evaluasi sumberdaya manusia, anggaran dengan efisien.",
                                        24 => "Saya memastikan setiap pelaporan administrasi dilakukan dengan akurat, akuntabel dan tepat waktu."
                                    ];
                                    echo $questions[$i];
                                    ?>
                                </td>
                                <?php $answer = $survey_data["q5_$i"] ?? ''; ?>
                                <td><input type="radio" name="q5_<?= $i ?>" value="1" <?= $answer == 1 ? 'checked' : '' ?> required></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="2" <?= $answer == 2 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="3" <?= $answer == 3 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="4" <?= $answer == 4 ? 'checked' : '' ?>></td>
                                <td><input type="radio" name="q5_<?= $i ?>" value="5" <?= $answer == 5 ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table><hr><br><br>

   
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