<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $title ?> - Print</title>
   <style>
   body {
      font-family: Arial, sans-serif;
      color: #000;
      background: #fff;
      margin: 0;
      padding: 20px;
      font-size: 12pt;
      line-height: 1.4;
   }

   .print-header {
      text-align: center;
      margin-bottom: 30px;
      border-bottom: 2px solid #000;
      padding-bottom: 15px;
   }

   .print-header h1 {
      margin: 0 0 10px 0;
      font-size: 18pt;
   }

   .print-header p {
      margin: 5px 0;
      font-size: 11pt;
   }

   .section {
      margin-bottom: 25px;
      page-break-inside: avoid;
   }

   .section-header {
      background: #f0f0f0;
      padding: 8px 12px;
      border: 1px solid #000;
      border-bottom: none;
      font-weight: bold;
      font-size: 13pt;
   }

   .section-content {
      border: 1px solid #000;
      padding: 0;
   }

   .table {
      width: 100%;
      border-collapse: collapse;
      margin: 0;
   }

   .table th,
   .table td {
      border: 1px solid #000;
      padding: 8px 12px;
      text-align: left;
   }

   .table th {
      background: #f8f8f8;
      font-weight: bold;
   }

   .table tfoot th {
      background: #e0e0e0;
   }

   .text-right {
      text-align: right;
   }

   .pl-4 {
      padding-left: 40px !important;
   }

   .summary-section {
      text-align: center;
      margin: 30px 0;
      padding: 20px;
      border: 2px solid #000;
   }

   .summary-numbers {
      display: flex;
      justify-content: space-around;
      margin: 20px 0;
   }

   .summary-item {
      padding: 15px;
      border: 1px solid #000;
      min-width: 150px;
   }

   .footer {
      margin-top: 40px;
      text-align: center;
      font-size: 10pt;
      color: #666;
      border-top: 1px solid #000;
      padding-top: 10px;
   }

   @media print {
      .no-print {
         display: none;
      }
   }
   </style>
</head>

<body>
   <!-- Header -->
   <div class="print-header">
      <h1>STATEMENT OF CASH FLOWS</h1>
      <p><strong>BJS Accounting System</strong></p>
      <p>Period: <?= $filters['start_date'] ?> to <?= $filters['end_date'] ?></p>
      <p>Generated on: <?= date('F j, Y \a\t g:i A') ?></p>
   </div>

   <!-- Operating Activities -->
   <div class="section">
      <div class="section-header">CASH FLOWS FROM OPERATING ACTIVITIES</div>
      <div class="section-content">
         <table class="table">
            <tbody>
               <tr>
                  <td width="70%">Net Income</td>
                  <td width="30%" class="text-right">
                     <?= number_format($cashFlow['operating_activities']['components']['net_income'], 2) ?></td>
               </tr>
               <tr>
                  <td colspan="2"><strong>Adjustments to reconcile net income to net cash:</strong></td>
               </tr>
               <tr>
                  <td class="pl-4">Depreciation & Amortization</td>
                  <td class="text-right">
                     <?= number_format($cashFlow['operating_activities']['components']['depreciation'], 2) ?></td>
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
                     <?= number_format($cashFlow['operating_activities']['components']['other_adjustments'], 2) ?></td>
               </tr>
            </tbody>
            <tfoot>
               <tr>
                  <th>Net Cash Provided by Operating Activities</th>
                  <th class="text-right"><?= number_format($cashFlow['operating_activities']['total'], 2) ?></th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>

   <!-- Investing Activities -->
   <div class="section">
      <div class="section-header">CASH FLOWS FROM INVESTING ACTIVITIES</div>
      <div class="section-content">
         <table class="table">
            <tbody>
               <tr>
                  <td width="70%">Purchase of Property and Equipment</td>
                  <td width="30%" class="text-right">
                     (<?= number_format($cashFlow['investing_activities']['components']['purchase_of_assets'], 2) ?>)
                  </td>
               </tr>
               <tr>
                  <td>Proceeds from Sale of Assets</td>
                  <td class="text-right">
                     <?= number_format($cashFlow['investing_activities']['components']['sale_of_assets'], 2) ?></td>
               </tr>
               <tr>
                  <td>Purchase of Investments</td>
                  <td class="text-right">
                     (<?= number_format($cashFlow['investing_activities']['components']['purchase_of_investments'], 2) ?>)
                  </td>
               </tr>
               <tr>
                  <td>Proceeds from Sale of Investments</td>
                  <td class="text-right">
                     <?= number_format($cashFlow['investing_activities']['components']['sale_of_investments'], 2) ?>
                  </td>
               </tr>
            </tbody>
            <tfoot>
               <tr>
                  <th>Net Cash Used in Investing Activities</th>
                  <th class="text-right"><?= number_format($cashFlow['investing_activities']['total'], 2) ?></th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>

   <!-- Financing Activities -->
   <div class="section">
      <div class="section-header">CASH FLOWS FROM FINANCING ACTIVITIES</div>
      <div class="section-content">
         <table class="table">
            <tbody>
               <tr>
                  <td width="70%">Proceeds from Loans</td>
                  <td width="30%" class="text-right">
                     <?= number_format($cashFlow['financing_activities']['components']['loan_proceeds'], 2) ?></td>
               </tr>
               <tr>
                  <td>Repayment of Loans</td>
                  <td class="text-right">
                     (<?= number_format($cashFlow['financing_activities']['components']['loan_repayments'], 2) ?>)</td>
               </tr>
               <tr>
                  <td>Capital Contributions</td>
                  <td class="text-right">
                     <?= number_format($cashFlow['financing_activities']['components']['capital_contributions'], 2) ?>
                  </td>
               </tr>
               <tr>
                  <td>Dividends Paid</td>
                  <td class="text-right">
                     (<?= number_format($cashFlow['financing_activities']['components']['dividends_paid'], 2) ?>)</td>
               </tr>
            </tbody>
            <tfoot>
               <tr>
                  <th>Net Cash Provided by Financing Activities</th>
                  <th class="text-right"><?= number_format($cashFlow['financing_activities']['total'], 2) ?></th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>

   <!-- Summary Section -->
   <div class="summary-section">
      <h3>SUMMARY OF CASH FLOW</h3>

      <div class="summary-numbers">
         <div class="summary-item">
            <strong>Operating</strong><br>
            <?= number_format($cashFlow['operating_activities']['total'], 2) ?>
         </div>
         <div class="summary-item">
            <strong>Investing</strong><br>
            <?= number_format($cashFlow['investing_activities']['total'], 2) ?>
         </div>
         <div class="summary-item">
            <strong>Financing</strong><br>
            <?= number_format($cashFlow['financing_activities']['total'], 2) ?>
         </div>
      </div>

      <table style="width: 60%; margin: 0 auto; border-collapse: collapse;">
         <tr>
            <td style="padding: 8px; border: 1px solid #000;"><strong>Net Increase (Decrease) in Cash</strong></td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; font-weight: bold;">
               <?= number_format($cashFlow['operating_activities']['total'] + $cashFlow['investing_activities']['total'] + $cashFlow['financing_activities']['total'], 2) ?>
            </td>
         </tr>
         <tr>
            <td style="padding: 8px; border: 1px solid #000;">Cash at Beginning of Period</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right;">
               <?= number_format($cashFlow['net_cash_flow']['beginning_cash'], 2) ?>
            </td>
         </tr>
         <tr style="background: #e0e0e0;">
            <td style="padding: 8px; border: 1px solid #000;"><strong>Cash at End of Period</strong></td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; font-weight: bold;">
               <?= number_format($cashFlow['net_cash_flow']['ending_cash'], 2) ?>
            </td>
         </tr>
      </table>
   </div>

   <div class="footer">
      This report was generated automatically by BJS Accounting System
   </div>

   <script>
   window.onload = function() {
      window.print();
   }
   </script>
</body>

</html>