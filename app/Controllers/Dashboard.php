<?php

namespace App\Controllers;

use App\Models\ReportModel;
use App\Models\TransactionModel;

class Dashboard extends BaseController
{
   protected $reportModel;
   protected $transactionModel;

   public function __construct()
   {
      $this->reportModel = new ReportModel();
      $this->transactionModel = new TransactionModel();
   }

   public function index()
   {
      $currentDate = date('Y-m-d');
      $firstDayOfMonth = date('Y-m-01');
      $lastDayOfMonth = date('Y-m-t');

      // Key Financial Metrics
      $data['metrics'] = $this->getFinancialMetrics($currentDate);

      // Recent Transactions
      $data['recentTransactions'] = $this->transactionModel
         ->getTransactionReferences($firstDayOfMonth, $currentDate);
      $data['recentTransactions'] = array_slice($data['recentTransactions'], 0, 10); // Last 10

      // Account Balances Summary
      $data['accountSummary'] = $this->getAccountSummary($currentDate);

      // Income vs Expenses (current month)
      $data['incomeVsExpenses'] = $this->getIncomeVsExpenses($firstDayOfMonth, $currentDate);

      $data['title'] = 'Financial Dashboard';

      return view('dashboard', $data);
   }

   private function getFinancialMetrics($asOfDate)
   {
      $balanceSheet = $this->reportModel->getBalanceSheet($asOfDate);
      $incomeStatement = $this->reportModel->getIncomeStatement(date('Y-m-01'), $asOfDate);

      return [
         'total_assets' => $balanceSheet['totals']['assets'],
         'total_liabilities' => $balanceSheet['totals']['liabilities'],
         'total_equity' => $balanceSheet['totals']['equity'],
         'net_income' => $incomeStatement['totals']['net_income'],
         'current_ratio' => $this->calculateCurrentRatio($balanceSheet),
         'working_capital' => $this->calculateWorkingCapital($balanceSheet)
      ];
   }

   private function getAccountSummary($asOfDate)
   {
      $trialBalance = $this->reportModel->getTrialBalance($asOfDate);

      $summary = [];
      foreach ($trialBalance as $account) {
         $balance = $account['balance'];
         if (!$account['normal_balance']) {
            $balance = -$balance;
         }

         $summary[] = [
            'code' => $account['code'],
            'name' => $account['name'],
            'group' => $account['group_name'],
            'balance' => $balance,
            'normal_balance' => $account['normal_balance'] ? 'Debit' : 'Credit'
         ];
      }

      return $summary;
   }

   private function getIncomeVsExpenses($startDate, $endDate)
   {
      $incomeStatement = $this->reportModel->getIncomeStatement($startDate, $endDate);

      return [
         'revenue' => $incomeStatement['totals']['revenue'],
         'expenses' => $incomeStatement['totals']['expenses'],
         'net_income' => $incomeStatement['totals']['net_income']
      ];
   }

   private function calculateCurrentRatio($balanceSheet)
   {
      // Simplified - you might want to identify current assets/liabilities specifically
      $currentAssets = $balanceSheet['totals']['assets']; // This should be only current assets
      $currentLiabilities = $balanceSheet['totals']['liabilities']; // This should be only current liabilities

      if ($currentLiabilities == 0) return 0;
      return $currentAssets / $currentLiabilities;
   }

   private function calculateWorkingCapital($balanceSheet)
   {
      $currentAssets = $balanceSheet['totals']['assets'];
      $currentLiabilities = $balanceSheet['totals']['liabilities'];

      return $currentAssets - $currentLiabilities;
   }
}