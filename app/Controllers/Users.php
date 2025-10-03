<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Shield\Entities\User;

class Users extends BaseController
{
   public function index()
   {
      $db = db_connect();

      $userModel = model('UserModel');
      $users = $userModel->withIdentities();
      $users = $userModel->findAll();

      // Get all user groups in a single query for efficiency
      $allUserGroups = [];
      $groupsQuery = $db->table('auth_groups_users')->get();
      foreach ($groupsQuery->getResultArray() as $row) {
         $allUserGroups[$row['user_id']][] = $row['group'];
      }

      // Pre-process the users
      $data['users'] = array_map(function ($user) use ($allUserGroups) {
         $userData = $user->toArray();
         $userData['groups'] = $allUserGroups[$user->id] ?? []; // Get groups from our pre-built array
         return $userData;
      }, $users);

      $data['title'] = 'Manage System Users';

      return view('users/index', $data);
   }

   public function edit($userId)
   {
      $db = db_connect();

      $userModel = model('UserModel');
      $user = $userModel->findById($userId);

      if (!$user) {
         throw new PageNotFoundException('User not found');
      }

      // Get available roles from role_permissions table
      $roleModel = new \App\Models\RoleModel();
      $availableRoles = $roleModel->getAllRoles();
      $data['allGroups'] = array_column($availableRoles, 'role_name');

      // If no roles exist yet, provide some defaults
      if (empty($data['allGroups'])) {
         $data['allGroups'] = ['superadmin', 'admin', 'accountant', 'viewer'];
      }

      // Get current user's groups
      $userGroupsQuery = $db->table('auth_groups_users')
         ->where('user_id', $userId)
         ->get();
      $data['userGroups'] = [];
      foreach ($userGroupsQuery->getResultArray() as $row) {
         $data['userGroups'][] = $row['group'];
      }

      $data['user'] = $user;
      $data['title'] = 'Edit User: ' . $user->username;

      return view('users/edit', $data);
   }

   public function update($userId)
   {
      $db = db_connect();

      $userModel = model('UserModel');
      $user = $userModel->findById($userId);

      if (!$user) {
         throw new PageNotFoundException('User not found');
      }

      $selectedGroups = $this->request->getPost('groups') ?? [];

      // Get current user's groups
      $currentGroupsQuery = $db->table('auth_groups_users')
         ->where('user_id', $userId)
         ->get();
      $currentGroups = [];
      foreach ($currentGroupsQuery->getResultArray() as $row) {
         $currentGroups[] = $row['group'];
      }

      // Add new groups
      foreach ($selectedGroups as $group) {
         if (!in_array($group, $currentGroups)) {
            $db->table('auth_groups_users')->insert([
               'user_id' => $userId,
               'group' => $group,
               'created_at' => date('Y-m-d H:i:s')
            ]);
         }
      }

      // Remove groups that were deselected
      foreach ($currentGroups as $currentGroup) {
         if (!in_array($currentGroup, $selectedGroups)) {
            $db->table('auth_groups_users')->where([
               'user_id' => $userId,
               'group' => $currentGroup
            ])->delete();
         }
      }

      return redirect()->to('/users')->with('message', 'User groups updated successfully!');
   }

   public function toggleStatus($userId)
   {
      $userModel = model('UserModel');
      $user = $userModel->findById($userId);

      if (!$user) {
         throw new PageNotFoundException('User not found');
      }

      $newStatus = !$user->active;
      $userModel->update($userId, ['active' => $newStatus]);

      $status = $newStatus ? 'activated' : 'deactivated';
      return redirect()->to('/users')->with('message', "User {$status} successfully.");
   }

   public function create()
   {
      // Get available roles from role_permissions table
      $roleModel = new \App\Models\RoleModel();
      $availableRoles = $roleModel->getAllRoles();
      $data['allGroups'] = array_column($availableRoles, 'role_name');

      if (empty($data['allGroups'])) {
         $data['allGroups'] = ['superadmin', 'admin', 'accountant', 'viewer'];
      }

      $data['title'] = 'Create New User';
      return view('users/create', $data);
   }

   public function store()
   {
      // DEBUG: Log the start of the method
      log_message('debug', 'Users::store() method called');
      log_message('debug', 'POST data: ' . print_r($this->request->getPost(), true));

      // Validate input
      $rules = [
         'username' => 'required|max_length[30]|min_length[3]',
         'email'    => 'required|valid_email|max_length[254]',
         'password' => 'required|min_length[8]',
         'password_confirm' => 'required|matches[password]'
      ];

      // DEBUG: Before validation
      log_message('debug', 'Starting validation');

      if (!$this->validate($rules)) {
         $errors = $this->validator->getErrors();
         log_message('debug', 'Validation failed: ' . print_r($errors, true));
         return redirect()->back()->withInput()->with('validation', $this->validator);
      }

      // DEBUG: Validation passed
      log_message('debug', 'Validation passed');

      // Get form data
      $username = $this->request->getPost('username');
      $email = $this->request->getPost('email');
      $password = $this->request->getPost('password');
      $selectedGroups = $this->request->getPost('groups') ?? ['viewer'];

      log_message('debug', "Creating user: {$username}, {$email}, groups: " . print_r($selectedGroups, true));

      // Create the user using Shield's UserModel
      $userModel = model('UserModel');

      try {
         // DEBUG: Before user creation
         log_message('debug', 'Creating user entity');

         $user = new \CodeIgniter\Shield\Entities\User([
            'username' => $username,
            'email'    => $email,
         ]);
         $user->setPassword($password);

         // DEBUG: Before save
         log_message('debug', 'Saving user to database');

         $userModel->save($user);

         // DEBUG: After save
         $userId = $userModel->getInsertID();
         log_message('debug', "User saved with ID: {$userId}");

         // Assign selected groups
         $db = db_connect();
         foreach ($selectedGroups as $group) {
            log_message('debug', "Assigning group: {$group} to user: {$userId}");
            $db->table('auth_groups_users')->insert([
               'user_id' => $userId,
               'group' => $group,
               'created_at' => date('Y-m-d H:i:s')
            ]);
         }

         log_message('debug', 'User creation completed successfully');
         return redirect()->to('/users')->with('message', 'User created successfully!');
      } catch (\Exception $e) {
         log_message('error', 'Error creating user: ' . $e->getMessage());
         log_message('error', 'Stack trace: ' . $e->getTraceAsString());
         return redirect()->back()->withInput()->with('error', 'Error creating user: ' . $e->getMessage());
      }
   }
}