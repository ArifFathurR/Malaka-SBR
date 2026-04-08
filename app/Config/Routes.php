<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Homelogin::index');
//$routes->get('home', 'SiriController::beranda');
//$routes->get('/dashboard', 'Dashboard::index');

//$routes->get('/profile', 'Profile::index');

$routes->get('/homelogin', 'Homelogin::index'); 
$routes->get('/homelogin/mitra', 'Homelogin::mitra'); 
$routes->get('/homelogin/pegawai_nonsso', 'Homelogin::pegawai_nonsso'); 
$routes->get('/homelogin/register', 'Homelogin::register');
$routes->post('do/register', 'Homelogin::do_register');

$routes->get('sso/login', 'SSOController::login');
$routes->get('sso/callback', 'SSOController::callback');
$routes->get('do/logout', 'SiriController::logout');
$routes->post('do/valid_login', 'SiriController::valid_login');
$routes->get('do', 'SiriController::index');
$routes->get('do/beranda', 'SiriController::beranda');
$routes->get('do/beranda_pemantau', 'SiriController::beranda_pemantau');

$routes->get('usaha', 'SiriController::updateUsaha');
$routes->get('usaha/getAllKabupaten', 'SiriController::getAllKabupatenJson');
$routes->get('usaha/getKecamatan/(:segment)', 'SiriController::getKecamatan/$1');

$routes->get('usaha/report_user', 'SiriController::activity');
$routes->get('usaha/report_user/(:segment)', 'SiriController::activity/$1');
$routes->post('report/deleteUsaha', 'SiriController::deleteUsaha');
$routes->post('report/update', 'SiriController::update_report_user');

$routes->get('usaha/getDesa/(:segment)', 'SiriController::getDesa/$1');
$routes->post('usaha/update', 'SiriController::update');
$routes->get('usaha/getSLS/(:segment)', 'SiriController::getSLS/$1');
$routes->post('usaha/insertUsaha', 'SiriController::insertUsaha');
$routes->get('usaha/dashboard', 'SiriController::dashboardUsaha');
$routes->get('usaha/maps/(:segment)', 'SiriController::maps/$1');
$routes->post('usaha/updateKoordinat/(:segment)', 'SiriController::updateKoordinat/$1');
$routes->get('usaha/panduan', 'SiriController::panduanPengisian');

$routes->post('siri/import_siri_user_proses', 'SiriController::import_siri_user_proses');
$routes->get('siri/import_user', 'SiriController::import_user');

$routes->get('usaha/export-data', 'SiriController::exportUsaha');
$routes->get('usaha/belum-malaka', 'SiriController::exportBelumMalaka');


$routes->get('pencarian', 'SiriController::pencarian');
$routes->post('pencarian/cari', 'SiriController::cariEstablishment');
$routes->post('pencarian/kecamatan', 'SiriController::getKecamatanbyKabkot');
$routes->post('pencarian/desa', 'SiriController::getDesaByKecamatan');

// Halaman detail dashboard
$routes->get('usaha/detail_update_kabupaten', 'SiriController::detail_update_kabupaten');
$routes->get('usaha/detail_user_updater', 'SiriController::detail_user_updater');
$routes->get('usaha/detail_update_desa', 'SiriController::detail_update_desa');
$routes->post('usaha/deleteUsaha', 'SiriController::deleteUsaha');

// Pelaporan ke Sipantau
$routes->get('pelaporan', 'Pelaporan::index');
$routes->post('pelaporan/simpan', 'Pelaporan::simpan');
$routes->get('pelaporan/laporan-saya', 'Pelaporan::dataLaporanSaya');
$routes->get('laporan/sipantau', 'LaporanSipantauController::index');
$routes->post('/pelaporan/hapus', 'Pelaporan::hapus');

$routes->get('lkm', 'LkmController::index');
$routes->get('lkm/get', 'LkmController::get');                   // prefill array
$routes->get('lkm/desa/(:segment)', 'LkmController::desa/$1');   // daftar desa
$routes->post('lkm/simpan', 'LkmController::simpan');            // upsert multi-segmen
$routes->get('lkm/saya', 'LkmController::saya');
$routes->get('lkm/export', 'LkmController::exportLkm');

$routes->get('usaha/daily_updates', 'SiriController::daily_updates');


// Data wilayah dinamis (AJAX)
$routes->get('wilayah/kecamatan', 'Wilayah::kecamatan');
$routes->get('wilayah/desa', 'Wilayah::desa');

// route lab dropdown (GET sudah ada)
$routes->get('usaha/lab-dropdown', 'SiriController::labDropdown');
$routes->get('usaha/geojson-list/(:segment)', 'SiriController::geojsonList/$1');
$routes->post('usaha/checkGeojson', 'SiriController::checkGeojson');
$routes->post('usaha/insertUsahaLab', 'SiriController::insertUsahaLab');

$routes->get('usaha/mapView', 'SiriController::mapView');            // halaman map (buka di tab baru)
$routes->post('usaha/updateCoordinates', 'SiriController::updateCoordinates');
$routes->post('usaha/update-minimal', 'SiriController::updateUsahaMinimal');

$routes->get('usaha/get-sls-by-desa/(:segment)', 'SiriController::getSlsByDesa/$1');

$routes->get('usaha/kakilima-dropdown', 'SiriController::kakilimaDropdown');
$routes->post('usaha/insertKakilima', 'SiriController::insertKakilima');
$routes->post('usaha/updateKakilima', 'SiriController::updateKakilima');

$routes->get('laboratorium', 'Laboratorium::index');
$routes->get('laboratorium/getGeojsonFiles/(:num)', 'Laboratorium::getGeojsonFiles/$1');
$routes->get('laboratorium/getUsaha/(:num)', 'Laboratorium::getUsaha/$1');
$routes->get('laboratorium/getUsaha/(:any)', 'Laboratorium::getUsaha/$1');
$routes->get('laboratorium/debugMapPaths', 'Laboratorium::debugMapPaths'); // optional

$routes->get('usaha/export-establishment-excel-indepth', 'SiriController::exportEstablishmentExcel_indepth');

// --- Fitur Labor Kab/Kota ---
$routes->get('usaha/laborkabkot', 'SiriController::laborkabkot');
$routes->post('usaha/updatelabor', 'SiriController::updateStatusLabor');

$routes->get('/usaha/laborkabkot_geo', 'SiriController::laborkabkot_geo');
$routes->post('/usaha/laborInsert', 'SiriController::tambah_laborkabkot');
$routes->post('/usaha/laborUpdate', 'SiriController::edit_laborkabkot');
$routes->post('usaha/deleteLabor', 'SiriController::delete_laborkabkot');
$routes->get('/usaha/labor_geo_rekap', 'SiriController::laborRekapGeo');
$routes->get('/usaha/labor_rekap', 'SiriController::laborRekap');

$routes->get('usaha/getPolygon', 'SiriController::getPolygon');
$routes->post('usaha/getPolygon', 'SiriController::getPolygon');

// Halaman GC (cari ID SBR + modal update)
// $routes->get('sbr/gc', 'SbrGC::index');
// $routes->post('sbr/gc/update', 'SbrGC::update');
// $routes->put('sbr/gc/(:num)', 'SbrGC::update/$1');
// $routes->get('sbr/gcexport', 'SbrGC::gcexport');
// $routes->get('sbr/export', 'SbrGC::export');

$routes->get('sbr/gc/create',              'SbrGC::create');
$routes->post('sbr/gc/store',              'SbrGC::store');
$routes->get('sbr/gc/tabledata',           'SbrGC::tabledata');
$routes->get('sbr/gc/keclist',             'SbrGC::keclist');
$routes->get('sbr/gc/keclistbykab/(:segment)', 'SbrGC::keclistByKab/$1');
$routes->get('sbr/gc/desalist/(:num)',     'SbrGC::desalist/$1');
$routes->get('sbr/gc',                     'SbrGC::index');
$routes->put('sbr/gc/(:num)',              'SbrGC::update/$1');
$routes->post('sbr/gc/update',             'SbrGC::update');
$routes->get('sbr/gcexport',               'SbrGC::gcexport');
$routes->get('sbr/export',                 'SbrGC::export');

$routes->get('/', 'HomeController::index');
//$routes->get('dashboard', 'Dashboard::index');

