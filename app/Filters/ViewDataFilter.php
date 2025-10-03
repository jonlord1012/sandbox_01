<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MenuItemModel;
use App\Models\SimpleMenuModel;

class ViewDataFilter implements FilterInterface
{
   public function before(RequestInterface $request, $arguments = null)
   {
      // This runs BEFORE the controller
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

         // Get the renderer service and inject global data
         $renderer = service('renderer');
         $renderer->setData([
            'menuData' => $menuData,
            'userData' => $userData
         ]);
      }

      return $request;
   }

   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
      // Nothing to do after controller
      return $response;
   }
}