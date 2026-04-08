<?php

use App\Models\EstablishmentModel;

class EstablishmentController extends BaseController
{
    public function index()
    {
        $idkab = session()->get('idkab');
        $model = new EstablishmentModel();

        $usaha = $model->getEstablishmentByKab($idkab);
        $kecamatan = $model->getKecamatan($idkab);

        return view('siripages/tabelusaha', [
            'usaha' => $usaha,
            'kecamatan' => $kecamatan,
        ]);
    }

    public function getKecamatan($idkab)
    {
        $model = new EstablishmentModel();
        return json_encode($model->getKecamatan($idkab));
    }

    public function getDesa($idkec)
    {
        $model = new EstablishmentModel();
        return json_encode($model->getDesa($idkec));
    }

    public function update()
    {
        $model = new EstablishmentModel();

        $placeId = $this->request->getPost('placeId');
        $status = $this->request->getPost('status_usaha');
        $kategori = $this->request->getPost('kategori');
        $idUser = session()->get('id_siri_user');
        $waktu = date('Y-m-d H:i:s');

        // Simpan ke history
        $model->insertHistory([
            'placeId' => $placeId,
            'id_siri_user' => $idUser,
            'waktu_input' => $waktu,
            'status_usaha' => $status,
            'kategori' => $kategori
        ]);

        // Update utama
        $model->update($placeId, [
            'status_usaha' => $status,
            'kategori' => $kategori,
            'last_update_by' => "$waktu oleh $idUser"
        ]);

        return redirect()->to(base_url('usaha'))->with('message', 'Berhasil diupdate');
    }
}
