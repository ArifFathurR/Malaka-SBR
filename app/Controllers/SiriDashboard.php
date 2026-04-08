<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Siri_model;

class SiriDashboard extends Controller
{
    protected $siriModel;

    public function __construct()
    {
        $this->siriModel = new Siri_model();
    }

    public function indexing()
    {
        $bulan = $this->request->getGet('bulan') ?? date('n');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $chartWilayah = $this->siriModel->getWilayahBarChart($bulan, $tahun);

        $prosesAktif = $this->siriModel->countProsesSedangBerlangsung();
        $prosesSelesai = $this->siriModel->countProsesByStatus(1);
        $kegiatantelatSelesai = $this->siriModel->countKegiatanTelatSelesai();
        $jumlahTim = $this->siriModel->countTim();
        $persentaseRealisasi = $this->siriModel->getPersentaseRealisasi();

        $kegiatanList = $this->siriModel->getAllKegiatanDetailWithTim();

        $chartLineData = $this->siriModel->getLineChartData();
        $chartBarData = $this->siriModel->getBarChartData();

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

    public function index()
    {
        
    }

    public function getWilayahChartAjax()
    {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $chartWilayah = $this->siriModel->getWilayahBarChart($bulan, $tahun);
        return $this->response->setJSON($chartWilayah);
    }
}

