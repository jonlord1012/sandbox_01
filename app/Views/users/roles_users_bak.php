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
               <li class="breadcrumb-item active">Role Users</li>
            </ol>
         </div>
      </div>
   </div>
</section>

<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-12">
            <div class="card card-primary">
               <div class="card-header">
                  <h3 class="card-title">
                     Users with Role: <strong><?= $roleName ?></strong>
                     <span class="badge badge-light"><?= count($users) ?> users</span>
                  </h3>
                  <div class="card-tools">
                     <a href="<?= base_url('users/create') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-user-plus"></i> Add New User
                     </a>
                     <a href="<?= base_url('users') ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-users"></i> Manage All Users
                     </a>
                  </div>
               </div>
               <div class="card-body">
                  <?php if (empty($users)): ?>
                  <div class="alert alert-info">
                     <h5><i class="icon fas fa-info"></i> No Users Found</h5>
                     No users are currently assigned to the <strong><?= $roleName ?></strong> role.
                     <br>
                     <a href="<?= base_url('users') ?>" class="btn btn-primary mt-2">
                        <i class="fas fa-users"></i> Assign Users to Role
                     </a>
                  </div>
                  <?php else: ?>
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Username</th>
                              <th>Email</th>
                              <th>Status</th>
                              <th>Last Login</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($users as $index => $user): ?>
                           <tr>
                              <td><?= $index + 1 ?></td>
                              <td>
                                 <strong><?= $user['username'] ?></strong>
                                 <?php if ($user['username'] === 'binjava'): ?>
                                 <span class="badge badge-danger">Super Admin</span>
                                 <?php endif; ?>
                              </td>
                              <td><?= $user['email'] ?></td>
                              <td>
                                 <span class="badge badge-<?= $user['active'] ? 'success' : 'danger' ?>">
                                    <?= $user['active'] ? 'Active' : 'Inactive' ?>
                                 </span>
                              </td>
                              <td>
                                 <?php
                                       // Get last login info (you might need to adjust this based on your Shield setup)
                                       $db = db_connect();
                                       $lastLogin = $db->table('auth_logins')
                                          ->where('user_id', $user['id'])
                                          ->orderBy('date', 'DESC')
                                          ->get()
                                          ->getRow();
                                       ?>
                                 <?php if ($lastLogin): ?>
                                 <small class="text-muted">
                                    <?= date('M j, Y g:i A', strtotime($lastLogin->date)) ?>
                                 </small>
                                 <?php else: ?>
                                 <span class="badge badge-secondary">Never</span>
                                 <?php endif; ?>
                              </td>
                              <td>
                                 <a href="<?= base_url('users/edit/' . $user['id']) ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                 </a>
                                 <a href="<?= base_url('users/toggle-status/' . $user['id']) ?>"
                                    class="btn btn-<?= $user['active'] ? 'danger' : 'success' ?> btn-sm"
                                    onclick="return confirm('Are you sure you want to <?= $user['active'] ? 'deactivate' : 'activate' ?> this user?')">
                                    <i class="fas fa-<?= $user['active'] ? 'ban' : 'check' ?>"></i>
                                    <?= $user['active'] ? 'Deactivate' : 'Activate' ?>
                                 </a>
                              </td>
                           </tr>
                           <?php endforeach; ?>
                        </tbody>
                     </table>
                  </div>
                  <?php endif; ?>
               </div>
               <div class="card-footer">
                  <a href="<?= base_url('roles') ?>" class="btn btn-default">
                     <i class="fas fa-arrow-left"></i> Back to Roles
                  </a>
                  <a href="<?= base_url('roles/manage/' . $roleName) ?>" class="btn btn-primary float-right">
                     <i class="fas fa-edit"></i> Manage Role Permissions
                  </a>
               </div>
            </div>
         </div>
      </div>

      <?php if (!empty($users)): ?>
      <div class="row">
         <div class="col-md-6">
            <div class="card card-info">
               <div class="card-header">
                  <h3 class="card-title">Role Summary</h3>
               </div>
               <div class="card-body">
                  <?php
                     $activeUsers = array_filter($users, fn($user) => $user['active']);
                     $inactiveUsers = array_filter($users, fn($user) => !$user['active']);
                     ?>
                  <ul class="list-unstyled">
                     <li><strong>Total Users:</strong> <?= count($users) ?></li>
                     <li><strong>Active Users:</strong> <span
                           class="badge badge-success"><?= count($activeUsers) ?></span></li>
                     <li><strong>Inactive Users:</strong> <span
                           class="badge badge-danger"><?= count($inactiveUsers) ?></span></li>
                     <li><strong>Role Created:</strong> <?= date('M j, Y') ?></li>
                  </ul>
               </div>
            </div>
         </div>

         <div class="col-md-6">
            <div class="card card-warning">
               <div class="card-header">
                  <h3 class="card-title">Quick Actions</h3>
               </div>
               <div class="card-body">
                  <div class="btn-group-vertical w-100">
                     <a href="<?= base_url('users/create') ?>" class="btn btn-success mb-2">
                        <i class="fas fa-user-plus"></i> Create New User
                     </a>
                     <a href="<?= base_url('users') ?>" class="btn btn-info mb-2">
                        <i class="fas fa-users"></i> Manage All Users
                     </a>
                     <a href="<?= base_url('roles/manage/' . $roleName) ?>" class="btn btn-primary">
                        <i class="fas fa-shield-alt"></i> Edit Role Permissions
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php endif; ?>
   </div>
</section>
<?= $this->endSection() ?>