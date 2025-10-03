<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
   protected $table = 'role_permissions';
   protected $primaryKey = 'id';
   protected $allowedFields = ['role_name', 'permission', 'description'];
   protected $useTimestamps = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';

   public function getAllRoles()
   {
      $db = db_connect();
      $query = $db->query("SELECT DISTINCT role_name FROM role_permissions ORDER BY role_name");
      return $query->getResultArray();
   }

   public function getRolePermissions($roleName)
   {
      return $this->where('role_name', $roleName)
         ->orderBy('permission')
         ->findAll();
   }

   public function getAvailablePermissions()
   {
      return [
         // User Management
         'users.view' => 'View Users',
         'users.create' => 'Create Users',
         'users.edit' => 'Edit Users',
         'users.delete' => 'Delete Users',

         // Menu Management
         'menus.view' => 'View Menu Management',
         'menus.create' => 'Create Menu Items',
         'menus.edit' => 'Edit Menu Items',
         'menus.delete' => 'Delete Menu Items',

         // Account Management
         'accounts.view' => 'View Chart of Accounts',
         'accounts.create' => 'Create Accounts',
         'accounts.edit' => 'Edit Accounts',
         'accounts.delete' => 'Delete Accounts',

         // Transaction Management
         'transactions.view' => 'View Journal Entries',
         'transactions.create' => 'Create Transactions',
         'transactions.edit' => 'Edit Transactions',
         'transactions.delete' => 'Delete Transactions',
         'transactions.approve' => 'Approve Transactions',

         // Reports
         'reports.view' => 'View Financial Reports',
         'reports.export' => 'Export Reports',

         // Dashboard
         'dashboard.view' => 'View Dashboard',

         // System
         'settings.view' => 'View System Settings',
         'settings.edit' => 'Edit System Settings',

         // Full Access (superadmin)
         '.*' => 'Full System Access'
      ];
   }

   public function updateRolePermissions($roleName, $permissions)
   {
      // Delete existing permissions for this role
      $this->where('role_name', $roleName)->delete();

      // Insert new permissions
      $data = [];
      foreach ($permissions as $permission) {
         $data[] = [
            'role_name' => $roleName,
            'permission' => $permission,
            'description' => $this->getAvailablePermissions()[$permission] ?? $permission
         ];
      }

      if (!empty($data)) {
         $this->insertBatch($data);
      }

      return true;
   }

   public function getUserPermissions($userId)
   {
      $db = db_connect();

      // Get user's groups from Shield
      $groupsQuery = $db->table('auth_groups_users')
         ->where('user_id', $userId)
         ->get();

      $permissions = [];
      foreach ($groupsQuery->getResultArray() as $group) {
         $rolePermissions = $this->getRolePermissions($group['group']);
         foreach ($rolePermissions as $rolePerm) {
            $permissions[] = $rolePerm['permission'];
         }
      }

      return array_unique($permissions);
   }
}