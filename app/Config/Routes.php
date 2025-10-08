<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Dashboard::index');
$routes->get('home', 'Home::index');
$routes->get('language/switch/(:segment)', 'Language::switch/$1');

$routes->group('', ['filter' => 'session'], static function ($routes) {

   /* dashboard */
   $routes->get('/dashboard', 'Dashboard::index');

   /* accounts */
   $routes->get('/accounts', 'Accounts::index');
   $routes->get('/accounts/create', 'Accounts::create');
   $routes->post('/accounts/store', 'Accounts::store');
   $routes->get('/accounts/edit/(:num)', 'Accounts::edit/$1');
   $routes->post('/accounts/update/(:num)', 'Accounts::update/$1');
   $routes->get('/accounts/toggleStatus/(:num)', 'Accounts::toggleStatus/$1');

   /* users */
   $routes->get('users/create', 'Users::create');
   $routes->post('users/store', 'Users::store');
   $routes->get('users', 'Users::index');
   $routes->get('users/edit/(:num)', 'Users::edit/$1');
   $routes->post('users/update/(:num)', 'Users::update/$1');
   $routes->get('users/toggleStatus/(:num)', 'Users::toggleStatus/$1');

   /* transactions */
   $routes->get('transactions', 'Transactions::index');
   $routes->get('transactions/create', 'Transactions::create');
   $routes->get('transactions/view/(:segment)', 'Transactions::view/$1');

   $routes->post('transactions/addToCart', 'Transactions::addToCart');
   $routes->post('transactions/postCart', 'Transactions::postCart');
   $routes->get('transactions/clearCart', 'Transactions::clearCart');
   $routes->get('transactions/getCart', 'Transactions::getCart');
   $routes->get('transactions/deleteCartItem/(:num)', 'Transactions::deleteCartItem/$1');

   $routes->get('transactions/reverse/(:segment)', 'Transactions::reverse/$1');
   $routes->post('transactions/processReverse', 'Transactions::processReverse');
   $routes->get('transactions/view-with-reversal/(:segment)', 'Transactions::viewWithReversal/$1');

   /* single entry */
   $routes->get('single_entry', 'SingleEntry::index');
   $routes->get('single_entry/create', 'SingleEntry::create');
   $routes->get('single_entry/header/(:segment)', 'SingleEntry::header/$1');
   $routes->post('single_entry/pick', 'SingleEntry::pick_header');
   $routes->post('single_entry/addToCart', 'SingleEntry::addEntry');
   $routes->get('single_entry/getCart/(:segment)', 'SingleEntry::getCart/$1');
   $routes->get('single_entry/deleteCartItem/(:num)', 'SingleEntry::deleteCartItem/$1');
   $routes->get('single_entry/clearCart/(:segment)', 'SingleEntry::clearCart/$1');
   $routes->post('single_entry/postCart', 'SingleEntry::postCart');


   /* Reports */
   $routes->get('reports', 'Reports::index');
   $routes->get('reports/cash-flow-statement', 'Reports::cashFlowStatement');
   $routes->get('reports/general-ledger', 'Reports::generalLedger');
   $routes->get('reports/trial-balance', 'Reports::trialBalance');
   $routes->get('reports/balance-sheet', 'Reports::balanceSheet');
   $routes->get('reports/income-statement', 'Reports::incomeStatement');
   $routes->get('reports/export-pdf/(:segment)', 'Reports::exportPdf/$1');
   $routes->get('reports/cash-flow-statement/print', 'Reports::cashFlowStatementPrint');

   // Role Management Routes
   $routes->group('roles', static function ($routes) {
      $routes->get('/', 'Roles::index');
      $routes->get('manage/(:segment)', 'Roles::manage/$1');
      $routes->post('manage/(:segment)', 'Roles::manage/$1');
      $routes->get('create', 'Roles::create');
      $routes->post('create', 'Roles::create');
      $routes->get('users/(:segment)', 'Roles::users/$1');
      $routes->get('delete/(:segment)', 'Roles::delete/$1');
      $routes->get('permissions/(:segment)', 'Roles::permissions/$1');
      $routes->post('permissions/(:segment)', 'Roles::permissions/$1');
   });

   // Menu Management Routes
   $routes->group('menus', static function ($routes) {
      $routes->get('/', 'Menus::index');

      // Menu Groups
      $routes->group('groups', static function ($routes) {
         $routes->get('/', 'Menus::groups');
         $routes->get('create', 'Menus::createGroup');
         $routes->post('create', 'Menus::createGroup');
         $routes->get('edit/(:num)', 'Menus::editGroup/$1');
         $routes->post('edit/(:num)', 'Menus::editGroup/$1');
         $routes->get('delete/(:num)', 'Menus::deleteGroup/$1');
      });

      // Menu Items
      $routes->group('items', static function ($routes) {
         $routes->get('/', 'Menus::items');
         $routes->get('create', 'Menus::createItem');
         $routes->post('create', 'Menus::createItem');
         $routes->get('edit/(:num)', 'Menus::editItem/$1');
         $routes->post('edit/(:num)', 'Menus::editItem/$1');
         $routes->get('delete/(:num)', 'Menus::deleteItem/$1');
      });

      // Group Access
      $routes->group('group-access', static function ($routes) {
         $routes->get('/', 'Menus::groupAccess');
         $routes->get('edit/(:segment)', 'Menus::editGroupAccess/$1');
         $routes->post('edit/(:segment)', 'Menus::editGroupAccess/$1');
      });
   });
});



service('auth')->routes($routes);
$routes->post('logout', '\CodeIgniter\Shield\Controllers\LoginController::logoutAction');


/* for testing purposes */
$routes->get('/debug-auth', function () {
   echo "<h1>Authentication Debug</h1>";

   try {
      $auth = service('auth');
      echo "<p>Auth service: " . ($auth ? "LOADED" : "NULL") . "</p>";
      echo "<p>Logged in: " . ($auth->loggedIn() ? "YES" : "NO") . "</p>";

      if ($auth->loggedIn()) {
         $user = $auth->user();
         echo "<p>User ID: " . $user->id . "</p>";
         echo "<p>Username: " . $user->username . "</p>";

         $authorize = service('authorize');
         echo "<p>Authorize service: " . ($authorize ? "LOADED" : "NULL") . "</p>";

         if ($authorize) {
            echo "<p>Testing permissions:</p>";
            $permissions = ['dashboard.view', 'accounts.view', 'users.view'];
            foreach ($permissions as $perm) {
               echo "<p> - $perm: " . ($authorize->hasPermission($perm) ? "YES" : "NO") . "</p>";
            }
         }
      }
   } catch (Exception $e) {
      echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
   }
});