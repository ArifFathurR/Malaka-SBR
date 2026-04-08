<h3><?= $kegiatan->nama_kegiatan_detail ?> (<?= $kegiatan->nama_kategori ?>)</h3>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Kab</th>
            <th>Nama Kab</th>
            <th>Target</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($detail as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $row->idkab ?></td>
                <td><?= $row->nama_kabupaten ?></td>
                <td><input type="text" value="<?= $row->target ?>" disabled></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>