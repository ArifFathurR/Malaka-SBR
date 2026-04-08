<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
            <!-- Sidenav Toggle Button-->
            <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i data-feather="menu"></i></button>
            <!-- Navbar Brand-->
            <!-- * * Tip * * You can use text or an image for your navbar brand.-->
            <!-- * * * * * * When using an image, we recommend the SVG format.-->
            <!-- * * * * * * Dimensions: Maximum height: 32px, maximum width: 240px-->
            <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="<?= base_url('sbr/gc') ?>">MALAKA</a>
            <!-- Navbar Search Input-->
           
            <!-- Navbar Items-->
            <ul class="navbar-nav align-items-center ms-auto">
                <div class="fw-500 user-name-wrapper" style="margin-right: 10px;">
                    Hai, <?= session('nama_user'); ?>!
                </div>

                <?php
                    $id_user = session('id_user'); // pastikan ini sesuai nama session-mu
                    $foto_path = 'public/file/fotouser/' . $id_user . '.jpg';
                    $default_avatar = 'public/file/usericon.png';

                    $avatar_url = file_exists(FCPATH . $foto_path) ? base_url($foto_path) : base_url($default_avatar);
                ?>
                
                <!-- User Dropdown -->
                <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
                    <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="img-fluid" src="<?= $avatar_url ?>" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                        <a class="dropdown-item" href="<?= base_url('do/logout'); ?>">
                            <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                            Logout
                        </a>
                    </div>
                </li>

            </ul>
        </nav>

<style>

    .user-name-wrapper {
        max-width: 120px;          
        white-space: nowrap;        
        overflow: hidden;          
        text-overflow: ellipsis;   
        display: inline-block;      
        vertical-align: middle;     
    }
    
    @media (max-width: 768px) {
        .user-name-wrapper {
            max-width: 90px;
        }
    }
</style>
