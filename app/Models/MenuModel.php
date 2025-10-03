<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
   protected $table = 'menu_items';
   protected $primaryKey = 'id';
   protected $allowedFields = ['menu_group_id', 'parent_id', 'name', 'url', 'icon', 'permission', 'order_number', 'is_active'];

   protected $useTimestamps = true;

   public function getUserMenu($userId)
   {
      // Use Shield's authentication service
      $auth = service('auth');
      $authorize = service('authorize');

      $builder = $this->db->table('menu_items mi');
      $builder->select('mg.name as group_name, mg.icon as group_icon, mi.*');
      $builder->join('menu_groups mg', 'mg.id = mi.menu_group_id');
      $builder->where('mi.is_active', true);
      $builder->where('mg.is_active', true);
      $builder->orderBy('mg.order_number, mi.order_number');

      $menuItems = $builder->get()->getResultArray();

      // Filter by Shield permissions
      $filteredMenu = [];
      foreach ($menuItems as $item) {
         if (empty($item['permission']) || $authorize->hasPermission($item['permission'])) {
            $filteredMenu[] = $item;
         }
      }

      return $this->buildMenuTree($filteredMenu);
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