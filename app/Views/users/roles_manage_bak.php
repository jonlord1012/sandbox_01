<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1><?= $title ?></h1>
         </div>
         <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
               <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
               <li class="breadcrumb-item"><a href="<?= base_url('roles') ?>">Role Management</a></li>
               <li class="breadcrumb-item active">Manage Permissions</li>
            </ol>
         </div>
      </div>
   </div>
</section>

<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-8">
            <div class="card card-primary">
               <div class="card-header">
                  <h3 class="card-title">Role: <strong><?= $roleName ?></strong></h3>
               </div>
               <form method="POST" action="<?= current_url() ?>">
                  <div class="card-body">
                     <div class="row">
                        <?php
                        $categories = [
                           'User Management' => array_filter($availablePermissions, fn($k) => strpos($k, 'users.') === 0, ARRAY_FILTER_USE_KEY),
                           'Menu Management' => array_filter($availablePermissions, fn($k) => strpos($k, 'menus.') === 0, ARRAY_FILTER_USE_KEY),
                           'Account Management' => array_filter($availablePermissions, fn($k) => strpos($k, 'accounts.') === 0, ARRAY_FILTER_USE_KEY),
                           'Transaction Management' => array_filter($availablePermissions, fn($k) => strpos($k, 'transactions.') === 0, ARRAY_FILTER_USE_KEY),
                           'Reports' => array_filter($availablePermissions, fn($k) => strpos($k, 'reports.') === 0, ARRAY_FILTER_USE_KEY),
                           'System' => array_filter($availablePermissions, fn($k) => strpos($k, 'settings.') === 0 || strpos($k, 'dashboard.') === 0, ARRAY_FILTER_USE_KEY),
                           'Full Access' => array_filter($availablePermissions, fn($k) => $k === '.*', ARRAY_FILTER_USE_KEY)
                        ];

                        foreach ($categories as $category => $perms):
                           if (empty($perms)) continue;
                        ?>
                        <div class="col-12">
                           <h5 class="mt-3 mb-2 border-bottom"><?= $category ?></h5>
                        </div>

                        <?php foreach ($perms as $perm => $desc): ?>
                        <div class="col-md-6">
                           <div class="form-group">
                              <div class="custom-control custom-checkbox">
                                 <input class="custom-control-input" type="checkbox" id="perm_<?= $perm ?>"
                                    name="permissions[]" value="<?= $perm ?>"
                                    <?= in_array($perm, $currentPermissions) ? 'checked' : '' ?>
                                    <?= $perm === '.*' ? 'onclick="toggleAllPermissions(this)"' : '' ?>>
                                 <label class="custom-control-label" for="perm_<?= $perm ?>">
                                    <strong><?= $perm ?></strong>
                                    <br>
                                    <small class="text-muted"><?= $desc ?></small>
                                 </label>
                              </div>
                           </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                     </div>
                  </div>
                  <div class="card-footer">
                     <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Permissions
                     </button>
                     <a href="<?= base_url('roles') ?>" class="btn btn-default">Cancel</a>

                     <button type="button" class="btn btn-info float-right" onclick="selectAllPermissions()">
                        <i class="fas fa-check-double"></i> Select All
                     </button>
                     <button type="button" class="btn btn-warning float-right mr-2" onclick="deselectAllPermissions()">
                        <i class="fas fa-times"></i> Deselect All
                     </button>
                  </div>
               </form>
            </div>
         </div>

         <div class="col-md-4">
            <div class="card card-info">
               <div class="card-header">
                  <h3 class="card-title">Current Permissions</h3>
               </div>
               <div class="card-body">
                  <?php if (!empty($rolePermissions)): ?>
                  <ul class="list-unstyled">
                     <?php foreach ($rolePermissions as $perm): ?>
                     <li>
                        <i class="fas fa-check text-success"></i>
                        <strong><?= $perm['permission'] ?></strong>
                        <br>
                        <small class="text-muted"><?= $perm['description'] ?></small>
                     </li>
                     <?php endforeach; ?>
                  </ul>
                  <?php else: ?>
                  <p class="text-muted">No permissions assigned yet.</p>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<script>
function selectAllPermissions() {
   document.querySelectorAll('input[type="checkbox"][name="permissions[]"]').forEach(checkbox => {
      checkbox.checked = true;
   });
}

function deselectAllPermissions() {
   document.querySelectorAll('input[type="checkbox"][name="permissions[]"]').forEach(checkbox => {
      checkbox.checked = false;
   });
}

function toggleAllPermissions(checkbox) {
   if (checkbox.checked) {
      // If "Full Access" is checked, check all other permissions
      selectAllPermissions();
   }
}
</script>
<?= $this->endSection() ?>