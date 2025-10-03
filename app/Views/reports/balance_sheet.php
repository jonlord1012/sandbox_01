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
            <form method="get" action="<?= site_url('reports/balance-sheet') ?>">
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
                           <a href="<?= site_url('reports/balance-sheet') ?>" class="btn btn-default">Reset</a>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>

      <!-- Balance Sheet Content -->
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Balance Sheet as of <?= $asOfDate ?></h3>
         </div>
         <div class="card-body">
            <?php if (empty($balanceSheet['assets']) && empty($balanceSheet['liabilities']) && empty($balanceSheet['equity'])) : ?>
            <div class="p-3 text-center">
               <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
               <p class="text-muted">No balance sheet data found.</p>
            </div>
            <?php else : ?>
            <div class="row">
               <!-- Assets Column -->
               <div class="col-md-6">
                  <div class="card card-success">
                     <div class="card-header">
                        <h3 class="card-title">ASSETS</h3>
                     </div>
                     <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                           <tbody>
                              <?php if (!empty($balanceSheet['assets'])) : ?>
                              <?php foreach ($balanceSheet['assets'] as $asset) : ?>
                              <tr>
                                 <td><?= $asset['name'] ?></td>
                                 <td class="text-right font-weight-bold">
                                    <?= number_format($asset['report_balance'], 2) ?>
                                 </td>
                              </tr>
                              <?php endforeach; ?>
                              <?php else : ?>
                              <tr>
                                 <td colspan="2" class="text-center text-muted">No assets</td>
                              </tr>
                              <?php endif; ?>
                           </tbody>
                           <tfoot class="table-success">
                              <tr>
                                 <th class="text-uppercase">Total Assets</th>
                                 <th class="text-right"><?= number_format($balanceSheet['totals']['assets'], 2) ?></th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>
               </div>

               <!-- Liabilities & Equity Column -->
               <div class="col-md-6">
                  <!-- Liabilities -->
                  <div class="card card-danger mb-3">
                     <div class="card-header">
                        <h3 class="card-title">LIABILITIES</h3>
                     </div>
                     <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                           <tbody>
                              <?php if (!empty($balanceSheet['liabilities'])) : ?>
                              <?php foreach ($balanceSheet['liabilities'] as $liability) : ?>
                              <tr>
                                 <td><?= $liability['name'] ?></td>
                                 <td class="text-right font-weight-bold">
                                    <?= number_format($liability['report_balance'], 2) ?>
                                 </td>
                              </tr>
                              <?php endforeach; ?>
                              <?php else : ?>
                              <tr>
                                 <td colspan="2" class="text-center text-muted">No liabilities</td>
                              </tr>
                              <?php endif; ?>
                           </tbody>
                           <tfoot class="table-danger">
                              <tr>
                                 <th class="text-uppercase">Total Liabilities</th>
                                 <th class="text-right"><?= number_format($balanceSheet['totals']['liabilities'], 2) ?>
                                 </th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>

                  <!-- Equity -->
                  <div class="card card-warning">
                     <div class="card-header">
                        <h3 class="card-title">EQUITY</h3>
                     </div>
                     <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                           <tbody>
                              <?php if (!empty($balanceSheet['equity'])) : ?>
                              <?php foreach ($balanceSheet['equity'] as $equity) : ?>
                              <tr>
                                 <td><?= $equity['name'] ?></td>
                                 <td class="text-right font-weight-bold">
                                    <?= number_format($equity['report_balance'], 2) ?>
                                 </td>
                              </tr>
                              <?php endforeach; ?>
                              <?php else : ?>
                              <tr>
                                 <td colspan="2" class="text-center text-muted">No equity accounts</td>
                              </tr>
                              <?php endif; ?>
                           </tbody>
                           <tfoot class="table-warning">
                              <tr>
                                 <th class="text-uppercase">Total Equity</th>
                                 <th class="text-right"><?= number_format($balanceSheet['totals']['equity'], 2) ?></th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>

                  <!-- Liabilities + Equity Total -->
                  <div class="mt-3 p-2 bg-light rounded">
                     <table class="table table-sm mb-0">
                        <tfoot>
                           <tr>
                              <th class="text-uppercase">Total Liabilities & Equity</th>
                              <th class="text-right">
                                 <?= number_format($balanceSheet['totals']['liabilities'] + $balanceSheet['totals']['equity'], 2) ?>
                              </th>
                           </tr>
                        </tfoot>
                     </table>
                  </div>
               </div>
            </div>

            <!-- Balance Verification -->
            <div class="row mt-4">
               <div class="col-12">
                  <?php
                     $assetsTotal = $balanceSheet['totals']['assets'];
                     $liabilitiesEquityTotal = $balanceSheet['totals']['liabilities'] + $balanceSheet['totals']['equity'];
                     $isBalanced = abs($assetsTotal - $liabilitiesEquityTotal) < 0.01; // Allow for rounding
                     ?>

                  <div class="alert alert-<?= $isBalanced ? 'success' : 'danger' ?> text-center">
                     <h4 class="alert-heading">
                        <?php if ($isBalanced) : ?>
                        <i class="fas fa-check-circle"></i> BALANCE SHEET BALANCED
                        <?php else : ?>
                        <i class="fas fa-exclamation-triangle"></i> BALANCE SHEET OUT OF BALANCE
                        <?php endif; ?>
                     </h4>
                     <p class="mb-0">
                        <strong>Assets (<?= number_format($assetsTotal, 2) ?>)
                           = Liabilities (<?= number_format($balanceSheet['totals']['liabilities'], 2) ?>)
                           + Equity (<?= number_format($balanceSheet['totals']['equity'], 2) ?>)</strong>
                        <br>
                        Difference: <strong><?= number_format($assetsTotal - $liabilitiesEquityTotal, 2) ?></strong>
                     </p>
                  </div>
               </div>
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

.card .card {
   box-shadow: none !important;
   margin-bottom: 0 !important;
}
</style>

<?= $this->endSection() ?>