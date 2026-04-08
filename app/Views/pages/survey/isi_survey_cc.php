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
    
    .video-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .video-grid .video-item {
        flex: 1 1 calc(33.333% - 16px); /* 3 kolom */
        max-width: calc(33.333% - 16px);
        box-sizing: border-box;
    }
    
    .video-grid iframe {
        width: 100%;
        height: auto;
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
                    <div class="page-header-subtitle">VOTING CALON CHANGE CHAMPION BPS PROVINSI RIAU</div>
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


 <div class="container-xl px-4 mt-n10">
    <div class="card">
        <div class="card-header">Calon Change Champion BPS Provinsi Riau 2025</div>
        <div class="card-body">
            <form id="kuesionerForm" action="<?= base_url('survey/proses_input_cc') ?>" method="post" onsubmit="return confirmSubmission()"> 
                <div class="row">
                    <div class="video-grid d-flex justify-content-center flex-wrap gap-4">
                        <div class="card mx-2">
                            <div class="card-header text-center">Afdi Rizal, SST, MT</div>
                            <div class="card-body">
                                <img src="<?= base_url('public/file/fotocc/afdi.jpg') ?>" frameborder="0" allowfullscreen style="width: 100%; height: 250px;"></img>
                            </div>
                        </div>
                        
                        <div class="card mx-2">
                            <div class="card-header text-center">Desi Damaiyanti, SST, M.E.</div>
                            <div class="card-body">
                                <img src="<?= base_url('public/file/fotocc/desi.jpg') ?>" frameborder="0" allowfullscreen style="width: 100%; height: 250px;"></img>
                            </div>
                        </div>
                        
                        <div class="card mx-2">
                            <div class="card-header text-center">Etania Ranu Andhika, SST, M.E., M.Sc.</div>
                            <div class="card-body">
                                <img src="<?= base_url('public/file/fotocc/etania.jpg') ?>" frameborder="0" allowfullscreen style="width: 100%; height: 250px;"></img>
                            </div>
                        </div>
                        
                        <div class="card mx-2">
                            <div class="card-header text-center">Ferdian Fadly, SST., M.Stat.</div>
                            <div class="card-body">
                                <img src="<?= base_url('public/file/fotocc/ferdian.jpg') ?>" frameborder="0" allowfullscreen style="width: 100%; height: 250px;"></img>
                            </div>
                        </div>
                        
                        <div class="card mx-2">
                            <div class="card-header text-center">Sri Mulyani, SST, M.Stat</div>
                            <div class="card-body">
                                <img src="<?= base_url('public/file/fotocc/srimulyani.jpg') ?>" frameborder="0" allowfullscreen style="width: 100%; height: 250px;"></img>
                            </div>
                        </div>
                        
                        <div class="card mx-2">
                            <div class="card-header text-center">Yandika Welra, SST</div>
                            <div class="card-body">
                                <img src="<?= base_url('public/file/fotocc/yandika.jpg') ?>" frameborder="0" allowfullscreen style="width: 100%; height: 250px;"></img>
                            </div>
                        </div>

                    </div>  
                </div>
                <br><br><hr>
                <div class="mb-3">
                    <label for="nama"><b>Saya memilih :</b></label><br>
                    <select class="form-control" name="pilihan_cc" id="pilihan_cc" required>
                        <option value="">-Pilih-</option>
                        <option value="Afdi Rizal, SST, M.T">Afdi Rizal, SST, M.T</option>
                        <option value="Desi Damaiyanti, SST, M.E.">Desi Damaiyanti, SST, M.E.</option>
                        <option value="Etania Ranu Andhika, SST, M.E., M.Sc.">Etania Ranu Andhika, SST, M.E., M.Sc.</option>
                        <option value="Ferdian Fadly, SST., M.Stat.">Ferdian Fadly, SST., M.Stat.</option>
                        <option value="Sri Mulyani, SST, M.Stat">Sri Mulyani, SST, M.Stat</option>
                        <option value="Yandika Welra, SST">Yandika Welra, SST</option>
                    </select>
                </div>
                
                <label for="komentar">Komentar (optional):</label>
                <textarea class="form-control" id="komentar" name="komentar" rows="3"></textarea><br>
                
                <div style="display: flex; justify-content: center; align-items: center; margin-top: 20px;">
                    <button class="btn btn-success me-2 my-1" type="submit">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmSubmission() {
        return confirm("Anda yakin akan mengirim?");
    }
</script>

<?= $this->endSection() ?>