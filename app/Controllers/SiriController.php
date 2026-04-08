<?php

namespace App\Controllers;
ob_start();

use App\Models\Sirilogin_model;
use App\Models\Siri_model;
use App\Models\EstablishmentModel;
use App\Models\EstablishmentLaborModel;
use App\Models\EstablishmentGCModel;


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");

date_default_timezone_set('Asia/Jakarta');

class SiriController extends BaseController
{
    protected $siriloginModel;
    protected $siriModel;
    protected $establishmentModel;
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->siriloginModel = new Sirilogin_model();
        $this->siriModel = new Siri_model();
        $this->establishmentModel = new EstablishmentModel();
        $this->est = new EstablishmentLaborModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        
        $this->estModel = new EstablishmentModel();
        $this->establishmentGCModel = new EstablishmentGCModel();

    }

    public function beranda()
    {
        // Cek apakah user sudah login
        if (!session()->has('id_user')) {
            return redirect()->to('/'); // Arahkan ke halaman login atau home
        }

        return view('siripages/dashboard_list');
    }

    public function maps($placeId)
    {
        $model = new EstablishmentModel();
        $usaha = $model->find($placeId);
        if (!$usaha) {
            return redirect()->to('usaha')->with('error', 'Usaha tidak ditemukan');
        }

        return view('siripages/maps', ['usaha' => $usaha]);
    }
    
    //Groundcheck
    public function gc_sbr()
    {
        // Cek Login
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        // 1. Ambil Input ID SBR
        $idsbr = $this->request->getGet('idsbr');
        
        // 2. Ambil Session User (Untuk keamanan)
        $sessionKab = session('idkab');

        $dataUsaha = [];

        // 3. Jika input terisi, cari data
        if ($idsbr) {
            $dataUsaha = $this->establishmentGCModel->searchByIdSbr($idsbr, $sessionKab);
        }

        $data = [
            'idsbr' => $idsbr,
            'usaha' => $dataUsaha
        ];

        return view('siripages/gcview', $data);
    }
    
    public function gc_sbrx()
    {
        $q = trim((string) $this->request->getGet('q'));
        $idkab = (int) (session('idkab') ?? 0);
    
        $model = new \App\Models\EstablishmentGCModel();
    
        $rows = [];
        $row  = null;
    
        if ($q !== '') {
            $rows = $model->searchUsahaGC($q, $idkab);
    
            // kalau cuma 1 hasil, tampilkan detail
            if (count($rows) === 1) {
                $row = $rows[0];
            }
        }
    
        return view('sbr/gc_view', [
            'q'    => $q,
            'rows' => $rows,
            'row'  => $row,
        ]);
    }


    public function updateKoordinat($placeId)
    {
        $model = new EstablishmentModel();

        $data = [
            'placeLatitude' => $this->request->getPost('latitude'),
            'placeLongitude' => $this->request->getPost('longitude'),
        ];

        $model->update($placeId, $data);

        // Ambil parameter wilayah
        $kab = $this->request->getPost('kabupaten');
        $kec = $this->request->getPost('kecamatan');
        $desa = $this->request->getPost('desa');

        // Redirect dengan parameter
        return redirect()->to(base_url("usaha?kabupaten=$kab&kecamatan=$kec&desa=$desa"))
                        ->with('success', 'Koordinat berhasil diperbarui.');
    }



    // ================================================================================================================================================

    public function valid_login()
    {
        $session = \Config\Services::session();
        $datas = $this->request->getPost();

        $username = $datas['username'] ?? '';
        $password = $datas['password'] ?? '';

        // Gunakan model yang sudah kamu siapkan
        $this->siriloginModel = new \App\Models\Sirilogin_model();

        $user = $this->siriloginModel->cekLogin($username, $password);

        if (!$user) {
            $session->setFlashdata('message', 'SOBAT ID atau Password salah');
            return redirect()->to(base_url('homelogin/mitra'));

        }

        // Jika valid, lanjut set session
        $this->siriModel = new \App\Models\Siri_model();
        $satker = $this->siriModel->ambilsatker($user['idkab'])->getRow();

        $session->set([
            'isLogin'   => true,
            'id_user'   => $user['id_siri_user'],
            'nama_user' => $user['nama_user'],
            'id_role'   => $user['id_role'],
            'idkab'     => $user['idkab'],
            'satker'    => $satker ? $satker->nmkab : '',
            'adminkab'  => $user['is_admin'] ?? 0,
        ]);

        if ($user['id_role'] == '3') {
            return redirect()->to('sbr/gc');
        } elseif ($user['id_role'] == '5') {
            return redirect()->to('sbr/gc');
        } else {
            return redirect()->to('sbr/gc');
        }
    }

    // Fungsi logout
    public function logout()
    {
        // Hapus semua session
        session()->destroy();

        // Redirect ke halaman login
        return redirect()->to('/');
    }

// ================================================================================================================================================

    public function updateUsaha()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
    
        $model = new EstablishmentModel();
        
    
        $idkab_session = session()->get('idkab');
        $idkab = $this->request->getGet('kabupaten') ?? $idkab_session;
        $idkec = $this->request->getGet('kecamatan');
        $iddesa = $this->request->getGet('desa');
        $idsls = $this->request->getGet('sls');
        $kdsls   = $idsls && $idsls !== '999' ? substr($idsls, -4) : null;
        $keyword = $this->request->getGet('keyword') ?? '';
        
        // Redirect hanya jika: user tidak mengisi SLS, tetapi juga tidak menggunakan keyword pencarian
        if ($idkab && $idkec && $iddesa && !$idsls && !$keyword) {
            return redirect()->to(base_url('usaha'));
        }

    
        $usaha = [];
        
        if ($idkab && $idkec && $iddesa && ($idsls || $keyword)) {

            $usahaQuery = $model->where('idkab', $idkab);
        
            if ($idkec) {
                $usahaQuery->where('iddesa LIKE', $idkec . '%');
            }
        
            if ($iddesa) {
                $usahaQuery->where('iddesa', $iddesa);
            }
        
            if ($idsls === '999') {
                // Jika Penambahan Baru dipilih
                $usahaQuery->where('baru', 1);
            } elseif ($kdsls && $kdsls !== '999') {
                $usahaQuery->where('kdsls', $kdsls);
            }
        
            if ($keyword) {
                $usahaQuery->groupStart()
                    ->like('placeName', $keyword)
                    ->orLike('placeAddress', $keyword)
                ->groupEnd();
            }
        
            $usaha = $usahaQuery->findAll();
        }
    
        $kabupaten = $idkab_session == '1400' ? $model->db->table('master_kab')->get()->getResultArray() : [];
        $kecamatan = $model->getKecamatan($idkab);
        $desa = $idkec ? $model->getDesa($idkec) : [];
        $sls = $iddesa ? $model->getSLS($iddesa) : [];

    
        // Ambil data SLS jika sudah pilih desa
        
    
        $statusLabels = [
            1  => '1. Aktif',
            2  => '2. Tidak Aktif',
            3  => '3. Bukan Usaha',
            4  => '4. Tidak Ditemukan',
        ];
    
        $kategoriLabels = [
            'A' => 'A. Pertanian, Kehutanan, dan Perikanan',
            'B' => 'B. Pertambangan dan Penggalian',
            'C' => 'C. Industri Pengolahan',
            'D' => 'D. Pengadaan Listrik dan Gas',
            'E' => 'E. Pengadaan Air, Pengelolaan Sampah, Limbah dan Daur Ulang',
            'F' => 'F. Konstruksi',
            'G' => 'G. Perdagangan Besar dan Eceran; Reparasi Mobil dan Sepeda Motor',
            'H' => 'H. Transportasi dan Pergudangan',
            'I' => 'I. Penyediaan Akomodasi dan Makan Minum',
            'J' => 'J. Informasi dan Komunikasi',
            'K' => 'K. Jasa Keuangan dan Asuransi',
            'L' => 'L. Real Estate',
            'M' => 'M. Aktivitas Profesional, Ilmiah, dan Teknis',
            'N' => 'N. Aktivitas Penyewaan dan Sewa Guna Usaha Tanpa Opsi',
            'O' => 'O. Administrasi Pemerintahan, Pertahanan dan Jaminan Sosial Wajib',
            'P' => 'P. Jasa Pendidikan',
            'Q' => 'Q. Jasa Kesehatan dan Kegiatan Sosial',
            'R' => 'R. Kesenian, Hiburan, dan Rekreasi',
            'S' => 'S. Aktivitas Jasa Lainnya',
            'T' => 'T. Aktivitas Sebagai Pemberi Kerja, dll',
            'U' => 'U. Aktivitas Badan Internasional Lainnya',
        ];
    
        $creators = [];
        foreach ($usaha as $u) {
            $creator = $model->getFirstHistoryUser($u['placeId']);
            $creators[$u['placeId']] = $creator;
        }
    
        //$peta = $this->request->getGet('peta');
        $viewData = [
            'usaha' => $usaha,
            'kabupaten' => $kabupaten,
            'kecamatan' => $kecamatan,
            'desa' => $desa,
            'sls' => $sls,
            'selected_kabupaten' => $idkab,
            'selected_kecamatan' => $idkec,
            'selected_desa' => $iddesa,
            'selected_sls' => $idsls,
            'keyword' => $keyword,
            'kategoriLabels' => $kategoriLabels,
            'statusLabels' => $statusLabels,
            'creators' => $creators
        ];
        
        return view('siripages/tabelusaha', $viewData);

    }


    public function getKecamatan($idkab)
    {
        $model = new EstablishmentModel();
        return json_encode($model->getKecamatan($idkab));
    }

    public function getDesa($idkec)
    {
        $model = new EstablishmentModel();
        return json_encode($model->getDesa($idkec));
    }
    
    public function getSLS($iddesa)
    {
        $model = new EstablishmentModel();
        return json_encode($model->getSLS($iddesa));
    }
    
    public function update_report_user()
    {
        if (! $this->request->getMethod() === 'post') {
            return redirect()->back();
        }

        $model = new EstablishmentModel();

        $post = $this->request->getPost();
        $placeId = $post['placeId'] ?? null;
        if (!$placeId) return redirect()->back()->with('error', 'placeId tidak ditemukan.');

        $placeName = $post['placeName'] ?? '';
        $placeAddress = $post['placeAddress'] ?? '';
        $status_usaha = $post['status_usaha'] ?? null;
        $kategori = $post['kategori'] ?? null;

        $userId = session('id_user');
        $userName = session('nama_user') ?? 'Unknown';

        // update establishment
        $data = [
            'placeName' => $placeName,
            'placeAddress' => $placeAddress,
            'status_usaha' => $status_usaha,
            'kategori' => $kategori,
            'last_update_by' => date('Y-m-d H:i:s') . ' oleh ' . $userName
        ];

        $model->updateEstablishment($placeId, $data);

        // insert history
        $history = [
            'placeId' => $placeId,
            'id_siri_user' => $userId,
            'waktu_input' => date('Y-m-d H:i:s'),
            'status_usaha' => $status_usaha,
            'kategori' => $kategori
        ];
        $model->insertHistory($history);

        return redirect()->back()->with('success', 'Data usaha berhasil diupdate.');
    }


    public function update()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        
        $model = new EstablishmentModel();

        $placeId = $this->request->getPost('placeId');
        $status = $this->request->getPost('status_usaha');
        $kategori = $this->request->getPost('kategori');
        $idUser = session()->get('id_user'); 
        
        $placeName = $this->request->getPost('placeName');
        $placeAddress = $this->request->getPost('placeAddress');

        $namaUser = session()->get('nama_user'); 
        $waktu = date('Y-m-d H:i:s');

        $peta   = esc($this->request->getPost('peta'));
        $keyword = $this->request->getGet('keyword') ?? ''; 
        
        $model->insertHistory([
            'placeId' => $placeId,
            'id_siri_user' => $idUser,
            'waktu_input' => $waktu,
            'status_usaha' => $status,
            'kategori' => $kategori,
            'placeName' => $placeName,      
            'placeAddress' => $placeAddress  
        ]);
        
        $model->update($placeId, [
            'placeName' => $placeName,
            'placeAddress' => $placeAddress,
            'status_usaha' => $status,
            'kategori' => $kategori,
            'last_update_by' => "$waktu oleh $namaUser"
        ]);


        // Ambil lokasi usaha yang sedang diupdate
        $usaha  = $model->find($placeId);
        $idkab  = $usaha['idkab'];
        $iddesa = $usaha['iddesa'];
        $kdsls  = $usaha['kdsls'] ?? null;
        $idkec  = substr($iddesa, 0, 7);
        
        // Penentuan idsls: jika kdsls tidak ada, gunakan 999
        $idsls  = ($kdsls !== null && $kdsls !== '') ? $iddesa . $kdsls : '999';

        $params = "kabupaten=$idkab&kecamatan=$idkec&desa=$iddesa";

        if ($keyword) {
            $params .= '&sls=&keyword=' . urlencode($keyword);
        } else {
            $params .= '&sls=' . $idsls;
        }
        
        if ($peta === '1') {
            $params .= '&peta=1';
        }
        
        return redirect()->to(base_url('usaha?' . $params))
            ->with('success', 'Status usaha berhasil diperbarui.');

    }

    public function insertUsaha()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        $model = new EstablishmentModel();

        $nama     = $this->request->getPost('placeName');
        $alamat   = $this->request->getPost('placeAddress');
        $iddesa   = $this->request->getPost('iddesa');
        $kategori = $this->request->getPost('kategori');
        $lat      = $this->request->getPost('latitude');
        $lng      = $this->request->getPost('longitude');
        
        $userId = session()->get('id_user');
        $waktu = date('Y-m-d H:i:s');

        $peta   = esc($this->request->getPost('peta'));

        $idkec = substr($iddesa, 0, 7);
        $idkab = substr($iddesa, 0, 4);

        $placeId = 'plc_' . time() . '_' . bin2hex(random_bytes(4)); // ID unik

        // Ambil nama kab/kec/desa
        $wilayah = $model->getWilayahDetail($iddesa);

        $data = [
            'placeId'         => $placeId,
            'placeName'       => $nama,
            'placeAddress'    => $alamat,
            'idprov'           => '14',
            'idkab'           => $idkab,
            'idkec'           => $idkec,
            'iddesa'          => $iddesa,
            'nmprov'          => 'RIAU',
            'nmkab'           => $wilayah->nmkab ?? '',
            'nmkec'           => $wilayah->nmkec ?? '',
            'nmdesa'          => $wilayah->nmdesa ?? '',
            'placeLatitude'   => $lat,
            'placeLongitude'  => $lng,
            'status_usaha'    => 1,
            'kategori'        => $kategori,
            'last_update_by'  => date('Y-m-d H:i:s') . ' oleh ' . session()->get('nama_user'),
            'baru'            => 1
        ];

        $model->insertUsaha($data);
        
        // ✅ Insert ke table history
        $model->insertHistory([
            'placeId'        => $placeId,
            'id_siri_user'   => $userId,
            'waktu_input'    => $waktu,
            'status_usaha'   => 1,
            'kategori'       => $kategori
        ]);

        
        if ($peta == '1') {
            return redirect()->to(base_url("usaha?kabupaten=$idkab&kecamatan=$idkec&desa=$iddesa&sls=999&peta=1"))
                ->with('success', 'Status usaha berhasil ditambahkan.');
        } else {
            return redirect()->to(base_url("usaha?kabupaten=$idkab&kecamatan=$idkec&desa=$iddesa&sls=999"))
                ->with('success', 'Status usaha berhasil ditambahkan.');
        }
        
    }


    public function deleteUsaha()
    {
        $placeId = $this->request->getPost('placeId');
        $model = new EstablishmentModel();
        $db = \Config\Database::connect();
    
        $currentUserId = session()->get('id_user');
        $creator = $model->getFirstHistoryUser($placeId);
    
        if ($creator && $creator->id_siri_user == $currentUserId) {
            $db->table('establishment_history')->where('placeId', $placeId)->delete();
            $model->delete($placeId);
            return redirect()->back()->with('success', 'Data usaha dan histori berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus data ini.');
        }
    }


    public function dashboardUsaha()
    {
        // Cek role
        if (session('id_role') != 1) {
            return redirect()->to(base_url('usaha'));
        }

        $model = new EstablishmentModel();

        $totalUsaha      = $model->getTotalEstablishment();
        $usahaUpdated    = $model->getUpdatedEstablishment();
        $distinctUsers   = $model->getDistinctUsers();
        $updatedByUser   = $model->getUpdateCountPerUser();
        $desaUpdated     = $model->getUpdatedDesaCount();
        $totalDesa       = $model->getTotalDesa();
        $latestUpdates   = $model->getLatestUpdates();
        $updatePerKab    = $model->getUpdatePerKab();
        $statusCounts    = $model->getUpdateCountByStatus();

        $jumlah_update   = $usahaUpdated;
        
        $statusRingkasan = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0
        ];
        
        foreach ($statusCounts as $row) {
            $kode = (int)$row['status_usaha'];
            $statusRingkasan[$kode] = (int)$row['jumlah'];
        }


        // Persentase
        $persentase_usaha = $totalUsaha > 0 ? round(($usahaUpdated / $totalUsaha) * 100, 1) : 0;
        $persentase_desa  = $totalDesa > 0 ? round(($desaUpdated / $totalDesa) * 100, 1) : 0;

        // Label untuk status dan kategori
        $statusLabels = [
            1  => '1. Aktif',
            2  => '2. Tidak Aktif',
            3  => '3. Bukan Usaha',
            4  => '4. Tidak Ditemukan',
        ];

        $kategoriLabels = [
            'A' => 'A. Pertanian, Kehutanan, dan Perikanan',
            'B' => 'B. Pertambangan dan Penggalian',
            'C' => 'C. Industri Pengolahan',
            'D' => 'D. Pengadaan Listrik dan Gas',
            'E' => 'E. Pengadaan Air, Pengelolaan Sampah, Limbah dan Daur Ulang',
            'F' => 'F. Konstruksi',
            'G' => 'G. Perdagangan Besar dan Eceran; Reparasi Mobil dan Sepeda Motor',
            'H' => 'H. Transportasi dan Pergudangan',
            'I' => 'I. Penyediaan Akomodasi dan Makan Minum',
            'J' => 'J. Informasi dan Komunikasi',
            'K' => 'K. Jasa Keuangan dan Asuransi',
            'L' => 'L. Real Estate',
            'M' => 'M. Aktivitas Profesional, Ilmiah, dan Teknis',
            'N' => 'N. Aktivitas Penyewaan dan Sewa Guna Usaha Tanpa Opsi',
            'O' => 'O. Administrasi Pemerintahan, Pertahanan dan Jaminan Sosial Wajib',
            'P' => 'P. Jasa Pendidikan',
            'Q' => 'Q. Jasa Kesehatan dan Kegiatan Sosial',
            'R' => 'R. Kesenian, Hiburan, dan Rekreasi',
            'S' => 'S. Aktivitas Jasa Lainnya',
            'T' => 'T. Aktivitas Sebagai Pemberi Kerja, dll',
            'U' => 'U. Aktivitas Badan Internasional Lainnya',
        ];

        // Format data terbaru
        foreach ($latestUpdates as &$item) {
            $statusKey   = $item['status_usaha'] ?? null;
            $kategoriKey = $item['kategori'] ?? null;
        
            // Status tetap mengikuti label yang tersedia
            $item['status_text'] = $statusLabels[$statusKey] ?? 'Belum diupdate';
        
            // Kategori: Jika status tidak sama dengan 1 namun sudah diisi, tampilkan "-"
            if (!empty($statusKey) && $statusKey != 1) {
                $item['kategori_text'] = '-';
            } else {
                $item['kategori_text'] = $kategoriLabels[$kategoriKey] ?? 'Belum diupdate';
            }
        }

        unset($item);

        // Persiapan data untuk Chart
        $chart_labels  = array_column($updatePerKab, 'nmkab');
        $chart_total   = array_column($updatePerKab, 'total');
        $chart_updated = array_column($updatePerKab, 'updated');
        $chart_baru = array_column($updatePerKab, 'baru'); 
        

        return view('siripages/dashboard_establishment', [
            'persentase_usaha'  => $persentase_usaha,
            'usaha_updated'     => $usahaUpdated,
            'total_usaha'       => $totalUsaha,
            'jumlah_user'       => count($distinctUsers),
            'persentase_desa'   => $persentase_desa,
            'jumlah_update'     => $jumlah_update,
            'updatePerKab'      => $updatePerKab,
            'latestUpdates'     => $latestUpdates,
            'chart_labels'      => $chart_labels,
            'chart_total'       => $chart_total,
            'chart_updated'     => $chart_updated,
            'chart_baru'        => $chart_baru,
            'statusRingkasan'   => $statusRingkasan,

        ]);
    }
    
    public function panduanPengisian()
    {
        return view('siripages/panduan');
    }


    // Halaman detail usaha per kabupaten (untuk card biru dan merah)
    public function detail_update_kabupaten()
    {
        $model = new EstablishmentModel();
        $data = $model->getUpdatePerKabupaten();
    
        return view('siripages/detail_table', [
            'title' => 'Detail Update Usaha per Kabupaten',
            'columns' => [
                'ID Kab', 'Nama Kabupaten',
                'Total Usaha (Awal)', 'Sudah Update',
                'Penambahan Baru',
                'Aktif', 'Tidak Aktif', 'Bukan Usaha', 'Tidak Ditemukan',
                'Persentase Update (%)'
            ],
            'rows' => $data
        ]);
    }

    // Halaman detail user yang mengupdate (untuk card hijau)
    public function detail_user_updater()
    {
        $model = new EstablishmentModel();
    
        // Ambil idkab dari session user
        $user_idkab = session('idkab');
    
        // Ambil data dari model
        $data = $model->getUserUpdatePerKab($user_idkab);
    
        // Format ulang data untuk ditampilkan di tabel
        $rows = [];
        $i = 1;
        foreach ($data as $row) {
            $rows[] = [
                'no' => $i++,
                'idkab' => $row['idkab'],
                'nmkab' => $row['nmkab'],
                'nama_user' => $row['nama_user'],
                'jumlah_update' => $row['jumlah_update'],                         // Usaha lama yang dimutakhirkan
                'dimutakhirkan_aktif' => $row['dimutakhirkan_aktif'],             // status_usaha = 1 AND baru IS NULL
                'dimutakhirkan_tidak_aktif' => $row['dimutakhirkan_tidak_aktif'], // status_usaha = 2 AND baru IS NULL
                'dimutakhirkan_bukan_usaha' => $row['dimutakhirkan_bukan_usaha'], // status_usaha = 3 AND baru IS NULL
                'tidak_ditemukan' => $row['tidak_ditemukan'],                    // status_usaha = 4 AND baru IS NULL
                'jumlah_baru' => $row['jumlah_baru'],                             // Usaha baru
                'total' => $row['total']
            ];
        }
    
        return view('siripages/detail_table', [
            'title' => 'Rekap Jumlah Update per User per Kabupaten',
            'columns' => [
                'No',
                'ID Kab',
                'Kabupaten/Kota',
                'Nama User',
                'Jumlah Usaha Dimutakhirkan',
                'Dimutakhirkan Aktif',
                'Dimutakhirkan Tidak Aktif',
                'Dimutakhirkan Bukan Usaha',
                'Tidak Ditemukan',
                'Jumlah Usaha Baru',
                'Total Update'
            ],
            'rows' => $rows
        ]);
    }


    // Halaman detail desa update (untuk card kuning)
    public function detail_update_desa()
    {
        $model = new EstablishmentModel();
        $session_idkab = session('idkab');
    
        // Kirim session_idkab ke model untuk filtering
        $data = $model->getUpdatePerDesa($session_idkab);
    
        return view('siripages/detail_table', [
            'title' => 'Detail Update per Desa',
            'columns' => [
                'Kode Desa',
                'Nama Desa',
                'Usaha Awal',
                'Usaha Awal Dimutakhirkan',
                'Usaha Baru',
                'Persentase Pengerjaan (%)'
            ],
            'rows' => $data
        ]);
    }
    
    public function report_user()
    {
        $model = new EstablishmentModel();
        $id_user = session('nama_user'); // atau sesuaikan dengan identitas user Anda
    
        //$summary = $model->getSummaryByUser('198311082007011004');
        //$report = $model->getReportByUser('198311082007011004');
        
        $summary = $model->getSummaryByUser($id_user);
        $report = $model->getReportByUser($id_user);
    
        $statusLabels = [
            1 => '1. Aktif',
            2 => '2. Tidak Aktif',
            3 => '3. Bukan Usaha',
            4 => '4. Tidak Ditemukan',
        ];
    
        return view('siripages/report_user', [
            'summary' => $summary,
            'report' => $report,
            'statusLabels' => $statusLabels,
            'user' => $id_user
        ]);
    }
    
    public function activity($user = null)
    {
        // pastikan ada nama user (bisa di-passing atau diambil dari session)
        $user = $user ?? session('nama_user') ?? '';

        $model = new EstablishmentModel();

        // ambil report (establishment yang pernah diupdate oleh $user)
        $report = $model->getReportByUserName($user);

        // ambil semua placeId untuk batch get creators
        $placeIds = array_filter(array_map(function($r){ return $r['placeId'] ?? null; }, $report));
        $creators = [];
        if (!empty($placeIds)) {
            $creators = $model->getFirstHistoryUsers($placeIds);
        }

        // build summary
        $summary = [
            'total_update'     => count($report),
            'existing_update'  => 0,
            'baru_update'      => 0
        ];
        foreach ($report as $r) {
            if (isset($r['baru']) && intval($r['baru']) === 1) $summary['baru_update']++;
            else $summary['existing_update']++;
        }

        // status labels for view
        $statusLabels = [
            1  => '1. Aktif',
            2  => '2. Tidak Aktif',
            3  => '3. Bukan Usaha',
            4  => '4. Tidak Ditemukan',
        ];

        $kategoriLabels = [ /* jika dibutuhkan, isi sama seperti view Anda */ ];

        return view('siripages/report_user', [
            'report' => $report,
            'creators' => $creators,
            'summary' => $summary,
            'user' => $user,
            'statusLabels' => $statusLabels,
            'kategoriLabels' => $kategoriLabels
        ]);
    }
    
    public function import_user()
    {
        $id_user = session('id_user');
        
        if ($id_user != '199003242012111001') {
            return redirect()->to(base_url('usaha'));
        }
    
        return view('siripages/import_user', [
            'user' => $id_user
        ]);
    }
    
    public function import_siri_user_proses()
    {
        $file = $this->request->getFile('file_csv');
    
        if ($file && $file->isValid() && $file->getExtension() === 'csv') {
            $handle = fopen($file->getTempName(), 'r');
    
            $model = new \App\Models\SiriUserModel();
            $inserted = 0;
            $skipped = 0;
    
            // Tidak perlu fgetcsv() pertama jika file tidak mengandung header
            while (($row = fgetcsv($handle, 1000, ';')) !== false) {
                // Validasi: harus ada 12 kolom
                if (count($row) < 13) {
                    continue;
                }
    
                $id_siri_user = trim($row[0]);
    
                // Skip jika user sudah ada
                if ($model->find($id_siri_user)) {
                    $skipped++;
                    continue;
                }
    
                // Buat array data untuk disimpan
                $data = [
                    'id_siri_user'   => $id_siri_user,
                    'idkab'          => trim($row[1]),
                    'nama_user'      => trim($row[2]),
                    'email'          => trim($row[3]),
                    'hp'             => trim($row[4]),
                    'jabatan'        => trim($row[5]),
                    'nama_instansi'  => trim($row[6]),
                    'tingkat_satker' => trim($row[7]),
                    'linkfoto'       => trim($row[8]),
                    'is_active'      => trim($row[9]),
                    'is_admin'       => trim($row[10]),
                    'id_role'        => trim($row[11]),
                    'password'       => trim($row[12]),
                ];
    
                $model->insert($data);
                $inserted++;
            }
    
            fclose($handle);
    
            return redirect()->back()->with('success', "Import selesai. Ditambahkan: $inserted, Dilewati (sudah ada): $skipped");
        }
    
        return redirect()->back()->with('error', 'File tidak valid atau tidak berhasil diunggah.');
    }
    
    public function pencarian()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
    
        $model = new EstablishmentModel();
        $idkab = session('idkab');
    
        $data['idkab'] = $idkab;
    
        if ($idkab == '1400') {
            $data['kabupaten'] = $model->getAllKabupaten();
            $data['kecamatan'] = []; // Akan dinamis lewat JS
        } else {
            $data['kecamatan'] = $model->getKecamatanByKabupaten($idkab);
        }
    
        $data['desa'] = $model->getDesaByKecamatan($data['kecamatan'][0]['idkec'] ?? null); // atau bisa ambil desa dari kecamatan pertama
        
    
        // Tambahkan kategori usaha
        $data['kategoriLabels'] = [
            'A' => 'A. Pertanian, Kehutanan, dan Perikanan',
            'B' => 'B. Pertambangan dan Penggalian',
            'C' => 'C. Industri Pengolahan',
            'D' => 'D. Pengadaan Listrik dan Gas',
            'E' => 'E. Pengadaan Air, Pengelolaan Sampah, Limbah dan Daur Ulang',
            'F' => 'F. Konstruksi',
            'G' => 'G. Perdagangan Besar dan Eceran; Reparasi Mobil dan Sepeda Motor',
            'H' => 'H. Transportasi dan Pergudangan',
            'I' => 'I. Penyediaan Akomodasi dan Makan Minum',
            'J' => 'J. Informasi dan Komunikasi',
            'K' => 'K. Jasa Keuangan dan Asuransi',
            'L' => 'L. Real Estate',
            'M' => 'M. Aktivitas Profesional, Ilmiah, dan Teknis',
            'N' => 'N. Aktivitas Penyewaan dan Sewa Guna Usaha Tanpa Opsi',
            'O' => 'O. Administrasi Pemerintahan, Pertahanan dan Jaminan Sosial Wajib',
            'P' => 'P. Jasa Pendidikan',
            'Q' => 'Q. Jasa Kesehatan dan Kegiatan Sosial',
            'R' => 'R. Kesenian, Hiburan, dan Rekreasi',
            'S' => 'S. Aktivitas Jasa Lainnya',
            'T' => 'T. Aktivitas Sebagai Pemberi Kerja, dll',
            'U' => 'U. Aktivitas Badan Internasional Lainnya',
        ];
    
        return view('siripages/pencarian_view', $data);
    }

    
    public function cariEstablishment()
    {
        $idkab = $this->request->getPost('idkab');
        $idkec = $this->request->getPost('idkec');
        $iddesa = $this->request->getPost('iddesa'); // tambahan
        $keyword = $this->request->getPost('keyword');
    
        $model = new EstablishmentModel();
        $results = $model->searchByKeyword($idkab, $idkec, $iddesa, $keyword); // tambahan iddesa
    
        if (count($results) > 50) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Hasil lebih dari 50, gunakan keyword yang lebih spesifik.'
            ]);
        }
    
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $results
        ]);
    }

    
    public function getKecamatanbyKabkot()
    {
        $idkab = $this->request->getPost('idkab');
        $model = new \App\Models\EstablishmentModel();
        $kecamatan = $model->getKecamatanByKabupaten($idkab);
    
        if ($kecamatan) {
            return $this->response->setJSON([
                'status' => 'success',
                'kecamatan' => $kecamatan
            ]);
        }
    
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Kecamatan tidak ditemukan.'
        ]);
    }

    public function getDesaByKecamatan()
    {
        $idkec = $this->request->getPost('idkec');
        $model = new EstablishmentModel();
    
        $desa = $model->getDesaByKecamatan($idkec);
    
        if ($desa) {
            return $this->response->setJSON([
                'status' => 'success',
                'desa' => $desa
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data desa tidak ditemukan.'
            ]);
        }
    }

    public function exportUsaha()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
    
        $idkab = session('idkab');
        $model = new \App\Models\EstablishmentModel();
        $data = $model->getExportData($idkab);
    
        $filename = 'exportMalaka_' . date('Ymd_His') . '.xls';
    
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
    
        echo '<table border="1">';
        echo '<tr>
                <th>IDSLS</th>
                <th>Nama Usaha</th>
                <th>Alamat</th>
                <th>Koordinat</th>
                <th>Status</th>
                <th>Kategori</th>
                <th>Flag Baru</th>
              </tr>';
    
        foreach ($data as $item) {
            $koordinat = $item['placeLatitude'] . ', ' . $item['placeLongitude'];
            $baru = $item['baru'] ?? ''; // jika kolom kosong atau null
            $idsls = ($item['kdsls']) ? $item['iddesa'] . $item['kdsls'] : $item['iddesa'];
    
            echo '<tr>';
            echo '<td style="mso-number-format:\'@\';">' . $idsls . '</td>';
            echo '<td>' . $item['placeName'] . '</td>';
            echo '<td>' . $item['placeAddress'] . '</td>';
            echo '<td>' . $koordinat . '</td>';
            echo '<td>' . $item['status'] . '</td>';
            echo '<td>' . $item['kategori'] . '</td>';
            echo '<td>' . $baru . '</td>';
            echo '</tr>';
        }
    
        echo '</table>';
        exit();
    }
    
    public function exportBelumMalaka()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        
        // Cek role
        if (session('id_role') != 1) {
            return redirect()->to(base_url('usaha'));
        }

        $idkab   = (string) (session('idkab') ?? '1400');
        $model   = new EstablishmentModel();
        $data    = $model->getBelumMalakaExport($idkab);
        $filename = 'export_belum_malaka_' . date('dmY_His') . '.xls';

        // header excel html
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        // helper escape
        $h = static function ($v) {
            return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
        };

        echo '<meta charset="UTF-8">';
        echo '<table border="1">';
        echo '<tr>
                <th>ID Kab</th>
                <th>ID Kec</th>
                <th>Nama Kec</th>
                <th>ID Desa</th>
                <th>Nama Desa</th>
                <th>Nama SLS</th>
                <th>Nama Establishment</th>
                <th>Alamat</th>
              </tr>';

        foreach ($data as $row) {
            echo '<tr>';
            // kolom kode dipaksa teks supaya 0 depan tidak hilang di Excel
            echo '<td style="mso-number-format:\'@\';">' . $h($row['idkab'] ?? '') . '</td>';
            echo '<td style="mso-number-format:\'@\';">' . $h($row['idkec'] ?? '') . '</td>';
            echo '<td>' . $h($row['nmkec'] ?? '') . '</td>';
            echo '<td style="mso-number-format:\'@\';">' . $h($row['iddesa'] ?? '') . '</td>';
            echo '<td>' . $h($row['nmdesa'] ?? '') . '</td>';
            echo '<td>' . $h($row['nmsls'] ?? '') . '</td>';
            echo '<td>' . $h($row['placeName'] ?? '') . '</td>';
            echo '<td>' . $h($row['placeAddress'] ?? '') . '</td>';
            echo '</tr>';
        }

        echo '</table>';
        exit();
    }
    
    public function daily_updates()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        
        $req   = $this->request;
        $start = $req->getGet('start') ?: '2025-08-26';
        $end   = $req->getGet('end')   ?: '2025-11-30';
        $idkab = (int) ($req->getGet('idkab') ?? 1400);

        // Validasi sederhana
        $startTs = strtotime($start);
        $endTs   = strtotime($end);
        if ($startTs === false || $endTs === false) {
            $startTs = strtotime('2025-08-26');
            $endTs   = strtotime('2025-11-30');
            $start   = date('Y-m-d', $startTs);
            $end     = date('Y-m-d', $endTs);
        }
        if ($startTs > $endTs) {
            $tmp     = $start; $start = $end; $end = $tmp;
            $startTs = strtotime($start);
            $endTs   = strtotime($end);
        }

        // Daftar tanggal sebagai header dinamis
        $dates = [];
        for ($d = $startTs; $d <= $endTs; $d = strtotime('+1 day', $d)) {
            $dates[] = date('Y-m-d', $d);
        }

        // Ambil agregat per user per tanggal dari model
        $model   = new EstablishmentModel();
        $rowsRaw = $model->getUserDailyCounts($start, $end, $idkab);
        // rowsRaw: [id_siri_user, nama_user, d(YYYY-MM-DD|null), cnt]

        // Bentuk pivot dan daftar user
        $pivot = [];
        $users = [];

        foreach ($rowsRaw as $r) {
            $uid = $r['id_siri_user'];
            $nm  = $r['nama_user'];
            $day = $r['d']; // bisa NULL
            $cnt = (int) ($r['cnt'] ?? 0);

            if (!isset($pivot[$uid])) {
                // inisialisasi semua tanggal 0
                $pivot[$uid] = array_fill_keys($dates, 0);
            }
            if (!isset($users[$uid])) {
                $users[$uid] = $nm;
            }
            if ($day !== null && isset($pivot[$uid][$day])) {
                $pivot[$uid][$day] = $cnt;
            }
        }

        // Pastikan semua user idkab muncul walau 0 total
        $allUsers = $this->getAllUsersByIdkab($idkab);
        foreach ($allUsers as $u) {
            $uid = $u['id_siri_user'];
            if (!isset($pivot[$uid])) {
                $pivot[$uid] = array_fill_keys($dates, 0);
            }
            $users[$uid] = $u['nama_user'];
        }

        // Header kolom: id_siri_user, Nama, tanggal2, Total
        $dateHeaders = array();
        foreach ($dates as $d) {
            $dateHeaders[] = date('d-m', strtotime($d));
        }
        $columns = array_merge(array('id_siri_user', 'Nama'), $dateHeaders, array('Total'));

        // Susun rows
        ksort($users); // biar rapi
        $rows = array();
        foreach ($users as $uid => $nama) {
            $row = array($uid, $nama);
            $rowTotal = 0;
            foreach ($dates as $d) {
                $v = (int) (isset($pivot[$uid][$d]) ? $pivot[$uid][$d] : 0);
                $row[] = $v;
                $rowTotal += $v;
            }
            $row[] = $rowTotal;
            $rows[] = $row;
        }

        return view('siripages/daily_updates', array(
            'title'   => 'Rekap Update Establishment per User (idkab ' . $idkab . ')',
            'columns' => $columns,
            'rows'    => $rows,
        ));
    }

    /**
     * Ambil semua user pada idkab tertentu agar user tanpa data ikut tampil.
     */
    private function getAllUsersByIdkab(int $idkab): array
    {
        $db  = db_connect();
        $sql = "SELECT id_siri_user, nama_user FROM siri_user WHERE idkab = ? ORDER BY id_siri_user";
        return $db->query($sql, array($idkab))->getResultArray();
    }
    
    public function labDropdown()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
    
        $model = new \App\Models\EstablishmentModel();
        $desaIds = ['1471040001', '1471060005'];
    
        // Ambil list desa
        try {
            $desaList = $model->getDesaByIds($desaIds);
            if (empty($desaList)) {
                $desaList = [
                    ['iddesa' => '1471040001', 'nmdesa' => 'Cinta Raja'],
                    ['iddesa' => '1471060005', 'nmdesa' => 'Kedungsari'],
                ];
            }
        } catch (\Throwable $e) {
            $desaList = [
                ['iddesa' => '1471040001', 'nmdesa' => 'Cinta Raja'],
                ['iddesa' => '1471060005', 'nmdesa' => 'Kedungsari'],
            ];
        }
    
        $selectedDesa = (string)($this->request->getGet('desa') ?? '');
        $selectedSls  = (string)($this->request->getGet('sls')  ?? 'all');
    
        $usaha = [];
        if ($selectedDesa !== '' && in_array($selectedDesa, $desaIds, true)) {
            if ($selectedSls === 'all' || $selectedSls === '') {
                $usaha = $model->getEstablishmentByDesa($selectedDesa);
            } else {
                // normalisasi ke 4 digit agar konsisten dengan penyimpanan
                $kdsls4 = str_pad(preg_replace('/\D/','', $selectedSls), 4, '0', STR_PAD_LEFT);
                $usaha  = $model->getEstablishmentByDesaAndSls($selectedDesa, $kdsls4);
            }
        }
    
        // creators mapping (opsional)
        $creators = [];
        foreach ($usaha as $u) {
            $placeId = is_array($u) ? ($u['placeId'] ?? '') : ($u->placeId ?? '');
            if ($placeId) {
                $creators[$placeId] = $model->getFirstHistoryUser($placeId);
            }
        }
    
        $googleMapsKey = 'AIzaSyAYaRBajjixtB0T1HqWEXOHbTDpJ8Tjcyc';
    
        // Jika sudah ada desa terpilih, preload opsi SLS untuk render awal
        $slsOptions = [];
        if ($selectedDesa !== '' && in_array($selectedDesa, $desaIds, true)) {
            $slsOptions = $model->getSlsOptions($selectedDesa);
        }
    
        return view('siripages/labdata', [
            'desaList'      => $desaList,
            'selectedDesa'  => $selectedDesa,
            'selectedSls'   => $selectedSls,
            'slsOptions'    => $slsOptions,
            'usaha'         => $usaha,
            'creators'      => $creators,
            'googleMapsKey' => $googleMapsKey,
        ]);
    }

    public function getSlsByDesa($iddesa)
    {
        if (!session()->has('id_user')) {
            return $this->response->setJSON([]);
        }
        $model = new \App\Models\EstablishmentModel();
        $rows  = $model->getSlsOptions($iddesa);
        return $this->response->setJSON($rows);
    }

    public function insertUsahaLab()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
    
        $model = new \App\Models\EstablishmentModel();
        $post  = $this->request->getPost();
        log_message('debug', 'insertUsahaLab POST: ' . print_r($post, true));
    
        $nama     = trim($post['placeName'] ?? '');
        $alamat   = trim($post['placeAddress'] ?? '');
        $iddesa   = trim($post['iddesa'] ?? '');
        $kategori = $post['kategori'] ?? null;
        $lat      = $post['latitude'] ?? null;
        $lng      = $post['longitude'] ?? null;
        
        // status & lokasi
        $status_usaha = (string)($post['status_usaha'] ?? '');
        $jenis_lokasi = isset($post['jenis_usaha_lokasi']) ? (string)$post['jenis_usaha_lokasi'] : '';
        $lokasilain   = trim($post['lokasilain_address'] ?? '');
        $keterangan   = trim($post['keterangan'] ?? '');
    
        // kdsls / nmsls (opsional)
        $kdsls = isset($post['kdsls']) ? trim($post['kdsls']) : null;
        $nmsls = isset($post['nmsls']) ? trim($post['nmsls']) : null;
        if ($kdsls !== null && $kdsls !== '') {
            $kdsls = str_pad(preg_replace('/\D/', '', $kdsls), 4, '0', STR_PAD_LEFT);
        }
    
        // Validasi minimal
        $missing = [];
        if ($nama === '')   $missing[] = 'Nama usaha';
        if ($alamat === '') $missing[] = 'Alamat';
        if ($iddesa === '') $missing[] = 'Desa';
        if (!is_numeric($lat) || !is_numeric($lng)) $missing[] = 'Koordinat';
        if ($status_usaha === '' || !in_array($status_usaha, ['1','2','3','4','5','6','7'], true)) {
            $missing[] = 'Status usaha';
        }
    
        $needJenis = in_array($status_usaha, ['1','7'], true);
        if ($needJenis) {
            if ($jenis_lokasi === '' || !in_array($jenis_lokasi, ['1','2','3'], true)) {
                $missing[] = 'Lokasi usaha';
            }
            if ($jenis_lokasi === '3' && $lokasilain === '') {
                $missing[] = 'Alamat lengkap usaha (lokasi lain)';
            }
        } else {
            // jika tidak diperlukan, kosongkan agar tidak tersimpan sembarang
            $jenis_lokasi = null;
            $lokasilain   = null;
        }
    
        if (!empty($missing)) {
            return redirect()->back()->withInput()->with('error', 'Field wajib: ' . implode(', ', $missing));
        }
    
        $userId = session()->get('id_user');
        $waktu  = date('Y-m-d H:i:s');
        $placeId = 'plc_' . time() . '_' . bin2hex(random_bytes(4));
    
        // ambil nama wilayah bila ada
        $wilayah = null;
        if ($iddesa !== '') {
            try {
                $wilayah = $model->getWilayahDetail($iddesa);
            } catch (\Throwable $e) { $wilayah = null; }
        }
    
        // Penambahan baru: tetap set 'baru' = 1 untuk statistik penambahan
        $isBaruFlag = 1;
    
        $data = [
            'placeId'         => $placeId,
            'placeName'       => $nama,
            'placeAddress'    => $alamat,
            'idprov'          => '14',
            'idkab'           => substr($iddesa, 0, 4),
            'idkec'           => substr($iddesa, 0, 7),
            'iddesa'          => $iddesa,
            'nmprov'          => 'RIAU',
            'nmkab'           => $wilayah->nmkab ?? '',
            'nmkec'           => $wilayah->nmkec ?? '',
            'nmdesa'          => $wilayah->nmdesa ?? '',
            'placeLatitude'   => $lat,
            'placeLongitude'  => $lng,
            'status_usaha'    => (int)$status_usaha,
            'kategori'        => $kategori,
            'kdsls'           => $kdsls,
            'nmsls'           => $nmsls,
            'jenis_usaha_lokasi' => $jenis_lokasi ? (int)$jenis_lokasi : null,
            'lokasilain_address' => $lokasilain ?: null,
            'keterangan'      => $keterangan ?: null,
            'indepth_flag'    => '1',
            'last_update_by'  => $waktu . ' oleh ' . (session()->get('nama_user') ?? ''),
            'baru'            => $isBaruFlag
        ];
    
        log_message('debug', 'insertUsahaLab insert data: ' . print_r($data, true));
    
        $model->insertUsaha($data);
    
        // histori
        $historyData = [
            'placeId'      => $placeId,
            'id_siri_user' => $userId,
            'waktu_input'  => $waktu,
            'status_usaha' => (int)$status_usaha,
            'kategori'     => $kategori
        ];
        $model->insertHistory($historyData);
    
        return redirect()->to(base_url("usaha/lab-dropdown?desa={$iddesa}"))
                         ->with('success', 'Usaha baru berhasil ditambahkan.');
    }


    // POST endpoint untuk mengecek geojson: menerima POST form data (lat,lng,iddesa,csrf)
    public function checkGeojson()
    {
        $lat = $this->request->getPost('lat');
        $lng = $this->request->getPost('lng');
        $iddesa = $this->request->getPost('iddesa');
    
        if ($lat === null || $lng === null || empty($iddesa)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing params']);
        }
    
        $lat = (float)$lat;
        $lng = (float)$lng;
        $iddesa = preg_replace('/\D/', '', (string)$iddesa);
    
        $dir = FCPATH . 'lokasi' . DIRECTORY_SEPARATOR . 'map' . DIRECTORY_SEPARATOR;
        if (!is_dir($dir)) {
            log_message('error', "checkGeojson: geojson dir not found: {$dir}");
            return $this->response->setJSON(['success' => false, 'message' => 'GeoJSON folder not found', 'path' => $dir]);
        }
    
        $files = scandir($dir);
        if (!$files) {
            log_message('error', "checkGeojson: no files in {$dir}");
            return $this->response->setJSON(['success' => false, 'message' => 'No files']);
        }
    
        // ray-casting point-in-ring
        $pointInRing = function(float $x, float $y, array $ring) {
            $inside = false;
            $n = count($ring);
            if ($n < 3) return false;
            for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
                $xi = (float)$ring[$i][0]; $yi = (float)$ring[$i][1];
                $xj = (float)$ring[$j][0]; $yj = (float)$ring[$j][1];
                $intersect = (($yi > $y) != ($yj > $y)) &&
                    ($x < ($xj - $xi) * ($y - $yi) / (($yj - $yi) ?: 1e-12) + $xi);
                if ($intersect) $inside = !$inside;
            }
            return $inside;
        };
    
        foreach ($files as $f) {
            if (in_array($f, ['.', '..'])) continue;
    
            // hanya file yang dimulai dengan iddesa dan 4 digit kdsls lalu .geojson
            if (!preg_match('/^' . preg_quote($iddesa, '/') . '(\d{4})\.geojson$/i', $f, $m)) continue;
    
            $kdsls_raw = $m[1]; // e.g. "0001"
            $kdsls = str_pad(preg_replace('/\D/','', $kdsls_raw), 4, '0', STR_PAD_LEFT);
    
            $path = $dir . $f;
            $txt = @file_get_contents($path);
            if ($txt === false) continue;
    
            $geo = json_decode($txt, true);
            if (!$geo) continue;
    
            $features = [];
            if (isset($geo['type']) && $geo['type'] === 'FeatureCollection' && isset($geo['features'])) {
                $features = $geo['features'];
            } elseif (isset($geo['type']) && $geo['type'] === 'Feature') {
                $features[] = $geo;
            } elseif (isset($geo['coordinates']) && isset($geo['type'])) {
                $features[] = ['geometry' => $geo];
            } else {
                continue;
            }
    
            foreach ($features as $feat) {
                if (!isset($feat['geometry'])) continue;
                $geom = $feat['geometry'];
                $type = $geom['type'] ?? null;
                $coords = $geom['coordinates'] ?? null;
                if (!$type || !$coords) continue;
    
                $checkPolygon = function($rings) use ($pointInRing, $lng, $lat) {
                    if (!is_array($rings) || count($rings) == 0) return false;
                    $outer = $rings[0];
                    if (!$pointInRing($lng, $lat, $outer)) return false;
                    for ($h = 1; $h < count($rings); $h++) {
                        if ($pointInRing($lng, $lat, $rings[$h])) {
                            return false;
                        }
                    }
                    return true;
                };
    
                $matched = false;
                if ($type === 'Polygon') {
                    if ($checkPolygon($coords)) $matched = true;
                } elseif ($type === 'MultiPolygon') {
                    foreach ($coords as $polygonRings) {
                        if ($checkPolygon($polygonRings)) { $matched = true; break; }
                    }
                }
    
                if ($matched) {
                    // ambil nmsls dari properties bila ada (beberapa nama var dipertimbangkan)
                    $nmsls = null;
                    if (isset($feat['properties']) && is_array($feat['properties'])) {
                        $props = $feat['properties'];
                        foreach (['nmsls','NMSLS','name','nama','Nama','NM_SLS','n_m_sls','nmsl'] as $k) {
                            if (isset($props[$k]) && $props[$k] !== '') {
                                $nmsls = (string)$props[$k];
                                break;
                            }
                        }
                    }
    
                    // jika belum ada nmsls, cari di tabel master_sls berdasarkan iddesa + kdsls
                    if (empty($nmsls)) {
                        try {
                            $db = \Config\Database::connect();
                            $row = $db->table('master_sls')
                                      ->where('iddesa', $iddesa)
                                      ->where('kdsls', $kdsls)
                                      ->get()
                                      ->getRow();
                            if ($row && isset($row->nmsls) && $row->nmsls !== '') {
                                $nmsls = (string)$row->nmsls;
                            } else {
                                // coba juga cari tanpa padding (just in case)
                                $row2 = $db->table('master_sls')
                                           ->where('iddesa', $iddesa)
                                           ->where('kdsls', ltrim($kdsls, '0'))
                                           ->get()
                                           ->getRow();
                                if ($row2 && isset($row2->nmsls) && $row2->nmsls !== '') {
                                    $nmsls = (string)$row2->nmsls;
                                }
                            }
                        } catch (\Throwable $e) {
                            log_message('error', 'checkGeojson DB lookup failed: ' . $e->getMessage());
                        }
                    }
    
                    if (empty($nmsls)) $nmsls = $kdsls; // fallback: tunjukkan kode agar tidak kosong
    
                    // debug log untuk memastikan file/kdsls/iddesa yang match
                    log_message('debug', "checkGeojson matched file={$f} iddesa={$iddesa} kdsls={$kdsls} nmsls=" . substr($nmsls,0,100));
    
                    return $this->response->setJSON([
                        'success' => true,
                        'kdsls' => $kdsls,
                        'nmsls' => $nmsls,
                        'file' => $f
                    ]);
                }
            }
        }
    
        return $this->response->setJSON(['success' => false]);
    }



    // opsional: daftar file geojson yang sesuai prefix
    public function geojsonList($prefix = null)
    {
        if (empty($prefix) || !preg_match('/^\d+$/', $prefix)) {
            return $this->response->setJSON([]);
        }

        $dir = FCPATH . 'lokasi' . DIRECTORY_SEPARATOR . 'map' . DIRECTORY_SEPARATOR;
        $result = [];
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $f) {
                if (in_array($f, ['.', '..'])) continue;
                if (stripos($f, $prefix) === 0 && preg_match('/\.geojson$/i', $f)) {
                    $result[] = $f;
                }
            }
        }
        return $this->response->setJSON($result);
    }
    
    public function getPolygon()
    {
        $iddesa = $this->request->getGet('iddesa');
        $kdsls  = $this->request->getGet('kdsls');
    
        if (empty($iddesa) || empty($kdsls)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'iddesa / kdsls kosong'
            ]);
        }
    
        // Format file geojson = {iddesa}{kdsls}.geojson
        $fileKey = $iddesa . str_pad($kdsls, 4, '0', STR_PAD_LEFT);
    
        $dir = FCPATH . 'lokasi/map/';
        if (!is_dir($dir)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Folder tidak ditemukan']);
        }
    
        $target = $fileKey . '.geojson';
        $filePath = $dir . $target;
    
        // 1) Jika file yang persis ada, langsung load
        if (file_exists($filePath)) {
            $content = json_decode(file_get_contents($filePath), true);
            return $this->response->setJSON(['success' => true, 'data' => $content]);
        }
    
        // 2) Jika file persis tidak ada → cari berdasarkan prefix iddesa
        $files = scandir($dir);
        $matched = null;
    
        foreach ($files as $f) {
            if (preg_match('/^' . $iddesa . '\d{4}\.geojson$/', $f)) {
                // ambil file pertama yang match prefix iddesa saja
                $matched = $f;
                break;
            }
        }
    
        if ($matched) {
            $content = json_decode(file_get_contents($dir . $matched), true);
            return $this->response->setJSON(['success' => true, 'data' => $content]);
        }
    
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Polygon tidak ditemukan untuk kombinasi ini'
        ]);
    }


    
    public function mapView()
    {
        // pastikan login
        if (! session()->has('id_user')) {
            return redirect()->to('/');
        }
    
        $placeId = $this->request->getGet('placeId');
        if (empty($placeId)) {
            return redirect()->back()->with('error', 'placeId tidak diberikan');
        }
    
        $model = new \App\Models\EstablishmentModel();
    
        // ambil record
        $rec = $model->where('placeId', $placeId)->first();
        if (! $rec) {
            return redirect()->back()->with('error', 'Data usaha tidak ditemukan');
        }
    
        // tentukan apakah user boleh melihat tombol Save:
        $sessionNama = (string) session('nama_user');
        $isAdmin = (string) session('id_role') === '1'; // admin role id 1
        $lastUpdateBy = (string) ($rec['last_update_by'] ?? $rec->last_update_by ?? '');
        $canSaveCoords = $isAdmin || ($sessionNama !== '' && stripos($lastUpdateBy, $sessionNama) !== false);
    
        // Google Maps key (controller or config)
        $googleMapsKey = 'AIzaSyAYaRBajjixtB0T1HqWEXOHbTDpJ8Tjcyc'; // ganti dengan key mu
    
        return view('siripages/map_edit', [
            'rec' => $rec,
            'canSaveCoords' => $canSaveCoords,
            'googleMapsKey' => $googleMapsKey
        ]);
    }
    
    public function updateCoordinates()
    {
        if (! session()->has('id_user')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated'])->setStatusCode(401);
        }
    
        $placeId = $this->request->getPost('placeId');
        $lat = $this->request->getPost('lat');
        $lng = $this->request->getPost('lng');
    
        if (empty($placeId) || $lat === null || $lng === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing parameters'])->setStatusCode(400);
        }
    
        $model = new \App\Models\EstablishmentModel();
        $rec = $model->where('placeId', $placeId)->first();
        if (! $rec) {
            return $this->response->setJSON(['success' => false, 'message' => 'Place not found'])->setStatusCode(404);
        }
    
        // permission check: admin atau last_update_by mengandung nama session
        $sessionNama = (string) session('nama_user');
        $isAdmin = (string) session('id_role') === '1';
        $last = (string) ($rec['last_update_by'] ?? $rec->last_update_by ?? '');
    
        if (! $isAdmin && ($sessionNama === '' || stripos($last, $sessionNama) === false)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied'])->setStatusCode(403);
        }
    
        // update koordinat (pastikan model punya method updateCoordinates)
        $lat = (float) $lat;
        $lng = (float) $lng;
    
        try {
            $ok = $model->updateCoordinates($placeId, $lat, $lng); // implementasi di model
            if ($ok) {
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Update failed'])->setStatusCode(500);
            }
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()])->setStatusCode(500);
        }
    }
    
    public function updateUsahaMinimal()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/'); // blokir jika tidak login
        }
    
        $post = $this->request->getPost();
    
        $placeId      = trim($post['placeId'] ?? '');
        $statusUsaha  = (string)($post['status_usaha'] ?? '');
        $namaUsaha  = (string)($post['placeName_Usaha'] ?? '');
        $placeAddress = trim($post['placeAddress'] ?? '');
        $jenisLokasi  = trim($post['upd_jenis_usaha_lokasi'] ?? ''); // radio baru
        $lokasiLain   = trim($post['lokasilain_address'] ?? '');
        $idUser       = session('id_user') ?? '-';
        $namaUser     = session('nama_user') ?? '';
    
        // --- Validasi dasar ---
        $missing = [];
        if ($placeId === '')      $missing[] = 'placeId';
        if ($placeAddress === '') $missing[] = 'Alamat';
        if ($statusUsaha === '' || !in_array($statusUsaha, ['1','2','3','4','5','6','7'], true)) {
            $missing[] = 'Status usaha (1..7)';
        }
    
        // Jika status 1/7 maka wajib pilih jenis lokasi
        if (in_array($statusUsaha, ['1','7'], true) && $jenisLokasi === '') {
            $missing[] = 'Jenis lokasi usaha';
        }
    
        // Jika jenis lokasi = 3 maka wajib isi alamat lain
        if ($jenisLokasi === '3' && $lokasiLain === '') {
            $missing[] = 'Alamat lengkap usaha (lokasi lain)';
        }
    
        if (!empty($missing)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Field wajib: ' . implode(', ', $missing));
        }
    
        $statusInt = (int)$statusUsaha;
    
        // --- Siapkan payload --- 2
        $payload = [
            'status_usaha'    => $statusInt,
            'placeName'       => $namaUsaha,
            'placeAddress'    => $placeAddress,
            'jenis_lokasi'    => $jenisLokasi !== '' ? $jenisLokasi : null,
            'lokasilain_address' => ($jenisLokasi === '3') ? $lokasiLain : null,
            'indepth_flag'       => 1,
        ];
    
        // --- Eksekusi update ---
        try {
            $model = new \App\Models\EstablishmentModel();
            $ok = $model->update($placeId, $payload);
    
            if (!$ok) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Update gagal dilakukan (data tidak ditemukan atau tidak berubah).');
            }
        } catch (\Throwable $e) {
            log_message('error', 'updateUsahaMinimal error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat update: ' . $e->getMessage());
        }
    
        // --- Redirect balik dengan konteks filter desa/SLS ---
        $desa = $this->request->getGet('desa') ?? ($post['iddesa'] ?? '');
        $sls  = $this->request->getGet('sls')  ?? ($post['kdsls'] ?? 'all');
    
        $qs = [];
        if ($desa !== '') $qs['desa'] = $desa;
        if ($sls  !== '') $qs['sls']  = $sls;
    
        $to = base_url('usaha/lab-dropdown');
        $url = $to . (!empty($qs) ? ('?' . http_build_query($qs)) : '');
    
        return redirect()
            ->to($url)
            ->with('success', 'Data usaha berhasil diperbarui.');
    }


    public function kakilimaDropdown()
    {
        if (! session()->has('id_user')) {
            return redirect()->to('/');
        }

        $model = new EstablishmentModel();

        $desaIds = ['1471040001', '1471060005'];

        try {
            $desaList = $model->getDesaByIds($desaIds);
            if (empty($desaList)) {
                $desaList = [
                    ['iddesa' => '1471040001', 'nmdesa' => 'Cinta Raja'],
                    ['iddesa' => '1471060005', 'nmdesa' => 'Kedungsari'],
                ];
            }
        } catch (\Throwable $e) {
            log_message('error','kakilimaDropdown getDesaByIds failed: '.$e->getMessage());
            $desaList = [
                ['iddesa' => '1471040001', 'nmdesa' => 'Cinta Raja'],
                ['iddesa' => '1471060005', 'nmdesa' => 'Kedungsari'],
            ];
        }

        $selectedDesa = (string)($this->request->getGet('desa') ?? '');
        $selectedSls  = (string)($this->request->getGet('sls') ?? 'all');

        $usaha = [];
        if ($selectedDesa !== '' && in_array($selectedDesa, $desaIds, true)) {
            if ($selectedSls === 'all' || $selectedSls === '') {
                $usaha = $model->getKakilimaByDesa($selectedDesa);
            } else {
                $usaha = $model->getKakilimaByDesaAndSls($selectedDesa, $selectedSls);
            }
        }

        $slsOptions = [];
        if ($selectedDesa !== '' && in_array($selectedDesa, $desaIds, true)) {
            $slsOptions = $model->getSlsOptions($selectedDesa);
        }

        // Ambil creator (first history user) untuk setiap placeId agar tombol Hapus muncul hanya untuk creator
        $creators = [];
        if (!empty($usaha)) {
            foreach ($usaha as $r) {
                $pid = is_array($r) ? ($r['placeId'] ?? '') : ($r->placeId ?? '');
                if (!$pid) continue;
                // gunakan method model yang sudah Anda punya: getFirstHistoryUser($placeId)
                try {
                    $row = $model->getFirstHistoryUser($pid);
                    $creators[$pid] = $row ? ($row->id_siri_user ?? null) : null;
                } catch (\Throwable $e) {
                    log_message('error',"kakilimaDropdown getFirstHistoryUser failed for {$pid}: ".$e->getMessage());
                    $creators[$pid] = null;
                }
            }
        }

        $googleMapsKey = getenv('GOOGLE_MAPS_KEY') ?: 'AIzaSyAYaRBajjixtB0T1HqWEXOHbTDpJ8Tjcyc';

        return view('siripages/kakilima', [
            'desaList'      => $desaList,
            'selectedDesa'  => $selectedDesa,
            'selectedSls'   => $selectedSls,
            'slsOptions'    => $slsOptions,
            'usaha'         => $usaha,
            'creators'      => $creators,
            'googleMapsKey' => $googleMapsKey
        ]);
    }

    public function insertKakilima()
    {
        if (! session()->has('id_user')) {
            return redirect()->to('/');
        }

        $post = $this->request->getPost();
        $model = new EstablishmentModel();

        $nama   = trim($post['placeName'] ?? '');
        $alamat = trim($post['placeAddress'] ?? '');
        $iddesa = trim($post['iddesa'] ?? '');
        $lat    = $post['latitude'] ?? null;
        $lng    = $post['longitude'] ?? null;

        $kaki_addr = trim($post['kakilima_address'] ?? '');
        $kaki_idds = trim($post['kakilima_iddesa'] ?? '');

        $kdsls = isset($post['kdsls']) ? trim($post['kdsls']) : null;
        $nmsls = isset($post['nmsls']) ? trim($post['nmsls']) : null;
        if ($kdsls !== null && $kdsls !== '') {
            $kdsls = str_pad(preg_replace('/\D/','', $kdsls), 4, '0', STR_PAD_LEFT);
        }

        $missing = [];
        if ($nama === '') $missing[] = 'Nama usaha';
        if ($alamat === '') $missing[] = 'Alamat usaha';
        if ($iddesa === '') $missing[] = 'Desa usaha';
        if (!is_numeric($lat) || !is_numeric($lng)) $missing[] = 'Koordinat usaha';
        if ($kaki_idds === '') $missing[] = 'Desa tempat tinggal pengusaha';
        if ($kaki_addr === '') $missing[] = 'Alamat tempat tinggal pengusaha';

        if (!empty($missing)) {
            return redirect()->back()->withInput()->with('error', 'Field wajib: ' . implode(', ', $missing));
        }

        $wilayah = $model->getWilayahDetail($iddesa);
        $placeId = 'plc_' . time() . '_' . bin2hex(random_bytes(4));
        $waktu   = date('Y-m-d H:i:s');

        $data = [
            'placeId'           => $placeId,
            'placeName'         => $nama,
            'placeAddress'      => $alamat,
            'idprov'            => '14',
            'idkab'             => substr($iddesa, 0, 4),
            'idkec'             => substr($iddesa, 0, 7),
            'iddesa'            => $iddesa,
            'nmprov'            => 'RIAU',
            'nmkab'             => $wilayah->nmkab ?? '',
            'nmkec'             => $wilayah->nmkec ?? '',
            'nmdesa'            => $wilayah->nmdesa ?? '',
            'placeLatitude'     => $lat,
            'placeLongitude'    => $lng,
            'status_usaha'      => 1,
            'jenis_usaha_lokasi'=> 2,            // default kaki lima
            'kdsls'             => $kdsls,
            'nmsls'             => $nmsls,
            'kakilima_flag'     => 1,
            'indepth_flag'      => '1',
            'kakilima_iddesa'   => $kaki_idds,
            'kakilima_address'  => $kaki_addr,
            'last_update_by'    => $waktu . ' oleh ' . (session()->get('nama_user') ?? ''),
            'baru'              => 1
        ];

        $model->insertUsaha($data);

        try {
            $model->insertHistory([
                'placeId'      => $placeId,
                'id_siri_user' => session()->get('id_user'),
                'waktu_input'  => $waktu,
                'status_usaha' => 1
            ]);
        } catch (\Throwable $e) {
            log_message('error','insertKakilima insertHistory failed: '.$e->getMessage());
        }

        // redirect back to filter context
        $returnDesa = $this->request->getPost('return_desa') ?? $this->request->getGet('desa') ?? $iddesa;
        $returnSls  = $this->request->getPost('return_sls') ?? $this->request->getGet('sls') ?? 'all';
        $qs = [];
        if ($returnDesa !== '') $qs['desa'] = $returnDesa;
        if ($returnSls !== '')  $qs['sls']  = $returnSls;

        return redirect()->to(base_url('usaha/kakilima-dropdown' . (!empty($qs) ? ('?' . http_build_query($qs)) : '')))
                         ->with('success','Usaha kaki lima berhasil ditambahkan.');
    }

    public function updateKakilima()
    {
        if (! session()->has('id_user')) {
            return redirect()->to('/');
        }

        $post    = $this->request->getPost();
        $placeId = trim($post['placeId'] ?? '');
        $nama    = trim($post['placeName'] ?? '');
        $alamat  = trim($post['placeAddress'] ?? '');
        $kakilima_address = trim($post['kakilima_address'] ?? '');
        $kakilima_iddesa  = trim($post['kakilima_iddesa'] ?? '');
        $kakilima_lat     = trim($post['kakilima_latitude'] ?? '');
        $kakilima_lng     = trim($post['kakilima_longitude'] ?? '');

        $missing = [];
        if ($placeId === '') $missing[] = 'placeId';
        if ($nama === '')    $missing[] = 'Nama usaha';
        if ($alamat === '')  $missing[] = 'Alamat usaha';

        if (!empty($missing)) {
            return redirect()->back()->withInput()->with('error','Field wajib: '.implode(', ',$missing));
        }

        $model = new EstablishmentModel();

        $payload = [
            'placeName'        => $nama,
            'placeAddress'     => $alamat,
            'indepth_flag'     => '1',
            'kakilima_address' => ($kakilima_address !== '' ? $kakilima_address : null),
        ];

        if ($kakilima_iddesa !== '') {
            $payload['kakilima_iddesa'] = $kakilima_iddesa;
            if ($kakilima_lat !== '')  $payload['kakilima_latitude']  = $kakilima_lat;
            if ($kakilima_lng !== '')  $payload['kakilima_longitude'] = $kakilima_lng;
        }

        $ok = $model->updateKakilimaFields($placeId, $payload);
        if (! $ok) {
            return redirect()->back()->withInput()->with('error','Update gagal.');
        }

        // redirect back to filter context
        $returnDesa = $this->request->getPost('return_desa') ?? $this->request->getGet('desa') ?? '';
        $returnSls  = $this->request->getPost('return_sls')  ?? $this->request->getGet('sls')  ?? 'all';
        $qs = [];
        if ($returnDesa !== '') $qs['desa'] = $returnDesa;
        if ($returnSls !== '')  $qs['sls']  = $returnSls;

        return redirect()->to(base_url('usaha/kakilima-dropdown' . (!empty($qs) ? ('?' . http_build_query($qs)) : '')))
                         ->with('success','Data usaha kaki lima berhasil diperbarui.');
    }
    
    public function getAllKabupatenJson()
    {
        if (!session()->has('id_user')) {
            return $this->response->setJSON([]);
        }
        $m = new \App\Models\EstablishmentModel();
        return $this->response->setJSON($m->getAllKabupaten());
    }
    
    public function exportEstablishmentExcel_indepth()
    {
        $model = new EstablishmentModel();
        $data = $model->getExportData_indepth();

        // kalau mau Excel (.xls)
        $filename = 'establishment_export_' . date('Ymd_His') . '.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        echo "<table border='1'>";
        echo "<tr>
            <th>nmdesa</th>
            <th>kdsls</th>
            <th>nmsls</th>
            <th>placeName</th>
            <th>placeAddress</th>
            <th>status_usaha</th>
            <th>jenis_usaha_lokasi</th>
            <th>lokasilain_address</th>
            <th>indepth_flag</th>
            <th>kakilima_flag</th>
            <th>alamat_rumah_kakilima</th>
            <th>keterangan</th>
        </tr>";

        foreach ($data as $row) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value ?? '') . "</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
        exit;
    }
    
    public function laborkabkot()
    {
        // ambil idkab user dari session
        $idkabUser = session('idkab');   // contoh: 1408, 1409, 1473, dst.
    
        if (!$idkabUser || strlen($idkabUser) != 4) {
            return redirect()->back()->with('error', 'Session idkab tidak valid.');
        }
    
        // master target desa
        $targetDesaMaster = [
            '1471060006' => 'Harjosari',
            '1471070003' => 'Sago',
        ];
    
        // =============================
        // FILTER DESA SESUAI SESSION
        // =============================
    
        if ($idkabUser === '1400') {
            // super-kab boleh lihat semua
            $targetDesa = $targetDesaMaster;
        } else {
            // filter berdasarkan prefix
            $targetDesa = [];
            foreach ($targetDesaMaster as $id => $nama) {
                if (substr($id, 0, 4) === $idkabUser) {
                    $targetDesa[$id] = $nama;
                }
            }
        }
    
        // =============================
        // Ambil request
        // =============================
        $iddesa = $this->request->getGet('iddesa');
        $kdsls  = $this->request->getGet('kdsls');
    
        // =============================
        // Proteksi URL: user nggak boleh pilih desa kabupaten lain
        // =============================
        if ($iddesa && $idkabUser !== '1400') {
            if (!isset($targetDesa[$iddesa])) {
                return redirect()
                    ->to(base_url('usaha/laborkabkot'))
                    ->with('error', 'Anda tidak memiliki akses ke desa tersebut.');
            }
        }
    
        // =============================
        // Query data SLS & usaha
        // =============================
        $model = new \App\Models\EstablishmentModel();
    
        $slsList = [];
        if ($iddesa) {
            $slsList = $model->getSlsByDesa2($iddesa);
        
            // FILTER KHUSUS UNTUK TEMBILAHAN KOTA (1403060004)
            if ($iddesa === '1403060004') {
                $allowedSls = [
                    '0001','0002','0003','0004','0005','0006',
                    '0011','0012','0013','0014',
                    '0033','0036','0037',
                    '0042','0047'
                ];
        
                $slsList = array_filter($slsList, function($row) use ($allowedSls) {
                    return in_array($row['kdsls'], $allowedSls);
                });
            }
        }

    
        $usahaList = [];
        if ($iddesa && $kdsls !== null && $kdsls !== '') {
            $usahaList = $model->getUsahaBySls($iddesa, $kdsls === '999' ? 'NULL' : $kdsls);
        }
    
        return view('siripages/laborkabkot', [
            'targetDesa' => $targetDesa,
            'slsList'    => $slsList,
            'usahaList'  => $usahaList,
            'iddesa'     => $iddesa,
            'kdsls'      => $kdsls,
        ]);
    }


    
    public function updateStatusLabor()
    {
        $placeId = $this->request->getPost('placeId');
        $status  = $this->request->getPost('status_usaha_labor');
    
        if (!$placeId || !$status) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }
    
        $model = new \App\Models\EstablishmentModel();
        $model->where('placeId', $placeId)->set('status_usaha_labor', $status)->update();
    
        return redirect()->back()->with('success', 'Status usaha labor berhasil diperbarui.');
    }

    public function laborkabkot_geo()
    {
        $idkabUser = session('idkab');   // contoh: "1408" atau "1400"
    
        if (!$idkabUser || strlen($idkabUser) != 4) {
            return redirect()->back()->with('error', 'Session idkab tidak valid.');
        }
    
        // GET request
        $iddesa = $this->request->getGet('iddesa');
        $kdsls  = $this->request->getGet('kdsls');
    
        // Master target desa
        $targetDesaMaster = [
            '1471060006' => 'Harjosari',
            '1471070003' => 'Sago',
        ];
    
        // -------------------------------------------------
        // FILTER DESA
        // Mode superkab: 1400 bisa melihat SEMUA
        // -------------------------------------------------
        if ($idkabUser === '1400') {
            $filteredDesa = $targetDesaMaster;
        } else {
            $filteredDesa = [];
            foreach ($targetDesaMaster as $id => $nama) {
                if (substr($id, 0, 4) === $idkabUser) {
                    $filteredDesa[$id] = $nama;
                }
            }
        }
    
        // -------------------------------------------------
        // Proteksi URL (kecuali superkab 1400)
        // -------------------------------------------------
        if ($iddesa && $idkabUser !== '1400') {
            if (!isset($filteredDesa[$iddesa])) {
                return redirect()
                    ->to(base_url('siri/laborkabkot_geo'))
                    ->with('error', 'Anda tidak memiliki akses ke desa tersebut.');
            }
        }
    
        // -------------------------------------------------
        // Ambil SLS jika desa valid
        // -------------------------------------------------
        // Ambil SLS
        $slsList = [];
        if ($iddesa) {
            $slsList = $this->est->getSlsByDesa($iddesa);
    
            // ================================================
            // FILTER KHUSUS UNTUK TEMBILAHAN KOTA (1403060004)
            // ================================================
            if ($iddesa === '1403060004') {
                $allowedSls = [
                    '0001','0002','0003','0004','0005','0006',
                    '0011','0012','0013','0014',
                    '0033','0036','0037',
                    '0042','0047'
                ];
    
                $slsList = array_filter($slsList, function($row) use ($allowedSls) {
                    return in_array($row['kdsls'], $allowedSls);
                });
            }
            // ================================================
        }
    
        // -------------------------------------------------
        // Proteksi kdsls (kecuali superkab 1400)
        // -------------------------------------------------
        if ($iddesa && $kdsls && $idkabUser !== '1400') {
    
            $validKdsls = array_column($slsList, 'kdsls');
    
            if (!in_array($kdsls, $validKdsls) && $kdsls !== '999') {
                return redirect()
                    ->to(base_url('siri/laborkabkot_geo') . '?iddesa=' . $iddesa)
                    ->with('error', 'Akses ke SLS ini tidak diizinkan.');
            }
        }
    
        // Ambil data labor
        $laborList = [];
        if ($iddesa && $kdsls) {
            $laborList = $this->est->getLaborByDesaSls($iddesa, $kdsls);
        }
    
        $data = [
            'desaList'      => $filteredDesa,
            'slsList'       => $slsList,
            'laborList'     => $laborList,
            'googleMapsKey' => 'AIzaSyAYaRBajjixtB0T1HqWEXOHbTDpJ8Tjcyc',
            'iddesa'        => $iddesa,
            'kdsls'         => $kdsls,
            'idkabUser'     => $idkabUser,
        ];
    
        return view('siripages/laborkabkot_geo_view', $data);
    }


    public function tambah_laborkabkot()
    {
        $iddesa = $this->request->getPost('iddesa');
        $kdsls  = $this->request->getPost('kdsls');

        $dataInsert = [
            'laborId'        => 'labor_' . bin2hex(random_bytes(5)),
            'laborName'      => $this->request->getPost('laborName'),
            'laborAddress'      => $this->request->getPost('laborAddress'),
            'no_urut_usaha'  => $this->request->getPost('no_urut_usaha'),
            'iddesa'         => $iddesa,
            'kdsls'          => $kdsls,
            'laborLatitude'  => $this->request->getPost('laborLatitude'),
            'laborLongitude' => $this->request->getPost('laborLongitude')
        ];

        $this->est->insert($dataInsert);

        return redirect()->to('/usaha/laborkabkot_geo?iddesa='.$iddesa.'&kdsls='.$kdsls)
       ->with('success','Data berhasil disimpan.');

    }

    public function edit_laborkabkot()
    {
        $laborId = $this->request->getPost('laborId');
        $iddesa  = $this->request->getPost('iddesa');
        $kdsls   = $this->request->getPost('kdsls');

        $dataUpdate = [
            'laborName'      => $this->request->getPost('laborName'),
            'no_urut_usaha'  => $this->request->getPost('no_urut_usaha'),
            'laborAddress'   => $this->request->getPost('laborAddress'),
            'laborLatitude'  => $this->request->getPost('laborLatitude'),
            'laborLongitude' => $this->request->getPost('laborLongitude')
        ];

        $this->est->update($laborId, $dataUpdate);

        return redirect()->to('/usaha/laborkabkot_geo?iddesa='.$iddesa.'&kdsls='.$kdsls)
       ->with('success','Data berhasil disimpan.');

    }
    
    public function delete_laborkabkot()
    {
        $id = $this->request->getPost('laborId');
        if (!$id) return redirect()->back()->with('error', 'ID tidak valid');
    
        $this->est->where('laborId', $id)->delete();
    
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
    
    public function laborRekapGeo()
    {
        $idkabUser = session('idkab');
        if (!$idkabUser || strlen($idkabUser) != 4) {
            return redirect()->back()->with('error', 'Session idkab tidak valid.');
        }
    
        $labor = new \App\Models\EstablishmentLaborModel();
    
        // Ambil master desa
        $targetDesa = $labor->getTargetDesa();
    
        // Filter desa sesuai idkab user
        if ($idkabUser === '1400') {
            $desaList = $targetDesa;
        } else {
            $desaList = [];
            foreach ($targetDesa as $id => $nama) {
                if (substr($id, 0, 4) === $idkabUser) {
                    $desaList[$id] = $nama;
                }
            }
        }
    
        $iddesa = $this->request->getGet('iddesa');
    
        // TAB 1 — Rekap Desa
        $rekapDesa = $labor->rekapPerDesa(array_keys($desaList));
    
        // TAB 2 — Rekap SLS
        $rekapSls = [];
        if ($iddesa) {
            $rekapSls = $labor->rekapPerSls($iddesa);
    
            // Filter khusus Tembilahan Kota
            if ($iddesa === '1403060004') {
                $allowed = [
                    '0001','0002','0003','0004','0005','0006',
                    '0011','0012','0013','0014',
                    '0033','0036','0037',
                    '0042','0047'
                ];
    
                $rekapSls = array_filter($rekapSls, function($row) use ($allowed) {
                    return in_array($row['kdsls'], $allowed);
                });
            }
    
            // Tambahkan validasi nomor urut
            foreach ($rekapSls as &$row) {
                $row['warning'] = $labor->cekNomorUrut($iddesa, $row['kdsls']);
            }
        }
        
        $tab = $this->request->getGet('tab') ?? '1';
    
        return view('siripages/labor_rekap_view', [
            'desaList'  => $desaList,
            'rekapDesa' => $rekapDesa,
            'rekapSls'  => $rekapSls,
            'iddesa'    => $iddesa,
            'tab'       => $tab,
        ]);
    }
    
    public function laborRekap()
    {
        $idkabUser = session('idkab');
        $iddesa    = $this->request->getGet('iddesa');
    
        $model = new \App\Models\EstablishmentModel();
    
        // Master target desa
        $targetDesaMaster = [
            '1471060006' => 'Harjosari',
            '1471070003' => 'Sago',
        ];
    
        // filter desa per kab
        if ($idkabUser !== '1400') {
            $filteredDesa = [];
            foreach ($targetDesaMaster as $id => $nama) {
                if (substr($id, 0, 4) === $idkabUser) {
                    $filteredDesa[$id] = $nama;
                }
            }
        } else {
            $filteredDesa = $targetDesaMaster;
        }
    
        $rekapSls = [];
        if ($iddesa) {
            $rekapSls = $model->rekapSlsByDesa_rekap($iddesa);
    
            // filter khusus Tembilahan
            $rekapSls = $model->filterAllowedSlsIfTembilahan($iddesa, $rekapSls);
        }
    
        return view('siripages/labor_rekap', [
            'filteredDesa' => $filteredDesa,
            'iddesa'       => $iddesa,
            'rekapSls'     => $rekapSls
        ]);
    }



}
