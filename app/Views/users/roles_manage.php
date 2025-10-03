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
                  <h3 class="card-title">Role: <strong><?= $groupName ?></strong></h3>
               </div>
               <form method="POST" action="<?= current_url() ?>">
                  <div class="card-body">
                     <div class="alert alert-info">
                        <strong>Note:</strong> This interface shows the current permissions.
                        To modify permissions, you need to update the <code>app/Config/AuthGroups.php</code> file.
                     </div>

                     <div class="row">
                        <?php
                        $categories = [
                           'User Management' => array_filter($availablePermissions, fn($k) => strpos($k, 'users.') === 0, ARRAY_FILTER_USE_KEY),
                           'Menu Management' => array_filter($availablePermissions, fn($k) => strpos($k, 'menus.') === 0, ARRAY_FILTER_USE_KEY),
                           'Account Management' => array_filter($availablePermissions, fn($k) => strpos($k, 'accounts.') === 0, ARRAY_FILTER_USE_KEY),
                           'Transaction Management' => array_filter($availablePermissions, fn($k) => strpos($k, 'transactions.') === 0, ARRAY_FILTER_USE_KEY),
                           'Reports' => array_filter($availablePermissions, fn($k) => strpos($k, 'reports.') === 0, ARRAY_FILTER_USE_KEY),
                           'System' => array_filter($availablePermissions, fn($k) => strpos($k, 'dashboard.') === 0 || strpos($k, 'roles.') === 0, ARRAY_FILTER_USE_KEY)
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
                                    <?= $groupName === 'superadmin' ? 'disabled' : '' ?>>
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
                     <?php if ($groupName !== 'superadmin'): ?>
                     <button type="submit" class="btn btn-primary" disabled
                        title="Update AuthGroups.php configuration file">
                        <i class="fas fa-save"></i> Save Permissions (Config Required)
                     </button>
                     <?php endif; ?>
                     <a href="<?= base_url('roles') ?>" class="btn btn-default">Back to Roles</a>
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
                  <?php if (!empty($currentPermissions)): ?>
                  <ul class="list-unstyled">
                     <?php foreach ($currentPermissions as $perm): ?>
                     <li>
                        <i class="fas fa-check text-success"></i>
                        <strong><?= $perm ?></strong>
                        <?php if (isset($availablePermissions[$perm])): ?>
                        <br>
                        <small class="text-muted"><?= $availablePermissions[$perm] ?></small>
                        <?php endif; ?>
                     </li>
                     <?php endforeach; ?>
                  </ul>
                  <?php else: ?>
                  <p class="text-muted">No specific permissions assigned (using defaults).</p>
                  <?php endif; ?>
               </div>
            </div>

            <div class="card card-warning">
               <div class="card-header">
                  <h3 class="card-title">Configuration Required</h3>
               </div>
               <div class="card-body">
                  <p>To modify permissions, edit <code>app/Config/AuthGroups.php</code>:</p>
                  <pre class="bg-dark text-light p-2 small">
'<?= $groupName ?>' => [
    'title'       => '<?= ucfirst($groupName) ?>',
    'description' => '<?= ucfirst($groupName) ?> role',
    'permissions' => [
        // Add permissions here
    ],
],
                            </pre>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<?= $this->endSection() ?>