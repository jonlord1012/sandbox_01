<?php

namespace App\Controllers;

use App\Models\AccountModel;
use App\Models\JournalTempModel;
use App\Models\TransactionModel;
use CodeIgniter\Exceptions\PageNotFoundException;



class SingleEntry extends BaseController
{
   public function index()
   {
      // Get filter parameters
      $startDate = $this->request->getGet('start_date');
      $endDate = $this->request->getGet('end_date');

      $data['transactions'] = model(transactionModel::class)->getSingleTransactionReferences($startDate, $endDate);
      $data['title'] = lang('Accounting.general_journal');
      $data['startDate'] = $startDate;
      $data['endDate'] = $endDate;
      return view('single_entry/index', $data);
   }

   public function create()
   {
      // List accounts for pick-as-header (active only)
      $data['title'] = lang('Accounting.new_journal_entry');
      $accounts = model(AccountModel::class)->where('is_active', 1)->where('group_id', 1)->findAll();
      $data['accounts'] = $accounts;
      return view('single_entry/header_pick', $data);
   }

   public function getCart($referenceId)
   {
      $db = db_connect();
      $userId = auth()->id();

      $cartItems = $db->table('journal_temp')
         ->select('journal_temp.*, accounts.code as account_code, accounts.name as account_name')
         ->join('accounts', 'accounts.id = journal_temp.account_id')
         ->where('journal_temp.reference_id', $referenceId)
         ->where('user_id', $userId)
         #->orderBy('account_id', 'ASC')
         ->get()
         ->getResultArray();

      return $this->response->setJSON($cartItems);
   }

   public function header($referenceId)
   {
      $title = lang('Accounting.general_journal');
      $db = db_connect();

      $accountId = $db->table('journal_temp')
         ->select('account_id')
         ->where('reference_id', $referenceId)
         ->where('description', 'Header Account')
         ->get()
         ->getRowArray();
      if (!$accountId) {
         throw new PageNotFoundException("Header account not found for reference ID: $referenceId");
      }

      $accountName = $db->table('journal_temp')
         ->select('accounts.name')
         ->join('accounts', 'accounts.id = journal_temp.account_id')
         ->where('reference_id', $referenceId)
         ->where('journal_temp.description', 'Header Account')
         ->limit(1)
         ->get()
         ->getRowArray();

      // Load picked header account and current temp transactions
      $accounts = model(AccountModel::class)->where('is_active', 1)->where('id !=', $accountId)->findAll();

      $entries = $db->table('journal_temp')
         ->select('journal_temp.*, accounts.code as account_code, accounts.name as account_name')
         ->join('accounts', 'accounts.id = journal_temp.account_id')
         ->where('journal_temp.user_id', user_id())
         ->where('journal_temp.reference_id', $referenceId)
         ->where('journal_temp.description !=', 'Header Account')
         ->get()
         ->getResultArray();


      return view('single_entry/entry_form', compact('accounts', 'entries', 'referenceId', 'accountName', 'accountId', 'title'));
   }

   public function pick_header()
   {
      $accountId = $this->request->getPost('header_account');
      if (!$accountId) {
         throw new PageNotFoundException("Account not found");
      }
      $db = db_connect();

      $referenceId = $this->request->getPost('reference_id') ? $this->request->getPost('reference_id') : 'REF-' . time();
      $data = [
         'user_id' => auth()->id(),
         'type' => 'debit', // default type for header
         'account_id' => $accountId,
         'amount' => '0.00', // header has 0 amount
         'transaction_date' => date('Y-m-d'),
         'description' => 'Header Account',
         'reference_id' => $this->request->getPost('reference_id') ? $this->request->getPost('reference_id') : 'REF-' . time()
      ];

      // Call the stored procedure to add to temp cart
      $sql = "CALL sp_add_to_journal_cart(?, ?, ?, ?, ?, ?, ?)";
      $db->query($sql, [
         $data['user_id'],
         $data['type'],
         $data['account_id'],
         $data['amount'],
         $data['transaction_date'],
         $data['description'],
         $data['reference_id']
      ]);

      return redirect()->to("/single_entry/header/" . $referenceId)->with('message', lang('Accounting.header_account_picked'));
   }

   public function addEntry()
   {
      $db = db_connect();
      // Add new transaction under header
      $data = [
         'user_id' => user_id(),
         'type' => $this->request->getPost('type'), // 'debit' or 'credit'
         'account_id' => $this->request->getPost('account_id'),
         'amount' => $this->request->getPost('amount'),
         'transaction_date' => $this->request->getPost('transaction_date'),
         'description' => $this->request->getPost('description'),
         'reference_id' => $this->request->getPost('reference_id'),
         'header_account' => $this->request->getPost('header_account') // Pass header account ID
      ];
      // Call the stored procedure to add to temp cart
      $sql = "CALL sp_add_to_journal_cart_single(?, ?, ?, ?, ?, ?, ?, ?)";
      $db->query($sql, [
         $data['user_id'],
         $data['type'],
         $data['account_id'],
         $data['amount'],
         $data['transaction_date'],
         $data['description'],
         $data['reference_id'],
         $data['header_account']
      ]);

      return redirect()->back()->with('message', lang('Accounting.add_to_cart_success'));
      #return redirect()->to("/single_entry/header/{$accountId}");
   }
   public function clearCart($referenceId)
   {
      $db = db_connect();
      $userId = auth()->id();

      $sql = "CALL sp_clear_journal_cart_single(?)";
      $db->query($sql, [$referenceId]);

      return redirect()->to('/single_entry')->with('message', 'Transaction cart cleared.');
   }
   public function postCart()
   {
      #return redirect()->back()->with('error', 'Posting is disabled in demo mode.');
      $referenceId = $this->request->getPost('reference_id');
      if (!$referenceId) {
         return redirect()->back()->with('error', 'Reference ID is required to post the transaction.');
      }
      // Post all journal_temp for this user & header account (calls stored proc)
      $db = db_connect();
      try {
         $db->query("CALL sp_post_journal_cart_single(?)", [$referenceId]);
         return redirect()->to("/single_entry")
            ->with('message', 'Transaction posted!');
      } catch (\Exception $e) {
         return redirect()->back()->with('error', $e->getMessage());
      }
   }
}