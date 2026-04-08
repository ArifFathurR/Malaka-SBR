<?php

namespace App\Cells;

use App\Models\EstablishmentModel;

class SidebarCell
{
    public function pointsBox(): string
    {
        $nama  = session('nama_user') ?? '';
        $model = new EstablishmentModel();
        $points = $model->getUserPoints($nama, '2025-08-26');

        return view('siripages/cells/points_box', ['points' => $points]);
    }
}
