<?php

namespace App\Controllers;

use App\Models\WilayahModel;
use CodeIgniter\Controller;

class Wilayah extends Controller
{
    public function kecamatan()
    {
        $idkab = session('idkab');
        $model = new WilayahModel();
        $data = $model->getKecamatan($idkab);
        return $this->response->setJSON($data);
    }

    public function desa()
    {
        $kode_kec = $this->request->getGet('kode_kec');
        $model = new WilayahModel();
        $data = $model->getDesa($kode_kec);
        return $this->response->setJSON($data);
    }
}
