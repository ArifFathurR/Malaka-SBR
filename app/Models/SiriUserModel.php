<?php

namespace App\Models;
use CodeIgniter\Model;

class SiriUserModel extends Model
{
    protected $table      = 'siri_user';
    protected $primaryKey = 'id_siri_user';
    protected $allowedFields = [
        'id_siri_user',
        'idkab',
        'nama_user',
        'email',
        'hp',
        'jabatan',
        'nama_instansi',
        'tingkat_satker',
        'linkfoto',
        'is_active',
        'is_admin',
        'id_role',
        'password'
    ];

    // Optional
    protected $useTimestamps = false;
}

