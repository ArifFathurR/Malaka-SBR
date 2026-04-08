<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="container-fluid px-4 mt-3">
  <h4 class="mb-3">LKM Saya</h4>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped align-middle lkm-table-mini">
          <thead class="table-light">
            <tr>
              <th style="width:25%">Kecamatan</th>
              <th style="width:35%">Desa</th>
              <th style="width:15%">Kode Project / SLS</th>
              <th style="width:25%" class="text-end">Total Muatan SLS</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($rows)): ?>
              <?php foreach ($rows as $r): ?>
                <tr>
                  <td><?= esc($r['nmkec']) ?></td>
                  <td><?= esc($r['nmdesa']) ?></td>
                  <td>
                    <a href="<?= base_url('lkm') ?>?idkec=<?= esc($r['idkec']) ?>&iddesa=<?= esc($r['iddesa']) ?>&kodesls=<?= esc($r['kodesls']) ?>"
                       class="link-primary fw-semibold">
                       <?= esc($r['kodesls']) ?>
                    </a>
                  </td>
                  <td class="text-end"><?= number_format((int)$r['total_sls'], 0, ',', '.') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center text-muted">Belum ada SLS yang kamu entri.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<style>
  .lkm-table-mini th, .lkm-table-mini td { white-space: nowrap; }
  @media (max-width: 768px){
    .lkm-table-mini th, .lkm-table-mini td { white-space: normal; }
  }
</style>

<?= $this->endSection() ?>
