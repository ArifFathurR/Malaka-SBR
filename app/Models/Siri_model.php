<?php

namespace App\Models;

use CodeIgniter\Model;

class Siri_model extends Model
{

    // ================================================================================================================================================
    function ambilsatker($p)
    {
        $query = "SELECT * from master_kab where idkab = ?";

        return $this->db->query($query, $p);
    }


    
}


