<?php

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
   public array $groups = [
      'superadmin' => [
         'title'       => 'Super Admin',
         'description' => 'Complete control of the site.',
         'permissions' => ['.*'],
      ],
      'admin' => [
         'title'       => 'Admin',
         'description' => 'Administrative access',
         'permissions' => [
            'dashboard.view',
            'users.view',
            'users.create',
            'users.edit',
            'accounts.view',
            'accounts.create',
            'accounts.edit',
            'transactions.view',
            'transactions.create',
            'transactions.edit',
            'reports.view',
            'menus.view',
            'roles.view'
         ],
      ],
      'accountant' => [
         'title'       => 'Accountant',
         'description' => 'Accounting department access',
         'permissions' => [
            'dashboard.view',
            'accounts.view',
            'accounts.create',
            'accounts.edit',
            'transactions.view',
            'transactions.create',
            'transactions.edit',
            'reports.view'
         ],
      ],
      'viewer' => [
         'title'       => 'Viewer',
         'description' => 'Read-only access',
         'permissions' => [
            'dashboard.view',
            'accounts.view',
            'transactions.view',
            'reports.view'
         ],
      ],
   ];

   public array $permissions = [
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

   public array $matrix = [
      'superadmin' => [
         'dashboard.*',
         'accounts.*',
         'transactions.*',
         'reports.*',
         'users.*',
         'menus.*',
         'roles.*'
      ],
   ];
}