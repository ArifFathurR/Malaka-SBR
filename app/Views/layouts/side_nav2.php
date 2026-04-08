<div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sidenav shadow-right sidenav-light">
                    <div class="sidenav-menu">
                        <div class="nav accordion" id="accordionSidenav">
                            
                            <!-- Sidenav Menu Heading (Menu Utama)-->
                            <div class="sidenav-menu-heading">Menu Privileges</div>
                            <?php if (session('id_role') == 1): ?>
                                <!-- Sidenav Link (Dashboard)-->
                                <a class="nav-link" href="<?= base_url('usaha/dashboard') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="activity"></i></div>
                                    Dashboard
                                </a>
                                
                                <?php if (session('idkab') == 1400): ?>
                                <a class="nav-link" href="<?= base_url('usaha/daily_updates') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="activity"></i></div>
                                        Capaian 1400
                                </a>
                                <?php endif; ?>
                                
                                <a class="nav-link" href="<?= base_url('usaha/belum-malaka') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="activity"></i></div>
                                    Belum Diupdate
                                </a>
                                
                                <?php if (session('idkab') != 1400): ?>
                                    <a class="nav-link" href="<?= base_url('lkm/export') ?>">
                                        <div class="nav-link-icon">
                                            <i data-feather="activity"></i></div>
                                        Export Data LKM
                                    </a>
                                    
                                    <a class="nav-link" href="<?= base_url('usaha/export-data') ?>">
                                        <div class="nav-link-icon">
                                            <i data-feather="activity"></i></div>
                                            Export Usaha Malaka
                                    </a>
                                <?php endif; ?>
                                
                            <?php endif; ?>
                            
                            
                            <!-- Sidenav Link (Dashboard)-->
                            <a class="nav-link" href="<?= base_url('usaha') ?>">
                                <div class="nav-link-icon">
                                    <i data-feather="repeat"></i></div>
                                Update Data
                            </a>
                            <a class="nav-link" href="<?= base_url('lokasi') ?>">
                                <div class="nav-link-icon">
                                    <i data-feather="repeat"></i></div>
                                Peta Lokasi Usaha
                            </a>
                            <!-- Sidenav Link (Dashboard)-->
                            <a class="nav-link" href="<?= base_url('pencarian') ?>">
                                <div class="nav-link-icon">
                                    <i data-feather="search"></i></div>
                                    Pencarian
                            </a>
                            
                            <a class="nav-link" href="<?= base_url('usaha/report_user') ?>">
                                <div class="nav-link-icon">
                                    <i data-feather="award"></i></div>
                                Progres Saya
                            </a>
                            
                            <a class="nav-link" href="<?= base_url('usaha/panduan') ?>">
                                <div class="nav-link-icon">
                                    <i data-feather="compass"></i></div>
                                Panduan Pengisian
                            </a>
                            
                            <?php if (session('id_user') == '199003242012111001'): ?>
                                <!-- Sidenav Link (Dashboard)-->
                                <a class="nav-link" href="<?= base_url('siri/import_user') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="activity"></i></div>
                                        Kelola Pengguna
                                </a>
                                

                            <?php endif; ?>
                            
                                <!-- Sidenav Link (Dashboard)-->
                                <br>
                                <hr>
                                <div class="sidenav-menu-heading">Indepth Study</div>
                                
                                <a class="nav-link" href="<?= base_url('usaha/laborkabkot_geo') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="repeat"></i>
                                    </div>
                                    Input Geotagging P
                                </a>
                                
                                <a class="nav-link" href="<?= base_url('usaha/laborkabkot') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="repeat"></i>
                                    </div>
                                    Update Status Prelist L
                                </a>
                                
                                <br><hr><br>
                                
                                <a class="nav-link" href="<?= base_url('usaha/labor_geo_rekap') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="repeat"></i>
                                    </div>
                                    Progres Geotagging P
                                </a>
                                
                                <a class="nav-link" href="<?= base_url('usaha/labor_rekap') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="repeat"></i>
                                    </div>
                                    Progres Dokumen Prelist L
                                </a>
                                
                            
                            <?php if (session('id_user') == '199003242012111001'): ?>
                                <br>
                                <div class="sidenav-menu-heading">Indepth Study</div>
                                <a class="nav-link" href="<?= base_url('usaha/lab-dropdown') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="repeat"></i>
                                    </div>
                                    Door to Door 
                                </a>
                                
                                <a class="nav-link" href="<?= base_url('usaha/kakilima-dropdown') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="repeat"></i>
                                    </div>
                                    Keliling/Kaki lima
                                </a>
                                
                            <?php endif; ?>
                            
                            
                            <?php if (session('idkab') == 1400): ?>
                                <br>
                                <?= view_cell('\App\Cells\SidebarCell::pointsBox') ?>
                            <?php endif; ?>
                            
                            
                            <?php if (session('id_user') == '199003242012111001'): ?>
                            <br>
                            <div class="sidenav-menu-heading">Menu Laporan</div>
                            <!-- Sidenav Link (Dashboard)-->
                                <a class="nav-link" href="<?= base_url('pelaporan') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="activity"></i></div>
                                        Input Laporan Kegiatan
                                </a>
                                
                                <a class="nav-link" href="<?= base_url('pelaporan/laporan-saya') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="compass"></i></div>
                                        Laporan Saya
                                </a>
                                <hr>
                                
                                
                                <a class="nav-link" href="<?= base_url('lkm') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="repeat"></i>
                                    </div>
                                    Input Data LKM
                                </a>
                            
                                <a class="nav-link" href="<?= base_url('lkm/saya') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="award"></i>
                                    </div>
                                    Data LKM Saya
                                </a>
                                <?php endif; ?>

                            
                            
                            <!-- Sidenav Link Nilai-->
                            <!--
                            <a class="nav-link" href="">
                                <div class="nav-link-icon">
                                    <i data-feather="activity"></i>
                                </div>
                                Report
                            </a>
                            -->

                        </div>
                    </div>
                    <!-- Sidenav Footer-->
                    <div class="sidenav-footer">
                        <div class="sidenav-footer-content">
                            <div class="sidenav-footer-subtitle">Login sebagai:</div>
                            <div class="sidenav-footer-title"><?= session('nama_user'); ?> </div>
                        </div>
                    </div>
                </nav>
            </div>