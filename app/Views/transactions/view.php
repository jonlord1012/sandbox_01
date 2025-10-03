<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1 class="m-0">
               <?= $title ?>
               <?php if ($transactionData['is_reversal']) : ?>
               <span class="badge badge-warning ml-2">REVERSAL ENTRY</span>
               <?php elseif ($transactionData['has_been_reversed']) : ?>
               <span class="badge badge-secondary ml-2">REVERSED</span>
               <?php endif; ?>
            </h1>
         </div>
         <div class="col-sm-6">
            <a href="<?= site_url('transactions') ?>" class="btn btn-secondary float-right">
               <i class="fas fa-arrow-left"></i> Back to Journal
            </a>
         </div>
      </div>
   </div>
</div>

<div class="content">
   <div class="container-fluid">
      <!-- Reversal Information Banner -->
      <?php if ($transactionData['is_reversal']) : ?>
      <div class="alert alert-warning">
         <h5><i class="fas fa-exchange-alt"></i> This is a Reversal Transaction</h5>
         <p class="mb-0">
            This transaction reverses:
            <strong>
               <a href="<?= site_url('transactions/view/' . $transactionData['reverses_reference']) ?>">
                  <?= $transactionData['reverses_reference'] ?>
               </a>
            </strong>
         </p>
      </div>
      <?php elseif ($transactionData['has_been_reversed']) : ?>
      <div class="alert alert-info">
         <h5><i class="fas fa-info-circle"></i> This transaction has been reversed</h5>
         <p class="mb-0">
            Reversal entries exist for this transaction. The net effect is zero.
         </p>
      </div>
      <?php endif; ?>
      <!-- Transaction Header -->
      <div class="card card-info">
         <div class="card-header">
            <h3 class="card-title">Transaction Summary</h3>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-md-3">
                  <strong>Reference ID:</strong><br>
                  <code><?= $referenceId ?></code>
                  <?php if ($transactionData['is_reversal']) : ?>
                  <span class="badge badge-warning ml-1">Reversal</span>
                  <?php endif; ?>
               </div>
               <div class="col-md-3">
                  <strong>Transaction Date:</strong><br>
                  <?= $transactionData['lines'][0]['transaction_date'] ?>
               </div>
               <div class="col-md-6">
                  <strong>Description:</strong><br>
                  <?= esc($transactionData['lines'][0]['description']) ?>
               </div>
            </div>
         </div>
      </div>

      <!-- Transaction Entries -->
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Journal Entries</h3>
         </div>
         <div class="card-body p-0">
            <table class="table table-striped">
               <thead>
                  <tr>
                     <th>Account</th>
                     <th>Account Name</th>
                     <th class="text-right">Debit</th>
                     <th class="text-right">Credit</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  $totalDebit = 0;
                  $totalCredit = 0;
                  ?>
                  <?php foreach ($transactionData['lines'] as $entry) : ?>
                  <tr>
                     <td><code><?= $entry['account_code'] ?></code></td>
                     <td><?= esc($entry['account_name']) ?></td>
                     <td class="text-right">
                        <?php if ($entry['debit'] > 0) : ?>
                        <span class="text-success"><?= number_format($entry['debit'], 2) ?></span>
                        <?php $totalDebit += $entry['debit']; ?>
                        <?php else : ?>
                        -
                        <?php endif; ?>
                     </td>
                     <td class="text-right">
                        <?php if ($entry['credit'] > 0) : ?>
                        <span class="text-danger"><?= number_format($entry['credit'], 2) ?></span>
                        <?php $totalCredit += $entry['credit']; ?>
                        <?php else : ?>
                        -
                        <?php endif; ?>
                     </td>
                  </tr>
                  <?php endforeach; ?>
               </tbody>
               <tfoot>
                  <tr class="table-secondary">
                     <th colspan="2" class="text-right">TOTALS:</th>
                     <th class="text-right"><?= number_format($totalDebit, 2) ?></th>
                     <th class="text-right"><?= number_format($totalCredit, 2) ?></th>
                  </tr>
                  <tr class="<?= $totalDebit === $totalCredit ? 'table-success' : 'table-danger' ?>">
                     <th colspan="2" class="text-right">BALANCE:</th>
                     <th colspan="2" class="text-center">
                        <?= number_format($totalDebit - $totalCredit, 2) ?>
                        <?php if ($totalDebit === $totalCredit) : ?>
                        <i class="fas fa-check-circle ml-2"></i> Balanced
                        <?php else : ?>
                        <i class="fas fa-exclamation-triangle ml-2"></i> Not Balanced
                        <?php endif; ?>
                     </th>
                  </tr>
               </tfoot>
            </table>
         </div>
      </div>
      <!-- Reversal Relationships -->
      <?php if ($transactionData['reverses_reference'] || !empty($transactionData['reversal_references'])) : ?>
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Reversal Relationships</h3>
         </div>
         <div class="card-body">
            <?php if ($transactionData['reverses_reference']) : ?>
            <div class="mb-3">
               <strong>This transaction reverses:</strong><br>
               <a href="<?= site_url('transactions/view/' . $transactionData['reverses_reference']) ?>"
                  class="btn btn-sm btn-outline-warning">
                  <i class="fas fa-external-link-alt"></i> <?= $transactionData['reverses_reference'] ?>
               </a>
            </div>
            <?php endif; ?>

            <?php if (!empty($transactionData['reversal_references'])) : ?>
            <div>
               <strong>This transaction has been reversed by:</strong><br>
               <?php foreach ($transactionData['reversal_references'] as $revRef) : ?>
               <a href="<?= site_url('transactions/view/' . $revRef) ?>" class="btn btn-sm btn-outline-info mr-1 mb-1">
                  <i class="fas fa-external-link-alt"></i> <?= $revRef ?>
               </a>
               <?php endforeach; ?>
            </div>
            <?php endif; ?>
         </div>
      </div>
      <?php endif; ?>

      <!-- Transaction Metadata -->
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Transaction Details</h3>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-md-4">
                  <strong>Created:</strong>
                  <?= date('Y-m-d H:i:s', strtotime($transactionData['lines'][0]['created_at'])) ?>
               </div>
               <div class="col-md-4">
                  <strong>Number of Entries:</strong> <?= count($transactionData['lines']) ?>
               </div>
               <div class="col-md-4">
                  <strong>Status:</strong>
                  <span class="badge badge-<?= $totalDebit === $totalCredit ? 'success' : 'danger' ?>">
                     <?= $totalDebit === $totalCredit ? 'BALANCED' : 'UNBALANCED' ?>
                  </span>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>