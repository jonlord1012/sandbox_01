<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
   protected $table            = 'journal';
   protected $primaryKey       = 'id';
   protected $useAutoIncrement = true;
   protected $returnType       = 'array';
   protected $useSoftDeletes   = false;
   protected $protectFields    = true;
   protected $allowedFields    = ['transaction_date', 'account_id', 'debit', 'credit', 'description', 'reference_id'];

   // Dates
   protected $useTimestamps = true;
   protected $dateFormat    = 'datetime';
   protected $createdField  = 'created_at';
   protected $updatedField  = 'updated_at';

   // Validation
   protected $validationRules = [
      'transaction_date' => 'required|valid_date',
      'account_id' => 'required|integer',
      'debit' => 'required|decimal',
      'credit' => 'required|decimal',
      'description' => 'required|max_length[500]'
   ];

   /**
    * Get transactions with account information for display
    */
   public function getTransactionsWithAccounts($startDate = null, $endDate = null)
   {
      $builder = $this->db->table('journal j');
      $builder->select('j.*, a.code as account_code, a.name as account_name, g.name as group_name');
      $builder->join('accounts a', 'a.id = j.account_id');
      $builder->join('groups g', 'g.id = a.group_id');
      $builder->orderBy('j.transaction_date', 'DESC');
      $builder->orderBy('j.created_at', 'DESC');

      if ($startDate) {
         $builder->where('j.transaction_date >=', $startDate);
      }
      if ($endDate) {
         $builder->where('j.transaction_date <=', $endDate);
      }

      return $builder->get()->getResultArray();
   }

   /**
    * Get transaction lines by reference_id (to show complete transactions)
    */
   public function getTransactionByReference($referenceId)
   {
      $builder = $this->db->table('journal j');
      $builder->select('j.*, a.code as account_code, a.name as account_name');
      $builder->join('accounts a', 'a.id = j.account_id');
      $builder->where('j.reference_id', $referenceId);
      $builder->orderBy('j.debit', 'DESC'); // Debits first, then credits

      return $builder->get()->getResultArray();
   }

   /**
    * Get all unique reference IDs for transaction listing
    */
   public function getTransactionReferences($startDate = null, $endDate = null)
   {
      $builder = $this->db->table('journal');
      $builder->select('reference_id, transaction_date, description, COUNT(*) as entry_count, SUM(debit) as total_debit, SUM(credit) as total_credit');
      $builder->groupBy('reference_id, transaction_date, description');
      $builder->orderBy('transaction_date', 'DESC');

      if ($startDate) {
         $builder->where('transaction_date >=', $startDate);
      }
      if ($endDate) {
         $builder->where('transaction_date <=', $endDate);
      }

      return $builder->get()->getResultArray();
   }

   /**
    * Get all unique reference IDs for transaction listing
    */
   public function getSingleTransactionReferences($startDate = null, $endDate = null)
   {
      $builder = $this->db->table('journal_temp');
      $builder->select('reference_id, max(transaction_date) as transaction_date, min(description) as description, COUNT(*) as entry_count, 
         case when type = "debit" then SUM(amount) else 0 end as total_debit, 
         case when type = "credit" then SUM(amount) else 0 end as total_credit,
         "Un-Posted" as status, 
         "single_entry/header/" as link')
         ->groupBy('reference_id, ');
      $builder->unionAll(function ($subQuery) use ($startDate, $endDate) {
         $subQuery->select('reference_id, max(transaction_date) as transaction_date, max(description) as description, COUNT(*) as entry_count, SUM(debit) as total_debit, SUM(credit) as total_credit, "Posted" as status, "transactions/view/" as link')
            ->from('journal');
         $subQuery->groupBy('reference_id ');
         $subQuery->orderBy('transaction_date', 'DESC');
         if ($startDate) {
            $subQuery->where('transaction_date >=', $startDate);
         }
         if ($endDate) {
            $subQuery->where('transaction_date <=', $endDate);
         }
      });

      if ($startDate) {
         $builder->where('transaction_date >=', $startDate);
      }
      if ($endDate) {
         $builder->where('transaction_date <=', $endDate);
      }
      $builder->orderBy('transaction_date', 'DESC');

      return $builder->get()->getResultArray();
   }

   /**
    * Check if a transaction can be reversed (not already reversed)
    */
   public function canReverse($referenceId)
   {
      // Get the transaction IDs for the given reference
      $originalIds = $this->db->table('journal')
         ->select('id')
         ->where('reference_id', $referenceId)
         ->get()
         ->getResultArray();

      if (empty($originalIds)) {
         return false;
      }

      $originalIds = array_column($originalIds, 'id');

      // Check if any reversal exists for these transaction IDs
      $reversalCheck = $this->db->table('journal')
         ->whereIn('reverses_transaction_id', $originalIds)
         ->countAllResults();

      return $reversalCheck === 0;
   }

   /**
    * Get transaction details for reversal
    */
   public function getTransactionForReversal($referenceId)
   {
      $builder = $this->db->table('journal j');
      $builder->select('j.*, a.code as account_code, a.name as account_name, g.name as group_name');
      $builder->join('accounts a', 'a.id = j.account_id');
      $builder->join('groups g', 'g.id = a.group_id');
      $builder->where('j.reference_id', $referenceId);
      $builder->orderBy('j.debit', 'DESC');

      return $builder->get()->getResultArray();
   }

   /**
    * Reverse a transaction
    */
   public function reverseTransaction($referenceId, $reversalReferenceId, $reversalDate, $description)
   {
      $db = db_connect();

      try {
         $sql = "CALL sp_reverse_transaction(?, ?, ?, ?)";
         $db->query($sql, [$referenceId, $reversalReferenceId, $reversalDate, $description]);

         return true;
      } catch (\Exception $e) {
         log_message('error', 'Reversal failed: ' . $e->getMessage());
         return false;
      }
   }

   /**
    * Get reversal history for a transaction
    */
   public function getReversalHistory($referenceId)
   {
      // First get the original transaction IDs
      $originalIds = $this->db->table('journal')
         ->select('id')
         ->where('reference_id', $referenceId)
         ->get()
         ->getResultArray();

      if (empty($originalIds)) {
         return [];
      }

      $originalIds = array_column($originalIds, 'id');

      $builder = $this->db->table('journal j');
      $builder->select('j.*, a.code as account_code, a.name as account_name');
      $builder->join('accounts a', 'a.id = j.account_id');
      $builder->where('j.reference_id LIKE ?', ['REV-' . $referenceId . '%'])
         ->orWhereIn('j.reverses_transaction_id', $originalIds);

      return $builder->get()->getResultArray();
   }

   /**
    * Get transaction with reversal status
    */
   public function getTransactionWithStatus($referenceId)
   {
      $transactionLines = $this->getTransactionByReference($referenceId);

      if (empty($transactionLines)) {
         return null;
      }

      $isReversal = $transactionLines[0]['is_reversal'] == 1;
      $canReverse = $this->canReverse($referenceId);

      // Get what transaction this reverses (if it's a reversal)
      $reversesTransactionId = $transactionLines[0]['reverses_transaction_id'] ?? null;
      $reversesReference = null;

      if ($reversesTransactionId) {
         $reversedTrans = $this->db->table('journal')
            ->select('reference_id')
            ->where('id', $reversesTransactionId)
            ->get()
            ->getRowArray();
         $reversesReference = $reversedTrans['reference_id'] ?? null;
      }

      // Get reversals of this transaction (if it's an original)
      $reversalReferences = [];
      if (!$isReversal) {
         $originalIds = array_column($transactionLines, 'id');
         $reversals = $this->db->table('journal')
            ->distinct()
            ->select('reference_id')
            ->whereIn('reverses_transaction_id', $originalIds)
            ->where('is_reversal', 1)
            ->get()
            ->getResultArray();
         $reversalReferences = array_column($reversals, 'reference_id');
      }

      return [
         'lines' => $transactionLines,
         'is_reversal' => $isReversal,
         'can_reverse' => $canReverse,
         'reverses_reference' => $reversesReference,
         'reversal_references' => $reversalReferences,
         'has_been_reversed' => !empty($reversalReferences)
      ];
   }
}