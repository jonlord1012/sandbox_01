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
            <form method="get" action="<?= site_url('reports/trial-balance') ?>">
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>As of Date</label>
                        <input type="date" name="as_of_date" class="form-control" value="<?= $asOfDate ?>">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                           <button type="submit" class="btn btn-info">Apply Filter</button>
                           <a href="<?= site_url('reports/trial-balance') ?>" class="btn btn-default">Reset</a>
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
            <h3 class="card-title">Trial Balance as of <?= $asOfDate ?></h3>
         </div>
         <div class="card-body p-0">
            <?php if (empty($trialBalance)) : ?>
            <div class="p-3 text-center">
               <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
               <p class="text-muted">No account balances found.</p>
            </div>
            <?php else : ?>
            <div class="table-responsive">
               <table class="table table-striped table-bordered">
                  <thead class="thead-dark">
                     <tr>
                        <th>Account Code</th>
                        <th>Account Name</th>
                        <th>Group</th>
                        <th class="text-right">Debit Total</th>
                        <th class="text-right">Credit Total</th>
                        <th class="text-right">Balance</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php foreach ($trialBalance as $account) : ?>
                     <tr>
                        <td><code><?= $account['code'] ?></code></td>
                        <td><?= $account['name'] ?></td>
                        <td>
                           <span class="badge badge-secondary"><?= $account['group_name'] ?></span>
                        </td>
                        <td class="text-right text-success">
                           <?= number_format($account['total_debit'], 2) ?>
                        </td>
                        <td class="text-right text-danger">
                           <?= number_format($account['total_credit'], 2) ?>
                        </td>
                        <td class="text-right font-weight-bold">
                           <?= number_format($account['balance'], 2) ?>
                           <?php if ($account['balance'] > 0) : ?>
                           <span class="badge badge-success">Debit</span>
                           <?php elseif ($account['balance'] < 0) : ?>
                           <span class="badge badge-danger">Credit</span>
                           <?php else : ?>
                           <span class="badge badge-secondary">Zero</span>
                           <?php endif; ?>
                        </td>
                     </tr>
                     <?php endforeach; ?>
                  </tbody>
                  <tfoot class="table-dark">
                     <tr>
                        <th colspan="3" class="text-right">TOTALS:</th>
                        <th class="text-right"><?= number_format($totals['debit'], 2) ?></th>
                        <th class="text-right"><?= number_format($totals['credit'], 2) ?></th>
                        <th class="text-right">
                           <?= number_format($totals['debit'] - $totals['credit'], 2) ?>
                           <?php if ($totals['debit'] === $totals['credit']) : ?>
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
               Report generated on: <?= date('Y-m-d H:i:s') ?> |
               As of Date: <?= $asOfDate ?>
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
}
</style>

<?= $this->endSection() ?>