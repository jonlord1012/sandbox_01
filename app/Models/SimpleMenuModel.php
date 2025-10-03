<?php

namespace App\Models;

use CodeIgniter\Model;

class SimpleMenuModel extends Model
{
   protected $table = 'menu_items';
   protected $primaryKey = 'id';

   public function getSimpleUserMenu($userId)
   {
      // Simple approach: Get all active menus without permission checks
      $builder = $this->db->table('menu_items mi');
      $builder->select('mi.*, mg.name as group_name, mg.icon as group_icon');
      $builder->join('menu_groups mg', 'mg.id = mi.menu_group_id');
      $builder->where('mi.is_active', true);
      $builder->where('mg.is_active', true);
      $builder->orderBy('mg.order_number, mi.order_number');

      $menuItems = $builder->get()->getResultArray();

      return $this->buildMenuTree($menuItems);
   }

   private function buildMenuTree($items, $parentId = null)
   {
      $branch = [];

      foreach ($items as $item) {
         if ($item['parent_id'] == $parentId) {
            $children = $this->buildMenuTree($items, $item['id']);
            if ($children) {
               $item['children'] = $children;
            }
            $branch[] = $item;
         }
      }

      return $branch;
   }
}