<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class Roles extends BaseController
{
   public function index()
   {
      $db = db_connect();

      // Get all groups and their user counts
      $groupsQuery = $db->table('auth_groups_users')
         ->select('group, COUNT(*) as user_count')
         ->groupBy('group')
         ->orderBy('group')
         ->get();

      $groups = [];
      foreach ($groupsQuery->getResultArray() as $row) {
         $groups[$row['group']] = $row['user_count'];
      }

      $data = [
         'title' => 'Role Management',
         'groups' => $groups,
         'availablePermissions' => $this->getAvailablePermissions()
      ];

      return view('users/roles_index', $data);
   }

   public function users($groupName)
   {
      $db = db_connect();

      // Get users in this group
      $usersQuery = $db->table('auth_groups_users ug')
         ->select('u.id, u.username, u.active, u.created_at, u.last_active')
         ->join('users u', 'u.id = ug.user_id')
         ->where('ug.group', $groupName)
         ->orderBy('u.username')
         ->get();

      $users = $usersQuery->getResultArray();

      // Get email from identities table for each user
      foreach ($users as &$user) {
         $identityQuery = $db->table('auth_identities')
            ->select('secret as email')
            ->where('user_id', $user['id'])
            ->where('type', 'email_password')
            ->get();
         $identity = $identityQuery->getRow();
         $user['email'] = $identity ? $identity->email : 'No email';
      }

      $data = [
         'title' => "Users in Role: {$groupName}",
         'groupName' => $groupName,
         'users' => $users
      ];

      return view('users/roles_users', $data);
   }

   public function permissions($groupName)
   {
      $authConfig = config('Auth');
      $groupPermissions = $authConfig->groups[$groupName]['permissions'] ?? [];

      if ($this->request->getMethod() === 'POST') {
         $selectedPermissions = $this->request->getPost('permissions') ?? [];

         // In a real app, you'd update the Auth config file
         // For now, we'll just show a message
         return redirect()->to('/roles')->with('message', "Permissions updated for {$groupName}! (Config update required)");
      }

      $data = [
         'title' => "Manage Permissions: {$groupName}",
         'groupName' => $groupName,
         'availablePermissions' => $this->getAvailablePermissions(),
         'currentPermissions' => $groupPermissions
      ];

      return view('users/roles_manage', $data);
   }

   private function getAvailablePermissions()
   {
      $authConfig = config('Auth');
      return $authConfig->permissions ?? [
         'dashboard.view' => 'Access Dashboard',
         'accounts.view' => 'View Accounts',
         'accounts.create' => 'Create Accounts',
         'accounts.edit' => 'Edit Accounts',
         'accounts.delete' => 'Delete Accounts',
         'transactions.view' => 'View Transactions',
         'transactions.create' => 'Create Transactions',
         'transactions.edit' => 'Edit Transactions',
         'transactions.delete' => 'Delete Transactions',
         'reports.view' => 'View Reports',
         'users.view' => 'View Users',
         'users.create' => 'Create Users',
         'users.edit' => 'Edit Users',
         'users.delete' => 'Delete Users',
         'menus.view' => 'View Menu Management',
         'roles.view' => 'View Role Management'
      ];
   }
}