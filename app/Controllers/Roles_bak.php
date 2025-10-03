<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class Roles extends BaseController
{
   protected $roleModel;

   public function __construct()
   {
      $this->roleModel = new \App\Models\RoleModel();
   }

   public function index()
   {
      $data = [
         'title' => 'Role & Permission Management',
         'roles' => $this->roleModel->getAllRoles()
      ];

      return view('users/roles_index', $data);
   }

   public function manage($roleName)
   {
      // Get available groups from Shield
      $db = db_connect();
      $groupsQuery = $db->table('auth_groups_users')
         ->select('group')
         ->distinct()
         ->get();

      $existingGroups = [];
      foreach ($groupsQuery->getResultArray() as $group) {
         $existingGroups[] = $group['group'];
      }

      // If role doesn't exist in Shield groups, show error
      if (!in_array($roleName, $existingGroups)) {
         return redirect()->to('/roles')->with('error', "Role '{$roleName}' not found in Shield groups!");
      }

      $rolePermissions = $this->roleModel->getRolePermissions($roleName);
      $currentPermissions = array_column($rolePermissions, 'permission');

      if ($this->request->getMethod() === 'POST') {
         $selectedPermissions = $this->request->getPost('permissions') ?? [];

         $this->roleModel->updateRolePermissions($roleName, $selectedPermissions);

         return redirect()->to('/roles')->with('message', "Permissions updated for role '{$roleName}'!");
      }

      $data = [
         'title' => "Manage Permissions: {$roleName}",
         'roleName' => $roleName,
         'availablePermissions' => $this->roleModel->getAvailablePermissions(),
         'currentPermissions' => $currentPermissions,
         'rolePermissions' => $rolePermissions
      ];

      return view('users/roles_manage', $data);
   }

   public function create()
   {
      if ($this->request->getMethod() === 'POST') {
         $roleName = $this->request->getPost('role_name');
         $description = $this->request->getPost('description');

         if (empty($roleName)) {
            return redirect()->back()->with('error', 'Role name is required!');
         }

         // Check if role already exists
         $existingRoles = $this->roleModel->getAllRoles();
         $existingRoleNames = array_column($existingRoles, 'role_name');

         if (in_array($roleName, $existingRoleNames)) {
            return redirect()->back()->with('error', "Role '{$roleName}' already exists!");
         }

         // Create role with basic permissions
         $basicPermissions = ['dashboard.view'];
         $this->roleModel->updateRolePermissions($roleName, $basicPermissions);

         return redirect()->to('/roles')->with('message', "Role '{$roleName}' created successfully!");
      }

      $data = [
         'title' => 'Create New Role'
      ];

      return view('users/roles_create', $data);
   }

   public function users($roleName)
   {
      $db = db_connect();

      // Get users with this role
      $usersQuery = $db->table('auth_groups_users ug')
         ->select('u.id, u.username, u.email, u.active')
         ->join('users u', 'u.id = ug.user_id')
         ->where('ug.group', $roleName)
         ->get();

      $users = $usersQuery->getResultArray();

      // Get last login info for each user
      foreach ($users as &$user) {
         $lastLoginQuery = $db->table('auth_logins')
            ->where('user_id', $user['id'])
            ->orderBy('date', 'DESC')
            ->get();
         $user['last_login'] = $lastLoginQuery->getRow();
      }

      $data = [
         'title' => "Users with Role: {$roleName}",
         'roleName' => $roleName,
         'users' => $users
      ];

      return view('users/roles_users', $data);
   }

   public function delete($roleName)
   {
      // Prevent deletion of system roles
      $protectedRoles = ['superadmin', 'admin', 'accountant', 'viewer'];

      if (in_array($roleName, $protectedRoles)) {
         return redirect()->to('/roles')->with('error', "Cannot delete system role '{$roleName}'!");
      }

      $db = db_connect();

      // Check if role has users
      $userCount = $db->table('auth_groups_users')
         ->where('group', $roleName)
         ->countAllResults();

      if ($userCount > 0) {
         return redirect()->to('/roles')->with('error', "Cannot delete role '{$roleName}' - it has {$userCount} user(s) assigned!");
      }

      // Delete role permissions
      $this->roleModel->where('role_name', $roleName)->delete();

      return redirect()->to('/roles')->with('message', "Role '{$roleName}' deleted successfully!");
   }
}