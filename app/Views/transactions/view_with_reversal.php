<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1 class="m-0"><?= $title ?></h1>
         </div>
         <div class="col-sm-6">
            <div class="float-right">
               <a href="<?= site_url('transactions') ?>" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Back to Journal
               </a>
               <?php if ($canReverse) : ?>
               <a href="<?= site_url('transactions/reverse/' . $referenceId) ?>" class="btn btn-danger">
                  <i class="fas fa-undo"></i> Reverse Transaction
               </a>
               <?php else : ?>
               <button class="btn btn-secondary" disabled title="This transaction has already been reversed">
                  <i class="fas fa-undo"></i> Already Reversed
               </button>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="content">
   <div class="container-fluid">
      <!-- Original Transaction (same as before) -->
      <!-- ... include the existing view.php content here ... -->

      <!-- Reversal History -->
      <?php if (!empty($reversalHistory)) : ?>
      <div class="card card-warning">
         <div class="card-header">
            <h3 class="card-title">Reversal History</h3>
         </div>
         <div class="card-body p-0">
            <div class="table-responsive">
               <table class="table table-striped">
                  <thead>
                     <tr>
                        <th>Reversal Date</th>
                        <th>Reversal Reference</th>
                        <th>Description</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php foreach ($reversalHistory as $reversal) : ?>
                     <tr>
                        <td><?= $reversal['transaction_date'] ?></td>
                        <td><code><?= $reversal['reference_id'] ?></code></td>
                        <td><?= esc($reversal['description']) ?></td>
                        <td>
                           <a href="<?= site_url('transactions/view/' . $reversal['reference_id']) ?>"
                              class="btn btn-sm btn-info">
                              <i class="fas fa-eye"></i> View
                           </a>
                        </td>
                     </tr>
                     <?php endforeach; ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <?php endif; ?>
   </div>
</div>

<?= $this->endSection() ?>