<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1 class="m-0"><?= $title ?></h1>
         </div>
      </div>
   </div>
</div>


<div class="content">
   <div class="container-fluid">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title"><?= lang('Accounting.single_entry_journal') ?></h3>
         </div>
         <div class="card-body">
            <form action="<?= site_url('single_entry/pick') ?>" method="POST" class="form-inline" id="singleEntryForm">
               <div class="row">
                  <label>Pilih <?= lang('App.account') ?> *</label>
                  <div class="col-md-6">
                     <div class="form-group">
                        <select name="header_account" class="form-control" required>
                           <option value="">-- <?= lang('App.select') . lang('App.account') ?> --</option>
                           <?php foreach ($accounts as $account) : ?>
                           <option value="<?= $account['id'] ?>">
                              [<?= $account['code'] ?>] <?= $account['name'] ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                  </div>
               </div>

               <div class="col-md-2">
                  <div class="form-group">
                     <input type="text" name="reference_id" class="form-control" value="REF-<?= time() ?>" disabled>
                  </div>
               </div>

               <div class="col-md-2">
                  <button type="submit" class="btn btn-primary form-control">
                     <i class="fas fa-plus"></i> <?= lang('Accounting.add_single_entry') ?>
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>