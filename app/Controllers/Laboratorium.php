<?php namespace App\Controllers;

use App\Models\LaboratoriumModel;
use CodeIgniter\Controller;

class Laboratorium extends Controller
{
    protected $model;
    // Masukkan Google Maps key di sini (jangan commit ke repo publik)
    protected $googleMapsKey = 'AIzaSyAYaRBajjixtB0T1HqWEXOHbTDpJ8Tjcyc';

    public function __construct()
    {
        helper(['url','filesystem','text']);
        $this->model = new LaboratoriumModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Daftar Usaha Berdasarkan Lokasi',
            'selected_kabupaten' => $this->request->getGet('kabupaten') ?? '',
            'selected_kecamatan' => $this->request->getGet('kecamatan') ?? '',
            'selected_desa' => $this->request->getGet('desa') ?? '',
            'statusLabels' => [0 => 'Tidak Aktif', 1 => 'Aktif'],
            'kategoriLabels' => [1 => 'Domestik', 2 => 'Industri', 3 => 'Jasa'],
            'googleMapsKey' => $this->googleMapsKey,
        ];

        return view('siripages/laboratorium/usaha', $data);
    }

    public function getGeojsonFiles($iddesa = null)
    {
        $iddesa = preg_replace('/[^0-9]/', '', (string)$iddesa);
        if (empty($iddesa)) return $this->response->setJSON(['success'=>false,'message'=>'Invalid iddesa']);

        $mapPath = FCPATH . 'lokasi' . DIRECTORY_SEPARATOR . 'map' . DIRECTORY_SEPARATOR;
        if (!is_dir($mapPath)) {
            log_message('error', "Laboratorium::getGeojsonFiles - mapPath not found: {$mapPath}");
            return $this->response->setJSON(['success'=>false,'message'=>'Folder lokasi/map tidak ditemukan.','mapPathChecked'=>$mapPath]);
        }

        $pattern = $mapPath . $iddesa . '*.geojson';
        $files = glob($pattern);
        $result = [];
        if ($files) {
            foreach ($files as $f) {
                if (is_file($f) && is_readable($f)) {
                    $basename = basename($f);
                    $url = base_url('lokasi/map/' . $basename);
                    $result[] = ['file'=>$basename,'url'=>$url];
                } else {
                    log_message('warning', "Laboratorium::getGeojsonFiles - not readable: {$f}");
                }
            }
        }

        return $this->response->setJSON(['success'=>true,'files'=>$result]);
    }

    public function getUsaha($iddesa = null)
    {
        $iddesa = preg_replace('/[^0-9]/', '', (string)$iddesa);
        if (empty($iddesa)) return $this->response->setJSON(['success'=>false,'message'=>'Invalid iddesa']);

        try {
            $rows = $this->model->getUsahaByIdDesa($iddesa);
            if (!is_array($rows)) $rows = [];

            // Normalisasi lat/lng: koma -> dot, cast ke float atau null
            foreach ($rows as &$r) {
                $latRaw = $r['placeLatitude'] ?? $r['latitude'] ?? $r['lat'] ?? ($r['kakilima_latitude'] ?? null);
                $lngRaw = $r['placeLongitude'] ?? $r['longitude'] ?? $r['lng'] ?? ($r['kakilima_longitude'] ?? null);
                $lat = null; $lng = null;
                if ($latRaw !== null && $latRaw !== '') {
                    $latClean = str_replace(',', '.', (string)$latRaw);
                    if (is_numeric($latClean)) $lat = (float)$latClean;
                }
                if ($lngRaw !== null && $lngRaw !== '') {
                    $lngClean = str_replace(',', '.', (string)$lngRaw);
                    if (is_numeric($lngClean)) $lng = (float)$lngClean;
                }
                $r['placeLatitude'] = $lat;
                $r['placeLongitude'] = $lng;
            }
            unset($r);

            return $this->response->setJSON(['success'=>true,'data'=>$rows]);
        } catch (\Throwable $e) {
            log_message('error','Laboratorium::getUsaha error: '.$e->getMessage());
            return $this->response->setJSON(['success'=>false,'message'=>'Server error saat mengambil data usaha']);
        }
    }

    public function debugMapPaths()
    {
        $p = FCPATH . 'lokasi' . DIRECTORY_SEPARATOR . 'map' . DIRECTORY_SEPARATOR;
        return $this->response->setJSON([
            'FCPATH'=>FCPATH,
            'checked_path'=>$p,
            'exists'=>is_dir($p),
            'files'=>is_dir($p) ? array_values(array_map('basename', glob($p . '*'))) : []
        ]);
    }
}
