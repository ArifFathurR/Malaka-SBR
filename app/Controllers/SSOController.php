<?php

namespace App\Controllers;

ob_start();

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");

use App\Libraries\SSOLibrary;
use \App\Models\Login_model;
use \App\Models\Loginshp_model;

class SSOController extends BaseController
{
    private $sso;

    public function __construct()
    {
        $this->sso = new SSOLibrary();
    }

    public function login()
    {
        // Arahkan user ke URL login SSO
        return redirect()->to($this->sso->getLoginUrl());
    }

    public function callback()
    {
        $this->session = \Config\Services::session();

        $code = $this->request->getGet('code');

        if (!$code) {
            echo "Login gagal: kode otorisasi tidak ditemukan.";
            return;
        }

        try {
            $accessToken = $this->sso->getAccessToken($code); // Ambil token akses
            if (!$accessToken) {
                echo "Login gagal: token akses tidak ditemukan.";
                return;
            }

            $userInfo = $this->sso->getUserInfo($accessToken); // Ambil informasi pengguna

            // Inisialisasi model
            $mentariModel = new Login_model();

            // Cek apakah user sudah ada di tabel `user`
            $existingUser = $mentariModel->getUserById($userInfo['nip']);

            if (!$existingUser) {
                // Proses download file foto
                $fotoUrl = $userInfo['foto'];
                $newFileName = $userInfo['nip'] . '.jpg'; // Nama file baru berdasarkan id_user
                $uploadPath = WRITEPATH . '../public/file/fotouser/'; // Folder untuk menyimpan file
                $savedPath = $uploadPath . $newFileName; // Path lengkap file yang disimpan
    
                // Download file dari URL
                $fileContent = file_get_contents($fotoUrl);
                if ($fileContent === false) {
                    throw new \Exception('Gagal mengunduh foto pengguna.');
                }
    
                // Simpan file ke server
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true); // Buat folder jika belum ada
                }
                file_put_contents($savedPath, $fileContent);
                
                // Tentukan tingkat_satker
                $kodeOrganisasi = substr($userInfo['organisasi'], 2, 2); // Ambil digit ke-3 dan ke-4
                $tingkatSatker = ($kodeOrganisasi === '00') ? 'Provinsi' : 'Kabkota';

                // Tambahkan user ke tabel `user`
                $data = [
                    'id_user' => $userInfo['nip'],
                    'nama_user' => $userInfo['name'],
                    'email' => $userInfo['email'],
                    'hp' => '', // Kosong
                    'jabatan' => $userInfo['jabatan'],
                    'nama_instansi' => $userInfo['kabupaten'],
                    'tingkat_satker' => $tingkatSatker,
                    'is_active' => 1,
                    'is_admin' => 0,
                    'id_role' => 5,
                    'idkab' => substr($userInfo['organisasi'], 0, 4),
                    'linkfoto' => base_url('public/file/fotouser/' . $newFileName) // Simpan path foto
                ];
    
                $mentariModel->addUser($data);
            }
    
            // Atur session login
            $sessionData = [
                'id_user' => $userInfo['nip'],
                'nama_user' => $userInfo['name'],
                'jabatan' => $userInfo['jabatan'],
                'email' => $userInfo['email'],
                'id_role' => isset($userInfo['id_role']) ? $userInfo['id_role'] : ($existingUser ? $existingUser['id_role'] : 5), 
                'idkab' => substr($userInfo['organisasi'], 0, 4),
                'nama_instansi' => $userInfo['kabupaten'],
                'avatar' => base_url('public/file/fotouser/' . $userInfo['nip'] . '.jpg'), // Path foto untuk session
            ];

            //session()->set($sessionData);
            $data['session'] = $this->session->set($sessionData);

            // Redirect ke halaman utama
            return redirect()->to('/sbr/gc');
        } catch (\Exception $e) {
            echo "Login gagal: " . $e->getMessage();
        }
    }

    // Fungsi logout
    public function logout()
    {
        // Hapus semua session
        session()->destroy();

        // Redirect ke halaman login
        return redirect()->to('/');
    }
}
