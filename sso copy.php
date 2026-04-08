<?php
session_start();
require "vendor/autoload.php";
//$url = $_GET['aplikasi'];
$url = "http://localhost/";
$provider = new JKD\SSO\Client\Provider\Keycloak([
    'authServerUrl'         => 'https://sso.bps.go.id',
    'realm'                 => 'pegawai-bps',
    'clientId'              => '11400-sipantau-se3',
    'clientSecret'          => '5d089991-31d8-46be-b2b3-184f68e5fe90',
    'redirectUri'           => 'https://webapps.bps.go.id/riau/sipantau/sso.php?aplikasi='.$url
]);

if (!isset($_GET['code'])) {
   // echo "1"; exit;
    // Untuk mendapatkan authorization code
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;

// Mengecek state yang disimpan saat ini untuk memitigasi serangan CSRF
} 
/*elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

}  */

else {
  //  echo "2"; exit;

    try {
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);
    } catch (Exception $e) {    
        exit('Gagal mendapatkan akses token : '.$e->getMessage());
    }

    // Opsional: Setelah mendapatkan token, anda dapat melihat data profil pengguna
    try {
        $user = $provider->getResourceOwner($token);
        
        $text = $user->getNip() ."|". $user->getName() ."|". $user->getEmail() ."|". $user->getUsername() ."|". $user->getNipBaru() ."|". $user->getKodeOrganisasi() ."|".
                       $user->getKodeProvinsi() ."|". $user->getKodeKabupaten() ."|". $user->getAlamatKantor() ."|". $user->getProvinsi() ."|". $user->getKabupaten() ."|".
                       $user->getGolongan() ."|". $user->getJabatan() ."|". $user->getUrlFoto() ."|". $user->getEselon();
        $plaintext = base64_encode($text);
        
        header('Location: '.$url .'?token='.  $plaintext);
   //     echo $plainext;
   //     exit;
        
        //$user = $provider->getResourceOwner($token);
        /*
            echo "Nama : ".$user->getName();
            echo "E-Mail : ". $user->getEmail();
            echo "Username : ". $user->getUsername();
            echo "NIP : ". $user->getNip();
            echo "NIP Baru : ". $user->getNipBaru();
            echo "Kode Organisasi : ". $user->getKodeOrganisasi();
            echo "Kode Provinsi : ". $user->getKodeProvinsi();
            echo "Kode Kabupaten : ". $user->getKodeKabupaten();
            echo "Alamat Kantor : ". $user->getAlamatKantor();
            echo "Provinsi : ". $user->getProvinsi();
            echo "Kabupaten : ". $user->getKabupaten();
            echo "Golongan : ". $user->getGolongan();
            echo "Jabatan : ". $user->getJabatan();
            echo "Foto : ". $user->getUrlFoto();
            echo "Eselon : ". $user->getEselon();
            */
            

    } catch (Exception $e) {
        exit('Gagal Mendapatkan Data Pengguna: '.$e->getMessage());
    }

    // Gunakan token ini untuk berinteraksi dengan API di sisi pengguna
    echo $token->getToken();
}

?>
