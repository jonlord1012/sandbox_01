<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupMenuModel extends Model
{
   protected $table = 'group_menu_access';
   protected $primaryKey = 'id';
   protected $allowedFields = ['group_name', 'menu_item_id', 'can_access'];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';

   public function getGroupAccess($groupName)
   {
      return $this->where('group_name', $groupName)
         ->findAll();
   }

   public function updateGroupAccess($groupName, $menuItems)
   {
      // Remove existing access
      $this->where('group_name', $groupName)->delete();

      // Add new access
      $data = [];
      foreach ($menuItems as $menuItemId) {
         $data[] = [
            'group_name' => $groupName,
            'menu_item_id' => $menuItemId,
            'can_access' => true
         ];
      }

      if (!empty($data)) {
         $this->insertBatch($data);
      }
   }

   public function getAllGroupsWithAccess()
   {
      $db = db_connect();
      $query = $db->query("
            SELECT DISTINCT `group` 
            FROM auth_groups_users 
            ORDER BY `group`
        ");
      return $query->getResultArray();
   }
}