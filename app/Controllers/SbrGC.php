<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EstablishmentGCModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SbrGC extends BaseController
{
    /**
     * GET /sbr/gc?idsbr=...
     */
    public function index()
{
    if (!session()->has('id_user')) {
        return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu.');
    }
    
    $idsbr      = trim((string) $this->request->getGet('idsbr'));
    $namaUsaha  = trim((string) $this->request->getGet('nama_usaha'));
    $userKab    = (string) (session('idkab') ?? null);
    
    $row = null;
    $rows = [];
    $searchType = '';
    
    if ($idsbr !== '') {
        $model  = new EstablishmentGCModel();
        $result = $model->searchByIdSbr($idsbr, $userKab);
        $row    = $result[0] ?? null;
        $searchType = 'idsbr';
    }
    
    if ($namaUsaha !== '') {
        $model = new EstablishmentGCModel();
        $rows  = $model->searchByNamaUsaha($namaUsaha, $userKab);
        $searchType = 'nama';
    }
    
    return view('siripages/gcview', [
        'idsbr'      => $idsbr,
        'namaUsaha'  => $namaUsaha,
        'row'        => $row,
        'rows'       => $rows,
        'searchType' => $searchType,
    ]);
}
public function tabledata()
{
    if (!session()->has('id_user')) {
        return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
    }
 
    $userKab = (string)(session('idkab') ?? '');
    $model   = new EstablishmentGCModel();
 
    $page    = max(1, (int)$this->request->getGet('page'));
    $perPage = min(100, max(10, (int)$this->request->getGet('perPage') ?: 50));
    $offset  = ($page - 1) * $perPage;
 
    $filters = [
        'idsbr'  => trim((string)$this->request->getGet('idsbr')),
        'nama'   => trim((string)$this->request->getGet('nama')),
        'idkec'  => trim((string)$this->request->getGet('idkec')),
        'iddesa' => trim((string)$this->request->getGet('iddesa')),
        'status' => trim((string)$this->request->getGet('status')),
    ];
 
    $data  = $model->getFiltered($userKab, $filters, $perPage, $offset);
    $total = $model->countFiltered($userKab, $filters);
 
    return $this->response->setJSON([
        'data'  => $data,
        'total' => $total,
    ]);
}
 
// ── 2. JSON endpoint list kecamatan ────────────────────────────────
public function keclist()
{
    if (!session()->has('id_user')) {
        return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
    }
 
    $userKab = (string)(session('idkab') ?? '');
    $model   = new EstablishmentGCModel();
 
    return $this->response->setJSON($model->getKecListByKab($userKab));
}

// Controller SbrGC.php
public function keclistByKab($idkab)
{
    if (!session()->has('id_user')) {
        return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
    }
    $model = new EstablishmentGCModel();
    return $this->response->setJSON($model->getKecByKab($idkab));
}
 
// ── 3. JSON endpoint list desa per kecamatan (lazy load) ───────────
public function desalist($idkec)
{
    if (!session()->has('id_user')) {
        return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
    }
 
    $model = new EstablishmentGCModel();
    return $this->response->setJSON($model->getDesaByKec($idkec));
}
 

    /**
     * PUT /sbr/gc/{id_sbr}
     */
    public function update($idsbr)
    {
        // Cek apakah user sudah login
        if (!session()->has('id_user')) {
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data PUT (method spoofing)
        $input = $this->request->getRawInput();

        // Validasi input
        $rules = [
            'gc_status' => 'required|in_list[1,3,4,9]',
            'gc_lat'    => 'required|decimal',
            'gc_lng'    => 'required|decimal',
        ];

        if (!$this->validateData($input, $rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Validasi gagal, pastikan semua field terisi benar.');
        }

        // Cek apakah data dengan id_sbr ada
        $model = new EstablishmentGCModel();
        $existingData = $model->find($idsbr);

        if (!$existingData) {
            return redirect()
                ->back()
                ->with('error', 'Data usaha dengan ID SBR tersebut tidak ditemukan.');
        }

        // Data yang akan diupdate
        // PENTING: Ambil id_user dari session (bukan id_siri_user)
        // Karena sistem login Anda menggunakan session 'id_user'
        $dataUpdate = [
            'gc_status'    => $input['gc_status'],
            'gc_latitude'  => $input['gc_lat'],
            'gc_longitude' => $input['gc_lng'],
            'id_siri_user' => session('id_user'),      // Ambil dari session login
            'updated_at'   => date('Y-m-d H:i:s'),
        ];

        // Update data
        try {
            $model->update($idsbr, $dataUpdate);

            // Log activity untuk audit trail
            log_message('info', sprintf(
                'GC Updated - ID SBR: %s, User: %s (%s), Status: %s, Lat: %s, Lng: %s',
                $idsbr,
                session('id_user'),
                session('nama_user') ?? 'Unknown',
                $input['gc_status'],
                $input['gc_lat'],
                $input['gc_lng']
            ));

            return redirect()
                ->to(base_url('sbr/gc?idsbr=' . urlencode($idsbr)))
                ->with('success', 'Koordinat GC berhasil disimpan.');

        } catch (\Exception $e) {
            // Log error
            log_message('error', 'Error updating GC: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }
    
    //exportdata
    
    public function gcexport()
    {
        $sessionIdkab = (int) (session('idkab') ?? 0);

        $model = new EstablishmentGCModel();

        // Dropdown kabkot dari master_kab (idkab, nmkab)
        $kabList = $model->getKabListForSession($sessionIdkab);

        // Selected kab dari GET, tapi kalau bukan 1400 dipaksa pakai session
        $selectedKab = (int) ($this->request->getGet('idkab') ?? $sessionIdkab);
        if ($sessionIdkab !== 1400) {
            $selectedKab = $sessionIdkab;
        }

        return view('siripages/gcexport', [
            'kabList'      => $kabList,
            'sessionIdkab' => $sessionIdkab,
            'selectedKab'  => $selectedKab,
        ]);
    }

    public function export()
    {
        $sessionIdkab = (int) (session('idkab') ?? 0);
        $getIdkab     = (int) $this->request->getGet('idkab');
    
        // =============================
        // LOGIKA KABUPATEN (FIX)
        // =============================
        if ($sessionIdkab === 1400) {
            // admin WAJIB pilih kab
            if (!$getIdkab) {
                return redirect()->to(base_url('sbr/gc'))
                    ->with('error', 'Silakan pilih Kabupaten/Kota terlebih dahulu.');
            }
            $idkab = $getIdkab;
        } else {
            // non-admin: kunci ke session
            $idkab = $sessionIdkab;
        }
    
        $model = new EstablishmentGCModel();
        $rows  = $model->getForExportByKab($idkab);
    
        if (empty($rows)) {
            return redirect()->to(base_url('sbr/gc'))
                ->with('error', 'Tidak ada data GC (gc_status terisi) untuk kabupaten ini.');
        }
    
        $headers = [
            'id_sbr',
            'nama_usaha',
            'alamat',
            'keberadaan_usaha',
            'idprov',
            'idkab',
            'idkec',
            'iddesa',
            'latitude',
            'longitude',
            'gc_status',
            'gc_latitude',
            'gc_longitude',
            'id_siri_user',
            'nama_user',
        ];
    
        $filename = 'establishment_gc_' . $idkab . '_' . date('Ymd_His') . '.csv';
    
        $this->response->setHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $this->response->setHeader('Cache-Control', 'max-age=0');
    
        $fh = fopen('php://temp', 'w+');
        fwrite($fh, "\xEF\xBB\xBF"); // BOM UTF-8
    
        fputcsv($fh, $headers);
    
        foreach ($rows as $row) {
            $line = [];
            foreach ($headers as $key) {
                $val = $row[$key] ?? '';
    
                if (in_array($key, [
                    'id_sbr',
                    'latitude',
                    'longitude',
                    'gc_latitude',
                    'gc_longitude',
                    'id_siri_user'
                ], true)) {
                    $val = "\t" . $val; // paksa TEXT
                }
    
                $line[] = $val;
            }
            fputcsv($fh, $line);
        }
    
        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);
    
        return $this->response->setBody($csv);
    }
    
    /**
 * GET /sbr/gc/create
 * Tampilkan form tambah data usaha baru
 */
public function create()
{
    if (!session()->has('id_user')) {
        return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu.');
    }

    $userKab = (string)(session('idkab') ?? '');
    $model   = new EstablishmentGCModel();

    // Ambil list kecamatan sesuai hak akses user
    $kecList = $model->getKecByKab($userKab);

    // Kalau admin provinsi (1400), ambil list kabupaten juga
    $kabList = [];
    if ($userKab === '1400') {
        $kabList = $model->getKabListForSession(1400);
    }

    return view('siripages/gccreate', [
        'kecList' => $kecList,
        'kabList' => $kabList,
        'userKab' => $userKab,
    ]);
}

/**
 * POST /sbr/gc/store
 * Simpan data usaha baru ke database
 */
public function store()
{
    if (!session()->has('id_user')) {
        return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu.');
    }
    $db      = \Config\Database::connect(); // ← tambahkan ini di awal
    $userKab = (string)(session('idkab') ?? '');


    // Validasi input
    $rules = [
        'nama_usaha'      => 'required|min_length[3]|max_length[255]',
        'alamat'          => 'required|min_length[5]|max_length[500]',
        'idkec'           => 'required',
        'iddesa'          => 'required',
        'keberadaan_usaha'=> 'permit_empty|max_length[100]',
    ];

    if (!$this->validate($rules)) {
        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    $model = new EstablishmentGCModel();

    // Tentukan idkab: kalau admin 1400 ambil dari POST, selainnya dari session
    $idkab = ($userKab === '1400')
        ? (string)($this->request->getPost('idkab') ?? '')
        : $userKab;

    if (empty($idkab)) {
        return redirect()->back()->withInput()
            ->with('error', 'Kabupaten/Kota wajib dipilih.');
    }

    // Ambil data kecamatan untuk dapatkan idprov
       $kecRow = $db->table('master_kec')          // ← $db bukan $this->db
        ->where('idkec', $this->request->getPost('idkec'))
        ->get()->getRowArray();

    // Ambil idprov dari master_kab
    $kabRow = $db->table('master_kab')          // ← $db bukan $this->db
        ->where('idkab', $idkab)
        ->get()->getRowArray();

    $idprov = $kabRow['idprov'] ?? '14'; // default Riau

    // Generate ID SBR otomatis
    $idSbr = $model->generateIdSbr($idkab);

    $data = [
        'id_sbr'           => $idSbr,
        'nama_usaha'       => $this->request->getPost('nama_usaha'),
        'alamat'           => $this->request->getPost('alamat'),
        // 'keberadaan_usaha' => $this->request->getPost('keberadaan_usaha') ?? null,
        'idprov'           => $idprov,
        'idkab'            => $idkab,
        'idkec'            => $this->request->getPost('idkec'),
        'iddesa'           => $this->request->getPost('iddesa'),
        'latitude'         => null,
        'longitude'        => null,
        'gc_status'        => null,
        'gc_latitude'      => null,
        'gc_longitude'     => null,
        'id_siri_user'     => session('id_user'),
    ];

    try {
        $model->insert($data);

        log_message('info', sprintf(
            'GC Create - ID SBR: %s, User: %s, Nama: %s',
            $idSbr,
            session('id_user'),
            $data['nama_usaha']
        ));

        return redirect()
            ->to(base_url('sbr/gc?idsbr=' . urlencode($idSbr)))
            ->with('success', 'Data usaha berhasil ditambahkan. ID SBR: ' . $idSbr);

    } catch (\Exception $e) {
        log_message('error', 'Error inserting GC: ' . $e->getMessage());

        return redirect()->back()->withInput()
            ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
    }
}

}