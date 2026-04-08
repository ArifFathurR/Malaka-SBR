<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Pelaporan extends BaseController
{
    protected $apiUrl = 'https://webapps.bps.go.id/riau/sipantau/api/pelaporan';
    protected $apiKey = '5537-HIJKL-55961';

    public function index()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
    
        
        $client = \Config\Services::curlrequest();
    
        try {
            $response = $client->get($this->apiUrl, [
                'headers' => [
                    'X-API-KEY' => $this->apiKey,
                    'X-USER-ID' => session('id_user')
                ],
                'verify' => false // sementara, bisa dihapus jika SSL-nya valid
            ]);
    
            $data = json_decode($response->getBody(), true);
    
            return view('siripages/pelaporanSipantau', [
                'dataPelaporan' => $data
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Gagal ambil data API: ' . $e->getMessage());
            return view('siripages/pelaporanSipantau', [
                'dataPelaporan' => [],
                'error' => 'Gagal mengambil data dari server pelaporan.'
            ]);
        }
    }

    public function simpan()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
    
        
        $client = \Config\Services::curlrequest();
    
        $imageBase64 = $this->request->getPost('image');
        $resume = $this->request->getPost('resume');
        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');
        $idkec = $this->request->getPost('idkec');
        $iddesa = $this->request->getPost('iddesa');
    
        // Validasi sederhana
        if (empty($imageBase64) || strpos($imageBase64, 'data:image') !== 0) {
            return redirect()->to('/pelaporan')->with('error', 'Gambar tidak valid atau belum diunggah.');
        }
    
        // Persiapan payload
        $postData = [
            'sobat_id' => session('id_user'),
            'resume' => $resume,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'idkec' => $idkec,
            'iddesa' => $iddesa,
            'image' => $imageBase64
        ];
    
        try {
            $response = $client->post($this->apiUrl, [
                'headers' => [
                    'X-API-KEY' => $this->apiKey
                ],
                'form_params' => $postData,
                'verify' => false
            ]);
    
            if ($response->getStatusCode() === 201) {
                return redirect()->to('/pelaporan/laporan-saya')->with('success', 'Pelaporan berhasil dikirim.');
            } else {
                return redirect()->to('/pelaporan')->with('error', 'Pelaporan gagal dikirim. Server menolak permintaan.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/pelaporan')->with('error', 'Kesalahan koneksi ke server pelaporan: ' . $e->getMessage());
        }
    }
    
    public function dataLaporanSaya()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
    
        
        $session = session();
        $sobat_id = $session->get('id_user');

        $client = \Config\Services::curlrequest();
        $url = 'https://webapps.bps.go.id/riau/sipantau/api/transaksi/' . $sobat_id;

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer 5537-HIJKL-559610'
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            return view('siripages/laporanSipantauSaya', ['laporan' => $result['data'] ?? []]);

        } catch (\Exception $e) {
            return view('siripages/laporanSipantauSaya', ['laporan' => [], 'error' => $e->getMessage()]);
        }
    }
    
    public function hapus()
    {
        $id = $this->request->getPost('id_laporan');
        if (!$id) {
            return redirect()->to('/pelaporan/laporan-saya')->with('error', 'ID laporan tidak ditemukan.');
        }
    
        $client = \Config\Services::curlrequest();
        $url = 'https://webapps.bps.go.id/riau/sipantau/api/pelaporan/hapus/' . $id;
    
        try {
            $response = $client->delete($url, [
                'headers' => [
                    'X-API-KEY' => $this->apiKey,
                    'X-USER-ID' => session('id_user')
                ],
                'verify' => false
            ]);
    
            $code = $response->getStatusCode();
    
            if ($code === 200 || $code === 204) {
                return redirect()->to('/pelaporan/laporan-saya')->with('success', 'Laporan berhasil dihapus.');
            } else {
                return redirect()->to('/pelaporan/laporan-saya')->with('error', 'Gagal menghapus laporan.');
            }
    
        } catch (\Exception $e) {
            return redirect()->to('/pelaporan/laporan-saya')->with('error', 'Kesalahan koneksi: ' . $e->getMessage());
        }
    }



}
