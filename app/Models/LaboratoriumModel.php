<?php namespace App\Models;

use CodeIgniter\Model;

class LaboratoriumModel extends Model
{
    protected $table = 'establishment';
    protected $primaryKey = 'placeId';
    protected $returnType = 'array';
    protected $allowedFields = [
        'placeId','placeName','placeAddress','placeLatitude','placeLongitude',
        'status_usaha','kategori','last_update_by','iddesa'
    ];

    public function getUsahaByIdDesa(string $iddesa): array
    {
        if (empty($iddesa)) return [];
        return $this->select('placeId, placeName, placeAddress, placeLatitude, placeLongitude, status_usaha, kategori, last_update_by, iddesa')
                    ->where('iddesa', $iddesa)
                    ->findAll();
    }
}
