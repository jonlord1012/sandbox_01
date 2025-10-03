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
               <a href="<?= site_url('reports') ?>" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Back to Reports
               </a>
               <button onclick="window.print()" class="btn btn-default">
                  <i class="fas fa-print"></i> Print
               </button>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="content">
   <div class="container-fluid">
      <!-- Filter Form -->
      <div class="card card-info">
         <div class="card-header">
            <h3 class="card-title">Filter Report</h3>
         </div>
         <div class="card-body">
            <form method="get" action="<?= site_url('reports/general-ledger') ?>">
               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Account</label>
                        <select name="account_id" class="form-control">
                           <option value="">All Accounts</option>
                           <?php foreach ($accounts as $account) : ?>
                           <option value="<?= $account['id'] ?>"
                              <?= $filters['account_id'] == $account['id'] ? 'selected' : '' ?>>
                              [<?= $account['code'] ?>] <?= $account['name'] ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="<?= $filters['start_date'] ?>">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" value="<?= $filters['end_date'] ?>">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                           <button type="submit" class="btn btn-info">Apply Filters</button>
                           <a href="<?= site_url('reports/general-ledger') ?>" class="btn btn-default">Reset</a>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>

      <!-- Report Content -->
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">General Ledger Report</h3>
            <div class="card-tools">
               <span class="badge badge-primary"><?= count($transactions) ?> Transactions</span>
            </div>
         </div>
         <div class="card-body p-0">
            <?php if (empty($transactions)) : ?>
            <div class="p-3 text-center">
               <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
               <p class="text-muted">No transactions found for the selected filters.</p>
            </div>
            <?php else : ?>
            <div class="table-responsive">
               <table class="table table-striped table-bordered">
                  <thead class="thead-dark">
                     <tr>
                        <th>Date</th>
                        <th>Account</th>
                        <th>Reference</th>
                        <th>Description</th>
                        <th class="text-right">Debit</th>
                        <th class="text-right">Credit</th>
                        <th class="text-right">Balance</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        $currentAccount = null;
                        $accountBalance = 0;
                        $reportTotals = ['debit' => 0, 'credit' => 0];
                        ?>

                     <?php foreach ($transactions as $index => $transaction) : ?>
                     <?php
                           // Calculate running balance
                           $accountBalance += $transaction['debit'] - $transaction['credit'];
                           $reportTotals['debit'] += $transaction['debit'];
                           $reportTotals['credit'] += $transaction['credit'];

                           // Check if we're starting a new account
                           $isNewAccount = $currentAccount !== $transaction['account_id'];
                           $currentAccount = $transaction['account_id'];
                           ?>

                     <!-- Account Header Row -->
                     <?php if ($isNewAccount) : ?>
                     <tr class="table-secondary">
                        <td colspan="7" class="font-weight-bold">
                           <i class="fas fa-folder-open mr-2"></i>
                           [<?= $transaction['account_code'] ?>] <?= $transaction['account_name'] ?>
                           <small class="text-muted ml-2">(<?= $transaction['group_name'] ?>)</small>
                        </td>
                     </tr>
                     <?php endif; ?>

                     <!-- Transaction Row -->
                     <tr>
                        <td><?= $transaction['transaction_date'] ?></td>
                        <td></td> <!-- Empty for alignment -->
                        <td>
                           <a href="<?= site_url('transactions/view/' . $transaction['reference_id']) ?>"
                              class="badge badge-info" title="View transaction details">
                              <?= $transaction['reference_id'] ?>
                           </a>
                        </td>
                        <td><?= esc($transaction['description']) ?></td>
                        <td class="text-right text-success">
                           <?php if ($transaction['debit'] > 0) : ?>
                           <?= number_format($transaction['debit'], 2) ?>
                           <?php else : ?>
                           -
                           <?php endif; ?>
                        </td>
                        <td class="text-right text-danger">
                           <?php if ($transaction['credit'] > 0) : ?>
                           <?= number_format($transaction['credit'], 2) ?>
                           <?php else : ?>
                           -
                           <?php endif; ?>
                        </td>
                        <td class="text-right font-weight-bold">
                           <?= number_format($accountBalance, 2) ?>
                        </td>
                     </tr>

                     <!-- Account Footer after last transaction of account -->
                     <?php
                           $nextTransaction = $transactions[$index + 1] ?? null;
                           $isLastOfAccount = !$nextTransaction || $nextTransaction['account_id'] !== $currentAccount;
                           ?>

                     <?php if ($isLastOfAccount) : ?>
                     <tr class="table-active">
                        <td colspan="4" class="text-right font-weight-bold">
                           Account Total:
                        </td>
                        <td class="text-right font-weight-bold">
                           <?= number_format($accountBalance > 0 ? $accountBalance : 0, 2) ?>
                        </td>
                        <td class="text-right font-weight-bold">
                           <?= number_format($accountBalance < 0 ? abs($accountBalance) : 0, 2) ?>
                        </td>
                        <td class="text-right font-weight-bold">
                           <?= number_format($accountBalance, 2) ?>
                        </td>
                     </tr>
                     <?php $accountBalance = 0; // Reset for next account 
                              ?>
                     <?php endif; ?>
                     <?php endforeach; ?>
                  </tbody>
                  <tfoot class="table-dark">
                     <tr>
                        <th colspan="4" class="text-right">GRAND TOTALS:</th>
                        <th class="text-right"><?= number_format($reportTotals['debit'], 2) ?></th>
                        <th class="text-right"><?= number_format($reportTotals['credit'], 2) ?></th>
                        <th class="text-right">
                           <?= number_format($reportTotals['debit'] - $reportTotals['credit'], 2) ?>
                           <?php if ($reportTotals['debit'] === $reportTotals['credit']) : ?>
                           <i class="fas fa-check-circle text-success ml-1" title="Balanced"></i>
                           <?php else : ?>
                           <i class="fas fa-exclamation-triangle text-danger ml-1" title="Not Balanced"></i>
                           <?php endif; ?>
                        </th>
                     </tr>
                  </tfoot>
               </table>
            </div>
            <?php endif; ?>
         </div>
         <div class="card-footer">
            <small class="text-muted">
               Report generated on: <?= date('Y-m-d H:i:s') ?>
               <?php if ($filters['start_date'] || $filters['end_date']) : ?>
               | Period:
               <?= $filters['start_date'] ? $filters['start_date'] : 'Beginning' ?>
               to
               <?= $filters['end_date'] ? $filters['end_date'] : 'End' ?>
               <?php endif; ?>
            </small>
         </div>
      </div>
   </div>
</div>

<style>
@media print {

   .content-header,
   .card-header,
   .card-info,
   .btn {
      display: none !important;
   }

   .card-body {
      padding: 0 !important;
   }

   table {
      font-size: 12px;
   }
}

.table-responsive {
   max-height: 70vh;
   overflow-y: auto;
}
</style>

<?= $this->endSection() ?>