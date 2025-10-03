<aside class="main-sidebar sidebar-dark-primary elevation-4">
   <a href="<?= base_url('dashboard') ?>" class="brand-link">
      <img src="<?= base_url('binjava_kufi.png') ?>" alt="Logo" class="brand-image img-circle elevation-3"
         style="opacity: .8">
      <span class="brand-text font-weight-light">BJS Accounting</span>
   </a>

   <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
         <div class="image">
            <img src="<?= base_url('template/img/user-default.jpg') ?>" class="img-circle elevation-2" alt="User Image">
         </div>
         <div class="info">
            <a href="#" class="d-block"><?= $userData->username ?? 'User' ?></a>
            <small class="text-light">
               <?php
               $groups = $userData->getGroups();
               echo !empty($groups) ? implode(', ', $groups) : 'User';
               ?>
            </small>
         </div>
      </div>

      <nav class="mt-2">
         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <?php if (!empty($menuData)): ?>
            <?php
               $currentGroup = '';
               foreach ($menuData as $item):
                  // Show group header if group changed
                  if ($item['group_name'] != $currentGroup):
                     $currentGroup = $item['group_name'];
               ?>
            <li class="nav-header"><?= strtoupper($currentGroup) ?></li>
            <?php endif; ?>

            <?php if (isset($item['children'])): ?>
            <!-- Parent menu with children -->
            <li class="nav-item has-treeview <?= url_is($item['url'] . '*') ? 'menu-open' : '' ?>">
               <a href="#" class="nav-link <?= url_is($item['url'] . '*') ? 'active' : '' ?>">
                  <i class="nav-icon <?= $item['icon'] ?>"></i>
                  <p>
                     <?= $item['name'] ?>
                     <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <?php foreach ($item['children'] as $child): ?>
                  <li class="nav-item">
                     <a href="<?= base_url($child['url']) ?>"
                        class="nav-link <?= url_is($child['url']) ? 'active' : '' ?>">
                        <i class="nav-icon <?= $child['icon'] ?>"></i>
                        <p><?= $child['name'] ?></p>
                     </a>
                  </li>
                  <?php endforeach; ?>
               </ul>
            </li>
            <?php else: ?>
            <!-- Single menu item -->
            <li class="nav-item">
               <a href="<?= base_url($item['url']) ?>" class="nav-link <?= url_is($item['url']) ? 'active' : '' ?>">
                  <i class="nav-icon <?= $item['icon'] ?>"></i>
                  <p><?= $item['name'] ?></p>
               </a>
            </li>
            <?php endif; ?>
            <?php endforeach; ?>
            <?php else: ?>
            <li class="nav-item">
               <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-exclamation-triangle"></i>
                  <p>No Menu Access</p>
               </a>
            </li>
            <?php endif; ?>
         </ul>
      </nav>
   </div>
</aside>