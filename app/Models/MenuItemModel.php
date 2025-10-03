<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuItemModel extends Model
{
   protected $table = 'menu_items';
   protected $primaryKey = 'id';
   protected $allowedFields = ['menu_group_id', 'parent_id', 'name', 'url', 'icon', 'permission', 'order_number', 'is_active'];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';

   public function getMenuWithGroups()
   {
      $builder = $this->db->table('menu_items mi');
      $builder->select('mi.*, mg.name as group_name, mg.icon as group_icon');
      $builder->join('menu_groups mg', 'mg.id = mi.menu_group_id');
      $builder->where('mi.is_active', true);
      $builder->where('mg.is_active', true);
      $builder->orderBy('mg.order_number, mi.order_number');

      return $builder->get()->getResultArray();
   }

   public function getUserMenu($userId)
   {
      // Get all active menu items with groups
      $menuItems = $this->getMenuWithGroups();


      return $this->buildMenuTree($menuItems);
   }

   /**
    * Safely check if user has permission
    */
   private function userHasPermission($permission)
   {
      // If no permission required, allow access
      if (empty($permission)) {
         return true;
      }

      // Safely check authorization service
      try {
         $authorize = service('authorize');

         // Check if authorize service is properly initialized
         if (!$authorize) {
            log_message('warning', 'Authorization service is null, allowing access to: ' . $permission);
            return true; // Fallback: allow access if service unavailable
         }

         return $authorize->hasPermission($permission);
      } catch (\Exception $e) {
         log_message('error', 'Permission check failed for ' . $permission . ': ' . $e->getMessage());
         return true; // Fallback: allow access on error
      }
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

   public function getParentMenus()
   {
      return $this->where('parent_id', null)
         ->where('is_active', true)
         ->orderBy('order_number', 'ASC')
         ->findAll();
   }

   public function getMenuByUrl($url)
   {
      return $this->where('url', $url)
         ->where('is_active', true)
         ->first();
   }
}