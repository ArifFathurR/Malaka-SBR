<div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sidenav shadow-right sidenav-light">
                    <div class="sidenav-menu">
                        <div class="nav accordion" id="accordionSidenav">
                            
                            
                                <br><br>
                                <a class="nav-link" href="<?= base_url('sbr/gc') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="repeat"></i>
                                    </div>
                                    Groundcheck SBR
                                </a>
                                
                                <?php if (session('id_role') == 1): ?>
                                <!-- Sidenav Link (Dashboard)-->
                                <a class="nav-link" href="<?= base_url('sbr/gcexport') ?>">
                                    <div class="nav-link-icon">
                                        <i data-feather="activity"></i></div>
                                    Export Hasil GC
                                </a>
                                
                            <?php endif; ?>
                                

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