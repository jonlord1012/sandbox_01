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
            <form method="get" action="<?= site_url('reports/income-statement') ?>">
               <div class="row">
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
                           <a href="<?= site_url('reports/income-statement') ?>" class="btn btn-default">Reset</a>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>

      <!-- Income Statement Content -->
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">
               Income Statement
               <br><small class="text-muted">For the period: <?= $filters['start_date'] ?> to
                  <?= $filters['end_date'] ?></small>
            </h3>
         </div>
         <div class="card-body">
            <?php if (empty($incomeStatement['revenue']) && empty($incomeStatement['expenses'])) : ?>
            <div class="p-3 text-center">
               <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
               <p class="text-muted">No income statement data found for the selected period.</p>
            </div>
            <?php else : ?>
            <!-- Revenue Section -->
            <div class="row mb-4">
               <div class="col-12">
                  <div class="card card-success">
                     <div class="card-header">
                        <h3 class="card-title">REVENUE</h3>
                     </div>
                     <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                           <tbody>
                              <?php if (!empty($incomeStatement['revenue'])) : ?>
                              <?php foreach ($incomeStatement['revenue'] as $revenue) : ?>
                              <tr>
                                 <td width="70%"><?= $revenue['name'] ?></td>
                                 <td width="30%" class="text-right font-weight-bold text-success">
                                    <?= number_format($revenue['report_balance'], 2) ?>
                                 </td>
                              </tr>
                              <?php endforeach; ?>
                              <?php else : ?>
                              <tr>
                                 <td colspan="2" class="text-center text-muted">No revenue</td>
                              </tr>
                              <?php endif; ?>
                           </tbody>
                           <tfoot class="table-success">
                              <tr>
                                 <th class="text-uppercase">Total Revenue</th>
                                 <th class="text-right"><?= number_format($incomeStatement['totals']['revenue'], 2) ?>
                                 </th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Expenses Section -->
            <div class="row mb-4">
               <div class="col-12">
                  <div class="card card-danger">
                     <div class="card-header">
                        <h3 class="card-title">EXPENSES</h3>
                     </div>
                     <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                           <tbody>
                              <?php if (!empty($incomeStatement['expenses'])) : ?>
                              <?php foreach ($incomeStatement['expenses'] as $expense) : ?>
                              <tr>
                                 <td width="70%"><?= $expense['name'] ?></td>
                                 <td width="30%" class="text-right font-weight-bold text-danger">
                                    (<?= number_format($expense['report_balance'], 2) ?>)
                                 </td>
                              </tr>
                              <?php endforeach; ?>
                              <?php else : ?>
                              <tr>
                                 <td colspan="2" class="text-center text-muted">No expenses</td>
                              </tr>
                              <?php endif; ?>
                           </tbody>
                           <tfoot class="table-danger">
                              <tr>
                                 <th class="text-uppercase">Total Expenses</th>
                                 <th class="text-right">
                                    (<?= number_format($incomeStatement['totals']['expenses'], 2) ?>)</th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Net Income Calculation -->
            <div class="row">
               <div class="col-12">
                  <div class="card card-<?= $incomeStatement['totals']['net_income'] >= 0 ? 'primary' : 'warning' ?>">
                     <div class="card-body text-center py-3">
                        <h3 class="mb-1">
                           <?php if ($incomeStatement['totals']['net_income'] >= 0) : ?>
                           <i class="fas fa-arrow-up text-success"></i> NET INCOME
                           <?php else : ?>
                           <i class="fas fa-arrow-down text-danger"></i> NET LOSS
                           <?php endif; ?>
                        </h3>
                        <h2 class="mb-0">
                           <?= number_format(abs($incomeStatement['totals']['net_income']), 2) ?>
                        </h2>
                        <p class="mb-0 text-muted">
                           Total Revenue (<?= number_format($incomeStatement['totals']['revenue'], 2) ?>)
                           - Total Expenses (<?= number_format($incomeStatement['totals']['expenses'], 2) ?>)
                        </p>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Detailed Calculation -->
            <div class="row mt-3">
               <div class="col-md-6 offset-md-3">
                  <table class="table table-bordered">
                     <tr>
                        <th class="text-right">Total Revenue</th>
                        <td class="text-right text-success">
                           <?= number_format($incomeStatement['totals']['revenue'], 2) ?></td>
                     </tr>
                     <tr>
                        <th class="text-right">Total Expenses</th>
                        <td class="text-right text-danger">
                           (<?= number_format($incomeStatement['totals']['expenses'], 2) ?>)</td>
                     </tr>
                     <tr class="table-<?= $incomeStatement['totals']['net_income'] >= 0 ? 'success' : 'danger' ?>">
                        <th class="text-right">
                           <?= $incomeStatement['totals']['net_income'] >= 0 ? 'NET INCOME' : 'NET LOSS' ?>
                        </th>
                        <th class="text-right"><?= number_format($incomeStatement['totals']['net_income'], 2) ?></th>
                     </tr>
                  </table>
               </div>
            </div>
            <?php endif; ?>
         </div>
         <div class="card-footer">
            <small class="text-muted">
               Report generated on: <?= date('Y-m-d H:i:s') ?> |
               Period: <?= $filters['start_date'] ?> to <?= $filters['end_date'] ?>
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

.card .card {
   box-shadow: none !important;
   margin-bottom: 0 !important;
}
</style>

<?= $this->endSection() ?>