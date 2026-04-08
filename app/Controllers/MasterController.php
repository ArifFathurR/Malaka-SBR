<?php

namespace App\Controllers;
ob_start();

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");

use App\Models\Mentari_model;

class MasterController extends BaseController
{
    protected $mentariModel;

    public function __construct()
    {
        $this->mentariModel = new Mentari_model();
    }

    public function index()
    {
        $data['outputs'] = $this->mentariModel->getAll_Output();
        return view('pages/output_list', $data);
    }

// ================================================================================================================================================

    public function read_output()
    {   
        $data['flashdata'] = session()->getFlashdata('success') ?? null; // Cek apakah flashdata disetel
        $data['outputs'] = $this->mentariModel->getAll_Output();
        return view('pages/output_list', $data);
    }

    public function create_output()
    {
        $validation = $this->validate([
            'nama_output' => 'required',
            'alias' => 'required',
            'fungsi' => 'required',
        ]);

        if (!$validation) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to('mastercontroller/read_output')->withInput();
        }

        $data = [
            $this->request->getPost('nama_output'),
            $this->request->getPost('alias'),
            $this->request->getPost('fungsi'),
            $this->request->getPost('status'),
        ];

        $this->mentariModel->insertData_output($data);

        session()->setFlashdata('success', 'Data berhasil ditambahkan.');
        return redirect()->to('mastercontroller/read_output');
    }

    public function update_output($id)
    {
        $validation = $this->validate([
            'nama_output' => 'required',
            'alias' => 'required',
            'fungsi' => 'required',
        ]);

        if (!$validation) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to('/master')->withInput();
        }

        $data = [
            $this->request->getPost('nama_output'),
            $this->request->getPost('alias'),
            $this->request->getPost('fungsi'),
            $this->request->getPost('status'),
        ];

        $this->mentariModel->updateData_output($id, $data);

        session()->setFlashdata('success', 'Data berhasil diperbarui.');
        return redirect()->to('mastercontroller/read_output');
    }

    public function delete_output($id)
    {
        $this->mentariModel->deleteData_output($id);
        session()->setFlashdata('success', 'Data berhasil dihapus.');
        return redirect()->to('mastercontroller/read_output');
    }


// ================================================================================================================================================

    // Fungsi Menampilkan Data Kegiatan
    public function read_kegiatan()
    {
        $data['kegiatans'] = $this->mentariModel->getAll_Kegiatan();
        $data['outputs'] = $this->mentariModel->getAll_Output();
        $data['flashdata'] = session()->getFlashdata('success') ?? null; // Flashdata sukses
        return view('pages/kegiatan_list', $data); // Load View
    }

    // Fungsi Menambahkan Data Kegiatan
    public function create_kegiatan()
    {
        // Validasi Input
        $validation = $this->validate([
            'id_output' => 'required',
            'nama_kegiatan' => 'required',
            'fungsi' => 'required',
            'alias' => 'required',
            'periode' => 'required',
        ]);

        if (!$validation) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to('mastercontroller/read_kegiatan')->withInput();
        }

        // Ambil Data dari Form
        $data = [
            $this->request->getPost('id_output'), // Foreign Key
            $this->request->getPost('nama_kegiatan'),
            $this->request->getPost('fungsi'),
            $this->request->getPost('alias'),
            $this->request->getPost('keterangan'),
            $this->request->getPost('periode'),
            $this->request->getPost('pelaksana'),
        ];

        // Simpan ke Database
        $this->mentariModel->insertData_Kegiatan($data);

        session()->setFlashdata('success', 'Data kegiatan berhasil ditambahkan.');
        return redirect()->to('mastercontroller/read_kegiatan');
    }

    // Fungsi Mengupdate Data Kegiatan
    public function update_kegiatan($id)
    {
        // Validasi Input
        $validation = $this->validate([
            'nama_kegiatan' => 'required',
            'fungsi' => 'required',
            'alias' => 'required',
            'periode' => 'required',
        ]);

        if (!$validation) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to('mastercontroller/read_kegiatan')->withInput();
        }

        // Ambil Data dari Form
        $data = [
            $this->request->getPost('nama_kegiatan'),
            $this->request->getPost('fungsi'),
            $this->request->getPost('alias'),
            $this->request->getPost('keterangan'),
            $this->request->getPost('periode'),
        ];

        // Update Data di Database
        $this->mentariModel->updateData_Kegiatan($id, $data);

        session()->setFlashdata('success', 'Data kegiatan berhasil diperbarui.');
        return redirect()->to('mastercontroller/read_kegiatan');
    }

    // Fungsi Menghapus Data Kegiatan
    public function delete_kegiatan($id)
    {
        $this->mentariModel->deleteData_Kegiatan($id);
        session()->setFlashdata('success', 'Data kegiatan berhasil dihapus.');
        return redirect()->to('mastercontroller/read_kegiatan');
    }

// ================================================================================================================================================

    public function read_kegiatan_detail()
    {
        $id_output = $this->request->getPost('id_output_detail'); // Input id_output dari form/filter

        //echo $id_output; exit; 
        
        if ($id_output) {
            // Jika ada filter id_output
            $data['kegiatan_details'] = $this->mentariModel->getAll_KegiatanDetail_ByOutput($id_output);
        } else {
            // Menampilkan semua data master_kegiatan_detail
            $data['kegiatan_details'] = $this->mentariModel->getAll_KegiatanDetail();
        }

        // Ambil data untuk dropdown dari tabel master_kegiatan
        $data['kegiatan_list'] = $this->mentariModel->getAll_Kegiatan();

        $data['outputs'] = $this->mentariModel->getAll_Output(); // Untuk dropdown output
        $data['selected_output'] = $id_output; // Menyimpan output terpilih (jika ada)
        return view('pages/kegiatan_detail_list', $data);
    }

    // Create data kegiatan detail
    public function create_kegiatan_detail()
    {
        // Ambil data input dari form
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $tanggal_selesai = $this->request->getPost('tanggal_selesai');

        // Validasi: Tanggal selesai tidak boleh kurang dari tanggal mulai
        if (strtotime($tanggal_selesai) < strtotime($tanggal_mulai)) {
            session()->setFlashdata('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
            return redirect()->to('mastercontroller/read_kegiatan_detail')->withInput();
        }

        $data = [
            $this->request->getPost('id_kegiatan'),          // Foreign Key
            $this->request->getPost('nama_kegiatan_detail'),
            $tanggal_mulai,
            $tanggal_selesai,
            $this->request->getPost('bulan'),
            $this->request->getPost('keterangan'),
        ];

        // Simpan data ke database
        $this->mentariModel->insertData_KegiatanDetail($data);
        session()->setFlashdata('success', 'Data kegiatan detail berhasil ditambahkan.');
        return redirect()->to('mastercontroller/read_kegiatan_detail');
    }


    // Update data kegiatan detail
    public function update_kegiatan_detail($id)
    {
        // Ambil data input dari form
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $tanggal_selesai = $this->request->getPost('tanggal_selesai');

        // Validasi: Tanggal selesai tidak boleh kurang dari tanggal mulai
        if (strtotime($tanggal_selesai) < strtotime($tanggal_mulai)) {
            session()->setFlashdata('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
            return redirect()->to('mastercontroller/read_kegiatan_detail')->withInput();
        }

        $data = [
            $this->request->getPost('nama_kegiatan_detail'),
            $tanggal_mulai,
            $tanggal_selesai,
            $this->request->getPost('bulan'),
            $this->request->getPost('keterangan'),
        ];

        // Update data di database
        $this->mentariModel->updateData_KegiatanDetail($id, $data);
        session()->setFlashdata('success', 'Data kegiatan detail berhasil diperbarui.');
        return redirect()->to('mastercontroller/read_kegiatan_detail');
    }


    // Delete data kegiatan detail
    public function delete_kegiatan_detail($id)
    {
        $this->mentariModel->deleteData_KegiatanDetail($id);
        session()->setFlashdata('success', 'Data kegiatan detail berhasil dihapus.');
        return redirect()->to('mastercontroller/read_kegiatan_detail');
    }

        
    // ================================================================================================================================================

    public function read_kegiatan_detail_proses()
    {
        $id_output = $this->request->getPost('id_output_detail'); // Input id_output dari form/filter

        $data['kegiatan_details'] = $this->mentariModel->getAll_KegiatanDetail_WithProses();
        $data['kategori_db'] = $this->mentariModel->read_kategori();
        $data['kegdetail'] = $this->mentariModel->getKegiatanDetail();
        $data['tim_list'] = $this->mentariModel->getAllTim_Array(); // <--- Tambahkan ini

        return view('pages/kegiatan_detail_proses_list', $data);
    }

    // Create data kegiatan detail
    public function create_kegiatan_detail_proses()
    {
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $tanggal_selesai = $this->request->getPost('tanggal_selesai');
        $status = '0';
        $realisasi = '0';

        if (strtotime($tanggal_selesai) < strtotime($tanggal_mulai)) {
            session()->setFlashdata('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
            return redirect()->to('mastercontroller/read_kegiatan_detail')->withInput();
        }

        $data = [
            $this->request->getPost('id_kegiatan_detail'),
            $this->request->getPost('uraian_proses'),
            $tanggal_mulai,
            $tanggal_selesai,
            $this->request->getPost('target'),
            $realisasi,
            $status,
            $this->request->getPost('keterangan'),
            $this->request->getPost('satuan'),
            $this->request->getPost('id_tim')
        ];

        $this->mentariModel->insertData_KegiatanDetailProses($data);
        session()->setFlashdata('success', 'Data kegiatan detail berhasil ditambahkan.');
        return redirect()->to('mastercontroller/read_kegiatan_detail_proses');
    }

    // Update data kegiatan detail
    public function update_kegiatan_detail_proses($id)
    {
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $tanggal_selesai = $this->request->getPost('tanggal_selesai');

        if (strtotime($tanggal_selesai) < strtotime($tanggal_mulai)) {
            session()->setFlashdata('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
            return redirect()->to('mastercontroller/read_kegiatan_detail_proses')->withInput();
        }

        $data = [
            $this->request->getPost('uraian_proses'),
            $tanggal_mulai,
            $tanggal_selesai,
            $this->request->getPost('target'),
            $this->request->getPost('keterangan'),
            $this->request->getPost('satuan')
        ];

        $this->mentariModel->updateData_KegiatanDetailProses($id, $data);
        session()->setFlashdata('success', 'Data kegiatan proses berhasil diperbarui.');
        return redirect()->to('mastercontroller/read_kegiatan_detail_proses');
    }

    // Delete data kegiatan detail
    public function delete_kegiatan_detail_proses($id)
    {
        $this->mentariModel->deleteData_KegiatanDetailProses($id);
        session()->setFlashdata('success', 'Data kegiatan detail berhasil dihapus.');
        return redirect()->to('mastercontroller/read_kegiatan_detail_proses');
    }

    public function read_tim()
    {
        $idkab = session()->get('idkab');
        $data['teams'] = $this->mentariModel->read_tim($idkab);
        return view('pages/tim_list', $data);
    }

    public function read_subtim($id_tim = null)
    {
        $model = new Mentari_model();

        // Ambil semua tim utama untuk dropdown
        $data['all_teams'] = $model->db->query("
            SELECT id_tim, nama_tim FROM master_tim ORDER BY nama_tim ASC
        ")->getResultArray();

        // Ambil ID tim dari GET atau URL parameter
        if ($this->request->getGet('id_tim')) {
            $id_tim = $this->request->getGet('id_tim');
        }

        // Jika tidak ada ID tim, gunakan tim pertama sebagai default
        if ($id_tim == null && !empty($data['all_teams'])) {
            $id_tim = $data['all_teams'][0]['id_tim'];
        }

        // Ambil data subtim sesuai tim yang dipilih
        $data['timwakil'] = $model->read_timwakil($id_tim);
        $data['selected_team'] = $id_tim;

        return view('pages/timwakil_list', $data);
    }

    public function read_anggota($id_tim_wakil)
    {
        $anggota = $this->mentariModel->read_anggota($id_tim_wakil);
        
        return $this->response->setJSON([
            'status' => 'success',
            'anggota' => $anggota
        ]);
    }

    public function read_tim_daerah()
    {
        $id_tim = $this->request->getGet('id_tim');
        $data['all_teams'] = $this->mentariModel->getAllTim();
        $data['selected_team'] = $id_tim;
        $data['subtim_daerah'] = $this->mentariModel->getSubtimDaerah($id_tim);
        return view('pages/tim_daerah_list', $data);
    }

    public function read_anggota_daerah($id_tim_wakil, $idkab)
    {
        $anggota = $this->mentariModel->getAnggotaByTimDaerah($id_tim_wakil, $idkab);
        return $this->response->setJSON([
            'status' => 'success',
            'anggota' => $anggota
        ]);
    }

// ==============================================================================================================================================

    public function get_wilayah_data()
    {
        $id_kegiatan_detail_proses = $this->request->getPost('id_kegiatan_detail_proses');

        $kegiatanDetail = $this->mentariModel->getKegiatanDetailProsesById($id_kegiatan_detail_proses);
        $wilayahData = $this->mentariModel->getWilayahByKegiatanDetailProses($id_kegiatan_detail_proses);
        $timWakilList = $this->mentariModel->getAllTimWakil();

        // Ambil id_tim_wakil dari data pertama yg ada (jika tersedia)
        $id_tim_wakil = null;
        foreach ($wilayahData as $w) {
            if (!empty($w['id_tim_wakil'])) {
                $id_tim_wakil = $w['id_tim_wakil'];
                break;
            }
        }

        return $this->response->setJSON([
            'nama_kegiatan_detail' => $kegiatanDetail['nama_kegiatan_detail'],
            'uraian_proses' => $kegiatanDetail['uraian_proses'],
            'id_tim_wakil' => $id_tim_wakil,
            'wilayah' => $wilayahData,
            'timwakil_list' => $timWakilList
        ]);
        
    }

    public function update_wilayah($id_kegiatan_detail_proses)
    {
        $targets = $this->request->getPost('target');
        $id_tim_wakil = $this->request->getPost('id_tim_wakil');

        foreach ($targets as $idkab => $target) {
            $this->mentariModel->updateOrInsertWilayah($id_kegiatan_detail_proses, $idkab, $target, $id_tim_wakil);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

// ================================================================================================================================================

    public function wilayah_transaksi()
    {
        $data['title'] = 'Wilayah Transaksi';
        $data['tim_list'] = $this->mentariModel->getAllTim();

        $id_tim = $this->request->getGet('id_tim');
        if ($id_tim) {
            $data['selected_tim'] = $id_tim;

            // ✅ Simpan id_tim ke flashdata untuk dipakai di redirect
            session()->setFlashdata('last_selected_tim', $id_tim);

            $data['transaksi_list'] = $this->mentariModel->getKegiatanByTim($id_tim);
        } else {
            $data['transaksi_list'] = [];
        }

        return view('pages/wilayah_transaksi_list', $data);
    }

    public function ajax_detail_kegiatan()
    {
        $id = $this->request->getGet('id');
        $kegiatan = $this->mentariModel->getKegiatanById($id);
        $detail = $this->mentariModel->getDetailKabupaten($id);

        if (!$kegiatan) {
            return $this->response->setBody("Data kegiatan tidak ditemukan.");
        }

        return view('pages/modal_detail_realisasi', [
            'kegiatan' => $kegiatan,
            'detail' => $detail
        ]);
    }

    public function update_realisasi_wilayah()
    {
        $id_proses = $this->request->getPost('id_kegiatan_detail_proses');
        $realisasi = $this->request->getPost('realisasi');

        if (!$id_proses || !is_array($realisasi)) {
            return redirect()->back()->with('error', 'Data tidak valid.');
        }

        foreach ($realisasi as $idkab => $value) {
            $value = intval($value);
            $this->mentariModel->updateRealisasiWilayah($id_proses, $idkab, $value);
        }

        // ⬇️ Redirect kembali ke halaman sesuai tim yang dipilih
        return redirect()->to('mastercontroller/wilayah_transaksi?id_tim=' . session()->getFlashdata('last_selected_tim'))
                        ->with('success', 'Realisasi berhasil diperbarui.');
    }

    public function wilayah_menu($id_kegiatan_detail_proses)
    {
        // Ambil detail kegiatan
        $kegiatan = $this->mentariModel->getKegiatanDetailProses($id_kegiatan_detail_proses);

        // Ambil id_tim dan nama_tim dari join
        $id_tim = $this->mentariModel->getTimIdByDetailProses($id_kegiatan_detail_proses);
        $nama_tim = $this->mentariModel->getNamaTimById($id_tim);

        if (!$kegiatan) {
            // Redirect atau tampilkan view khusus kalau tidak ditemukan
            return view('errors/html/error_404', ['message' => 'Data kegiatan tidak ditemukan.']);
        }

        // Ambil daftar wilayah dengan target > 0
        $wilayah_raw = $this->mentariModel->getWilayahByDetailProses($id_kegiatan_detail_proses);

        $wilayah_list = [];

        foreach ($wilayah_raw as $row) {
            $last_update = $this->mentariModel->getLastUpdateTransaksi($id_kegiatan_detail_proses, $row['idkab']);
        
            $histori = $this->mentariModel->getHistoriTransaksiPerWilayah($id_kegiatan_detail_proses, $row['idkab']);
        
            $wilayah_list[] = [
                'idkab' => $row['idkab'],
                'nama_kab' => $row['nama_kab'],
                'target' => $row['target'],
                'nama_user_selesai' => $row['nama_user_selesai'],
                'realisasi' => $row['realisasi'],
                'id_kegiatan_wilayah' => $row['id_kegiatan_wilayah'],
                'id_kegiatan_detail_proses' => $row['id_kegiatan_detail_proses'],
                'status' => $row['status'],
                'last_update' => $last_update ? date('d M Y H:i:s', strtotime($last_update->tanggal_input)) : null,
                'last_user' => $last_update->nama_user ?? null,
                'last_user_kab' => ($last_update && $last_update->idkab == 1400) ? 'Provinsi' : ($last_update->nama_kab ?? null),
                'histori' => $histori,
            ];
        }
        

        $data = [
            'kegiatan' => $kegiatan,
            'wilayah_list' => $wilayah_list,
            'id_kegiatan_detail_proses' => $id_kegiatan_detail_proses,
            'title' => 'Detail Wilayah Transaksi',
            'id_tim' => $id_tim,
            'nama_tim' => $nama_tim,
        ];

        return view('pages/wilayah_menu_list', $data);
    }

    // Helper untuk format update terakhir
    private function formatUpdateInfo($tanggal, $user, $idkab)
    {
        date_default_timezone_set('Asia/Jakarta'); // Set timezone WIB
        $lokasi = ($idkab == 1400) ? 'Provinsi' : 'Kab/Kota';
        $datetime = date('d M Y H:i:s', strtotime($tanggal));
        return "$datetime<br>oleh $user ($lokasi)";
    }

    public function insert_transaksi()
    {
        $id_kegiatan_detail_proses = $this->request->getPost('id_kegiatan_detail_proses');
        $idkab = $this->request->getPost('idkab');
        $realisasi = $this->request->getPost('transaksi_realisasi');
        $bukti_dukung = $this->request->getPost('bukti_dukung');
        $id_user = session()->get('id_user');
        $session_idkab = session()->get('idkab');

        // Validasi input
        if ($realisasi === null || $realisasi === '') {
            return redirect()->back()->with('error', 'Realisasi harus diisi.');
        }

        if ($session_idkab != 1400 && $session_idkab != $idkab) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengupdate wilayah ini.');
        }

        // Tentukan status berdasarkan siapa yang input
        $status = ($session_idkab == 1400) ? 2 : 1;

        $result = $this->mentariModel->simpanTransaksiBaru($id_kegiatan_detail_proses, $idkab, $realisasi, $bukti_dukung, $id_user, $status);

        if ($result) {
            return redirect()->to('mastercontroller/wilayah_menu/' . $id_kegiatan_detail_proses)
                            ->with('success', 'Realisasi berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan data.');
        }
    }

    public function insert_transaksi_2()
    {
        $id_kegiatan_detail_proses = $this->request->getPost('id_kegiatan_detail_proses');
        $idkab = $this->request->getPost('idkab');
        $realisasi = $this->request->getPost('transaksi_realisasi');
        $bukti_dukung = $this->request->getPost('bukti_dukung');
        $id_user = session()->get('id_user');
        $session_idkab = session()->get('idkab');

        // Validasi input
        if ($realisasi === null || $realisasi === '') {
            return redirect()->back()->with('error', 'Realisasi harus diisi.');
        }

        if ($session_idkab != 1400 && $session_idkab != $idkab) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengupdate wilayah ini.');
        }

        // Tentukan status berdasarkan siapa yang input
        $status = ($session_idkab == 1400) ? 2 : 1;

        $result = $this->mentariModel->simpanTransaksiBaru($id_kegiatan_detail_proses, $idkab, $realisasi, $bukti_dukung, $id_user, $status);

        if ($result) {
            return redirect()->to('mastercontroller/wilayah_detail/' . $idkab)
                            ->with('success', 'Realisasi berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan data.');
        }
    }

    public function wilayah_detail($idkab)
    {
        $session_idkab = session()->get('idkab');
        if ($session_idkab != 1400 && $session_idkab != $idkab) {
            return redirect()->to('dashboard')->with('error', 'Anda tidak memiliki akses ke wilayah ini.');
        }

        $kegiatan_list = $this->mentariModel->getKegiatanWilayahByIdkab($idkab);

        foreach ($kegiatan_list as &$row) {
            $last_update = $this->mentariModel->getLastUpdateTransaksi($row->id_kegiatan_detail_proses, $row->idkab);
            $histori = $this->mentariModel->getHistoriTransaksiPerWilayah($row->id_kegiatan_detail_proses, $row->idkab);

            $row->last_update = $last_update ? date('d M Y H:i:s', strtotime($last_update->tanggal_input)) : null;
            $row->nama_user = $last_update->nama_user ?? null;
            $row->histori = $histori;
        }

        $data = [
            'title' => 'Detail Wilayah',
            'idkab' => $idkab,
            'kegiatan_list' => $kegiatan_list
        ];

        return view('pages/wilayah_detail_list', $data);
    }

    public function verifikasi_status()
    {
        $id_kegiatan_wilayah = $this->request->getPost('id_kegiatan_wilayah');
        $status = $this->request->getPost('statuse');
        $session_idkab = session()->get('idkab');

       // echo $status; exit; 
        if ($session_idkab != 1400) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk memverifikasi.');
        }

        $result = $this->mentariModel->verifikasiStatusKegiatanWilayah($id_kegiatan_wilayah, $status);

        if ($result) {
            return redirect()->back()->with('success', 'Status kegiatan berhasil diverifikasi.');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui status.');
        }
    }

    public function verifikasi_status_selesai()
    {
        $id_kegiatan_wilayah = $this->request->getPost('id_kegiatan_wilayah');
        $idkab = $this->request->getPost('idkab');
        $rating = $this->request->getPost('rating');
        $session_idkab = session()->get('idkab');
        $session_iduser = session()->get('id_user');

        if ($session_idkab != 1400 && $session_idkab != $idkab) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin.');
        }

        $result = $this->mentariModel->verifikasiStatusKegiatanWilayahSelesai($id_kegiatan_wilayah, $rating, $session_iduser);

        if ($result) {
            return redirect()->back()->with('success', 'Kegiatan berhasil ditandai sebagai selesai dan diberi rating.');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui status.');
        }
    }


// ================================================================================================================================================

    public function calendar_kegiatan()
    {
        return view('pages/kegiatan_calendar');
    }

    public function get_kegiatan_events()
    {
        $data = $this->mentariModel->getAllKegiatanDetailWithTim();

        // Warna per tim
        $colors = [
            1 => ['bg' => '#FFD700', 'text' => '#000000'],
            2 => ['bg' => '#00BFFF', 'text' => '#FFFFFF'],
            3 => ['bg' => '#32CD32', 'text' => '#FFFFFF'],
            4 => ['bg' => '#FF4500', 'text' => '#FFFFFF'],
            5 => ['bg' => '#8A2BE2', 'text' => '#FFFFFF'],
            6 => ['bg' => '#FF69B4', 'text' => '#000000'],
            7 => ['bg' => '#20B2AA', 'text' => '#FFFFFF'],
            8 => ['bg' => '#A0522D', 'text' => '#FFFFFF'],
            9 => ['bg' => '#DC143C', 'text' => '#FFFFFF'],
            10 => ['bg' => '#00008B', 'text' => '#FFFFFF'],
        ];

        $events = [];
        foreach ($data as $row) {
            $color = $colors[$row['id_tim']] ?? ['bg' => '#ccc', 'text' => '#000'];
            $events[] = [
                'id' => $row['id_kegiatan_detail_proses'],
                'title' => $row['uraian_proses'],
                'start' => $row['tanggal_mulai'],
                'end' => date('Y-m-d', strtotime($row['tanggal_selesai'] . ' +1 day')),
                'backgroundColor' => $color['bg'],
                'textColor' => $color['text'],
                'extendedProps' => $row,
            ];
        }

        return $this->response->setJSON($events);
    }

// ================================================================================================================================================

    public function wilayah_menu_berlangsung()
    {
        $data['title'] = 'Daftar Kegiatan yang Sedang Berlangsung';
        $data['prosesSedangList'] = $this->mentariModel->getProsesSedangBerlangsung();

        return view('pages/wilayah_berlangsung_list', $data);
    }

    public function wilayah_menu_terlambat()
    {
        $data['kegiatanTerlambat'] = $this->mentariModel->getKegiatanTerlambatDetail();

        return view('pages/wilayah_terlambat_list', $data);
    }

// ================================================================================================================================================

    public function wilayah_nilai()
    {
        $bulan = $this->request->getGet('bulan') ?? date('n');
        $id_tim = $this->request->getGet('id_tim');
        $uraian_proses = $this->request->getGet('uraian_proses');

        $rekap = $this->mentariModel->getRekapitulasiNilai($bulan, $id_tim, $uraian_proses);
        $timList = $this->mentariModel->getAllTim();
        $prosesList = $id_tim ? $this->mentariModel->getUraianProsesByTim($id_tim) : [];

        return view('pages/wilayah_nilai_list', [
            'rekap' => $rekap,
            'timList' => $timList,
            'prosesList' => $prosesList,
            'bulan' => $bulan,
            'id_tim' => $id_tim,
            'uraian_proses' => $uraian_proses
        ]);
    }

}
