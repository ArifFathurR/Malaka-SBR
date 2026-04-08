<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class LaporanSipantauController extends Controller
{
    public function index()
    {
        $session = session();
        $sobat_id = $session->get('id_user');

        $client = \Config\Services::curlrequest();
        $url = 'https://webapps.bps.go.id/riau/sipantau/api/transaksi/' . $sobat_id;

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer x'
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            return view('siripages/laporanSipantauSaya', ['laporan' => $result['data'] ?? []]);

        } catch (\Exception $e) {
            return view('siripages/laporanSipantauSaya', ['laporan' => [], 'error' => $e->getMessage()]);
        }
    }
}
