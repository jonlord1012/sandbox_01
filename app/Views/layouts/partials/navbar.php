<nav class="main-header navbar navbar-expand navbar-white navbar-light">
   <!-- Left navbar links -->
   <ul class="navbar-nav">
      <li class="nav-item">
         <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
         <a href="<?= site_url('/') ?>" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
         <a href="home" class="nav-link">Contact</a>
      </li>
   </ul>
   <?php
   $currentLang = current_language();
   $availableLangs = available_languages();
   ?>
   <!-- Right navbar links -->
   <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
         <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="flag-icon flag-icon-<?= $availableLangs[$currentLang]['flag'] ?>"></i>
            <?= strtoupper($currentLang) ?>
         </a>
         <div class="dropdown-menu dropdown-menu-right p-0">
            <?php foreach ($availableLangs as $code => $lang): ?>
            <?php if ($code !== $currentLang): ?>
            <a href="<?= site_url("language/switch/{$code}") ?>" class="dropdown-item">
               <i class="flag-icon flag-icon-<?= $lang['flag'] ?> mr-2"></i>
               <?= $lang['name'] ?>
            </a>
            <?php endif; ?>
            <?php endforeach; ?>
         </div>
      </li>
      <li class="nav-item">
         <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
         </a>
      </li>
      <li class="nav-item dropdown">
         <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-user"></i>
         </a>
         <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item">
               <i class="fas fa-user mr-2"></i> My Profile
            </a>
            <div class="dropdown-divider"></div>

            <!-- With this form: -->
            <form action="<?= site_url('logout') ?>" method="POST" class="d-inline">
               <?= csrf_field() ?>
               <button type="submit" class="dropdown-item btn btn-link">
                  <!-- Style the button to look like a link -->
                  <i class="fas fa-sign-out-alt mr-2"></i> Logout
               </button>
            </form>
         </div>
      </li>
   </ul>
</nav>