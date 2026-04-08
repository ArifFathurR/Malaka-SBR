<?php

namespace App\Controllers;

use App\Models\Sirilogin_model;

class Homelogin extends BaseController
{

    public function index()
    {
        $this->session = \Config\Services::session();

        if ($this->session->has('isLogin')) {
            return redirect()->to('sbr/gc');
        }

        // return view('layout/v_login');
        return view('pantau/v_login_depan');
    }

    public function mitra()
    {
        $this->session = \Config\Services::session();

        if ($this->session->has('isLogin')) {
            return redirect()->to('sbr/gc');
        }

        // return view('layout/v_login');
        return view('pantau/v_login_mitra');
    }
    
    public function pegawai_nonsso()
    {
        $this->session = \Config\Services::session();

        if ($this->session->has('isLogin')) {
            return redirect()->to('sbr/gc');
        }

        // return view('layout/v_login');
        return view('pantau/v_login_pegawai_nonSSO');
    }

    /**
     * Tampilkan halaman form registrasi mitra
     */
    public function register()
    {
        $this->session = \Config\Services::session();

        if ($this->session->has('isLogin')) {
            return redirect()->to('sbr/gc');
        }

        // Ambil daftar kabupaten untuk dropdown
        $db = \Config\Database::connect();
        $kabupaten = $db->table('master_kab')->orderBy('nmkab', 'ASC')->get()->getResultArray();

        return view('pantau/v_register_mitra', ['kabupaten' => $kabupaten]);
    }

    /**
     * Proses pendaftaran user baru
     */
    public function do_register()
    {
        $id_siri_user        = trim((string) $this->request->getPost('id_siri_user'));
        $nama_user           = trim((string) $this->request->getPost('nama_user'));
        $idkab               = $this->request->getPost('idkab');
        $hp                  = trim((string) $this->request->getPost('hp'));
        $email               = trim((string) $this->request->getPost('email'));
        $password            = (string) $this->request->getPost('password');
        $konfirmasi_password = (string) $this->request->getPost('konfirmasi_password');

        log_message('info', '[Register] Attempt: id=' . $id_siri_user . ' idkab=' . $idkab);

        // --- Validasi server-side ---
        if (empty($id_siri_user) || empty($nama_user) || empty($idkab) || empty($password)) {
            log_message('warning', '[Register] Validasi gagal: field wajib kosong');
            return redirect()->to('homelogin/register')
                ->withInput()
                ->with('error', 'Semua kolom wajib diisi!');
        }

        if (strlen($password) < 6) {
            return redirect()->to('homelogin/register')
                ->withInput()
                ->with('error', 'Password minimal 6 karakter!');
        }

        if ($password !== $konfirmasi_password) {
            return redirect()->to('homelogin/register')
                ->withInput()
                ->with('error', 'Password dan konfirmasi password tidak cocok!');
        }

        $model = new Sirilogin_model();

        // Cek apakah SOBAT ID sudah terdaftar (langsung via DB, bypass cache model)
        $db = \Config\Database::connect();
        $existing = $db->table('siri_user')->where('id_siri_user', $id_siri_user)->countAllResults();
        if ($existing > 0) {
            log_message('warning', '[Register] SOBAT ID sudah ada: ' . $id_siri_user);
            return redirect()->to('homelogin/register')
                ->withInput()
                ->with('error', 'SOBAT ID ' . esc($id_siri_user) . ' sudah terdaftar. Silakan login.');
        }

        // Simpan user baru langsung via DB insert
        $berhasil = $db->table('siri_user')->insert([
            'id_siri_user' => $id_siri_user,
            'password'     => md5($password),
            'nama_user'    => $nama_user,
            'idkab'        => $idkab,
            'hp'           => $hp    !== '' ? $hp    : null,
            'email'        => $email !== '' ? $email : null,
            'jabatan'      => "mitra statistik",
            'nama_instansi' => "BPS",
            'tingkat_satker' => "Kabkota",
            'id_role'      => 4,
            'is_admin'     => 0,
            'is_active'    => 1,
        ]);

        if ($berhasil) {
            log_message('info', '[Register] Berhasil: ' . $id_siri_user);
            return redirect()->to('homelogin/mitra')
                ->with('success', 'Pendaftaran berhasil! Silakan login dengan SOBAT ID dan password Anda.');
        } else {
            // Tampilkan error dari model untuk debug
            $errors = $model->errors();
            $errMsg = !empty($errors) ? implode(', ', $errors) : 'Kesalahan tidak diketahui.';
            log_message('error', '[Register] Insert gagal: ' . $errMsg);
            return redirect()->to('homelogin/register')
                ->withInput()
                ->with('error', 'Gagal mendaftar: ' . $errMsg);
        }
    }

}
