<h5><?= $kegiatan->nama_kegiatan_detail ?> (<?= $kegiatan->uraian_proses ?>)</h5>

<form method="post" action="<?= base_url('mastercontroller/update_realisasi_wilayah') ?>">
    <input type="hidden" name="id_kegiatan_detail_proses" value="<?= $kegiatan->id_kegiatan_detail_proses ?>">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Kab</th>
                <th>Nama Kab</th>
                <th>Target</th>
                <th>Realisasi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($detail)): ?>
                <?php $i = 1; foreach ($detail as $row): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $row->idkab ?></td>
                        <td><?= $row->nmkab ?></td>
                        <td><?= $row->target ?></td>
                        <td>
                            <input type="number" name="realisasi[<?= $row->idkab ?>]" value="<?= $row->realisasi ?>" class="form-control">
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center text-danger">Tidak ada data kabupaten</td></tr>
            <?php endif ?>
        </tbody>
    </table>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
