<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Mentari_model;

class Dashboard extends Controller
{
    protected $mentariModel;

    public function __construct()
    {
        $this->mentariModel = new Mentari_Model();
    }

    public function index()
    {
        $bulan = $this->request->getGet('bulan') ?? date('n');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $chartWilayah = $this->mentariModel->getWilayahBarChart($bulan, $tahun);

        $prosesAktif = $this->mentariModel->countProsesSedangBerlangsung();
        $prosesSelesai = $this->mentariModel->countProsesByStatus(1);
        $kegiatantelatSelesai = $this->mentariModel->countKegiatanTelatSelesai();
        $jumlahTim = $this->mentariModel->countTim();
        $persentaseRealisasi = $this->mentariModel->getPersentaseRealisasi();

        $kegiatanList = $this->mentariModel->getAllKegiatanDetailWithTim();

        $chartLineData = $this->mentariModel->getLineChartData();
        $chartBarData = $this->mentariModel->getBarChartData();

     //   return view('pages/test_chart');

        return view('pages/dashboard_list', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'chartWilayah' => $chartWilayah,
            'prosesAktif' => $prosesAktif,
            'prosesSelesai' => $prosesSelesai,
            'persentaseRealisasi' => $persentaseRealisasi,
            'kegiatantelatSelesai' => $kegiatantelatSelesai,
            'jumlahTim' => $jumlahTim,
            'kegiatanList' => $kegiatanList,
            'chartLineData' => $chartLineData,
            'chartBarData' => $chartBarData
        ]);
    }

    public function getWilayahChartAjax()
    {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $chartWilayah = $this->mentariModel->getWilayahBarChart($bulan, $tahun);
        return $this->response->setJSON($chartWilayah);
    }
}

