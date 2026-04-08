<?php

namespace App\Controllers;

class Home extends BaseController
{

    public function index()
    {
        $this->session = \Config\Services::session();

        if ($this->session->has('id_user')) {
            return redirect()->to('do/beranda');
        }

        return view('pantau/v_login_depan');
    }


}
