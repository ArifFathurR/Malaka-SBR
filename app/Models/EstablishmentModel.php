<?php

namespace App\Models;
use CodeIgniter\Model;

class EstablishmentModel extends Model
{
    protected $table = 'establishment';
    protected $primaryKey = 'placeId';
    
    protected $allowedFields = [
        'placeId','placeName','placeAddress','placeBusinessStatus','placeTypes',
        'placeLatitude','placeLongitude','idprov','idkab','iddesa','idkec',
        'kdbs','kdsls','nmprov','nmkab','nmkec','nmdesa','nmsls','linkfoto',
        'kategori','status_usaha','last_update_by','baru', 'status_usaha_labor',
        // requirement baru
        'jenis_usaha_lokasi',      // 1=bangunan ini, 2=keliling/kaki lima, 3=bangunan tetap di lokasi lain
        'lokasilain_address',      // alamat lengkap bila jenis_usaha_lokasi = 3
        'keterangan',              // catatan/keterangan
        'indepth_flag',
        // biarkan kolom lama tetap ada untuk kompatibilitas (meski tidak dipakai di create)
        'kakilima_flag','kakilima_iddesa','kakilima_address'
    ];


    // Get semua data establishment berdasarkan idkab
    public function getEstablishmentByKab($idkab)
    {
        return $this->where('idkab', $idkab)->findAll();
    }

    // Simpan histori update ke table establishment_history
    public function insertHistory($data)
    {
        return $this->db->table('establishment_history')->insert($data);
    }

    // Versi lain yang kamu punya, ikut dirapikan juga
    public function getKecamatan($idkab)
    {
        return $this->db->table('master_kec')
            ->where('idkab', $idkab)
            ->orderBy('idkec', 'ASC')
            ->get()
            ->getResult();
    }

    // Get desa berdasarkan idkec
    public function getDesa($idkec)
    {
        return $this->db->table('master_desa')
            ->where('idkec', $idkec)
            ->orderBy('iddesa', 'ASC')
            ->get()
            ->getResult();
    }

    public function getSLSbyDesa($iddesa)
    {
        return $this->db->table('master_sls')
                    ->select('idsls, nmsls')
                    ->where('iddesa', $iddesa)
                    ->orderBy('nmsls')
                    ->get()
                    ->getResult();
    }
    
    public function getSlsByDesa2(string $iddesa): array
    {
        return $this->db->table('master_sls')
            ->select('kdsls, nmsls')
            ->where('iddesa', $iddesa)
            ->orderBy('nmsls', 'ASC')
            ->get()
            ->getResultArray();
    }

    
    public function getSLS($iddesa)
    {
        return $this->db->table('master_sls')
            ->where('iddesa', $iddesa)
            ->orderBy('nmsls', 'ASC')
            ->get()
            ->getResult();
    }


    public function insertUsaha($data)
    {
        return $this->insert($data);
    }
    
    public function getFirstHistoryUser_lama($placeId)
    {
        return $this->db->table('establishment e')
            ->join('establishment_history h', 'h.placeId = e.placeId', 'inner')
            ->join('siri_user u', 'u.id_siri_user = h.id_siri_user', 'left')
            ->select('h.id_siri_user, u.nama_user')
            ->where('e.placeId', $placeId)
            ->where('e.baru', 1)
            ->orderBy('h.waktu_input', 'ASC')
            ->limit(1)
            ->get()
            ->getRow();
    }
    
    /**
     * Ambil report berdasarkan nama user (cari pada kolom last_update_by yang menyimpan "oleh NAMA")
     *
     * @param string $user
     * @return array
     */
    public function getReportByUserName(string $user): array
    {
        if (trim($user) === '') return [];
    
        // Ambil semua record yang last_update_by mengandung "oleh $user"
        $builder = $this->db->table($this->table);
        $builder->like('last_update_by', 'oleh ' . $user); // LIKE '%oleh user%'
        $rows = $builder->get()->getResultArray();
    
        // Helper: ekstrak datetime dari kolom last_update_by
        $parseDate = function ($row) {
            $raw = $row['last_update_by'] ?? '';
            if (!$raw) return null;
            // asumsikan format "YYYY-MM-DD HH:MM:SS oleh NAMA"
            $parts = explode(' oleh ', $raw, 2);
            $dt = trim($parts[0]);
            // validasi dan kembalikan timestamp (atau null)
            try {
                $d = new \DateTime($dt);
                return $d->getTimestamp();
            } catch (\Exception $e) {
                return null;
            }
        };
    
        // lampirkan timestamp ke tiap row supaya mudah sort
        foreach ($rows as &$r) {
            $r['_ts'] = $parseDate($r) ?? 0;
        }
        unset($r);
    
        // sort descending berdasarkan _ts
        usort($rows, function ($a, $b) {
            return ($b['_ts'] <=> $a['_ts']);
        });
    
        // hapus kolom bantu _ts sebelum return
        foreach ($rows as &$r) {
            unset($r['_ts']);
        }
        unset($r);
    
        return $rows;
    }

    /**
     * Ambil creator pertama (history) untuk multiple placeIds.
     * Mengembalikan associative array: placeId => object(row)
     *
     * @param array $placeIds
     * @return array
     */
    public function getFirstHistoryUsers(array $placeIds = []): array
    {
        $out = [];
        if (empty($placeIds)) return $out;

        // sanitasi array dan gunakan binding (query builder tidak support subselect with IN binding easily),
        // jadi kita gunakan query manual dengan binding placeholders.
        $placeIds = array_values(array_unique($placeIds));
        $placeCount = count($placeIds);
        $placePlaceholders = implode(',', array_fill(0, $placeCount, '?'));

        // Subquery: minimal waktu per placeId
        $sql = "
            SELECT eh.placeId, eh.id_siri_user, eh.waktu_input
            FROM establishment_history eh
            JOIN (
                SELECT placeId, MIN(waktu_input) AS minw
                FROM establishment_history
                WHERE placeId IN ({$placePlaceholders})
                GROUP BY placeId
            ) sub ON eh.placeId = sub.placeId AND eh.waktu_input = sub.minw
        ";

        $query = $this->db->query($sql, $placeIds);
        $rows = $query->getResult();

        foreach ($rows as $r) {
            $out[$r->placeId] = $r; // object with placeId, id_siri_user, waktu_input
        }
        return $out;
    }

    /**
     * Ambil creator pertama untuk single placeId (fallback)
     *
     * @param string $placeId
     * @return object|null
     */
    public function getFirstHistoryUser(string $placeId)
    {
        $builder = $this->db->table('establishment_history');
        $builder->where('placeId', $placeId);
        $builder->orderBy('waktu_input', 'ASC');
        $builder->limit(1);
        $q = $builder->get();
        return $q->getRow();
    }
    
    public function updateEstablishment(string $placeId, array $data)
    {
        return $this->db->table($this->table)->where('placeId', $placeId)->update($data);
    }

    /**
     * Delete establishment by placeId
     */
    public function deleteEstablishment(string $placeId)
    {
        return $this->db->table($this->table)->where('placeId', $placeId)->delete();
    }

    public function getSLSDetail($idsls)
    {
        return $this->db->table('master_sls')
            ->where('idsls', $idsls)
            ->get()
            ->getRow();
    }

    public function getUpdatePerKabupaten()
    {
        return $this->db->table($this->table)
            ->select("
                idkab,
                nmkab,
                SUM(CASE WHEN baru IS NULL THEN 1 ELSE 0 END) AS total_usaha,
                SUM(CASE WHEN status_usaha IS NOT NULL AND baru IS NULL THEN 1 ELSE 0 END) AS total_updated,
                SUM(CASE WHEN baru = 1 THEN 1 ELSE 0 END) AS penambahan_baru,
                SUM(CASE WHEN status_usaha = 1 THEN 1 ELSE 0 END) AS aktif,
                SUM(CASE WHEN status_usaha = 2 THEN 1 ELSE 0 END) AS tidak_aktif,
                SUM(CASE WHEN status_usaha = 3 THEN 1 ELSE 0 END) AS bukan_usaha,
                SUM(CASE WHEN status_usaha = 4 THEN 1 ELSE 0 END) AS tidak_ditemukan,
                 -- Persentase mutakhir = (updated + baru) / (lama + baru)
                ROUND(
                    100 * (
                        SUM(CASE WHEN status_usaha IS NOT NULL AND baru IS NULL THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN baru = 1 THEN 1 ELSE 0 END)
                    ) / NULLIF(
                        SUM(CASE WHEN baru IS NULL THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN baru = 1 THEN 1 ELSE 0 END),
                    0), 1
                ) AS persentase
            ")
            ->groupBy('idkab, nmkab')
            ->orderBy('idkab')
            ->get()
            ->getResultArray();
    }

    public function getDistinctUserUpdates()
    {
        return $this->db->table('establishment_history h')
            ->select('h.id_siri_user, u.nama_user, COUNT(*) as jumlah_update')
            ->join('siri_user u', 'h.id_siri_user = u.id_siri_user', 'left')
            ->groupBy('h.id_siri_user, u.nama_user')
            ->orderBy('jumlah_update', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getUserUpdatePerKab($user_idkab)
    {
        $builder = $this->db->table('establishment')
            ->select("
                idkab,
                nmkab,
                SUBSTRING_INDEX(last_update_by, ' oleh ', -1) AS nama_user,
    
                -- Jumlah usaha lama yang dimutakhirkan (hanya status 1-4 dan baru IS NULL)
                SUM(CASE 
                    WHEN baru IS NULL AND status_usaha IN (1, 2, 3, 4) THEN 1 
                    ELSE 0 
                END) AS jumlah_update,
    
                -- Jumlah usaha baru (baru = 1)
                SUM(CASE WHEN baru = 1 THEN 1 ELSE 0 END) AS jumlah_baru,
    
                -- Total update = usaha lama yang dimutakhirkan + usaha baru
                SUM(CASE 
                    WHEN baru IS NULL AND status_usaha IN (1, 2, 3, 4) THEN 1 
                    WHEN baru = 1 THEN 1 
                    ELSE 0 
                END) AS total,
    
                -- Rincian status (hanya untuk usaha lama)
                SUM(CASE WHEN baru IS NULL AND status_usaha = 1 THEN 1 ELSE 0 END) AS dimutakhirkan_aktif,
                SUM(CASE WHEN baru IS NULL AND status_usaha = 2 THEN 1 ELSE 0 END) AS dimutakhirkan_tidak_aktif,
                SUM(CASE WHEN baru IS NULL AND status_usaha = 3 THEN 1 ELSE 0 END) AS dimutakhirkan_bukan_usaha,
                SUM(CASE WHEN baru IS NULL AND status_usaha = 4 THEN 1 ELSE 0 END) AS tidak_ditemukan
            ")
            ->where('last_update_by IS NOT NULL')
            ->groupBy(['idkab', 'nmkab', 'nama_user'])
            ->orderBy('idkab ASC, nama_user ASC');
    
        if ($user_idkab !== '1400') {
            $builder->where('idkab', $user_idkab);
        }
    
        return $builder->get()->getResultArray();
    }

    public function getUpdatePerDesa($session_idkab = null)
    {
        $builder = $this->db->table($this->table)
            ->select("
                iddesa,
                MAX(nmdesa) AS nmdesa,
                
                SUM(CASE WHEN baru IS NULL THEN 1 ELSE 0 END) AS jumlah_awal,
                SUM(CASE WHEN baru IS NULL AND status_usaha IS NOT NULL THEN 1 ELSE 0 END) AS jumlah_mutakhir,
                SUM(CASE WHEN baru = 1 THEN 1 ELSE 0 END) AS jumlah_baru,
                
                ROUND(
                    100 * (
                        SUM(CASE WHEN baru IS NULL AND status_usaha IS NOT NULL THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN baru = 1 THEN 1 ELSE 0 END)
                    ) / NULLIF(
                        SUM(CASE WHEN baru IS NULL THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN baru = 1 THEN 1 ELSE 0 END),
                    0), 2
                ) AS persentase_mutakhir
            ")
            ->groupBy('iddesa, idkab')
            ->orderBy('idkab');
    
        // Tambahkan filter idkab jika user bukan level provinsi
        if ($session_idkab !== '1400') {
            $builder->where('idkab', $session_idkab);
        }
    
        return $builder->get()->getResultArray();
    }

    // Total seluruh data usaha
    public function getTotalEstablishment()
    {
        return $this->countAll();
    }

    // Jumlah usaha yang sudah diupdate (status_usaha tidak null)
    public function getUpdatedEstablishment()
    {
        return $this->where('status_usaha IS NOT NULL')->countAllResults();
    }

    // Daftar user unik yang pernah melakukan update
    public function getDistinctUsers()
    {
        return $this->db->table('establishment_history')
                        ->select('id_siri_user')
                        ->distinct()
                        ->get()
                        ->getResultArray();
    }

    // Jumlah update yang dilakukan per user
    public function getUpdateCountPerUser()
    {
        return $this->db->table('establishment_history')
                        ->select('id_siri_user, COUNT(*) as jumlah')
                        ->groupBy('id_siri_user')
                        ->get()
                        ->getResultArray();
    }

    // Jumlah desa unik yang sudah ada update
    public function getUpdatedDesaCount()
    {
        return $this->where('status_usaha IS NOT NULL')
                    ->select('iddesa')
                    ->distinct()
                    ->countAllResults();
    }

    // Total desa unik dalam data usaha
    public function getTotalDesa()
    {
        return $this->select('iddesa')
                    ->distinct()
                    ->countAllResults();
    }

    // Ambil 10 data update terbaru berdasarkan waktu update
    public function getLatestUpdates()
    {
        return $this->where('status_usaha IS NOT NULL')
                    ->orderBy('last_update_by', 'DESC')
                    ->limit(10)
                    ->findAll();
    }

    public function getUpdatePerKab()
    {
        return $this->db->table($this->table)
            ->select(
                'idkab, nmkab, 
                 SUM(CASE WHEN baru IS NULL THEN 1 ELSE 0 END) as total,
                 SUM(CASE WHEN baru IS NULL AND status_usaha IS NOT NULL THEN 1 ELSE 0 END) as updated,
                 SUM(CASE WHEN baru = 1 THEN 1 ELSE 0 END) as baru'
            )
            ->groupBy('idkab, nmkab')
            ->orderBy('idkab')
            ->get()
            ->getResultArray();
    }


    public function getWilayahDetail($iddesa)
    {
        return $this->db->table('master_desa d')
            ->select('d.nmdesa, k.nmkec, b.nmkab')
            ->join('master_kec k', 'k.idkec = LEFT(d.iddesa, 7)')
            ->join('master_kab b', 'b.idkab = LEFT(d.iddesa, 4)')
            ->where('d.iddesa', $iddesa)
            ->get()->getRow();
    }
    
    public function getReportByUser($id_user)
    {
        return $this->db->table('establishment')
            ->select([
                'placeId',
                'placeName',
                'placeAddress',
                'status_usaha',
                'kategori',
                'baru',
                'last_update_by'
            ])
            ->like('last_update_by', $id_user) // pastikan 'last_update_by' menyimpan nama atau ID user
            ->orderBy('last_update_by', 'DESC')
            ->get()->getResultArray();
    }
    
    public function getSummaryByUser($id_user)
    {
        return $this->db->table('establishment')
            ->select("
                COUNT(*) AS total_update,
                SUM(CASE WHEN baru = 1 THEN 1 ELSE 0 END) AS baru_update,
                SUM(CASE WHEN baru = 0 OR baru IS NULL THEN 1 ELSE 0 END) AS existing_update
            ")
            ->like('last_update_by', $id_user)
            ->get()->getRowArray();
    }

    public function getUpdateCountByStatus()
    {
        return $this->select('status_usaha, COUNT(*) as jumlah')
                    ->where('last_update_by IS NOT NULL')
                    ->groupBy('status_usaha')
                    ->findAll();
    }
    
    public function searchByKeyword($idkab, $idkec, $iddesa, $keyword)
    {
        $builder = $this->select('establishment.*, mk.nmkab, mkc.nmkec, md.nmdesa')
            ->join('master_kab mk', 'mk.idkab = establishment.idkab')
            ->join('master_kec mkc', 'mkc.idkec = establishment.idkec')
            ->join('master_desa md', 'md.iddesa = establishment.iddesa')
            ->where('establishment.idkab', $idkab)
            ->where('establishment.idkec', $idkec)
            ->like('placeName', $keyword)
            ->limit(50);
    
        if ($iddesa !== 'all') {
            $builder->where('establishment.iddesa', $iddesa);
        }
    
        return $builder->get()->getResultArray();
    }

    
    public function getAllKabupaten()
    {
        return $this->db->table('master_kab')->get()->getResultArray();
    }
    
    public function getKecamatanByKabupaten($idkab)
    {
        return $this->db->table('master_kec')
            ->where('idkab', $idkab)
            ->orderBy('idkec', 'ASC')
            ->get()
            ->getResultArray();
    }
    
    public function getDesaByKecamatan($idkec)
    {
        return $this->db->table('master_desa')
            ->where('idkec', $idkec)
            ->orderBy('iddesa', 'ASC')
            ->get()
            ->getResultArray();
    }
    
    public function getDesaByIds(array $ids)
    {
        // Filter: hanya string non-kosong
        $ids = array_values(array_filter($ids, function ($v) {
            return is_string($v) && $v !== '';
        }));
    
        if (empty($ids)) {
            return [];
        }
    
        // Query
        $builder = $this->db->table('master_desa')
            ->select('iddesa, nmdesa')
            ->whereIn('iddesa', $ids)
            ->orderBy('iddesa', 'ASC');
    
        $rows = $builder->get()->getResultArray();
    
        return is_array($rows) ? $rows : [];
    }

    
    public function getExportData($idkab = null)
    {
        $builder = $this->select("
            iddesa,
            kdsls,
            placeName,
            placeAddress,
            placeLatitude,
            placeLongitude,
            status_usaha AS status,
            kategori,
            baru
        ");
    
        if ($idkab !== '1400') {
            $builder->where('idkab', $idkab);
        }
    
        return $builder->findAll();
    }
    
    public function getExportData_indepth()
    {
        // ambil dua desa: Cinta Raja dan Kedungsari
        $iddesaList = ['1471040001', '1471060005'];

        return $this->select("
                nmdesa,
                kdsls,
                nmsls,
                placeName,
                placeAddress,
                status_usaha,
                jenis_usaha_lokasi,
                lokasilain_address,
                indepth_flag,
                kakilima_flag,
                kakilima_address AS alamat_rumah_kakilima, 
                keterangan
            ")
            ->whereIn('iddesa', $iddesaList)
            ->orderBy('nmdesa', 'ASC')
            ->orderBy('kdsls', 'ASC')
            ->findAll();
    }
    

    public function getBelumMalakaExport(string $sessionIdkab = '1400'): array
    {
        $builder = $this->db->table('establishment')
            ->select([
                'idkab',
                'idkec',
                'nmkec',
                'iddesa',
                'nmdesa',
                'nmsls',
                'placeName',
                'placeAddress',
            ])
            ->where('status_usaha IS NULL')
            ->where('last_update_by IS NULL');
    
        if ($sessionIdkab !== '1400') {
            $builder->where('idkab', $sessionIdkab);
        }
    
        return $builder
            ->orderBy('idkab, idkec, iddesa, nmsls, placeName')
            ->get()
            ->getResultArray();
    }
    
    public function getUserPoints(string $namaUser, string $startDate = '2025-08-26'): int
    {
        $row = $this->select("
                    COALESCE(
                      SUM(
                        CASE
                          WHEN baru = 1 THEN 1
                          WHEN ( (baru IS NULL OR baru = 0) AND status_usaha = 1 ) THEN 1
                          ELSE 0
                        END
                      ), 0
                    ) AS total_points
                ")
                ->like('last_update_by', $namaUser) // filter berdasarkan user
                ->where("DATE(SUBSTRING_INDEX(last_update_by, ' oleh ', 1)) >=", $startDate)
                ->first();
    
        return (int)($row['total_points'] ?? 0);
    }
    
    public function getUserDailyCounts(string $startDate, string $endDate, int $idkabFilter = 1400): array
    {
        // endDate + 1 hari agar mudah dipakai di filter "<"
        $endPlus1 = date('Y-m-d', strtotime($endDate . ' +1 day'));

        $sql = "
            SELECT
                su.id_siri_user,
                su.nama_user,
                DATE(t.last_update_at) AS d,
                COUNT(t.placeId) AS cnt
            FROM siri_user su
            LEFT JOIN (
                SELECT
                    e.placeId,
                    -- parse datetime sebelum 'oleh'
                    STR_TO_DATE(
                        TRIM(SUBSTRING_INDEX(e.last_update_by, 'oleh', 1)),
                        '%Y-%m-%d %H:%i:%s'
                    ) AS last_update_at,
                    -- normalisasi nama setelah 'oleh '
                    REGEXP_REPLACE(
                        TRIM(SUBSTRING_INDEX(e.last_update_by, 'oleh', -1)),
                        '\\\\s+', ' '
                    ) AS norm_name,
                    e.status_usaha
                FROM establishment e
                WHERE e.last_update_by IS NOT NULL
            ) t
                ON LOWER(su.nama_user) = LOWER(t.norm_name)
                AND (t.status_usaha = 1 OR t.status_usaha = 7)
                AND t.last_update_at >= ?
                AND t.last_update_at <  ?
            WHERE su.idkab = ?
            GROUP BY su.id_siri_user, su.nama_user, d
            ORDER BY su.id_siri_user, d
        ";

        return $this->db->query($sql, [$startDate, $endPlus1, $idkabFilter])->getResultArray();
    }
    
    // Ambil semua establishment untuk satu iddesa
    public function getEstablishmentByDesa(string $iddesa)
    {
        return $this->where('iddesa', $iddesa)
                    ->orderBy('placeName', 'ASC')
                    ->findAll();
    }
    
    // Ambil establishment untuk multiple iddesa (mis. dua desa yang kamu minta)
    public function getEstablishmentByDesaIds(array $ids)
    {
        if (empty($ids)) return [];
    
        return $this->db->table($this->table)
            ->whereIn('iddesa', $ids)
            ->orderBy('nmdesa', 'ASC')
            ->orderBy('placeName', 'ASC')
            ->get()
            ->getResultArray(); // return array supaya view lebih mudah pakai indeks ['placeName'] dst.
    }
    
    // cek sampai sini
    
    /**
     * List file .geojson di folder lokasi/map yang dimulai dengan $prefix (opsional)
     *
     * @param string|null $prefix
     * @return array  // list nama file (string)
     */
    public function geojsonList($prefix = null): array
    {
        $dir = FCPATH . 'lokasi' . DIRECTORY_SEPARATOR . 'map' . DIRECTORY_SEPARATOR;
        $result = [];
    
        if (!is_dir($dir)) return $result;
    
        $files = scandir($dir);
        foreach ($files as $f) {
            if (in_array($f, ['.', '..'])) continue;
            if (!preg_match('/\.geojson$/i', $f)) continue;
            if ($prefix !== null && $prefix !== '') {
                if (stripos($f, $prefix) !== 0) continue;
            }
            $result[] = $f;
        }
    
        return $result;
    }
    
    /**
     * Cari kdsls (4 digit) dari file geojson prefix iddesa yang mengandung titik lat/lng.
     * Mengembalikan string kdsls (mis. '0001') atau null jika tidak ditemukan.
     *
     * @param string $iddesa  // prefix file, mis. '1471040001'
     * @param float $lat
     * @param float $lng
     * @return string|null
     */
    public function findKdslsFromGeojson(string $iddesa, float $lat, float $lng): ?string
    {
        $dir = FCPATH . 'lokasi' . DIRECTORY_SEPARATOR . 'map' . DIRECTORY_SEPARATOR;
        if (!is_dir($dir)) return null;
    
        // scan files matching ^{iddesa}\d{4}\.geojson$
        $files = scandir($dir);
        foreach ($files as $f) {
            if (!preg_match('/^' . preg_quote($iddesa, '/') . '(\d{4})\.geojson$/i', $f, $m)) continue;
            $kdsls = $m[1];
    
            $path = $dir . $f;
            if (!is_readable($path)) continue;
    
            $content = file_get_contents($path);
            if ($content === false) continue;
    
            $geo = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE || empty($geo)) continue;
    
            // GeoJSON FeatureCollection or Feature or Geometry
            // Normalize to features array
            $features = [];
            if (isset($geo['type']) && $geo['type'] === 'FeatureCollection' && isset($geo['features'])) {
                $features = $geo['features'];
            } elseif (isset($geo['type']) && $geo['type'] === 'Feature') {
                $features = [$geo];
            } elseif (isset($geo['type']) && in_array($geo['type'], ['Polygon','MultiPolygon'])) {
                // wrap into a pseudo-feature
                $features = [['type' => 'Feature', 'geometry' => $geo]];
            }
    
            // iterate features and test point-in-polygon
            foreach ($features as $feature) {
                try {
                    if ($this->pointInGeojsonFeature($lat, $lng, $feature)) {
                        return $kdsls;
                    }
                } catch (\Throwable $e) {
                    // jika ada error parsing satu feature, lanjutkan ke file berikutnya
                    continue;
                }
            }
        }
    
        return null;
    }
    
    /**
     * Cek satu GeoJSON feature apakah titik berada di dalamnya.
     * Support geometry types: Polygon, MultiPolygon.
     *
     * @param float $lat
     * @param float $lng
     * @param array $feature
     * @return bool
     */
    protected function pointInGeojsonFeature(float $lat, float $lng, array $feature): bool
    {
        if (empty($feature['geometry'])) return false;
        $geom = $feature['geometry'];
        $type = $geom['type'] ?? null;
        $coords = $geom['coordinates'] ?? null;
        if (!$type || !$coords) return false;
    
        // quick bbox rejection: compute bounding box of feature (over all polygons)
        $minLat = $minLng = INF;
        $maxLat = $maxLng = -INF;
    
        if ($type === 'Polygon') {
            foreach ($coords as $ring) {
                foreach ($ring as $pt) {
                    $ptLng = (float)$pt[0]; $ptLat = (float)$pt[1];
                    $minLat = min($minLat, $ptLat); $maxLat = max($maxLat, $ptLat);
                    $minLng = min($minLng, $ptLng); $maxLng = max($maxLng, $ptLng);
                }
            }
        } elseif ($type === 'MultiPolygon') {
            foreach ($coords as $poly) {
                foreach ($poly as $ring) {
                    foreach ($ring as $pt) {
                        $ptLng = (float)$pt[0]; $ptLat = (float)$pt[1];
                        $minLat = min($minLat, $ptLat); $maxLat = max($maxLat, $ptLat);
                        $minLng = min($minLng, $ptLng); $maxLng = max($maxLng, $ptLng);
                    }
                }
            }
        } else {
            // not supported geometry type
            return false;
        }
    
        // if point outside bbox -> false early
        if ($lat < $minLat || $lat > $maxLat || $lng < $minLng || $lng > $maxLng) {
            return false;
        }
    
        // detailed check
        if ($type === 'Polygon') {
            // coords: [ [ [lng,lat], ... ring0 ], [ ring1 ], ... ]
            foreach ($coords as $ring) {
                // ring is an array of [lng,lat]
                if ($this->isPointInPolygon($lat, $lng, $ring)) {
                    return true;
                }
            }
            return false;
        } elseif ($type === 'MultiPolygon') {
            foreach ($coords as $poly) {
                // poly is array of rings
                foreach ($poly as $ring) {
                    if ($this->isPointInPolygon($lat, $lng, $ring)) {
                        return true;
                    }
                }
            }
            return false;
        }
    
        return false;
    }
    
    /**
     * Ray-casting algorithm — check if point(lat,lng) inside polygon ring.
     * $ring is array of points [ [lng,lat], [lng,lat], ... ]
     *
     * @param float $lat
     * @param float $lng
     * @param array $ring
     * @return bool
     */
    protected function isPointInPolygon(float $lat, float $lng, array $ring): bool
    {
        $inside = false;
        $n = count($ring);
        if ($n < 3) return false;
    
        $j = $n - 1;
        for ($i = 0; $i < $n; $i++) {
            // GeoJSON coords are [lng, lat]
            $xi = (float)$ring[$i][1]; // lat
            $yi = (float)$ring[$i][0]; // lng
            $xj = (float)$ring[$j][1];
            $yj = (float)$ring[$j][0];
    
            // check if edge intersects horizontal ray at $lat
            $intersect = (($xi > $lat) !== ($xj > $lat))
                && ($lng < ($yj - $yi) * ($lat - $xi) / ($xj - $xi + 0.0) + $yi);
    
            if ($intersect) $inside = !$inside;
            $j = $i;
        }
    
        return $inside;
    }
    
    public function updateCoordinates(string $placeId, float $lat, float $lng)
    {
        return $this->set(['placeLatitude' => $lat, 'placeLongitude' => $lng])
                    ->where('placeId', $placeId)
                    ->update();
    }
    
    // Ambil SLS (kdsls, nmsls) per iddesa, urut nama
    public function getSlsOptions(string $iddesa): array
    {
        return $this->db->table('master_sls')
            ->select('kdsls, nmsls')
            ->where('iddesa', $iddesa)
            ->orderBy('nmsls', 'ASC')
            ->get()->getResultArray();
    }
    
    // Ambil establishment per desa + SLS spesifik
    public function getEstablishmentByDesaAndSls(string $iddesa, string $kdsls)
    {
        return $this->where('iddesa', $iddesa)
                    ->where('kdsls', $kdsls)
                    ->orderBy('placeName', 'ASC')
                    ->findAll();
    }

    public function updateStatusAlamat(string $placeId, int $statusUsaha, string $placeAddress): bool
    {
        if ($placeId === '' || $statusUsaha < 1 || $statusUsaha > 7) {
            return false;
        }
    
        // Gunakan builder langsung agar benar-benar hanya 2 kolom ini yang diupdate.
        $builder = $this->db->table($this->table);
        return $builder->where('placeId', $placeId)
            ->set('status_usaha', $statusUsaha)
            ->set('placeAddress', $placeAddress)
            ->update();
    }
    
    /**
     * (Opsional) Ambil 1 baris untuk verifikasi/preview modal update.
     */
    public function getByPlaceId(string $placeId)
    {
        return $this->where('placeId', $placeId)->first();
    }

    // Ambil usaha Kaki Lima per desa
    public function getKakilimaByDesa(string $iddesa)
    {
        return $this->where('iddesa', $iddesa)
                    ->where('kakilima_flag', 1)
                    ->orderBy('placeName', 'ASC')
                    ->findAll();
    }
    
    // Ambil usaha Kaki Lima per desa + SLS
    public function getKakilimaByDesaAndSls(string $iddesa, string $kdsls)
    {
        return $this->where('iddesa', $iddesa)
                    ->where('kakilima_flag', 1)
                    ->where('kdsls', str_pad(preg_replace('/\D/','', $kdsls), 4, '0', STR_PAD_LEFT))
                    ->orderBy('placeName', 'ASC')
                    ->findAll();
    }
    
    /**
     * Update khusus Kaki Lima: hanya kolom yang diizinkan,
     * TIDAK menyentuh last_update_by.
     * Controller akan menyiapkan idkab/idkec/nm* jika iddesa diubah.
     */
    public function updateKakilimaFields(string $placeId, array $data): bool
    {
        if ($placeId === '') return false;
    
        // Batasi whitelist kolom yang boleh diubah di fitur ini
        $allowed = [
            'placeName',
            'placeAddress',
            'lokasilain_address', 'kakilima_address', 'indepth_flag', 'kakilima_iddesa'
        ];
    
        $payload = [];
        foreach ($allowed as $k) {
            if (array_key_exists($k, $data)) $payload[$k] = $data[$k];
        }
        if (empty($payload)) return false;
    
        return $this->db->table($this->table)
            ->where('placeId', $placeId)
            ->update($payload);
    }
    
    // EstablishmentModel.php
    public function getUsahaBySls($iddesa, $kdsls)
    {
        $builder = $this->db->table('establishment');
        $builder->where('iddesa', $iddesa);
    
        if ($kdsls === 'NULL' || $kdsls === null) {
            $builder->where('kdsls', null);
        } else {
            $builder->where('kdsls', $kdsls);
        }
    
        return $builder
            ->select('placeId, placeName, placeAddress, status_usaha, status_usaha_labor')
            ->orderBy('placeName', 'ASC')
            ->get()
            ->getResultArray();
    }
    
    public function filterAllowedSlsIfTembilahan(string $iddesa, array $rows): array
    {
        if ($iddesa !== '1403060004') {
            return $rows;
        }
    
        $allowed = [
            '0001','0002','0003','0004','0005','0006',
            '0011','0012','0013','0014',
            '0033','0036','0037',
            '0042','0047'
        ];
    
        return array_values(array_filter($rows, function($row) use ($allowed) {
            return in_array($row['kdsls'], $allowed);
        }));
    }
    
    public function rekapSlsByDesa_rekap(string $iddesa): array
    {
        return $this->db->table($this->table . ' e')
            ->select("
                e.kdsls,
                ms.nmsls,
                COUNT(*) AS total_usaha,
                SUM(CASE WHEN e.status_usaha_labor IS NOT NULL THEN 1 ELSE 0 END) AS sudah_update,
                SUM(CASE WHEN e.status_usaha_labor IS NULL THEN 1 ELSE 0 END) AS belum_update
            ")
            ->join('master_sls ms', 'ms.iddesa = e.iddesa AND ms.kdsls = e.kdsls', 'left')
            ->where('e.iddesa', $iddesa)
            ->groupBy('e.kdsls, ms.nmsls')
            ->orderBy('e.kdsls', 'ASC')
            ->get()->getResultArray();
    }


}
