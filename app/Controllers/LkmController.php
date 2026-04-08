<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EntriLkmModel;
use App\Models\EstablishmentModel;

class LkmController extends BaseController
{
    private function currentUserId(): ?string
    {
        return session('id_siri_user') ?? session('id_user') ?? null;
    }

    public function index()
    {
        $idkecQS   = $this->request->getGet('idkec');
        $iddesaQS  = $this->request->getGet('iddesa');
        $kodeslsQS = $this->request->getGet('kodesls'); // opsional

        $est = new EstablishmentModel();
        $idkabSession = (string) (session('idkab') ?? '1400');

        $kecamatan = ($idkabSession !== '1400')
            ? $est->getKecamatanByKabupaten($idkabSession)
            : [];

        $desa = [];
        if ($idkecQS) {
            $desa = $est->getDesaByKecamatan($idkecQS);
        }
        
        // prefll kodesls dari QS: ambil digit, cut 4, pad 4
        $prefillKodesls = null;
        if ($kodeslsQS !== null) {
            $num = preg_replace('/\D/', '', (string)$kodeslsQS);
            if ($num !== '') {
                $prefillKodesls = str_pad(substr($num, 0, 4), 4, '0', STR_PAD_LEFT);
            }
        }

        return view('siripages/form_lkm', [
            'kecamatan'          => $kecamatan,
            'desa'               => $desa,
            'selected_kecamatan' => $idkecQS,
            'selected_desa'      => $iddesaQS,
            'prefill_kodesls'    => $prefillKodesls,   // <— ini kuncinya
        ]);
    }

    /** JSON: daftar desa berdasarkan kecamatan */
    public function desa(string $idkec)
    {
        $rows = (new EstablishmentModel())->getDesaByKecamatan($idkec);
        return $this->response->setJSON($rows);
    }

    /** JSON: prefill semua segmen untuk iddesa + kodesls */
    public function get()
    {
        $iddesa  = preg_replace('/\D/', '', (string) $this->request->getGet('iddesa'));
        $kodesls = preg_replace('/\D/', '', (string) $this->request->getGet('kodesls'));
        if ($kodesls !== '') {
            $kodesls = str_pad($kodesls, 4, '0', STR_PAD_LEFT);
        }

        if (!preg_match('/^\d{10}$/', $iddesa ?? '') || !preg_match('/^\d{4}$/', $kodesls ?? '')) {
            return $this->response->setJSON([]); // invalid -> list kosong
        }

        $list = (new EntriLkmModel())->listByDesaSlsWithUser($iddesa, $kodesls);

        $out = array_map(static function ($r) {
            return [
                'no_segmen'        => $r['no_segmen'],
                'jmlkk'            => (int) $r['jmlkk'],
                'btt'              => (int) $r['btt'],
                'bttk'             => (int) $r['bttk'],
                'bku'              => (int) $r['bku'],
                'bbtt_nu'          => (int) $r['bbtt_nu'],
                'perkiraan_usaha'  => (int) $r['perkiraan_usaha'],
                'total_muatan'     => (int) $r['total_muatan'],
                'updated_by'       => $r['nama_user'] ?? null,
                'tanggal_update'   => $r['tanggal_update'] ?? null,
            ];
        }, $list);

        return $this->response->setJSON($out);
    }

    /** Simpan: iterasi 8 baris; upsert setiap baris valid (iddesa,kodesls,no_segmen) */
    public function simpan()
    {
        $userId = $this->currentUserId();
        if (!$userId) {
            return redirect()->to('/')->with('error', 'Sesi berakhir. Silakan login kembali.');
        }

        $iddesa  = preg_replace('/\D/', '', (string) $this->request->getPost('iddesa'));
        $kodesls = preg_replace('/\D/', '', (string) $this->request->getPost('kodesls'));
        if ($kodesls !== '') {
            $kodesls = str_pad($kodesls, 4, '0', STR_PAD_LEFT);
        }

        if (!preg_match('/^\d{10}$/', $iddesa ?? '') || !preg_match('/^\d{4}$/', $kodesls ?? '')) {
            return redirect()->back()->withInput()->with('error', 'Wilayah atau Kode SLS tidak valid.');
        }

        $rows = $this->request->getPost('rows') ?? [];
        if (!is_array($rows)) $rows = [];

        $m = new EntriLkmModel();
        $saved = 0;

        foreach ($rows as $row) {
            // Normalisasi segmen: terima "2", "02", "S2", "s02" -> "S02"
            $rawSeg = strtoupper((string) ($row['no_segmen'] ?? ''));
            $rawSeg = preg_replace('/\s+/', '', $rawSeg);
            if (!preg_match('/^(?:S)?0*([0-9]{1,2})$/', $rawSeg, $match)) {
                continue; // segmen tidak valid, skip
            }
            $seg = 'S' . str_pad($match[1], 2, '0', STR_PAD_LEFT);

            // Ambil angka; kosong -> 0
            $jmlkk = (int) max(0, (int) ($row['jmlkk'] ?? 0));
            $btt   = (int) max(0, (int) ($row['btt'] ?? 0));
            $bttk  = (int) max(0, (int) ($row['bttk'] ?? 0));
            $bku   = (int) max(0, (int) ($row['bku'] ?? 0));
            $bbtt  = (int) max(0, (int) ($row['bbtt_nu'] ?? 0));
            $usaha = (int) max(0, (int) ($row['perkiraan_usaha'] ?? 0));

            // Total = (maks{jmlkk, btt}) + bttk + bbtt_non_usaha + muatan_usaha
            $base  = max($jmlkk, $btt);
            $total = $base + $bttk + $bbtt + $usaha;

            $payload = [
                'iddesa'           => $iddesa,
                'kodesls'          => $kodesls,
                'no_segmen'        => $seg,
                'id_siri_user'     => $userId,
                'jmlkk'            => $jmlkk,
                'btt'              => $btt,
                'bttk'             => $bttk,
                'bku'              => $bku,
                'bbtt_nu'          => $bbtt,
                'perkiraan_usaha'  => $usaha,
                'total_muatan'     => $total,
            ];

            try {
                if ($m->upsertByKey($payload)) {
                    $saved++;
                }
            } catch (\Throwable $e) {
                // Fallback kalau tabrakan unique, paksa update
                $exist = $m->where('iddesa', $iddesa)
                           ->where('kodesls', $kodesls)
                           ->where('no_segmen', $seg)
                           ->first();
                if ($exist) {
                    $payload['id_entri_lkm'] = $exist['id_entri_lkm'];
                    if ($m->save($payload)) $saved++;
                }
            }
        }

        if ($saved < 1) {
            return redirect()->back()->withInput()->with('error', 'Tidak ada baris segmen valid untuk disimpan.');
        }

        return redirect()->back()->with('success', "Berhasil menyimpan {$saved} baris segmen.");
    }
    
    public function saya()
    {
        $userId = session('id_siri_user') ?? session('id_user');
        if (!$userId) {
            return redirect()->to('/')->with('error', 'Sesi berakhir. Silakan login.');
        }
    
        $m = new \App\Models\EntriLkmModel();
        $rows = $m->listSlsByUser((string)$userId);
    
        return view('siripages/lkm_saya', [
            'rows' => $rows
        ]);
    }
    
    public function exportLkm()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
    
        $idkab    = (string) (session('idkab') ?? '1400');
        $model    = new \App\Models\EntriLkmModel();
        $rows     = $model->getExportByKab($idkab);
        $filename = 'export_lkm_' . date('dmY_His') . '.xls';
    
        $h = static function ($v) {
            return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
        };
    
        // Header dulu, jangan ada output sebelum ini (hindari BOM/whitespace)
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");
    
        echo '<meta charset="UTF-8">';
        echo '<table border="1">';
        echo '<tr>
                <th>ID Kab</th>
                <th>ID Kec</th>
                <th>Nama Kec</th>
                <th>ID Desa</th>
                <th>Nama Desa</th>
                <th>Kode SLS</th>
                <th>No Segmen</th>
                <th>KK(5)</th>
                <th>BTT(6)</th>
                <th>BTTK(7)</th>
                <th>BKU(8)</th>
                <th>BBTT non Usaha(9)</th>
                <th>Muatan Usaha(10)</th>
                <th>Total(11)</th>
                <th>Update Oleh</th>
                <th>Tanggal Update</th>
              </tr>';
    
        foreach ($rows as $r) {
            echo '<tr>';
            echo '<td style="mso-number-format:\'@\';">'.$h($r['idkab'] ?? '').'</td>';
            echo '<td style="mso-number-format:\'@\';">'.$h($r['idkec'] ?? '').'</td>';
            echo '<td>'.$h($r['nmkec'] ?? '').'</td>';
            echo '<td style="mso-number-format:\'@\';">'.$h($r['iddesa'] ?? '').'</td>';
            echo '<td>'.$h($r['nmdesa'] ?? '').'</td>';
            echo '<td style="mso-number-format:\'@\';">'.$h($r['kodesls'] ?? '').'</td>';
            echo '<td style="mso-number-format:\'@\';">'.$h($r['no_segmen'] ?? '').'</td>';
            echo '<td>'.$h($r['jmlkk'] ?? 0).'</td>';
            echo '<td>'.$h($r['btt'] ?? 0).'</td>';
            echo '<td>'.$h($r['bttk'] ?? 0).'</td>';
            echo '<td>'.$h($r['bku'] ?? 0).'</td>';
            echo '<td>'.$h($r['bbtt_nu'] ?? 0).'</td>';
            echo '<td>'.$h($r['perkiraan_usaha'] ?? 0).'</td>';
            echo '<td>'.$h($r['total_muatan'] ?? 0).'</td>';
            echo '<td>'.$h($r['nama_user'] ?? ($r['id_siri_user'] ?? '')).'</td>';
            echo '<td>'.$h($r['tanggal_update'] ?? '').'</td>';
            echo '</tr>';
        }
        echo '</table>';
        exit();
    }

    
}
