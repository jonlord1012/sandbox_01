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
      <div class="row">
         <!-- Cash Flow Card -->
         <div class="col-lg-6 col-12">
            <div class="card card-dark">
               <div class="card-header">
                  <h3 class="card-title">Cash Flow Statement</h3>
               </div>
               <div class="card-body">
                  <p>Track cash movements through operations, investing, and financing.</p>
                  <ul>
                     <li>Operating activities (day-to-day business)</li>
                     <li>Investing activities (assets & investments)</li>
                     <li>Financing activities (loans & equity)</li>
                  </ul>
               </div>
               <div class="card-footer">
                  <a href="<?= site_url('reports/cash-flow-statement') ?>" class="btn btn-dark">
                     <i class="fas fa-water"></i> View Cash Flow
                  </a>
               </div>
            </div>
         </div>
         <!-- General Ledger Card -->
         <div class="col-lg-6 col-12">
            <div class="card card-primary">
               <div class="card-header">
                  <h3 class="card-title">General Ledger</h3>
               </div>
               <div class="card-body">
                  <p>View all transactions organized by account.</p>
                  <ul>
                     <li>Complete transaction history</li>
                     <li>Filter by account and date range</li>
                     <li>Running balances</li>
                  </ul>
               </div>
               <div class="card-footer">
                  <a href="<?= site_url('reports/general-ledger') ?>" class="btn btn-primary">
                     <i class="fas fa-book"></i> View General Ledger
                  </a>
               </div>
            </div>
         </div>

         <!-- Trial Balance Card -->
         <div class="col-lg-6 col-12">
            <div class="card card-info">
               <div class="card-header">
                  <h3 class="card-title">Trial Balance</h3>
               </div>
               <div class="card-body">
                  <p>Summary of all account balances.</p>
                  <ul>
                     <li>Debit and credit totals</li>
                     <li>Verify accounting equation</li>
                     <li>As of specific date</li>
                  </ul>
               </div>
               <div class="card-footer">
                  <a href="<?= site_url('reports/trial-balance') ?>" class="btn btn-info">
                     <i class="fas fa-balance-scale"></i> View Trial Balance
                  </a>
               </div>
            </div>
         </div>

         <!-- Balance Sheet Card -->
         <div class="col-lg-6 col-12">
            <div class="card card-success">
               <div class="card-header">
                  <h3 class="card-title">Balance Sheet</h3>
               </div>
               <div class="card-body">
                  <p>Financial position at a specific date.</p>
                  <ul>
                     <li>Assets = Liabilities + Equity</li>
                     <li>Snapshot of financial health</li>
                     <li>As of specific date</li>
                  </ul>
               </div>
               <div class="card-footer">
                  <a href="<?= site_url('reports/balance-sheet') ?>" class="btn btn-success">
                     <i class="fas fa-file-invoice-dollar"></i> View Balance Sheet
                  </a>
               </div>
            </div>
         </div>

         <!-- Income Statement Card -->
         <div class="col-lg-6 col-12">
            <div class="card card-warning">
               <div class="card-header">
                  <h3 class="card-title">Income Statement</h3>
               </div>
               <div class="card-body">
                  <p>Financial performance over a period.</p>
                  <ul>
                     <li>Revenue - Expenses = Net Income</li>
                     <li>Profitability analysis</li>
                     <li>Date range selection</li>
                  </ul>
               </div>
               <div class="card-footer">
                  <a href="<?= site_url('reports/income-statement') ?>" class="btn btn-warning">
                     <i class="fas fa-chart-line"></i> View Income Statement
                  </a>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>