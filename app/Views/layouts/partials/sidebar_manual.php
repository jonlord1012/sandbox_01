<aside class="main-sidebar sidebar-dark-primary elevation-4">
   <!-- Brand Logo -->
   <a href="<?= site_url('/') ?>" class="brand-link">
      <img src="<?= base_url('binjava_kufi.png') ?>" alt="Logo" class="brand-image img-circle elevation-3"
         style="opacity: .8">
      <span class="brand-text font-weight-light">BJS Accounting</span>
   </a>

   <!-- Sidebar -->
   <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
         <div class="image">
            <img src="<?= base_url('template/img/user-default.jpg') ?>" class="img-circle elevation-2" alt="User Image">
         </div>
         <div class="info">
            <a href="#" class="d-block">User Name</a>
         </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
            <li class="nav-item">
               <a href="<?= site_url('dashboard') ?>" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>Dashboard</p>
               </a>
            </li>
            <li class="nav-header">MASTER DATA</li>
            <li class="nav-item">
               <a href="<?= site_url('accounts') ?>" class="nav-link">
                  <i class="nav-icon fas fa-th"></i>
                  <p>Chart of Accounts</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('users') ?>" class="nav-link">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Master Users</p>
               </a>
            </li>
            <li class="nav-header">TRANSACTIONS</li>
            <li class="nav-item">
               <a href="<?= site_url('transactions') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>General Journal</p>
               </a>
            </li>
            <li class="nav-header">REPORTS</li>
            <li class="nav-item">
               <a href="<?= site_url('reports/cash-flow-statement') ?>" class="nav-link">
                  <i class="nav-icon fas fa-water"></i>
                  <p>Cash Flow Statement</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('reports') ?>" class="nav-link">
                  <i class="nav-icon fas fa-chart-bar"></i>
                  <p>Financial Reports</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('reports/general-ledger') ?>" class="nav-link">
                  <i class="nav-icon fas fa-file-invoice"></i>
                  <p>General Ledger</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('reports/balance-sheet') ?>" class="nav-link">
                  <i class="nav-icon fas fa-balance-scale"></i>
                  <p>Balance Sheet</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('reports/income-statement') ?>" class="nav-link">
                  <i class="nav-icon fas fa-chart-line"></i>
                  <p>Income Statement</p>
               </a>
            </li>
         </ul>
      </nav>
      <!-- /.sidebar-menu -->
   </div>
   <!-- /.sidebar -->
</aside>