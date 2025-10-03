<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class Menus extends BaseController
{
   protected $menuGroupModel;
   protected $menuItemModel;
   protected $groupMenuModel;

   public function __construct()
   {
      $this->menuGroupModel = new \App\Models\MenuGroupModel();
      $this->menuItemModel = new \App\Models\MenuItemModel();
      #$this->groupMenuModel = new \App\Models\GroupMenuModel();
      $this->groupMenuModel = new \App\Models\RoleModel();
   }

   public function index()
   {
      $data = [
         'title' => 'Menu Management',
         'menuGroups' => $this->menuGroupModel->findAll(),
         'menuItems' => $this->menuItemModel->getMenuWithGroups()
      ];
      $viewData = array_merge($this->getGlobalViewData(), $data);

      return view('menus/index', $viewData);
   }

   // MENU GROUPS MANAGEMENT
   public function groups()
   {
      $data = [
         'title' => 'Menu Groups Management',
         'menuGroups' => $this->menuGroupModel->findAll()
      ];
      $data = array_merge($this->getGlobalViewData(), $data);

      return view('menus/groups', $data);
   }

   public function createGroup()
   {
      if ($this->request->getMethod() === 'POST') {
         $rules = [
            'name' => 'required|max_length[100]',
            'icon' => 'required|max_length[50]',
            'order_number' => 'required|integer'
         ];

         if ($this->validate($rules)) {
            $this->menuGroupModel->save([
               'name' => $this->request->getPost('name'),
               'icon' => $this->request->getPost('icon'),
               'order_number' => $this->request->getPost('order_number'),
               'is_active' => $this->request->getPost('is_active') ? true : false
            ]);

            return redirect()->to('/menus/groups')->with('message', 'Menu group created successfully!');
         }
      }

      $data = [
         'title' => 'Create Menu Group',
         'validation' => $this->validator ?? null
      ];
      $data = array_merge($this->getGlobalViewData(), $data);

      return view('menus/create_group', $data);
   }

   public function editGroup($id)
   {
      $group = $this->menuGroupModel->find($id);
      if (!$group) {
         throw new PageNotFoundException('Menu group not found');
      }

      if ($this->request->getMethod() === 'POST') {
         $rules = [
            'name' => 'required|max_length[100]',
            'icon' => 'required|max_length[50]',
            'order_number' => 'required|integer'
         ];

         if ($this->validate($rules)) {
            $this->menuGroupModel->update($id, [
               'name' => $this->request->getPost('name'),
               'icon' => $this->request->getPost('icon'),
               'order_number' => $this->request->getPost('order_number'),
               'is_active' => $this->request->getPost('is_active') ? true : false
            ]);

            return redirect()->to('/menus/groups')->with('message', 'Menu group updated successfully!');
         }
      }

      $data = [
         'title' => 'Edit Menu Group',
         'group' => $group,
         'validation' => $this->validator ?? null
      ];
      $data = array_merge($this->getGlobalViewData(), $data);
      return view('menus/edit_group', $data);
   }

   public function deleteGroup($id)
   {
      $group = $this->menuGroupModel->find($id);
      if (!$group) {
         throw new PageNotFoundException('Menu group not found');
      }

      // Check if group has menu items
      $menuItems = $this->menuItemModel->where('menu_group_id', $id)->countAllResults();
      if ($menuItems > 0) {
         return redirect()->back()->with('error', 'Cannot delete group that has menu items!');
      }

      $this->menuGroupModel->delete($id);
      return redirect()->to('/menus/groups')->with('message', 'Menu group deleted successfully!');
   }

   // MENU ITEMS MANAGEMENT
   public function items()
   {
      $data = [
         'title' => 'Menu Items Management',
         'menuItems' => $this->menuItemModel->getMenuWithGroups(),
         'menuGroups' => $this->menuGroupModel->getActiveGroups(),
         'parentMenus' => $this->menuItemModel->getParentMenus()
      ];
      $data = array_merge($this->getGlobalViewData(), $data);
      return view('menus/items', $data);
   }

   public function createItem()
   {
      if ($this->request->getMethod() === 'POST') {
         $rules = [
            'name' => 'required|max_length[100]',
            'url' => 'required|max_length[255]',
            'permission' => 'required|max_length[100]',
            'menu_group_id' => 'required|integer',
            'order_number' => 'required|integer'
         ];

         if ($this->validate($rules)) {
            $this->menuItemModel->save([
               'name' => $this->request->getPost('name'),
               'url' => $this->request->getPost('url'),
               'icon' => $this->request->getPost('icon'),
               'permission' => $this->request->getPost('permission'),
               'menu_group_id' => $this->request->getPost('menu_group_id'),
               'parent_id' => $this->request->getPost('parent_id') ?: null,
               'order_number' => $this->request->getPost('order_number'),
               'is_active' => $this->request->getPost('is_active') ? true : false
            ]);

            return redirect()->to('/menus/items')->with('message', 'Menu item created successfully!');
         }
      }

      $data = [
         'title' => 'Create Menu Item',
         'menuGroups' => $this->menuGroupModel->getActiveGroups(),
         'parentMenus' => $this->menuItemModel->getParentMenus(),
         'validation' => $this->validator ?? null
      ];
      $data = array_merge($this->getGlobalViewData(), $data);
      return view('menus/create_item', $data);
   }

   public function editItem($id)
   {
      $item = $this->menuItemModel->find($id);
      if (!$item) {
         throw new PageNotFoundException('Menu item not found');
      }

      if ($this->request->getMethod() === 'POST') {
         $rules = [
            'name' => 'required|max_length[100]',
            'url' => 'required|max_length[255]',
            'permission' => 'required|max_length[100]',
            'menu_group_id' => 'required|integer',
            'order_number' => 'required|integer'
         ];

         if ($this->validate($rules)) {
            $this->menuItemModel->update($id, [
               'name' => $this->request->getPost('name'),
               'url' => $this->request->getPost('url'),
               'icon' => $this->request->getPost('icon'),
               'permission' => $this->request->getPost('permission'),
               'menu_group_id' => $this->request->getPost('menu_group_id'),
               'parent_id' => $this->request->getPost('parent_id') ?: null,
               'order_number' => $this->request->getPost('order_number'),
               'is_active' => $this->request->getPost('is_active') ? true : false
            ]);

            return redirect()->to('/menus/items')->with('message', 'Menu item updated successfully!');
         }
      }

      $data = [
         'title' => 'Edit Menu Item',
         'item' => $item,
         'menuGroups' => $this->menuGroupModel->getActiveGroups(),
         'parentMenus' => $this->menuItemModel->getParentMenus(),
         'validation' => $this->validator ?? null
      ];
      $data = array_merge($this->getGlobalViewData(), $data);
      return view('menus/edit_item', $data);
   }

   public function deleteItem($id)
   {
      $item = $this->menuItemModel->find($id);
      if (!$item) {
         throw new PageNotFoundException('Menu item not found');
      }

      // Check if item has children
      $childItems = $this->menuItemModel->where('parent_id', $id)->countAllResults();
      if ($childItems > 0) {
         return redirect()->back()->with('error', 'Cannot delete menu item that has child items!');
      }

      $this->menuItemModel->delete($id);
      return redirect()->to('/menus/items')->with('message', 'Menu item deleted successfully!');
   }

   // GROUP ACCESS MANAGEMENT
   public function groupAccess()
   {
      $data = [
         'title' => 'Group Menu Access',
         'groups' => $this->groupMenuModel->getAllRoles(),
         'menuItems' => $this->menuItemModel->getMenuWithGroups()
      ];
      $data = array_merge($this->getGlobalViewData(), $data);
      return view('menus/group_access', $data);
   }

   public function editGroupAccess($groupName)
   {
      $groupAccess = $this->groupMenuModel->getRolePermissions($groupName);
      $accessibleMenuIds = array_column($groupAccess, 'menu_item_id');

      if ($this->request->getMethod() === 'POST') {
         $selectedMenus = $this->request->getPost('menu_items') ?? [];
         $this->groupMenuModel->updateGroupAccess($groupName, $selectedMenus);

         return redirect()->to('/menus/group-access')->with('message', "Group access updated for {$groupName}!");
      }

      $data = [
         'title' => "Edit Access for Group: {$groupName}",
         'groupName' => $groupName,
         'menuItems' => $this->menuItemModel->getMenuWithGroups(),
         'accessibleMenuIds' => $accessibleMenuIds
      ];
      $data = array_merge($this->getGlobalViewData(), $data);
      return view('menus/edit_group_access', $data);
   }
}