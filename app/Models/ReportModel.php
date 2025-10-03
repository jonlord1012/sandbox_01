<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
   protected $db;

   public function __construct()
   {
      $this->db = db_connect();
   }

   /**
    * Get General Ledger - all transactions for specific account or all accounts
    */
   public function getGeneralLedger($accountId = null, $startDate = null, $endDate = null)
   {
      $builder = $this->db->table('journal j');
      $builder->select('j.*, a.code as account_code, a.name as account_name, g.name as group_name, g.category');
      $builder->join('accounts a', 'a.id = j.account_id');
      $builder->join('groups g', 'g.id = a.group_id');
      $builder->orderBy('a.code', 'ASC');
      $builder->orderBy('j.transaction_date', 'ASC');
      $builder->orderBy('j.created_at', 'ASC');

      if ($accountId) {
         $builder->where('j.account_id', $accountId);
      }

      if ($startDate) {
         $builder->where('j.transaction_date >=', $startDate);
      }

      if ($endDate) {
         $builder->where('j.transaction_date <=', $endDate);
      }

      return $builder->get()->getResultArray();
   }

   /**
    * Get Trial Balance - account balances up to a specific date
    */
   public function getTrialBalance($asOfDate = null)
   {
      $builder = $this->db->table('journal j');
      $builder->select('a.id, a.code, a.name, g.name as group_name, g.category, g.is_debit as normal_balance');
      $builder->select('SUM(j.debit) as total_debit, SUM(j.credit) as total_credit');
      $builder->select('(SUM(j.debit) - SUM(j.credit)) as balance');
      $builder->join('accounts a', 'a.id = j.account_id');
      $builder->join('groups g', 'g.id = a.group_id');
      $builder->where('a.is_active', 1);
      $builder->groupBy('a.id, a.code, a.name, g.name, g.category, g.is_debit');

      if ($asOfDate) {
         $builder->where('j.transaction_date <=', $asOfDate);
      }

      $builder->orderBy('a.code', 'ASC');

      return $builder->get()->getResultArray();
   }

   /**
    * Get Balance Sheet data
    */
   public function getBalanceSheet($asOfDate = null)
   {
      $trialBalance = $this->getTrialBalance($asOfDate);

      $balanceSheet = [
         'assets' => [],
         'liabilities' => [],
         'equity' => [],
         'totals' => [
            'assets' => 0,
            'liabilities' => 0,
            'equity' => 0
         ]
      ];

      foreach ($trialBalance as $account) {
         $balance = $account['balance'];

         // Adjust balance based on normal balance
         if (!$account['normal_balance']) {
            $balance = -$balance; // Credit accounts show as positive for reporting
         }

         switch ($account['category']) {
            case 'ASSETS':
               $balanceSheet['assets'][] = $account + ['report_balance' => $balance];
               $balanceSheet['totals']['assets'] += $balance;
               break;
            case 'LIABILITIES':
               $balanceSheet['liabilities'][] = $account + ['report_balance' => $balance];
               $balanceSheet['totals']['liabilities'] += $balance;
               break;
            case 'EQUITY':
               $balanceSheet['equity'][] = $account + ['report_balance' => $balance];
               $balanceSheet['totals']['equity'] += $balance;
               break;
         }
      }

      return $balanceSheet;
   }

   /**
    * Get Income Statement data
    */
   public function getIncomeStatement($startDate = null, $endDate = null)
   {
      $builder = $this->db->table('journal j');
      $builder->select('a.id, a.code, a.name, g.name as group_name');
      $builder->select('SUM(j.debit) as total_debit, SUM(j.credit) as total_credit');
      $builder->select('(SUM(j.debit) - SUM(j.credit)) as balance');
      $builder->join('accounts a', 'a.id = j.account_id');
      $builder->join('groups g', 'g.id = a.group_id');
      $builder->where('a.is_active', 1);
      $builder->whereIn('g.category', ['REVENUE', 'EXPENSES']);
      $builder->groupBy('a.id, a.code, a.name, g.name');

      if ($startDate) {
         $builder->where('j.transaction_date >=', $startDate);
      }

      if ($endDate) {
         $builder->where('j.transaction_date <=', $endDate);
      }

      $builder->orderBy('g.category', 'DESC'); // Revenue first, then expenses
      $builder->orderBy('a.code', 'ASC');

      $results = $builder->get()->getResultArray();

      $incomeStatement = [
         'revenue' => [],
         'expenses' => [],
         'totals' => [
            'revenue' => 0,
            'expenses' => 0,
            'net_income' => 0
         ]
      ];

      foreach ($results as $account) {
         $balance = $account['balance'];

         // Fix: Check the category properly
         $category = '';
         foreach (['REVENUE', 'EXPENSES'] as $cat) {
            if (strpos($account['group_name'], $cat) !== false) {
               $category = $cat;
               break;
            }
         }

         if ($category === 'REVENUE') {
            $balance = -$balance; // Show revenue as positive
            $incomeStatement['revenue'][] = $account + ['report_balance' => $balance];
            $incomeStatement['totals']['revenue'] += $balance;
         } else {
            $incomeStatement['expenses'][] = $account + ['report_balance' => $balance];
            $incomeStatement['totals']['expenses'] += $balance;
         }
      }

      $incomeStatement['totals']['net_income'] =
         $incomeStatement['totals']['revenue'] - $incomeStatement['totals']['expenses'];

      return $incomeStatement;
   }

   /**
    * Get account list for dropdowns
    */
   public function getAccountsForDropdown()
   {
      $builder = $this->db->table('accounts a');
      $builder->select('a.id, a.code, a.name, g.name as group_name');
      $builder->join('groups g', 'g.id = a.group_id');
      $builder->where('a.is_active', 1);
      $builder->orderBy('a.code', 'ASC');

      return $builder->get()->getResultArray();
   }

   /**
    * Get Cash Flow Statement data
    */
   public function getCashFlowStatement($startDate, $endDate)
   {
      // Get beginning and ending balance sheet for the period
      $beginningBalanceSheet = $this->getBalanceSheet($startDate);
      $endingBalanceSheet = $this->getBalanceSheet($endDate);

      // Get income statement for the period
      $incomeStatement = $this->getIncomeStatement($startDate, $endDate);

      // Get all transactions for the period for detailed analysis
      $periodTransactions = $this->getGeneralLedger(null, $startDate, $endDate);

      return [
         'period' => [
            'start_date' => $startDate,
            'end_date' => $endDate
         ],
         'operating_activities' => $this->calculateOperatingActivities($incomeStatement, $beginningBalanceSheet, $endingBalanceSheet, $periodTransactions),
         'investing_activities' => $this->calculateInvestingActivities($periodTransactions),
         'financing_activities' => $this->calculateFinancingActivities($periodTransactions),
         'net_cash_flow' => $this->calculateNetCashFlow($beginningBalanceSheet, $endingBalanceSheet)
      ];
   }

   /**
    * Calculate Cash from Operating Activities (Indirect Method)
    */
   private function calculateOperatingActivities($incomeStatement, $beginningBS, $endingBS, $transactions)
   {
      // Start with net income
      $netIncome = $incomeStatement['totals']['net_income'];

      // Adjust for non-cash items and working capital changes
      $adjustments = [
         'net_income' => $netIncome,
         'depreciation' => $this->calculateDepreciation($transactions),
         'changes_in_receivables' => $this->calculateWorkingCapitalChange($beginningBS, $endingBS, 'receivables'),
         'changes_in_payables' => $this->calculateWorkingCapitalChange($beginningBS, $endingBS, 'payables'),
         'changes_in_inventory' => $this->calculateWorkingCapitalChange($beginningBS, $endingBS, 'inventory'),
         'other_adjustments' => 0
      ];

      $totalAdjustments = array_sum(array_slice($adjustments, 1)); // Exclude net_income
      $cashFromOperations = $netIncome + $totalAdjustments;

      return [
         'components' => $adjustments,
         'total' => $cashFromOperations
      ];
   }

   /**
    * Calculate Cash from Investing Activities
    */
   private function calculateInvestingActivities($transactions)
   {
      $investingActivities = [
         'purchase_of_assets' => 0,
         'sale_of_assets' => 0,
         'purchase_of_investments' => 0,
         'sale_of_investments' => 0
      ];

      foreach ($transactions as $transaction) {
         // Identify investing activities by account groups
         if (
            strpos($transaction['group_name'], 'ASSETS') !== false &&
            !in_array($transaction['account_code'], ['AL-1010', 'AL-1011', 'AL-1012', 'AL-1020', 'AL-1021'])
         ) { // Exclude cash accounts
            if ($transaction['debit'] > 0) {
               $investingActivities['purchase_of_assets'] += $transaction['debit'];
            } else {
               $investingActivities['sale_of_assets'] += $transaction['credit'];
            }
         }

         // Investment transactions (you might have specific investment accounts)
         if (
            strpos($transaction['account_name'], 'INVESTASI') !== false ||
            strpos($transaction['account_code'], 'AI-') === 0
         ) {
            if ($transaction['debit'] > 0) {
               $investingActivities['purchase_of_investments'] += $transaction['debit'];
            } else {
               $investingActivities['sale_of_investments'] += $transaction['credit'];
            }
         }
      }

      $netInvesting = ($investingActivities['sale_of_assets'] + $investingActivities['sale_of_investments'])
         - ($investingActivities['purchase_of_assets'] + $investingActivities['purchase_of_investments']);

      return [
         'components' => $investingActivities,
         'total' => $netInvesting
      ];
   }

   /**
    * Calculate Cash from Financing Activities
    */
   private function calculateFinancingActivities($transactions)
   {
      $financingActivities = [
         'loan_proceeds' => 0,
         'loan_repayments' => 0,
         'capital_contributions' => 0,
         'dividends_paid' => 0
      ];

      foreach ($transactions as $transaction) {
         // Loan transactions
         if (
            strpos($transaction['group_name'], 'LIABILITIES') !== false ||
            strpos($transaction['account_name'], 'HUTANG') !== false ||
            strpos($transaction['account_code'], 'L-') === 0
         ) {
            if ($transaction['credit'] > 0) {
               $financingActivities['loan_proceeds'] += $transaction['credit'];
            } else {
               $financingActivities['loan_repayments'] += $transaction['debit'];
            }
         }

         // Equity transactions
         if (
            strpos($transaction['group_name'], 'EQUITY') !== false ||
            strpos($transaction['account_code'], 'E-') === 0
         ) {
            if ($transaction['credit'] > 0 && strpos($transaction['description'], 'MODAL') !== false) {
               $financingActivities['capital_contributions'] += $transaction['credit'];
            }
         }

         // Dividend transactions (you might need to create specific accounts for this)
         if (
            strpos($transaction['description'], 'DIVIDEN') !== false ||
            strpos($transaction['description'], 'DISTRIBUSI') !== false
         ) {
            $financingActivities['dividends_paid'] += $transaction['debit'];
         }
      }

      $netFinancing = ($financingActivities['loan_proceeds'] + $financingActivities['capital_contributions'])
         - ($financingActivities['loan_repayments'] + $financingActivities['dividends_paid']);

      return [
         'components' => $financingActivities,
         'total' => $netFinancing
      ];
   }

   /**
    * Calculate Net Cash Flow
    */
   private function calculateNetCashFlow($beginningBS, $endingBS)
   {
      // Get cash accounts balance change
      $beginningCash = $this->getCashBalance($beginningBS);
      $endingCash = $this->getCashBalance($endingBS);

      return [
         'beginning_cash' => $beginningCash,
         'ending_cash' => $endingCash,
         'net_change' => $endingCash - $beginningCash
      ];
   }

   /**
    * Helper: Get total cash balance from balance sheet
    */
   private function getCashBalance($balanceSheet)
   {
      $cashBalance = 0;

      foreach ($balanceSheet['assets'] as $asset) {
         if (
            strpos($asset['name'], 'KAS') !== false ||
            strpos($asset['name'], 'BANK') !== false ||
            strpos($asset['account_code'], 'AL-101') === 0 ||
            strpos($asset['account_code'], 'AL-102') === 0
         ) {
            $cashBalance += $asset['report_balance'];
         }
      }

      return $cashBalance;
   }

   /**
    * Helper: Calculate depreciation for the period
    */
   private function calculateDepreciation($transactions)
   {
      $depreciation = 0;

      foreach ($transactions as $transaction) {
         if (
            strpos($transaction['account_name'], 'PENYUSUTAN') !== false ||
            strpos($transaction['account_name'], 'DEPRESIASI') !== false ||
            strpos($transaction['account_code'], 'X-570') === 0
         ) {
            $depreciation += $transaction['debit']; // Depreciation expense is debit
         }
      }

      return $depreciation;
   }

   /**
    * Helper: Calculate working capital changes
    */
   private function calculateWorkingCapitalChange($beginningBS, $endingBS, $type)
   {
      // Simplified calculation - in a real system, you'd identify specific accounts
      $beginningBalance = 0;
      $endingBalance = 0;

      // This is a simplified version - you'd need to map your specific accounts
      switch ($type) {
         case 'receivables':
            // Accounts receivable change
            foreach ($beginningBS['assets'] as $asset) {
               if (strpos($asset['name'], 'PIUTANG') !== false) {
                  $beginningBalance += $asset['report_balance'];
               }
            }
            foreach ($endingBS['assets'] as $asset) {
               if (strpos($asset['name'], 'PIUTANG') !== false) {
                  $endingBalance += $asset['report_balance'];
               }
            }
            break;

         case 'payables':
            // Accounts payable change
            foreach ($beginningBS['liabilities'] as $liability) {
               if (strpos($liability['name'], 'HUTANG') !== false) {
                  $beginningBalance += $liability['report_balance'];
               }
            }
            foreach ($endingBS['liabilities'] as $liability) {
               if (strpos($liability['name'], 'HUTANG') !== false) {
                  $endingBalance += $liability['report_balance'];
               }
            }
            break;

         case 'inventory':
            // Inventory change
            foreach ($beginningBS['assets'] as $asset) {
               if (
                  strpos($asset['name'], 'PERSEDIAAN') !== false ||
                  strpos($asset['name'], 'INVENTORY') !== false
               ) {
                  $beginningBalance += $asset['report_balance'];
               }
            }
            foreach ($endingBS['assets'] as $asset) {
               if (
                  strpos($asset['name'], 'PERSEDIAAN') !== false ||
                  strpos($asset['name'], 'INVENTORY') !== false
               ) {
                  $endingBalance += $asset['report_balance'];
               }
            }
            break;
      }

      return $endingBalance - $beginningBalance;
   }
}