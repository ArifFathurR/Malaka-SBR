<?php

namespace App\Models;
use CodeIgniter\Model;

class EstablishmentLaborModel extends Model
{
    protected $table            = 'establishment_labor';
    protected $primaryKey       = 'laborId';
    protected $allowedFields    = [
        'laborId',
        'laborName',
        'laborAddress',
        'iddesa',
        'kdsls',
        'no_urut_usaha',
        'placeId',
        'laborLatitude',
        'laborLongitude'
    ];
    
    public function getTargetDesa()
    {
        return [
            '1471060006' => 'Harjosari',
            '1471070003' => 'Sago',
        ];
    }


    // ambil desa berdasarkan idkab
    public function getDesaByKab($idkab)
    {
        return $this->db->table('master_desa')
            ->where('LEFT(iddesa,4)', $idkab)
            ->orderBy('nmdesa')
            ->get()->getResultArray();
    }

    // ambil SLS berdasarkan iddesa
    public function getSlsByDesa($iddesa)
    {
        return $this->db->table('master_sls')
            ->where('iddesa', $iddesa)
            ->orderBy('kdsls')
            ->get()->getResultArray();
    }

    // ambil data labor berdasarkan desa & sls
    public function getLaborByDesaSls($iddesa, $kdsls)
    {
        return $this->where([
                'iddesa' => $iddesa,
                'kdsls'  => $kdsls
            ])
            ->orderBy('no_urut_usaha', 'ASC')
            ->findAll();
    }
    
    public function rekapPerDesa(array $desaIds)
    {
        return $this->db->table($this->table)
            ->select('iddesa, COUNT(*) AS jml_usaha')
            ->whereIn('iddesa', $desaIds)
            ->groupBy('iddesa')
            ->orderBy('iddesa')
            ->get()->getResultArray();
    }
    
    public function rekapPerSls(string $iddesa)
    {
        return $this->db->table($this->table . ' l')
            ->select('l.kdsls, s.nmsls, COUNT(*) AS jml_usaha')
            ->join('master_sls s', 's.iddesa = l.iddesa AND s.kdsls = l.kdsls', 'left')
            ->where('l.iddesa', $iddesa)
            ->groupBy('l.kdsls, s.nmsls')
            ->orderBy('l.kdsls', 'ASC')
            ->get()->getResultArray();
    }

    public function cekNomorUrut(string $iddesa, string $kdsls): string
    {
        $rows = $this->db->table($this->table)
            ->select('no_urut_usaha')
            ->where('iddesa', $iddesa)
            ->where('kdsls', $kdsls)
            ->orderBy('no_urut_usaha')
            ->get()->getResultArray();
    
        if (empty($rows)) return '';
    
        $nums = array_column($rows, 'no_urut_usaha');
        sort($nums);
    
        $min = 1;
        $max = max($nums);
    
        $range   = range($min, $max);
        $missing = array_diff($range, $nums);
    
        $count = array_count_values($nums);
        $dupes = array_keys(array_filter($count, function($x){
            return $x > 1;
        }));
    
        $warnings = [];
    
        if (!empty($missing)) {
            $warnings[] = 'Nomor hilang: ' . implode(', ', $missing);
        }
    
        if (!empty($dupes)) {
            $warnings[] = 'Nomor ganda: ' . implode(', ', $dupes);
        }
    
        return implode(' | ', $warnings);
    }

    
}
