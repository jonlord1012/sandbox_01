<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountModel extends Model
{
   protected $DBGroup          = 'default';
   protected $table            = 'accounts';
   protected $primaryKey       = 'id';
   protected $useAutoIncrement = true;
   protected $insertID         = 0;
   protected $returnType       = 'array'; // Can be changed to 'object' if you prefer
   protected $useSoftDeletes   = false;
   protected $protectFields    = true;
   // Specify the fields that can be set during save, insert, update
   protected $allowedFields    = ['group_id', 'code', 'name', 'is_debit', 'description', 'is_active'];

   // Dates
   protected $useTimestamps = true;
   protected $dateFormat    = 'datetime';
   protected $createdField  = 'created_at';
   protected $updatedField  = 'updated_at';

   // Validation
   protected $validationRules      = [
      'group_id' => 'required|integer',
      'code'     => 'required|max_length[20]|is_unique[accounts.code,id,{id}]',
      'name'     => 'required|max_length[255]',
      'is_debit' => 'required|integer',
      'is_active' => 'permit_empty|integer'
   ];
   protected $validationMessages   = [
      'code' => [
         'is_unique' => 'This account code is already in use. Please choose another.'
      ]
   ];
   protected $skipValidation       = false;
   protected $cleanValidationRules = true;

   // Callbacks
   // protected $allowCallbacks = true;
   // protected $beforeInsert   = [];
   // protected $afterInsert    = [];
   // protected $beforeUpdate   = [];
   // protected $afterUpdate    = [];
   // protected $beforeFind     = [];
   // protected $afterFind      = [];
   // protected $beforeDelete   = [];
   // protected $afterDelete    = [];

   /**
    * Fetches accounts with their related group data.
    * This is useful for displaying the group name in views.
    */
   public function getAccountsWithGroups()
   {
      return $this->select('accounts.*, groups.code as group_code, groups.name as group_name, groups.category')
         ->join('groups', 'groups.id = accounts.group_id')
         ->orderBy('accounts.code', 'ASC')
         ->findAll();
   }

   /**
    * Fetches a single account with its group data.
    */
   public function getAccountWithGroup($id)
   {
      return $this->select('accounts.*, groups.code as group_code, groups.name as group_name, groups.category')
         ->join('groups', 'groups.id = accounts.group_id')
         ->where('accounts.id', $id)
         ->first();
   }
}