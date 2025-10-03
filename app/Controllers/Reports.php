<?php

namespace App\Controllers;

use App\Models\ReportModel;
use App\Models\AccountModel;

class Reports extends BaseController
{
   protected $reportModel;
   protected $accountModel;

   public function __construct()
   {
      $this->reportModel = new ReportModel();
      $this->accountModel = new AccountModel();
   }

   public function index()
   {
      $data['title'] = 'Financial Reports';
      return view('reports/index', $data);
   }

   public function generalLedger()
   {
      // Get filter parameters
      $accountId = $this->request->getGet('account_id');
      $startDate = $this->request->getGet('start_date');
      $endDate = $this->request->getGet('end_date');

      $data['transactions'] = $this->reportModel->getGeneralLedger($accountId, $startDate, $endDate);
      $data['accounts'] = $this->reportModel->getAccountsForDropdown();
      $data['title'] = 'General Ledger Report';
      $data['filters'] = [
         'account_id' => $accountId,
         'start_date' => $startDate,
         'end_date' => $endDate
      ];

      return view('reports/general_ledger', $data);
   }

   public function trialBalance()
   {
      $asOfDate = $this->request->getGet('as_of_date') ?? date('Y-m-d');

      $data['trialBalance'] = $this->reportModel->getTrialBalance($asOfDate);
      $data['title'] = 'Trial Balance Report';
      $data['asOfDate'] = $asOfDate;

      // Calculate totals
      $data['totals'] = [
         'debit' => 0,
         'credit' => 0
      ];

      foreach ($data['trialBalance'] as $account) {
         $data['totals']['debit'] += $account['total_debit'];
         $data['totals']['credit'] += $account['total_credit'];
      }

      return view('reports/trial_balance', $data);
   }

   public function balanceSheet()
   {
      $asOfDate = $this->request->getGet('as_of_date') ?? date('Y-m-d');

      $data['balanceSheet'] = $this->reportModel->getBalanceSheet($asOfDate);
      $data['title'] = 'Balance Sheet Report';
      $data['asOfDate'] = $asOfDate;

      return view('reports/balance_sheet', $data);
   }

   public function incomeStatement()
   {
      $startDate = $this->request->getGet('start_date') ?? date('Y-m-01'); // First day of current month
      $endDate = $this->request->getGet('end_date') ?? date('Y-m-t'); // Last day of current month

      $data['incomeStatement'] = $this->reportModel->getIncomeStatement($startDate, $endDate);
      $data['title'] = 'Income Statement Report';
      $data['filters'] = [
         'start_date' => $startDate,
         'end_date' => $endDate
      ];

      return view('reports/income_statement', $data);
   }

   /**
    * Export reports to PDF
    */
   public function exportPdf($reportType)
   {
      // We'll implement PDF export in the next step
      return "PDF export for {$reportType} will be implemented soon";
   }

   public function cashFlowStatement()
   {
      $startDate = $this->request->getGet('start_date') ?? date('Y-m-01', strtotime('-1 month'));
      $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');

      $data['cashFlow'] = $this->reportModel->getCashFlowStatement($startDate, $endDate);
      $data['title'] = 'Cash Flow Statement';
      $data['filters'] = [
         'start_date' => $startDate,
         'end_date' => $endDate
      ];

      return view('reports/cash_flow_statement', $data);
   }
   public function cashFlowStatementPrint()
   {
      $startDate = $this->request->getGet('start_date') ?? date('Y-m-01', strtotime('-1 month'));
      $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');

      $data['cashFlow'] = $this->reportModel->getCashFlowStatement($startDate, $endDate);
      $data['title'] = 'Cash Flow Statement';
      $data['filters'] = [
         'start_date' => $startDate,
         'end_date' => $endDate
      ];

      return view('reports/cash_flow_statement_print', $data);
   }
}