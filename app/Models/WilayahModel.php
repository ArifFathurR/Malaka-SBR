<?php

namespace App\Models;

use CodeIgniter\Model;

class WilayahModel extends Model
{
    public function getKecamatan($idkab)
    {
        return $this->db->table('master_kec')
            ->where('idkab', $idkab)
            ->orderBy('nmkec')
            ->get()
            ->getResultArray();
    }

    public function getDesa($idkec)
    {
        return $this->db->table('master_desa')
            ->where('idkec', $idkec)
            ->orderBy('nmdesa')
            ->get()
            ->getResultArray();
    }
}
