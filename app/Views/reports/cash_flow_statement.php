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
               <a href="<?= site_url('reports/cash-flow-statement/print?start_date=' . $filters['start_date'] . '&end_date=' . $filters['end_date']) ?>"
                  class="btn btn-default" target="_blank">
                  <i class="fas fa-print"></i> Print
               </a>
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
            <form method="get" action="<?= site_url('reports/cash-flow-statement') ?>">
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
                           <a href="<?= site_url('reports/cash-flow-statement') ?>" class="btn btn-default">Reset</a>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>

      <!-- Cash Flow Statement Content -->
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">
               Statement of Cash Flows
               <br><small class="text-muted">For the period: <?= $filters['start_date'] ?> to
                  <?= $filters['end_date'] ?></small>
            </h3>
         </div>
         <div class="card-body">
            <?php if (empty($cashFlow)) : ?>
            <div class="p-3 text-center">
               <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
               <p class="text-muted">No cash flow data available for the selected period.</p>
            </div>
            <?php else : ?>
            <!-- Cash Flow Statement -->
            <div class="row justify-content-center">
               <div class="col-md-10">
                  <!-- Operating Activities -->
                  <div class="card card-success mb-4">
                     <div class="card-header">
                        <h3 class="card-title">Cash Flows from Operating Activities</h3>
                     </div>
                     <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                           <tbody>
                              <tr>
                                 <td width="70%">Net Income</td>
                                 <td width="30%" class="text-right">
                                    <?= number_format($cashFlow['operating_activities']['components']['net_income'], 2) ?>
                                 </td>
                              </tr>
                              <tr>
                                 <td>Adjustments to reconcile net income to net cash:</td>
                                 <td></td>
                              </tr>
                              <tr>
                                 <td class="pl-4">Depreciation & Amortization</td>
                                 <td class="text-right">
                                    <?= number_format($cashFlow['operating_activities']['components']['depreciation'], 2) ?>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="pl-4">Changes in Accounts Receivable</td>
                                 <td class="text-right">
                                    <?= number_format($cashFlow['operating_activities']['components']['changes_in_receivables'], 2) ?>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="pl-4">Changes in Accounts Payable</td>
                                 <td class="text-right">
                                    <?= number_format($cashFlow['operating_activities']['components']['changes_in_payables'], 2) ?>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="pl-4">Changes in Inventory</td>
                                 <td class="text-right">
                                    <?= number_format($cashFlow['operating_activities']['components']['changes_in_inventory'], 2) ?>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="pl-4">Other Adjustments</td>
                                 <td class="text-right">
                                    <?= number_format($cashFlow['operating_activities']['components']['other_adjustments'], 2) ?>
                                 </td>
                              </tr>
                           </tbody>
                           <tfoot class="table-success">
                              <tr>
                                 <th>Net Cash Provided by Operating Activities</th>
                                 <th class="text-right">
                                    <?= number_format($cashFlow['operating_activities']['total'], 2) ?></th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>

                  <!-- Investing Activities -->
                  <div class="card card-primary mb-4">
                     <div class="card-header">
                        <h3 class="card-title">Cash Flows from Investing Activities</h3>
                     </div>
                     <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                           <tbody>
                              <tr>
                                 <td width="70%">Purchase of Property and Equipment</td>
                                 <td width="30%" class="text-right text-danger">
                                    (<?= number_format($cashFlow['investing_activities']['components']['purchase_of_assets'], 2) ?>)
                                 </td>
                              </tr>
                              <tr>
                                 <td>Proceeds from Sale of Assets</td>
                                 <td class="text-right text-success">
                                    <?= number_format($cashFlow['investing_activities']['components']['sale_of_assets'], 2) ?>
                                 </td>
                              </tr>
                              <tr>
                                 <td>Purchase of Investments</td>
                                 <td class="text-right text-danger">
                                    (<?= number_format($cashFlow['investing_activities']['components']['purchase_of_investments'], 2) ?>)
                                 </td>
                              </tr>
                              <tr>
                                 <td>Proceeds from Sale of Investments</td>
                                 <td class="text-right text-success">
                                    <?= number_format($cashFlow['investing_activities']['components']['sale_of_investments'], 2) ?>
                                 </td>
                              </tr>
                           </tbody>
                           <tfoot class="table-primary">
                              <tr>
                                 <th>Net Cash Used in Investing Activities</th>
                                 <th class="text-right">
                                    <?= number_format($cashFlow['investing_activities']['total'], 2) ?></th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>

                  <!-- Financing Activities -->
                  <div class="card card-warning mb-4">
                     <div class="card-header">
                        <h3 class="card-title">Cash Flows from Financing Activities</h3>
                     </div>
                     <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                           <tbody>
                              <tr>
                                 <td width="70%">Proceeds from Loans</td>
                                 <td width="30%" class="text-right text-success">
                                    <?= number_format($cashFlow['financing_activities']['components']['loan_proceeds'], 2) ?>
                                 </td>
                              </tr>
                              <tr>
                                 <td>Repayment of Loans</td>
                                 <td class="text-right text-danger">
                                    (<?= number_format($cashFlow['financing_activities']['components']['loan_repayments'], 2) ?>)
                                 </td>
                              </tr>
                              <tr>
                                 <td>Capital Contributions</td>
                                 <td class="text-right text-success">
                                    <?= number_format($cashFlow['financing_activities']['components']['capital_contributions'], 2) ?>
                                 </td>
                              </tr>
                              <tr>
                                 <td>Dividends Paid</td>
                                 <td class="text-right text-danger">
                                    (<?= number_format($cashFlow['financing_activities']['components']['dividends_paid'], 2) ?>)
                                 </td>
                              </tr>
                           </tbody>
                           <tfoot class="table-warning">
                              <tr>
                                 <th>Net Cash Provided by Financing Activities</th>
                                 <th class="text-right">
                                    <?= number_format($cashFlow['financing_activities']['total'], 2) ?></th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>

                  <!-- Net Cash Flow Summary -->
                  <div class="card card-dark">
                     <div class="card-body text-center py-4">
                        <h4 class="mb-3">Summary of Cash Flow</h4>
                        <div class="row">
                           <div class="col-md-4">
                              <div class="border rounded p-3 bg-light">
                                 <h5 class="text-success">Operating</h5>
                                 <h3><?= number_format($cashFlow['operating_activities']['total'], 2) ?></h3>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="border rounded p-3 bg-light">
                                 <h5 class="text-primary">Investing</h5>
                                 <h3><?= number_format($cashFlow['investing_activities']['total'], 2) ?></h3>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="border rounded p-3 bg-light">
                                 <h5 class="text-warning">Financing</h5>
                                 <h3><?= number_format($cashFlow['financing_activities']['total'], 2) ?></h3>
                              </div>
                           </div>
                        </div>

                        <hr class="my-4">

                        <div class="row justify-content-center">
                           <div class="col-md-8">
                              <table class="table table-bordered">
                                 <tr>
                                    <th>Net Increase (Decrease) in Cash</th>
                                    <td class="text-right font-weight-bold">
                                       <?= number_format($cashFlow['operating_activities']['total'] + $cashFlow['investing_activities']['total'] + $cashFlow['financing_activities']['total'], 2) ?>
                                    </td>
                                 </tr>
                                 <tr>
                                    <th>Cash at Beginning of Period</th>
                                    <td class="text-right">
                                       <?= number_format($cashFlow['net_cash_flow']['beginning_cash'], 2) ?></td>
                                 </tr>
                                 <tr class="table-dark">
                                    <th>Cash at End of Period</th>
                                    <th class="text-right">
                                       <?= number_format($cashFlow['net_cash_flow']['ending_cash'], 2) ?></th>
                                 </tr>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
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



<?= $this->endSection() ?>