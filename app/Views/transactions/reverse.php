<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1 class="m-0"><?= $title ?></h1>
         </div>
         <div class="col-sm-6">
            <a href="<?= site_url('transactions/view/' . $transaction['reference_id']) ?>"
               class="btn btn-secondary float-right">
               <i class="fas fa-arrow-left"></i> Back to Transaction
            </a>
         </div>
      </div>
   </div>
</div>

<div class="content">
   <div class="container-fluid">
      <!-- Original Transaction Summary -->
      <div class="card card-warning">
         <div class="card-header">
            <h3 class="card-title">Original Transaction to Reverse</h3>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-md-3">
                  <strong>Reference ID:</strong><br>
                  <code><?= $transaction['reference_id'] ?></code>
               </div>
               <div class="col-md-3">
                  <strong>Date:</strong><br>
                  <?= $transaction['date'] ?>
               </div>
               <div class="col-md-6">
                  <strong>Description:</strong><br>
                  <?= esc($transaction['description']) ?>
               </div>
            </div>

            <hr>

            <h5>Transaction Entries:</h5>
            <div class="table-responsive">
               <table class="table table-sm">
                  <thead>
                     <tr>
                        <th>Account</th>
                        <th class="text-right">Debit</th>
                        <th class="text-right">Credit</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php foreach ($transaction['lines'] as $line) : ?>
                     <tr>
                        <td>[<?= $line['account_code'] ?>] <?= $line['account_name'] ?></td>
                        <td class="text-right text-success">
                           <?= $line['debit'] > 0 ? number_format($line['debit'], 2) : '-' ?>
                        </td>
                        <td class="text-right text-danger">
                           <?= $line['credit'] > 0 ? number_format($line['credit'], 2) : '-' ?>
                        </td>
                     </tr>
                     <?php endforeach; ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>

      <!-- Reversal Form -->
      <div class="card card-danger">
         <div class="card-header">
            <h3 class="card-title">Create Reversal Entry</h3>
         </div>
         <div class="card-body">
            <?php if (isset($errors)) : ?>
            <div class="alert alert-danger">
               <ul class="mb-0">
                  <?php foreach ($errors as $error) : ?>
                  <li><?= esc($error) ?></li>
                  <?php endforeach; ?>
               </ul>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <form action="<?= site_url('transactions/processReverse') ?>" method="post">
               <?= csrf_field() ?>
               <input type="hidden" name="reference_id" value="<?= $transaction['reference_id'] ?>">

               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="reversal_date">Reversal Date *</label>
                        <input type="date" name="reversal_date" class="form-control"
                           value="<?= old('reversal_date', date('Y-m-d')) ?>" required>
                     </div>
                  </div>
               </div>

               <div class="form-group">
                  <label for="reversal_description">Reversal Description *</label>
                  <textarea name="reversal_description" class="form-control" rows="3" required
                     placeholder="Explain why this transaction is being reversed"><?= old('reversal_description', 'Reversal of transaction ' . $transaction['reference_id']) ?></textarea>
                  <small class="form-text text-muted">
                     This description will appear on the reversal entries for audit purposes.
                  </small>
               </div>

               <div class="alert alert-info">
                  <h5><i class="fas fa-info-circle"></i> How Reversal Works</h5>
                  <p class="mb-0">
                     Reversing a transaction creates opposite entries that cancel out the original transaction.
                     The original transaction remains in the system for audit purposes, but its net effect becomes zero.
                  </p>
               </div>

               <div class="form-group">
                  <button type="submit" class="btn btn-danger"
                     onclick="return confirm('Are you sure you want to reverse this transaction? This action cannot be undone.')">
                     <i class="fas fa-undo"></i> Confirm Reversal
                  </button>
                  <a href="<?= site_url('transactions') ?>" class="btn btn-secondary">Cancel</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>