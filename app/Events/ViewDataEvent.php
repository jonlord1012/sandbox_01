<?php

namespace App\Events;

use CodeIgniter\Events\Events;
use App\Models\MenuItemModel;
use App\Models\SimpleMenuModel;

class ViewDataEvent
{
   public static function injectGlobalData()
   {
      $auth = service('auth');

      if ($auth->loggedIn()) {
         $userData = $auth->user();
         $menuData = [];

         // Get menu data
         try {
            $menuModel = new MenuItemModel();
            $menuData = $menuModel->getUserMenu($userData->id);
         } catch (\Exception $e) {
            log_message('error', 'Menu loading failed: ' . $e->getMessage());
            $simpleMenuModel = new SimpleMenuModel();
            $menuData = $simpleMenuModel->getSimpleUserMenu($userData->id);
         }

         // Get the current renderer and inject data
         $renderer = service('renderer');
         $currentData = $renderer->getData();

         // Merge with existing data (don't override)
         $renderer->setData(array_merge([
            'menuData' => $menuData,
            'userData' => $userData
         ], $currentData));
      }
   }
}