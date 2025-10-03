<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuGroupModel extends Model
{
   protected $table = 'menu_groups';
   protected $primaryKey = 'id';
   protected $allowedFields = ['name', 'icon', 'order_number', 'is_active'];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';

   public function getActiveGroups()
   {
      return $this->where('is_active', true)
         ->orderBy('order_number', 'ASC')
         ->findAll();
   }
}