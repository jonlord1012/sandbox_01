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
               <li class="breadcrumb-item active">Role Management</li>
            </ol>
         </div>
      </div>
   </div>
</section>

<section class="content">
   <div class="container-fluid">
      <?php if (session('message')): ?>
      <div class="alert alert-success alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         <?= session('message') ?>
      </div>
      <?php endif; ?>

      <div class="row">
         <div class="col-md-8">
            <div class="card card-primary">
               <div class="card-header">
                  <h3 class="card-title">System Roles</h3>
               </div>
               <div class="card-body p-0">
                  <div class="table-responsive">
                     <table class="table table-striped">
                        <thead>
                           <tr>
                              <th>Role Name</th>
                              <th>Users Count</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($groups as $groupName => $userCount): ?>
                           <tr>
                              <td>
                                 <strong><?= $groupName ?></strong>
                                 <?php if ($groupName === 'superadmin'): ?>
                                 <span class="badge badge-danger">System</span>
                                 <?php endif; ?>
                              </td>
                              <td>
                                 <span class="badge badge-success"><?= $userCount ?> users</span>
                              </td>
                              <td>
                                 <a href="<?= base_url('roles/permissions/' . $groupName) ?>"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-shield-alt"></i> Permissions
                                 </a>
                                 <a href="<?= base_url('roles/users/' . $groupName) ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-users"></i> View Users
                                 </a>
                              </td>
                           </tr>
                           <?php endforeach; ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-md-4">
            <div class="card card-info">
               <div class="card-header">
                  <h3 class="card-title">About Shield Roles</h3>
               </div>
               <div class="card-body">
                  <p><strong>How it works:</strong></p>
                  <ul>
                     <li>Roles are managed through Shield's group system</li>
                     <li>Permissions are defined in <code>app/Config/Auth.php</code></li>
                     <li>Group permissions are configured in <code>app/Config/AuthGroups.php</code></li>
                  </ul>

                  <div class="alert alert-warning">
                     <strong>Note:</strong> To modify group permissions, you need to update the
                     <code>AuthGroups.php</code> configuration file.
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<?= $this->endSection() ?>