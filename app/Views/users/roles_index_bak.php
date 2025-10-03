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

      <?php if (session('error')): ?>
      <div class="alert alert-danger alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         <?= session('error') ?>
      </div>
      <?php endif; ?>

      <div class="row">
         <div class="col-md-8">
            <div class="card card-primary">
               <div class="card-header">
                  <h3 class="card-title">System Roles</h3>
                  <div class="card-tools">
                     <a href="<?= base_url('roles/create') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Create New Role
                     </a>
                  </div>
               </div>
               <div class="card-body p-0">
                  <div class="table-responsive">
                     <table class="table table-striped">
                        <thead>
                           <tr>
                              <th>Role Name</th>
                              <th>Permissions Count</th>
                              <th>Users Count</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($roles as $role): ?>
                           <?php
                              $db = db_connect();
                              $permCount = $db->table('role_permissions')
                                 ->where('role_name', $role['role_name'])
                                 ->countAllResults();
                              $userCount = $db->table('auth_groups_users')
                                 ->where('group', $role['role_name'])
                                 ->countAllResults();
                              ?>
                           <tr>
                              <td>
                                 <strong><?= $role['role_name'] ?></strong>
                                 <?php if ($role['role_name'] === 'superadmin'): ?>
                                 <span class="badge badge-danger">System</span>
                                 <?php endif; ?>
                              </td>
                              <td>
                                 <span class="badge badge-info"><?= $permCount ?> permissions</span>
                              </td>
                              <td>
                                 <span class="badge badge-success"><?= $userCount ?> users</span>
                              </td>
                              <td>
                                 <a href="<?= base_url('roles/manage/' . $role['role_name']) ?>"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Manage Permissions
                                 </a>
                                 <a href="<?= base_url('roles/users/' . $role['role_name']) ?>"
                                    class="btn btn-info btn-sm">
                                    <i class="fas fa-users"></i> View Users
                                 </a>
                                 <?php if ($role['role_name'] !== 'superadmin' && !in_array($role['role_name'], ['admin', 'accountant', 'viewer'])): ?>
                                 <a href="<?= base_url('roles/delete/' . $role['role_name']) ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete role <?= $role['role_name'] ?>? This will remove all permissions for this role.')">
                                    <i class="fas fa-trash"></i> Delete
                                 </a>
                                 <?php endif; ?>
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
                  <h3 class="card-title">About Role Management</h3>
               </div>
               <div class="card-body">
                  <p><strong>How it works:</strong></p>
                  <ul>
                     <li>Roles are managed through Shield groups</li>
                     <li>Permissions control access to system features</li>
                     <li>Users inherit permissions from their assigned roles</li>
                  </ul>

                  <p><strong>Default Roles:</strong></p>
                  <ul>
                     <li><strong>superadmin</strong> - Full system access</li>
                     <li><strong>admin</strong> - Administrative access</li>
                     <li><strong>accountant</strong> - Accounting functions</li>
                     <li><strong>viewer</strong> - Read-only access</li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<?= $this->endSection() ?>