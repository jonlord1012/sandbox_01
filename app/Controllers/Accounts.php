<?php

namespace App\Controllers;

use App\Models\AccountModel;
use App\Models\GroupModel; // We'll create this next
use CodeIgniter\Exceptions\PageNotFoundException;

class Accounts extends BaseController
{
   protected $accountModel;
   protected $groupModel;

   public function __construct()
   {
      $this->accountModel = new AccountModel();
      $this->groupModel = new GroupModel(); // Initialize the GroupModel
   }

   public function index()
   {
      // Get all accounts with group info
      $data['accounts'] = $this->accountModel->getAccountsWithGroups();
      $data['title'] = 'Manage Chart of Accounts';

      return view('accounts/index', $data);
   }

   public function create()
   {
      // Get groups for the dropdown
      $data['groups'] = $this->groupModel->findAll();
      $data['title'] = 'Create New Account';

      return view('accounts/create', $data);
   }

   public function store()
   {
      // Get form data
      $data = [
         'group_id'    => $this->request->getPost('group_id'),
         'code'        => $this->request->getPost('code'),
         'name'        => $this->request->getPost('name'),
         'is_debit'    => $this->request->getPost('is_debit'),
         'description' => $this->request->getPost('description'),
         'is_active'   => $this->request->getPost('is_active') ?? 1 // Default to active
      ];

      // Validate and save
      if (!$this->accountModel->save($data)) {
         // If validation fails, redirect back with errors and input
         return redirect()->back()->withInput()->with('errors', $this->accountModel->errors());
      }

      // Success message
      return redirect()->to('/accounts')->with('message', 'Account created successfully!');
   }

   public function edit($id)
   {
      // Find the account
      $data['account'] = $this->accountModel->getAccountWithGroup($id);

      if (empty($data['account'])) {
         throw new PageNotFoundException('Account not found');
      }

      // Get groups for the dropdown
      $data['groups'] = $this->groupModel->findAll();
      $data['title'] = 'Edit Account: ' . $data['account']['code'];

      return view('accounts/edit', $data);
   }

   public function update($id)
   {
      // Find the account first to ensure it exists
      $account = $this->accountModel->find($id);
      if (!$account) {
         throw new PageNotFoundException('Account not found');
      }

      // Get form data
      $data = [
         'group_id'    => $this->request->getPost('group_id'),
         'code'        => $this->request->getPost('code'),
         'name'        => $this->request->getPost('name'),
         'is_debit'    => $this->request->getPost('is_debit'),
         'description' => $this->request->getPost('description'),
         'is_active'   => $this->request->getPost('is_active') ?? 0
      ];

      // Validate and save (pass the ID to update, not insert)
      if (!$this->accountModel->update($id, $data)) {
         return redirect()->back()->withInput()->with('errors', $this->accountModel->errors());
      }

      return redirect()->to('/accounts')->with('message', 'Account updated successfully!');
   }

   // Soft delete or hard delete? Let's implement a toggle active/inactive instead for safety.
   public function toggleStatus($id)
   {
      $account = $this->accountModel->find($id);
      if (!$account) {
         throw new PageNotFoundException('Account not found');
      }

      $newStatus = $account['is_active'] ? 0 : 1;
      $this->accountModel->update($id, ['is_active' => $newStatus]);

      $status = $newStatus ? 'activated' : 'deactivated';
      return redirect()->to('/accounts')->with('message', "Account {$status} successfully!");
   }
}