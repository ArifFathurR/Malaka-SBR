<?php

namespace App\Models;

use CodeIgniter\Model;

class EstablishmentGCModel extends Model
{
    protected $table            = 'establishment_gc';
    protected $primaryKey       = 'id_sbr';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_sbr', 'nama_usaha', 'alamat', 'keberadaan_usaha',
        'idprov', 'idkab', 'idkec', 'iddesa', 'kdsls',
        'latitude', 'longitude', 'gc_status',
        'gc_latitude', 'gc_longitude', 'id_siri_user',
        'created_at', 'updated_at'
    ];
    
    // Gunakan timestamps otomatis
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Pencarian ID SBR dengan JOIN ke Master Wilayah
     */
    public function searchByIdSbr($idsbr, $userKab = null)
    {
        $builder = $this->table($this->table);
        $builder->select('establishment_gc.*, 
                          master_kab.nmkab, 
                          master_kec.nmkec, 
                          master_desa.nmdesa');
        
        // Join ke Master Tables
        $builder->join('master_kab', 'master_kab.idkab = establishment_gc.idkab', 'left');
        $builder->join('master_kec', 'master_kec.idkec = establishment_gc.idkec', 'left');
        $builder->join('master_desa', 'master_desa.iddesa = establishment_gc.iddesa', 'left');

        // Filter Security
        if ($userKab && $userKab !== '1400') {
            $builder->where('establishment_gc.idkab', $userKab);
        }

        //$builder->like('establishment_gc.id_sbr', $idsbr);
        $builder->where('establishment_gc.id_sbr', $idsbr);


        return $builder->findAll();
    }
    
    /**
 * Pencarian berdasarkan Nama Usaha dengan JOIN ke Master Wilayah
 */
public function searchByNamaUsaha($namaUsaha, $userKab = null, $limit = 50)
{
    $builder = $this->table($this->table);
    $builder->select('establishment_gc.*, 
                      master_kab.nmkab, 
                      master_kec.nmkec, 
                      master_desa.nmdesa');
    
    $builder->join('master_kab', 'master_kab.idkab = establishment_gc.idkab', 'left');
    $builder->join('master_kec', 'master_kec.idkec = establishment_gc.idkec', 'left');
    $builder->join('master_desa', 'master_desa.iddesa = establishment_gc.iddesa', 'left');
    
    if ($userKab && $userKab !== '1400') {
        $builder->where('establishment_gc.idkab', $userKab);
    }
    
    $builder->like('establishment_gc.nama_usaha', $namaUsaha);
    $builder->limit($limit);
    
    return $builder->findAll();
}
    
   public function searchUsahaGC(string $keyword, int $idkab): array
    {
        $builder = $this->db->table('establishment_gc e')
            ->select([
                'e.id_sbr',
                'e.nama_usaha',
                'e.alamat',
                'e.gc_status',
                'e.gc_latitude',
                'e.gc_longitude',
                'e.latitude',
                'e.longitude',
                'k.nmkab',
                'c.nmkec',
                'd.nmdesa'
            ])
            ->join('master_kab k', 'k.idkab = e.idkab', 'left')
            ->join('master_kec c', 'c.idkec = e.idkec', 'left')
            ->join('master_desa d', 'd.iddesa = e.iddesa', 'left');
    
        // ✅ kalau admin provinsi (1400), JANGAN where idkab
        // (opsional: kalau mau tetap batasi provinsi, pakai where e.idprov=14)
        if ($idkab !== 1400) {
            $builder->where('e.idkab', $idkab);
        } else {
            $builder->where('e.idprov', 14); // opsional, kalau memang hanya provinsi Riau
        }
    
        $builder->groupStart()
                ->where('e.id_sbr', $keyword)      // exact
                ->orLike('e.nama_usaha', $keyword) // like
            ->groupEnd()
            ->orderBy('e.nama_usaha', 'ASC')
            ->limit(20);
    
        return $builder->get()->getResultArray();
    }


    
    public function getKabListForSession(int $sessionIdkab): array
    {
        $builder = $this->db->table('master_kab')
            ->select('idkab, nmkab')
            ->orderBy('idkab', 'ASC');
    
        if ($sessionIdkab !== 1400) {
            $builder->where('idkab', $sessionIdkab);
        }
    
        return $builder->get()->getResultArray();
    }


    // =========================
    // EXPORT DATA
    // =========================
    public function getForExportByKab(int $idkab): array
    {
        $builder = $this->db->table('establishment_gc e')
            ->select([
                'e.id_sbr',
                'e.nama_usaha',
                'e.alamat',
                'e.keberadaan_usaha',
                'e.idprov',
                'e.idkab',
                'e.idkec',
                'e.iddesa',
                'e.latitude',
                'e.longitude',
                'e.gc_status',
                'e.gc_latitude',
                'e.gc_longitude',
                'e.id_siri_user',
                'u.nama_user',
            ])
            ->join('siri_user u', 'u.id_siri_user = e.id_siri_user', 'left')
            ->where('e.idkab', $idkab)
            // ✅ hanya yang gc_status terisi
            ->where('e.gc_status IS NOT NULL', null, false)
            ->where("TRIM(e.gc_status) <> ''", null, false)
            ->orderBy('e.id_sbr', 'ASC');

        return $builder->get()->getResultArray();
    }
    
    // ── Kecamatan list berdasarkan idkab user ──────────────────────────
public function getKecListByKab(string $idkab): array
{
    $builder = $this->db->table('master_kec k')
        ->select('k.idkec, k.nmkec')
        ->join('establishment e', 'e.idkec = k.idkec', 'inner')  // ganti 'establishment' sesuai tabel usaha Anda
        ->groupBy('k.idkec, k.nmkec')
        ->orderBy('k.nmkec', 'ASC');
 
    if (!empty($idkab) && $idkab !== '1400') {
        $builder->where('e.idkab', $idkab);
    }
 
    return $builder->get()->getResultArray();
}
 
// ── Desa list per kecamatan (lazy) ────────────────────────────────
public function getDesaByKec(string $idkec): array
{
    return $this->db->table('master_desa')
        ->select('iddesa, nmdesa')
        ->where('idkec', $idkec)
        ->orderBy('nmdesa', 'ASC')
        ->get()
        ->getResultArray();
}
 
// ── Data tabel terfilter dengan pagination ─────────────────────────
public function getFiltered(string $userKab, array $f, int $limit, int $offset): array
{
    $builder = $this->db->table($this->table . ' e')
        ->select('
            e.id_sbr, e.nama_usaha, e.alamat,
            e.idkec, e.iddesa,
            e.latitude, e.longitude,
            e.gc_status, e.gc_latitude, e.gc_longitude,
            k.nmkec, d.nmdesa
        ')
        ->join('master_kec k',   'k.idkec  = e.idkec',  'left')
        ->join('master_desa d',  'd.iddesa = e.iddesa',  'left')
        ->orderBy('e.id_sbr', 'ASC')
        ->limit($limit, $offset);
 
    // Scope per kabupaten (kecuali admin 1400)
    if (!empty($userKab) && $userKab !== '1400') {
        $builder->where('e.idkab', $userKab);
    }
 
    // Filter opsional
    if (!empty($f['idsbr']))  $builder->like('e.id_sbr',    $f['idsbr'],  'both');
    if (!empty($f['nama']))   $builder->like('e.nama_usaha', $f['nama'],   'both');
    if (!empty($f['idkec']))  $builder->where('e.idkec',     $f['idkec']);
    if (!empty($f['iddesa'])) $builder->where('e.iddesa',    $f['iddesa']);
 
    // Status: '0' = belum diupdate (NULL / kosong), lainnya literal
    if ($f['status'] !== '') {
        if ($f['status'] === '0') {
            $builder->groupStart()
                ->where('e.gc_status', null)
                ->orWhere('e.gc_status', '')
                ->groupEnd();
        } else {
            $builder->where('e.gc_status', $f['status']);
        }
    }
 
    return $builder->get()->getResultArray();
}
 
// ── Hitung total rows (untuk pagination) ──────────────────────────
public function countFiltered(string $userKab, array $f): int
{
    $builder = $this->db->table($this->table . ' e');
 
    if (!empty($userKab) && $userKab !== '1400') {
        $builder->where('e.idkab', $userKab);
    }
    if (!empty($f['idsbr']))  $builder->like('e.id_sbr',    $f['idsbr'],  'both');
    if (!empty($f['nama']))   $builder->like('e.nama_usaha', $f['nama'],   'both');
    if (!empty($f['idkec']))  $builder->where('e.idkec',     $f['idkec']);
    if (!empty($f['iddesa'])) $builder->where('e.iddesa',    $f['iddesa']);
 
    if ($f['status'] !== '') {
        if ($f['status'] === '0') {
            $builder->groupStart()
                ->where('e.gc_status', null)
                ->orWhere('e.gc_status', '')
                ->groupEnd();
        } else {
            $builder->where('e.gc_status', $f['status']);
        }
    }
 
    return (int)$builder->countAllResults();
}


/**
 * Generate ID SBR baru (auto-increment sederhana berbasis idkab)
 * Format: {idkab}{urutan 6 digit}
 * Sesuaikan pola ini dengan format ID SBR sistem Anda.
 */
public function generateIdSbr(string $idkab): string
{
    $last = $this->db->table($this->table)
        ->select('id_sbr')
        ->like('id_sbr', $idkab, 'after')
        ->orderBy('id_sbr', 'DESC')
        ->limit(1)
        ->get()
        ->getRowArray();

    if (!$last) {
        return $idkab . '000001';
    }

    $num = (int) substr($last['id_sbr'], strlen($idkab));
    return $idkab . str_pad($num + 1, 6, '0', STR_PAD_LEFT);
}

/**
 * Ambil daftar kecamatan berdasarkan idkab (untuk form create)
 */
public function getKecByKab(string $idkab): array
{
    $builder = $this->db->table('master_kec')
        ->select('idkec, nmkec')
        ->orderBy('nmkec', 'ASC');

    if (!empty($idkab) && $idkab !== '1400') {
        $builder->where('idkab', $idkab);
    }

    return $builder->get()->getResultArray();
}

}