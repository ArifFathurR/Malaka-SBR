<?php

namespace App\Models;

use CodeIgniter\Model;

class Mentari_model extends Model
{
    //protected $table = 'master_output';
    //protected $primaryKey = 'id_output';

    // ================================================================================================================================================

    // Mengambil semua data
    public function getAll_Output()
    {
        $query = $this->db->query("SELECT * FROM master_output");
        return $query->getResultArray(); // Mengembalikan array
    }

    public function getAllKabupaten()
    {
        $sql = "SELECT idkab, nmkab FROM master_kab ORDER BY idkab";
        return $this->db->query($sql)->getResultArray();
    }

    // Menambah data baru
    public function insertData_output($data)
    {
        $sql = "INSERT INTO master_output (nama_output, alias, fungsi, status) VALUES (?, ?, ?, ?)";
        $this->db->query($sql, $data);
        return $this->db->affectedRows(); // Mengembalikan jumlah baris yang diubah
    }

    // Memperbarui data berdasarkan ID
    public function updateData_output($id, $data)
    {
        $sql = "UPDATE master_output SET nama_output = ?, alias = ?, fungsi = ?, status = ? WHERE id_output = ?";
        $this->db->query($sql, array_merge($data, [$id]));
        return $this->db->affectedRows(); // Mengembalikan jumlah baris yang diubah
    }

    // Menghapus data berdasarkan ID
    public function deleteData_output($id)
    {
        $sql = "DELETE FROM master_output WHERE id_output = ?";
        $this->db->query($sql, [$id]);
        return $this->db->affectedRows(); // Mengembalikan jumlah baris yang dihapus
    }

    // Mengambil satu data berdasarkan ID
    public function getById_output($id)
    {
        $sql = "SELECT * FROM master_output WHERE id_output = ?";
        $query = $this->db->query($sql, [$id]);
        return $query->getRowArray(); // Mengembalikan satu baris sebagai array
    }

// ================================================================================================================================================

    // Mengambil semua data dari tabel master_kegiatan
    // Mengambil semua data kegiatan dengan relasi ke master_output
        public function getAll_Kegiatan()
        {
            $sql = "SELECT 
                        k.*, 
                        o.nama_output 
                    FROM 
                        master_kegiatan k
                    LEFT JOIN 
                        master_output o 
                    ON 
                        k.id_output = o.id_output";

            $query = $this->db->query($sql);
            return $query->getResultArray(); // Mengembalikan array
        }


    // Menambah data baru ke tabel master_kegiatan
    public function insertData_Kegiatan($data)
    {
        $sql = "INSERT INTO master_kegiatan (id_output, nama_kegiatan, fungsi, alias, keterangan, periode, pelaksana) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->db->query($sql, $data);
        return $this->db->affectedRows(); // Mengembalikan jumlah baris yang ditambahkan
    }

    // Memperbarui data berdasarkan ID
    public function updateData_Kegiatan($id, $data)
    {
        $sql = "UPDATE master_kegiatan 
                SET nama_kegiatan = ?, fungsi = ?, alias = ?, keterangan = ?, periode = ?
                WHERE id_kegiatan = ?";
        $this->db->query($sql, array_merge($data, [$id])); // Menggabungkan data dan ID
        return $this->db->affectedRows(); // Mengembalikan jumlah baris yang diubah
    }

    // Menghapus data berdasarkan ID
    public function deleteData_Kegiatan($id)
    {
        $sql = "DELETE FROM master_kegiatan WHERE id_kegiatan = ?";
        $this->db->query($sql, [$id]);
        return $this->db->affectedRows(); // Mengembalikan jumlah baris yang dihapus
    }

    // Mengambil satu data berdasarkan ID
    public function getById_Kegiatan($id)
    {
        $sql = "SELECT * FROM master_kegiatan WHERE id_kegiatan = ?";
        $query = $this->db->query($sql, [$id]);
        return $query->getRowArray(); // Mengembalikan satu baris sebagai array
    }

// ================================================================================================================================================

    // Mengambil semua data dari tabel master_kegiatan_detail
    public function getAll_KegiatanDetail()
    {
        $sql = "SELECT 
                    kd.id_kegiatan_detail, 
                    k.nama_kegiatan, 
                    kd.nama_kegiatan_detail, 
                    kd.tanggal_mulai, 
                    kd.tanggal_selesai, 
                    kd.bulan, 
                    kd.keterangan
                FROM master_kegiatan_detail kd
                LEFT JOIN master_kegiatan k 
                    ON kd.id_kegiatan = k.id_kegiatan";

        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    // Mengambil data master_kegiatan_detail dengan join ke master_kegiatan
    public function getAll_KegiatanDetail_ByOutput($id_output)
    {
        $sql = "SELECT 
                    kd.id_kegiatan_detail, 
                    k.nama_kegiatan, 
                    kd.nama_kegiatan_detail, 
                    kd.tanggal_mulai, 
                    kd.tanggal_selesai, 
                    kd.bulan,
                    kd.keterangan
                FROM master_kegiatan_detail kd
                LEFT JOIN master_kegiatan k 
                    ON kd.id_kegiatan = k.id_kegiatan
                WHERE k.id_output = ?";

        $query = $this->db->query($sql, [$id_output]);
        return $query->getResultArray(); // Mengembalikan array
    }

    // Insert data ke master_kegiatan_detail
    public function insertData_KegiatanDetail($data)
    {
        $sql = "INSERT INTO master_kegiatan_detail (id_kegiatan, nama_kegiatan_detail, tanggal_mulai, tanggal_selesai, bulan, keterangan) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $this->db->query($sql, $data);
        return $this->db->affectedRows();
    }

    // Update data berdasarkan ID
    public function updateData_KegiatanDetail($id, $data)
    {
        $sql = "UPDATE master_kegiatan_detail 
                SET nama_kegiatan_detail = ?, tanggal_mulai = ?, tanggal_selesai = ?, bulan = ?, keterangan = ?
                WHERE id_kegiatan_detail = ?";
        $this->db->query($sql, array_merge($data, [$id]));
        return $this->db->affectedRows();
    }

    // Delete data berdasarkan ID
    public function deleteData_KegiatanDetail($id)
    {
        $sql = "DELETE FROM master_kegiatan_detail WHERE id_kegiatan_detail = ?";
        $this->db->query($sql, [$id]);
        return $this->db->affectedRows();
    }

// ================================================================================================================================================

    public function getAll_KegiatanDetail_WithProses()
    {
        $sql = "SELECT kd.*, k.nama_kegiatan 
                FROM master_kegiatan_detail kd
                JOIN master_kegiatan k ON kd.id_kegiatan = k.id_kegiatan";

        $query = $this->db->query($sql);
        $kegiatan_details = $query->getResultArray();

        foreach ($kegiatan_details as &$detail) {
            $sqlProses = "SELECT * FROM master_kegiatan_detail_proses 
                        WHERE id_kegiatan_detail = ?";
            $queryProses = $this->db->query($sqlProses, [$detail['id_kegiatan_detail']]);
            $prosesList = $queryProses->getResultArray();

            foreach ($prosesList as &$p) {
                $p['is_assigned'] = $this->isWilayahAssigned($p['id_kegiatan_detail_proses']);
            }

            $detail['proses'] = $prosesList;
        }

        return $kegiatan_details;
    }


    // Insert data ke master_kegiatan_detail
    public function insertData_KegiatanDetailProses($data)
    {
        $sql = "INSERT INTO master_kegiatan_detail_proses 
                (id_kegiatan_detail, uraian_proses, tanggal_mulai, tanggal_selesai, target, realisasi, status, keterangan, satuan, id_tim ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->db->query($sql, $data);
        return $this->db->affectedRows();
    }

    // Update data berdasarkan ID
    public function updateData_KegiatanDetailProses($id, $data)
    {
        $sql = "UPDATE master_kegiatan_detail_proses
                SET uraian_proses = ?, tanggal_mulai = ?, tanggal_selesai = ?, target = ?, keterangan = ?, satuan = ?
                WHERE id_kegiatan_detail_proses = ?";
        $this->db->query($sql, array_merge($data, [$id]));
        return $this->db->affectedRows();
    }

    // Delete data berdasarkan ID
    public function deleteData_KegiatanDetailProses($id)
    {
        $sql = "DELETE FROM master_kegiatan_detail_proses WHERE id_kegiatan_detail_proses = ?";
        $this->db->query($sql, [$id]);
        return $this->db->affectedRows();
    }

    public function read_kategori()
    {
        $sql = "SELECT * FROM master_kategori";

        $query = $this->db->query($sql);
        return $query->getResultArray(); 
    }

    public function getKegiatanDetail()
    {
        $sql = "SELECT * FROM master_kegiatan_detail";

        $query = $this->db->query($sql);
        return $query->getResultArray(); 
    }

    public function getKegiatanDetailProsesById($id_kegiatan_detail_proses)
    {
        $sql = "SELECT kp.*, kd.nama_kegiatan_detail
                FROM master_kegiatan_detail_proses kp
                JOIN master_kegiatan_detail kd ON kp.id_kegiatan_detail = kd.id_kegiatan_detail
                WHERE kp.id_kegiatan_detail_proses = ?";
        return $this->db->query($sql, [$id_kegiatan_detail_proses])->getRowArray();
    }

    public function isWilayahAssigned($id_kegiatan_detail_proses)
    {
        return $this->db->table('kegiatan_wilayah')
            ->where('id_kegiatan_detail_proses', $id_kegiatan_detail_proses)
            ->countAllResults() > 0;
    }

    public function getWilayahByKegiatanDetailProses($id_kegiatan_detail_proses)
    {
        $sql = "SELECT mk.idkab, mk.nmkab, COALESCE(kw.target, 0) AS target, kw.id_tim_wakil
                FROM master_kab mk
                LEFT JOIN kegiatan_wilayah kw ON mk.idkab = kw.idkab AND kw.id_kegiatan_detail_proses = ?
                ORDER BY mk.idkab ASC";
        return $this->db->query($sql, [$id_kegiatan_detail_proses])->getResultArray();
    }

    public function updateOrInsertWilayah($id_kegiatan_detail_proses, $idkab, $target, $id_tim_wakil)
    {
        $sql = "SELECT COUNT(*) as count FROM kegiatan_wilayah WHERE id_kegiatan_detail_proses = ? AND idkab = ?";
        $count = $this->db->query($sql, [$id_kegiatan_detail_proses, $idkab])->getRow()->count;

        if ($count > 0) {
            $this->db->table('kegiatan_wilayah')
                ->where(['id_kegiatan_detail_proses' => $id_kegiatan_detail_proses, 'idkab' => $idkab])
                ->update([
                    'target' => $target,
                    'id_tim_wakil' => $id_tim_wakil
                ]);
        } else {
            $this->db->table('kegiatan_wilayah')
                ->insert([
                    'id_kegiatan_detail_proses' => $id_kegiatan_detail_proses,
                    'idkab' => $idkab,
                    'target' => $target,
                    'id_tim_wakil' => $id_tim_wakil
                ]);
        }
    }

// ===============================================================================================================================================

    public function getAllTimWakil()
    {
        $sql = "SELECT id_tim_wakil, nama_tim_wakil FROM master_timwakil ORDER BY nama_tim_wakil ASC";
        return $this->db->query($sql)->getResultArray();
    }


    public function read_tim($idkab)
    {
        $sql = "
            SELECT 
                mt.id_tim, 
                mt.nama_tim, 
                (
                    SELECT u.nama_user 
                    FROM user_tim_junction utj 
                    JOIN user u ON utj.id_user = u.id_user 
                    WHERE utj.id_tim_wakil IN (
                        SELECT id_tim_wakil 
                        FROM master_timwakil 
                        WHERE id_tim = mt.id_tim
                    ) 
                    AND utj.role = 1
                    LIMIT 1
                ) AS ketua_tim,
                (
                    SELECT COUNT(*) 
                    FROM user_tim_junction utj 
                    WHERE utj.id_tim_wakil IN (
                        SELECT id_tim_wakil 
                        FROM master_timwakil 
                        WHERE id_tim = mt.id_tim
                    )
                ) AS jumlah_anggota
            FROM master_tim mt
            WHERE mt.idkab = ?
            ORDER BY mt.id_tim ASC
        ";

        return $this->db->query($sql, [$idkab])->getResultArray();
    }


    /**
     * Mengambil daftar tim wakil berdasarkan `id_tim`
     */
    public function read_timwakil($id_tim)
    {
        return $this->db->query("
            SELECT 
                mtw.id_tim_wakil, 
                mtw.nama_tim_wakil, 
                (
                    SELECT u.nama_user 
                    FROM user_tim_junction utj 
                    JOIN user u ON utj.id_user = u.id_user 
                    WHERE utj.id_tim_wakil = mtw.id_tim_wakil 
                    AND utj.role = 2
                    LIMIT 1
                ) AS pj_tim
            FROM master_timwakil mtw
            WHERE mtw.id_tim = ?
            ORDER BY mtw.id_tim_wakil ASC
        ", [$id_tim])->getResultArray();
    }

    /**
     * Mengambil daftar anggota berdasarkan `id_tim_wakil`
     */
    public function read_anggota($id_tim_wakil)
    {
        return $this->db->query("
            SELECT u.nama_user 
            FROM user_tim_junction utj
            JOIN user u ON utj.id_user = u.id_user
            WHERE utj.id_tim_wakil = ?
            ORDER BY u.nama_user ASC
        ", [$id_tim_wakil])->getResultArray();
    }
    
    public function getAllTim()
    {
        return $this->db->table('master_tim')->get()->getResult();
    }

    public function getAllTim_Array()
    {
        return $this->db->table('master_tim')->get()->getResultArray();
    }

    public function getSubtimDaerah($id_tim)
    {
        $builder = $this->db->table('user_tim_daerah utd');
        $builder->select('
            utd.id_tim_wakil, 
            mtw.nama_tim_wakil, 
            utd.idkab, 
            mk.nmkab, 
            (
                SELECT u.nama_user 
                FROM user u 
                JOIN user_tim_daerah pj ON u.id_user = pj.id_user 
                WHERE pj.id_tim_wakil = utd.id_tim_wakil AND pj.idkab = utd.idkab AND pj.role = 1 
                LIMIT 1
            ) as pj_user
        ');
        $builder->join('master_timwakil mtw', 'utd.id_tim_wakil = mtw.id_tim_wakil');
        $builder->join('master_kab mk', 'utd.idkab = mk.idkab');
        if ($id_tim) {
            $builder->where('mtw.id_tim', $id_tim);
        }
        $builder->groupBy(['utd.id_tim_wakil', 'utd.idkab']);
        return $builder->get()->getResultArray();
    }

    public function getAnggotaByTimDaerah($id_tim_wakil, $idkab)
    {
        return $this->db->table('user_tim_daerah utd')
            ->join('user u', 'u.id_user = utd.id_user')
            ->select('u.nama_user')
            ->where('utd.id_tim_wakil', $id_tim_wakil)
            ->where('utd.idkab', $idkab)
            ->get()->getResultArray();
    }


// ===============================================================================================================================================

    public function getKegiatanByTim($id_tim)
    {
        return $this->db->table('master_kegiatan_detail_proses d')
            ->select('
                d.id_kegiatan_detail_proses,
                d.uraian_proses,
                d.target AS target_master,
                kd.nama_kegiatan_detail,
                COALESCE(w.total_target, 0) AS target,
                COALESCE(w.total_realisasi, 0) AS realisasi
            ')
            ->join('master_kegiatan_detail kd', 'kd.id_kegiatan_detail = d.id_kegiatan_detail')
            ->join('master_kegiatan k', 'k.id_kegiatan = kd.id_kegiatan') // tambahan join ke master_kegiatan
            ->join('(
                SELECT id_kegiatan_detail_proses, 
                    SUM(target) AS total_target, 
                    SUM(realisasi) AS total_realisasi 
                FROM kegiatan_wilayah 
                GROUP BY id_kegiatan_detail_proses
            ) w', 'w.id_kegiatan_detail_proses = d.id_kegiatan_detail_proses', 'left')
            ->where('k.id_tim', $id_tim)
            ->get()
            ->getResult();
    }

    public function getKegiatanById($id)
    {
        return $this->db->table('master_kegiatan_detail_proses d')
            ->select('d.id_kegiatan_detail_proses, d.uraian_proses, kd.nama_kegiatan_detail')
            ->join('master_kegiatan_detail kd', 'kd.id_kegiatan_detail = d.id_kegiatan_detail', 'left')
            ->where('d.id_kegiatan_detail_proses', $id)
            ->get()->getRow();
    }

    public function getDetailKabupaten($id_kegiatan_detail_proses)
    {
        return $this->db->table('kegiatan_wilayah k')
            ->select('k.idkab, r.nmkab, k.target, k.realisasi')
            ->join('master_kab r', 'r.idkab = k.idkab', 'left')
            ->where('k.id_kegiatan_detail_proses', $id_kegiatan_detail_proses)
            ->where('k.target >', 0)
            ->orderBy('k.idkab')
            ->get()->getResult();
    }

    public function updateRealisasiWilayah($id_proses, $idkab, $realisasi)
    {
        $builder = $this->db->table('kegiatan_wilayah');
        $builder->where('id_kegiatan_detail_proses', $id_proses);
        $builder->where('idkab', $idkab);
        $builder->update(['realisasi' => $realisasi]);
    }

    public function getWilayahDetailByProses($id_kegiatan_detail_proses)
    {
        return $this->db->table('kegiatan_wilayah w')
            ->select('w.idkab, k.nmkab, w.target, w.realisasi, w.tanggal_input, w.status')
            ->join('master_kab k', 'k.idkab = w.idkab')
            ->where('w.id_kegiatan_detail_proses', $id_kegiatan_detail_proses)
            ->orderBy('w.idkab')
            ->get()->getResult();
    }

    public function getNamaKegiatanDetailProses($id_kegiatan_detail_proses)
    {
        return $this->db->table('master_kegiatan_detail_proses d')
            ->select('d.uraian_proses, kd.nama_kegiatan_detail')
            ->join('master_kegiatan_detail kd', 'kd.id_kegiatan_detail = d.id_kegiatan_detail')
            ->where('d.id_kegiatan_detail_proses', $id_kegiatan_detail_proses)
            ->get()->getRow();
    }

    public function getKegiatanDetailProses($id_kegiatan_detail_proses)
    {
        return $this->db->table('master_kegiatan_detail_proses d')
            ->select('
                d.uraian_proses, 
                d.tanggal_mulai, 
                d.tanggal_selesai, 
                d.target, 
                d.satuan,
                COALESCE(SUM(w.realisasi), 0) AS realisasi,
                k.nama_kegiatan_detail
            ')
            ->join('master_kegiatan_detail k', 'k.id_kegiatan_detail = d.id_kegiatan_detail', 'left')
            ->join('kegiatan_wilayah w', 'w.id_kegiatan_detail_proses = d.id_kegiatan_detail_proses', 'left')
            ->where('d.id_kegiatan_detail_proses', $id_kegiatan_detail_proses)
            ->groupBy('d.uraian_proses, d.tanggal_mulai, d.tanggal_selesai, d.target, d.satuan, k.nama_kegiatan_detail')
            ->get()->getRow();
    }

    // Ambil wilayah berdasarkan kegiatan
    public function getWilayahByDetailProses($id_kegiatan_detail_proses)
    {
        return $this->db->table('kegiatan_wilayah w')
            ->select('
                w.id_kegiatan_detail_proses,
                w.id_kegiatan_wilayah,
                w.idkab,
                mk.nmkab AS nama_kab,
                w.target,
                w.realisasi,
                w.status,
                w.id_user_selesai,
                u.nama_user AS nama_user_selesai
            ')
            ->join('master_kab mk', 'mk.idkab = w.idkab', 'left')
            ->join('user u', 'u.id_user = w.id_user_selesai', 'left') // <-- penting ini
            ->where('w.id_kegiatan_detail_proses', $id_kegiatan_detail_proses)
            ->where('w.target >', 0)
            ->orderBy('w.idkab', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getLastUpdateTransaksi($id_kegiatan_detail_proses, $idkab)
    {
        return $this->db->table('kegiatan_transaksi kt')
            ->select('kt.tanggal_input, mu.nama_user, mk.nmkab AS nama_kab, mu.idkab')
            ->join('kegiatan_wilayah kw', 'kw.id_kegiatan_wilayah = kt.id_kegiatan_wilayah', 'left')
            ->join('user mu', 'mu.id_user = kt.id_user', 'left')
            ->join('master_kab mk', 'mk.idkab = mu.idkab', 'left')
            ->where('kw.id_kegiatan_detail_proses', $id_kegiatan_detail_proses)
            ->where('kw.idkab', $idkab) 
            ->orderBy('kt.tanggal_input', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();
    }

    public function zgetHistoriTransaksiPerWilayah($id_kegiatan_detail_proses, $idkab)
    {
        return $this->db->table('kegiatan_transaksi t')
            ->select('t.*, u.nama_user')
            ->join('user u', 'u.id_user = t.id_user', 'left')
            ->where('t.id_kegiatan_detail_proses', $id_kegiatan_detail_proses)
            ->where('t.idkab', $idkab)
            ->orderBy('t.tanggal_input', 'ASC')
            ->get()->getResult();
    }

    public function getHistoriTransaksiPerWilayah($id_kegiatan_detail_proses, $idkab)
    {
        return $this->db->table('kegiatan_transaksi kt')
            ->select('kt.transaksi_realisasi, kt.bukti_dukung, kt.tanggal_input, u.nama_user')
            ->join('user u', 'u.id_user = kt.id_user', 'left')
            ->join('kegiatan_wilayah kw', 'kw.id_kegiatan_wilayah = kt.id_kegiatan_wilayah', 'left')
            ->where('kw.id_kegiatan_detail_proses', $id_kegiatan_detail_proses)
            ->where('kw.idkab', $idkab)
            ->orderBy('kt.tanggal_input', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getTimIdByDetailProses($id_kegiatan_detail_proses)
    {
        return $this->db->table('master_kegiatan_detail_proses d')
            ->join('master_kegiatan_detail kd', 'kd.id_kegiatan_detail = d.id_kegiatan_detail', 'left')
            ->join('master_kegiatan k', 'k.id_kegiatan = kd.id_kegiatan', 'left')
            ->where('d.id_kegiatan_detail_proses', $id_kegiatan_detail_proses)
            ->select('k.id_tim')
            ->get()
            ->getRow('id_tim');
    }

    public function getNamaTimById($id_tim)
    {
        return $this->db->table('master_tim')
            ->where('id_tim', $id_tim)
            ->get()
            ->getRow('nama_tim');
    }

    public function simpanTransaksiBaru($id_kegiatan_detail_proses, $idkab, $realisasi, $bukti_dukung, $id_user, $status)
    {
        // 1. Update kegiatan_wilayah
        $updateWilayah = $this->db->query("
            UPDATE kegiatan_wilayah 
            SET realisasi = ? 
            WHERE id_kegiatan_detail_proses = ? AND idkab = ?
        ", [$realisasi, $id_kegiatan_detail_proses, $idkab]);

        // 1.1. Update kegiatan_wilayah status
        $updateStatus = $this->db->query("
            UPDATE kegiatan_wilayah 
            SET status = ? 
            WHERE id_kegiatan_detail_proses = ? AND idkab = ?
        ", [$status, $id_kegiatan_detail_proses, $idkab]);

        // 2. Hitung total realisasi
        $totalResult = $this->db->query("
            SELECT SUM(realisasi) AS total_realisasi 
            FROM kegiatan_wilayah 
            WHERE id_kegiatan_detail_proses = ?
        ", [$id_kegiatan_detail_proses]);
        $total = $totalResult->getRow()->total_realisasi ?? 0;

        // 3. Update master_kegiatan_detail_proses
        $updateMaster = $this->db->query("
            UPDATE master_kegiatan_detail_proses 
            SET realisasi = ? 
            WHERE id_kegiatan_detail_proses = ?
        ", [$total, $id_kegiatan_detail_proses]);

        // 4. Cari id_kegiatan_wilayah yang sesuai
        $id_kegiatan_wilayah = $this->db->table('kegiatan_wilayah')
            ->select('id_kegiatan_wilayah')
            ->where('id_kegiatan_detail_proses', $id_kegiatan_detail_proses)
            ->where('idkab', $idkab)
            ->get()
            ->getRow('id_kegiatan_wilayah');

        // 5. Insert ke kegiatan_transaksi
        $insertTransaksi = $this->db->query("
            INSERT INTO kegiatan_transaksi (id_kegiatan_wilayah, transaksi_realisasi, bukti_dukung, tanggal_input, id_user)
            VALUES (?, ?, ?, NOW(), ?)
        ", [$id_kegiatan_wilayah, $realisasi, $bukti_dukung, $id_user]);

        return $updateWilayah && $updateStatus && $updateMaster && $insertTransaksi;
    }

    // Ambil daftar kegiatan wilayah berdasarkan idkab
    public function getKegiatanWilayahByIdkab($idkab)
    {
        $sql = "
            SELECT 
                d.id_kegiatan_detail_proses,
                d.uraian_proses,
                kd.nama_kegiatan_detail,
                kd.tanggal_mulai,
                kd.tanggal_selesai,
                w.id_kegiatan_wilayah,
                w.idkab,
                w.target,
                w.realisasi,
                w.status,
                t.tanggal_input AS last_update,
                u.nama_user
            FROM kegiatan_wilayah w
            JOIN master_kegiatan_detail_proses d ON w.id_kegiatan_detail_proses = d.id_kegiatan_detail_proses
            JOIN master_kegiatan_detail kd ON d.id_kegiatan_detail = kd.id_kegiatan_detail
            LEFT JOIN (
                SELECT kt.id_kegiatan_wilayah, MAX(kt.tanggal_input) AS max_input
                FROM kegiatan_transaksi kt
                GROUP BY kt.id_kegiatan_wilayah
            ) latest ON latest.id_kegiatan_wilayah = w.id_kegiatan_wilayah
            LEFT JOIN kegiatan_transaksi t ON 
                t.id_kegiatan_wilayah = latest.id_kegiatan_wilayah AND 
                t.tanggal_input = latest.max_input
            LEFT JOIN user u ON t.id_user = u.id_user
            WHERE w.idkab = ?
            AND w.target > 0
            ORDER BY kd.tanggal_selesai DESC
        ";

        return $this->db->query($sql, [$idkab])->getResult();
    }


    // Ambil nama kabupaten
    public function getNamaKabupaten($idkab)
    {
        return $this->db->table('master_kab')
            ->select('nmkab')
            ->where('idkab', $idkab)
            ->get()
            ->getRow('nmkab');
    }

    public function verifikasiStatusKegiatanWilayah($id_kegiatan_wilayah, $status)
    {
        return $this->db->table('kegiatan_wilayah')
            ->where('id_kegiatan_wilayah', $id_kegiatan_wilayah)
            ->update(['status' => $status]);
    }

    public function verifikasiStatusKegiatanWilayahSelesai($id_kegiatan_wilayah, $rating, $id_user_selesai)
    {
        return $this->db->table('kegiatan_wilayah')
            ->where('id_kegiatan_wilayah', $id_kegiatan_wilayah)
            ->update([
                'status' => 9,
                'rating' => $rating,
                'tanggal_selesai' => date('Y-m-d H:i:s'),
                'id_user_selesai' => $id_user_selesai
            ]);
    }


// ================================================================================================================================================

    public function getAllKegiatanDetailWithTim()
    {
        $builder = $this->db->table('master_kegiatan_detail_proses kp');
        $builder->select('
            kp.id_kegiatan_detail_proses, 
            kp.uraian_proses, 
            kp.tanggal_mulai, 
            kp.tanggal_selesai, 
            kp.target, 
            kp.realisasi, 
            kp.id_tim, 
            kp.status,
            mt.nama_tim,
            mkd.nama_kegiatan_detail
        ');
        $builder->join('master_tim mt', 'kp.id_tim = mt.id_tim', 'left');
        $builder->join('master_kegiatan_detail mkd', 'kp.id_kegiatan_detail = mkd.id_kegiatan_detail', 'left');
        return $builder->get()->getResultArray();
    }

// ==================================================================================================================================================

    public function countProsesByStatus($status)
    {
        return $this->db->table('master_kegiatan_detail_proses')
            ->where('status', $status)
            ->countAllResults();
    }

    public function countProsesSedangBerlangsung()
    {
        $today = date('Y-m-d');

        return $this->db->table('master_kegiatan_detail_proses')
            ->where('tanggal_mulai <=', $today)
            ->where('tanggal_selesai >=', $today)
            ->countAllResults(); 
    }

    public function getProsesSedangBerlangsung()
    {
        $today = date('Y-m-d');

        return $this->db->table('master_kegiatan_detail_proses kp')
            ->select('
                kp.id_kegiatan_detail_proses,
                kp.uraian_proses,
                kp.tanggal_mulai,
                kp.tanggal_selesai,
                kp.target,
                kp.realisasi,
                mt.nama_tim
            ')
            ->join('master_tim mt', 'kp.id_tim = mt.id_tim', 'left')
            ->where('kp.tanggal_mulai <=', $today)
            ->where('kp.tanggal_selesai >=', $today)
            ->get()->getResultArray();
    }

    public function countKegiatanTelatSelesai()
    {
        $today = date('Y-m-d');

        return $this->db->table('master_kegiatan_detail_proses')
            ->where('tanggal_selesai <', $today)
            ->where('realisasi < target')
            ->countAllResults();
    }

    public function getKegiatanTerlambatDetail()
    {
        $today = date('Y-m-d');

        return $this->db->table('master_kegiatan_detail_proses kp')
            ->select('
                kp.id_kegiatan_detail_proses,
                kp.uraian_proses,
                kp.tanggal_mulai,
                kp.tanggal_selesai,
                kp.target,
                kp.realisasi,
                mt.nama_tim
            ')
            ->join('master_tim mt', 'kp.id_tim = mt.id_tim', 'left')
            ->where('kp.tanggal_selesai <', $today)
            ->where('kp.realisasi < kp.target')
            ->get()->getResultArray();
    }

    public function countKegiatanThisMonth()
    {
        $bulanIni = date('Y-m');
        return $this->db->table('master_kegiatan_detail_proses')
            ->like('tanggal_mulai', $bulanIni)
            ->orLike('tanggal_selesai', $bulanIni)
            ->countAllResults();
    }

    public function countTim()
    {
        return $this->db->table('master_tim')->countAllResults();
    }

    public function getLineChartData()
    {
        $builder = $this->db->table('master_kegiatan_detail_proses');
        $builder->select("DATE_FORMAT(tanggal_mulai, '%M') as bulan, SUM(realisasi) as total");
        $builder->groupBy("bulan");
        $data = $builder->get()->getResultArray();

        $labels = array_column($data, 'bulan');
        $totals = array_column($data, 'total');

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Realisasi',
                'data' => $totals,
                'borderColor' => 'blue',
                'fill' => false
            ]]
        ];
    }

    public function getBarChartData()
    {
        $builder = $this->db->table('master_kegiatan_detail_proses kp');
        $builder->select('mt.nama_tim, SUM(kp.target) as target, SUM(kp.realisasi) as realisasi');
        $builder->join('master_tim mt', 'kp.id_tim = mt.id_tim');
        $builder->groupBy('mt.nama_tim');
        $data = $builder->get()->getResultArray();

        $labels = array_column($data, 'nama_tim');
        $target = array_column($data, 'target');
        $realisasi = array_column($data, 'realisasi');

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Target',
                    'data' => $target,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.7)'
                ],
                [
                    'label' => 'Realisasi',
                    'data' => $realisasi,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.7)'
                ]
            ]
        ];
    }

    public function getWilayahBarChart($bulan, $tahun)
    {
        $sql = "
            SELECT 
                mk.idkab,
                mk.nmkab,
                SUM(CASE 
                    WHEN MONTH(kp.tanggal_mulai) = ? AND YEAR(kp.tanggal_mulai) = ? 
                    THEN kw.target ELSE 0 END) AS target,
                MAX(kt.transaksi_realisasi) AS realisasi
            FROM kegiatan_wilayah kw
            JOIN master_kab mk ON kw.idkab = mk.idkab
            JOIN master_kegiatan_detail_proses kp ON kw.id_kegiatan_detail_proses = kp.id_kegiatan_detail_proses
            LEFT JOIN (
                SELECT id_kegiatan_wilayah, MAX(tanggal_input) AS latest_date
                FROM kegiatan_transaksi
                WHERE MONTH(tanggal_input) = ? AND YEAR(tanggal_input) = ?
                GROUP BY id_kegiatan_wilayah
            ) last_tx ON last_tx.id_kegiatan_wilayah = kw.id_kegiatan_wilayah
            LEFT JOIN kegiatan_transaksi kt 
                ON kt.id_kegiatan_wilayah = kw.id_kegiatan_wilayah 
                AND kt.tanggal_input = last_tx.latest_date
            WHERE kw.target > 0
            GROUP BY mk.idkab, mk.nmkab
            ORDER BY mk.idkab ASC
        ";
    
        $data = $this->db->query($sql, [$bulan, $tahun, $bulan, $tahun])->getResultArray();
    
        return [
            'labels' => array_column($data, 'nmkab'),
            'datasets' => [
                [
                    'label' => 'Target',
                    'data' => array_map('floatval', array_column($data, 'target')),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.7)'
                ],
                [
                    'label' => 'Realisasi',
                    'data' => array_map('floatval', array_column($data, 'realisasi')),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.7)'
                ]
            ]
        ];
    } 
    
    public function getPersentaseRealisasi()
    {
        $builder = $this->db->table('kegiatan_wilayah');
        $builder->select('SUM(target) AS total_target, SUM(realisasi) AS total_realisasi');
        $result = $builder->get()->getRow();

        $total_target = (float) $result->total_target;
        $total_realisasi = (float) $result->total_realisasi;

        if ($total_target == 0) return 0;

        $persen = round(($total_realisasi / $total_target) * 100, 1);
        return min($persen, 100); // jaga-jaga tidak lebih dari 100%
    }

// ================================================================================================================================================
    

    public function getUraianProsesByTim($id_tim)
    {
        return $this->db->table('master_kegiatan_detail_proses')
            ->select('uraian_proses')
            ->where('id_tim', $id_tim)
            ->groupBy('uraian_proses')
            ->orderBy('uraian_proses', 'asc')
            ->get()->getResult();
    }

    public function getRekapitulasiNilai($bulan, $id_tim = null, $uraian_proses = null)
    {
        $query = $this->db->table('master_kegiatan_detail_proses kp')
            ->select('kp.id_kegiatan_detail_proses, kp.uraian_proses, kp.tanggal_mulai, kp.tanggal_selesai, kp.target AS target_proses, kp.realisasi AS realisasi_proses, mt.nama_tim, kp.id_tim')
            ->join('master_tim mt', 'kp.id_tim = mt.id_tim', 'left')
            ->where('MONTH(kp.tanggal_selesai)', $bulan);

        if ($id_tim) {
            $query->where('kp.id_tim', $id_tim);
        }

        if ($uraian_proses && $uraian_proses !== 'ALL') {
            $query->where('kp.uraian_proses', $uraian_proses);
        }

        $proses = $query->get()->getResultArray();
        $rekap = [];

        foreach ($proses as $p) {
            $wilayah = $this->db->table('kegiatan_wilayah kw')
                ->select('kw.id_kegiatan_detail_proses, kw.id_kegiatan_wilayah, kw.idkab, mk.nmkab, kw.target, kw.realisasi, kw.rating,
                    (
                        SELECT MIN(kt.tanggal_input) 
                        FROM kegiatan_transaksi kt 
                        WHERE kt.id_kegiatan_wilayah = kw.id_kegiatan_wilayah 
                        AND kt.transaksi_realisasi >= kw.target
                    ) AS tgl_realisasi')
                ->join('master_kab mk', 'kw.idkab = mk.idkab')
                ->where('kw.id_kegiatan_detail_proses', $p['id_kegiatan_detail_proses'])
                ->where('kw.target >', 0)
                ->get()->getResultArray();

            usort($wilayah, fn($a, $b) => strtotime($a['tgl_realisasi'] ?? '9999-12-31') - strtotime($b['tgl_realisasi'] ?? '9999-12-31'));
            $ranking_realisasi = array_column($wilayah, 'idkab');

            usort($wilayah, fn($a, $b) => strtotime($p['tanggal_selesai']) - strtotime($p['tanggal_selesai']));
            $ranking_selesai = array_column($wilayah, 'idkab');

            $total_wilayah = count($wilayah);
            $no = 1;

            foreach ($wilayah as $w) {
                $target = (float) $w['target'];
                $realisasi = (float) $w['realisasi'];
                $persen_realisasi = $target > 0 ? ($realisasi / $target) : 0;

                $nilai_target = round(min($persen_realisasi, 1) * 70, 2);
                $ranking_r = array_search($w['idkab'], $ranking_realisasi);
                $nilai_realisasi = round((($total_wilayah - $ranking_r) / $total_wilayah) * 7.5, 2);

                $ranking_s = array_search($w['idkab'], $ranking_selesai);
                $nilai_selesai = round((($total_wilayah - $ranking_s) / $total_wilayah) * 7.5, 2);

                $rating = (float) $w['rating'];
                $nilai_rating = round(($rating / 10) * 15, 2);
                $total_nilai = round($nilai_target + $nilai_realisasi + $nilai_selesai + $nilai_rating, 2);

                $rekap[] = [
                    'no' => $no++,
                    'id_kegiatan_detail_proses' => $p['id_kegiatan_detail_proses'],
                    'uraian_proses' => $p['uraian_proses'],
                    'nama_tim' => $p['nama_tim'],
                    'tanggal_mulai' => $p['tanggal_mulai'],
                    'tanggal_selesai' => $p['tanggal_selesai'],
                    'idkab' => $w['idkab'],
                    'nmkab' => $w['nmkab'],
                    'target' => $w['target'],
                    'realisasi' => $w['realisasi'],
                    'nilai_target' => $nilai_target,
                    'nilai_realisasi' => $nilai_realisasi,
                    'nilai_selesai' => $nilai_selesai,
                    'nilai_rating' => $nilai_rating,
                    'total_nilai' => $total_nilai
                ];
            }
        }

        return $rekap;
    }


}
