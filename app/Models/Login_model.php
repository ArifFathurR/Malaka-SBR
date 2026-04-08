<?php

namespace App\Models;

use CodeIgniter\Model;

class Login_model extends Model
{
    protected $table = 'siri_user'; // Tabel yang digunakan
    protected $primaryKey = 'id_siri_user'; // Primary key tabel
    protected $allowedFields = [
        'id_siri_user', 'nama_user', 'email', 'hp', 'jabatan', 
        'nama_instansi', 'tingkat_satker', 'is_active', 
        'is_admin', 'id_role', 'idkab', 'linkfoto'
    ];

    /**
     * Cek apakah user sudah ada di database.
     *
     * @param string $idUser
     * @return array|null
     */
    public function getUserById($idUser)
    {
        return $this->where('id_siri_user', $idUser)->first();
    }

    /**
     * Tambahkan user baru ke database.
     *
     * @param array $data
     * @return bool
     */
    public function addUser($data)
    {
        return $this->insert($data);
    }
}
