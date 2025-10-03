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
               <span class="text-muted">As of: <?= date('F j, Y') ?></span>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="content">
   <div class="container-fluid">
      <!-- Key Metrics Cards -->
      <div class="row">
         <!-- Total Assets -->
         <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
               <div class="inner">
                  <h3><?= number_format($metrics['total_assets'], 2) ?></h3>
                  <p>Total Assets</p>
               </div>
               <div class="icon">
                  <i class="fas fa-landmark"></i>
               </div>
               <a href="<?= site_url('reports/balance-sheet') ?>" class="small-box-footer">
                  View Balance Sheet <i class="fas fa-arrow-circle-right"></i>
               </a>
            </div>
         </div>

         <!-- Net Income -->
         <div class="col-lg-3 col-6">
            <div class="small-box bg-<?= $metrics['net_income'] >= 0 ? 'success' : 'danger' ?>">
               <div class="inner">
                  <h3><?= number_format($metrics['net_income'], 2) ?></h3>
                  <p>Net Income (MTD)</p>
               </div>
               <div class="icon">
                  <i class="fas fa-chart-line"></i>
               </div>
               <a href="<?= site_url('reports/income-statement') ?>" class="small-box-footer">
                  View Income Statement <i class="fas fa-arrow-circle-right"></i>
               </a>
            </div>
         </div>

         <!-- Current Ratio -->
         <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
               <div class="inner">
                  <h3><?= number_format($metrics['current_ratio'], 2) ?></h3>
                  <p>Current Ratio</p>
               </div>
               <div class="icon">
                  <i class="fas fa-balance-scale"></i>
               </div>
               <a href="<?= site_url('reports/balance-sheet') ?>" class="small-box-footer">
                  More Info <i class="fas fa-arrow-circle-right"></i>
               </a>
            </div>
         </div>

         <!-- Working Capital -->
         <div class="col-lg-3 col-6">
            <div class="small-box bg-<?= $metrics['working_capital'] >= 0 ? 'primary' : 'danger' ?>">
               <div class="inner">
                  <h3><?= number_format($metrics['working_capital'], 2) ?></h3>
                  <p>Working Capital</p>
               </div>
               <div class="icon">
                  <i class="fas fa-money-bill-wave"></i>
               </div>
               <a href="<?= site_url('reports/balance-sheet') ?>" class="small-box-footer">
                  More Info <i class="fas fa-arrow-circle-right"></i>
               </a>
            </div>
         </div>
      </div>

      <!-- Charts and Detailed Information -->
      <div class="row">
         <!-- Income vs Expenses Chart -->
         <div class="col-md-6">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">Income vs Expenses (This Month)</h3>
               </div>
               <div class="card-body">
                  <div class="chart">
                     <canvas id="incomeExpensesChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  </div>
                  <div class="mt-3 text-center">
                     <span class="badge badge-success mr-3">
                        Revenue: <?= number_format($incomeVsExpenses['revenue'], 2) ?>
                     </span>
                     <span class="badge badge-danger mr-3">
                        Expenses: <?= number_format($incomeVsExpenses['expenses'], 2) ?>
                     </span>
                     <span class="badge badge-<?= $incomeVsExpenses['net_income'] >= 0 ? 'primary' : 'warning' ?>">
                        Net: <?= number_format($incomeVsExpenses['net_income'], 2) ?>
                     </span>
                  </div>
               </div>
            </div>
         </div>

         <!-- Recent Transactions -->
         <div class="col-md-6">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">Recent Transactions</h3>
                  <div class="card-tools">
                     <a href="<?= site_url('transactions') ?>" class="btn btn-tool">
                        <i class="fas fa-list"></i> View All
                     </a>
                  </div>
               </div>
               <div class="card-body p-0">
                  <?php if (empty($recentTransactions)) : ?>
                  <div class="p-3 text-center text-muted">
                     No recent transactions
                  </div>
                  <?php else : ?>
                  <div class="table-responsive">
                     <table class="table table-sm table-hover">
                        <thead>
                           <tr>
                              <th>Date</th>
                              <th>Reference</th>
                              <th class="text-right">Amount</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($recentTransactions as $trans) : ?>
                           <tr>
                              <td><?= $trans['transaction_date'] ?></td>
                              <td>
                                 <a href="<?= site_url('transactions/view/' . $trans['reference_id']) ?>">
                                    <?= $trans['reference_id'] ?>
                                 </a>
                              </td>
                              <td class="text-right">
                                 <span
                                    class="badge badge-<?= $trans['total_debit'] > $trans['total_credit'] ? 'success' : 'danger' ?>">
                                    <?= number_format(max($trans['total_debit'], $trans['total_credit']), 2) ?>
                                 </span>
                              </td>
                           </tr>
                           <?php endforeach; ?>
                        </tbody>
                     </table>
                  </div>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>

      <!-- Account Balances Summary -->
      <div class="row mt-4">
         <div class="col-12">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">Account Balances Summary</h3>
                  <div class="card-tools">
                     <a href="<?= site_url('reports/trial-balance') ?>" class="btn btn-tool">
                        <i class="fas fa-balance-scale"></i> Full Trial Balance
                     </a>
                  </div>
               </div>
               <div class="card-body p-0">
                  <div class="table-responsive">
                     <table class="table table-striped">
                        <thead>
                           <tr>
                              <th>Account Code</th>
                              <th>Account Name</th>
                              <th>Group</th>
                              <th class="text-right">Balance</th>
                              <th>Normal Balance</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($accountSummary as $account) : ?>
                           <tr>
                              <td><code><?= $account['code'] ?></code></td>
                              <td><?= $account['name'] ?></td>
                              <td>
                                 <span class="badge badge-secondary"><?= $account['group'] ?></span>
                              </td>
                              <td class="text-right font-weight-bold">
                                 <?= number_format($account['balance'], 2) ?>
                              </td>
                              <td>
                                 <span
                                    class="badge badge-<?= $account['normal_balance'] == 'Debit' ? 'danger' : 'success' ?>">
                                    <?= $account['normal_balance'] ?>
                                 </span>
                              </td>
                           </tr>
                           <?php endforeach; ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
   // Income vs Expenses Chart
   var incomeExpensesCtx = document.getElementById('incomeExpensesChart').getContext('2d');
   var incomeExpensesChart = new Chart(incomeExpensesCtx, {
      type: 'doughnut',
      data: {
         labels: ['Revenue', 'Expenses'],
         datasets: [{
            data: [
               <?= $incomeVsExpenses['revenue'] ?>,
               <?= $incomeVsExpenses['expenses'] ?>
            ],
            backgroundColor: [
               '#28a745',
               '#dc3545'
            ],
            borderWidth: 2,
            borderColor: '#fff'
         }]
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         legend: {
            position: 'bottom'
         },
         title: {
            display: true,
            text: 'Income vs Expenses Distribution'
         }
      }
   });

   // Auto-refresh dashboard every 5 minutes
   setTimeout(function() {
      window.location.reload();
   }, 300000); // 5 minutes
});
</script>
<?= $this->endSection() ?>