<?php

namespace App\Models;

use CodeIgniter\Model;

class Sirilogin_model extends Model
{
    protected $table = 'siri_user';
    protected $primaryKey = 'id_siri_user';
    protected $allowedFields = [
        'id_siri_user', 'password', 'nama_user', 'idkab',
        'hp', 'jabatan', 'nama_instansi', 'linkfoto',
        'email', 'id_role', 'is_admin'
    ];

    public function cekLogin($username, $password)
    {
        // Validasi input kosong
        if (empty($username) || empty($password)) {
            log_message('warning', 'Login gagal: Input kosong');
            return false;
        }

        // Ambil data user
        $user = $this->where('id_siri_user', $username)->first();

        // Cek apakah user ditemukan
        if (!$user) {
            log_message('warning', 'Login gagal: User tidak ditemukan - ' . $username);
            return false;
        }

        // Verifikasi password (gunakan md5 sesuai sistem yang sekarang)
        if ($user['password'] !== md5($password)) {
            log_message('warning', 'Login gagal: Password salah untuk user ' . $username);
            return false;
        }

        // Semua valid
        return $user;
    }
}
