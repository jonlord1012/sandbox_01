<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\AccountModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Transactions extends BaseController
{
    protected $transactionModel;
    protected $accountModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->accountModel = new AccountModel();
    }

    public function index()
    {
        // Get filter parameters
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $data['transactions'] = $this->transactionModel->getTransactionReferences($startDate, $endDate);
        $data['title'] = lang('Accounting.general_journal');
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        return view('transactions/index', $data);
    }

    public function view($referenceId)
    {
        $transactionData = $this->transactionModel->getTransactionWithStatus($referenceId);

        if (!$transactionData) {
            throw new PageNotFoundException(lang('App.no_trans_error'));
        }

        $data['transactionData'] = $transactionData;
        $data['title'] = 'Transaction Details: ' . $referenceId;
        $data['referenceId'] = $referenceId;


        return view('transactions/view', $data);
    }

    public function create()
    {
        // Get active accounts for dropdown
        $data['accounts'] = $this->accountModel->where('is_active', 1)->findAll();
        $data['title'] = lang('Accounting.create_transaction');

        return view('transactions/create', $data);
    }

    /**
     * Handle the transaction batching system we designed
     */
    public function addToCart()
    {
        $db = db_connect();

        $data = [
            'user_id' => auth()->id(),
            'type' => $this->request->getPost('type'),
            'account_id' => $this->request->getPost('account_id'),
            'amount' => $this->request->getPost('amount'),
            'transaction_date' => $this->request->getPost('transaction_date'),
            'description' => $this->request->getPost('description'),
            'reference_id' => $this->request->getPost('reference_id')
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

        return redirect()->back()->with('message', lang('Accounting.add_to_cart_success'));
    }

    public function postCart()
    {
        $db = db_connect();
        $userId = auth()->id();

        try {
            // Call the stored procedure to post the entire cart
            $sql = "CALL sp_post_journal_cart(?)";
            $db->query($sql, [$userId]);

            return redirect()->to('/transactions')->with('message', lang('App.success_create'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error',  lang('App.error_occurred') . $e->getMessage());
        }
    }

    public function clearCart()
    {
        $db = db_connect();
        $userId = auth()->id();

        $sql = "CALL sp_clear_journal_cart(?)";
        $db->query($sql, [$userId]);

        return redirect()->to('/transactions/create')->with('message', 'Transaction cart cleared.');
    }

    public function getCart()
    {
        $db = db_connect();
        $userId = auth()->id();

        $cartItems = $db->table('journal_temp')
            ->select('journal_temp.*, accounts.code as account_code, accounts.name as account_name')
            ->join('accounts', 'accounts.id = journal_temp.account_id')
            ->where('user_id', $userId)
            ->get()
            ->getResultArray();

        return $this->response->setJSON($cartItems);
    }

    public function deleteCartItem($itemId)
    {
        $db = db_connect();
        $userId = auth()->id();

        // Verify the item belongs to the current user
        $item = $db->table('journal_temp')
            ->where('id', $itemId)
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found or access denied.');
        }

        $db->table('journal_temp')->where('id', $itemId)->delete();

        return redirect()->back()->with('message', 'Entry removed from transaction.');
    }

    public function reverse($referenceId)
    {
        $transactionData = $this->transactionModel->getTransactionWithStatus($referenceId);

        if (!$transactionData) {
            return redirect()->to('/transactions')->with('error', 'Transaction not found.');
        }

        if (!$transactionData['can_reverse']) {
            if ($transactionData['is_reversal']) {
                $message = 'Cannot reverse a reversal transaction.';
            } else {
                $message = 'Transaction has already been reversed.';
            }
            return redirect()->to('/transactions/view/' . $referenceId)->with('error', $message);
        }

        $data['transaction'] = [
            'reference_id' => $referenceId,
            'date' => $transactionData['lines'][0]['transaction_date'],
            'description' => $transactionData['lines'][0]['description'],
            'lines' => $transactionData['lines']
        ];

        $data['title'] = 'Reverse Transaction: ' . $referenceId;

        return view('transactions/reverse', $data);
    }

    public function processReverse()
    {
        $referenceId = $this->request->getPost('reference_id');
        $reversalDate = $this->request->getPost('reversal_date');
        $reversalDescription = $this->request->getPost('reversal_description');

        // Generate reversal reference ID
        $reversalReferenceId = 'REV-' . $referenceId . '-' . time();

        // Validate input
        $rules = [
            'reversal_date' => 'required|valid_date',
            'reversal_description' => 'required|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Attempt reversal
        $success = $this->transactionModel->reverseTransaction(
            $referenceId,
            $reversalReferenceId,
            $reversalDate,
            $reversalDescription
        );

        if ($success) {
            return redirect()->to('/transactions')->with('message', 'Transaction reversed successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to reverse transaction. Please try again.');
        }
    }

    public function viewWithReversal($referenceId)
    {
        // Enhanced view that shows reversal status
        $data['transactionLines'] = $this->transactionModel->getTransactionByReference($referenceId);

        if (empty($data['transactionLines'])) {
            throw new PageNotFoundException('Transaction not found');
        }

        $data['canReverse'] = $this->transactionModel->canReverse($referenceId);
        $data['reversalHistory'] = $this->transactionModel->getReversalHistory($referenceId);
        $data['title'] = 'Transaction Details: ' . $referenceId;
        $data['referenceId'] = $referenceId;

        return view('transactions/view_with_reversal', $data);
    }
}