<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupModel extends Model
{
   protected $table            = 'groups';
   protected $primaryKey       = 'id';
   protected $useAutoIncrement = true;
   protected $returnType       = 'array';
   protected $useSoftDeletes   = false;
   protected $protectFields    = true;
   protected $allowedFields    = ['code', 'name', 'category', 'is_debit', 'description'];

   protected $useTimestamps = true;
   protected $createdField  = 'created_at';
   protected $updatedField  = 'updated_at';

   protected $validationRules    = [
      'code' => 'required|max_length[10]|is_unique[groups.code,id,{id}]',
      'name' => 'required|max_length[100]',
      'category' => 'required|in_list[ASSETS,LIABILITIES,EQUITY,REVENUE,EXPENSES,OTHER INCOME & EXPENSES,TEMP ACCOUNT]',
      'is_debit' => 'required|integer'
   ];
   protected $validationMessages = [];
   protected $skipValidation     = false;
}