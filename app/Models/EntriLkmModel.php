<?php

namespace App\Models;

use CodeIgniter\Model;

class EntriLkmModel extends Model
{
    protected $table         = 'entri_lkm';
    protected $primaryKey    = 'id_entri_lkm';
    protected $returnType    = 'array';

    // Auto-isi kolom tanggal_update setiap insert/update
    protected $useTimestamps = true;
    protected $createdField  = '';                    // tidak pakai created_at
    protected $updatedField  = 'tanggal_update';

    protected $allowedFields = [
        'iddesa',
        'id_siri_user',
        'kodesls',
        'no_segmen',
        'jmlkk',
        'btt',
        'bttk',
        'bku',
        'bbtt_nu',
        'perkiraan_usaha',
        'total_muatan',
        'tanggal_update',
    ];

    /**
     * Ambil semua baris untuk kombinasi iddesa + kodesls, beserta nama user terakhir.
     */
    public function listByDesaSlsWithUser(string $iddesa, string $kodesls): array
    {
        return $this->select('entri_lkm.*, u.nama_user')
            ->join('siri_user u', 'u.id_siri_user = entri_lkm.id_siri_user', 'left')
            ->where('entri_lkm.iddesa', $iddesa)
            ->where('entri_lkm.kodesls', $kodesls)
            ->orderBy('entri_lkm.no_segmen', 'ASC')
            ->findAll();
    }

    /**
     * Upsert satu baris berdasarkan (iddesa, kodesls, no_segmen).
     */
    public function upsertByKey(array $data): bool
    {
        $existing = $this->where('iddesa', $data['iddesa'])
                         ->where('kodesls', $data['kodesls'])
                         ->where('no_segmen', $data['no_segmen'])
                         ->first();
        if ($existing) {
            $data['id_entri_lkm'] = $existing['id_entri_lkm'];
        }
        return $this->save($data);
    }

    public function listSlsByUser(string $idSiriUser): array
    {
        // group per SLS yang diisi user ini
        return $this->db->table('entri_lkm e')
            ->select("
                d.idkec,
                k.nmkec,
                e.iddesa,
                d.nmdesa,
                e.kodesls,
                SUM(e.total_muatan) AS total_sls
            ")
            ->join('master_desa d', 'd.iddesa = e.iddesa', 'left')
            ->join('master_kec k', 'k.idkec = d.idkec', 'left')
            ->where('e.id_siri_user', $idSiriUser)
            ->groupBy('d.idkec, k.nmkec, e.iddesa, d.nmdesa, e.kodesls')
            ->orderBy('k.nmkec ASC, d.nmdesa ASC, e.kodesls ASC')
            ->get()
            ->getResultArray();
    }

    public function getExportByKab(string $sessionIdkab = '1400'): array
    {
        $b = $this->db->table('entri_lkm e')
            ->select("
                LEFT(e.iddesa,4) AS idkab,
                LEFT(e.iddesa,7) AS idkec,
                k.nmkec,
                e.iddesa,
                d.nmdesa,
                e.kodesls,
                e.no_segmen,
                e.jmlkk, e.btt, e.bttk, e.bku, e.bbtt_nu, e.perkiraan_usaha, e.total_muatan,
                e.tanggal_update,
                e.id_siri_user,
                u.nama_user
            ")
            ->join('master_desa d', 'd.iddesa = e.iddesa', 'left')
            ->join('master_kec k', 'k.idkec = LEFT(e.iddesa,7)', 'left')
            ->join('siri_user u', 'u.id_siri_user = e.id_siri_user', 'left');
    
        if ($sessionIdkab !== '1400') {
            $b->where('LEFT(e.iddesa,4)', $sessionIdkab);
        }
    
        return $b->orderBy('idkab, idkec, e.iddesa, e.kodesls, e.no_segmen')->get()->getResultArray();
    }

    
}
