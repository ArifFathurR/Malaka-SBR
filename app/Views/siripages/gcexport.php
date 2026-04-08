<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h4>Export Establishment GC</h4>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
      <?= esc(session()->getFlashdata('error')) ?>
    </div>
  <?php endif; ?>

  <form method="get" action="<?= base_url('sbr/gcexport') ?>" class="row g-3 align-items-end">

    <!-- DROPDOWN KAB/KOTA -->
    <div class="col-md-6">
      <label class="form-label">Kabupaten / Kota</label>
      <select name="idkab"
              class="form-select"
              <?= ($sessionIdkab !== 1400 ? 'disabled' : '') ?>>

        <?php foreach ($kabList as $k): ?>
          <option value="<?= esc($k['idkab']) ?>"
            <?= ((int)$selectedKab === (int)$k['idkab'] ? 'selected' : '') ?>>
            <?= esc($k['idkab']) ?> - <?= esc($k['nmkab']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- supaya value tetap terkirim kalau select disabled -->
      <?php if ($sessionIdkab !== 1400): ?>
        <input type="hidden" name="idkab" value="<?= esc($selectedKab) ?>">
      <?php endif; ?>
    </div>

    <!-- BUTTON -->
    <div class="col-md-6 d-flex gap-2">

      <a href="<?= base_url('sbr/export?idkab=' . $selectedKab) ?>"
         class="btn btn-success">
        Export CSV
      </a>
    </div>
  </form>

  <hr>

</div>

<?= $this->endSection() ?>
