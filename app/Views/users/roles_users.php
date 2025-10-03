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
                     Users in Role: <strong><?= $groupName ?></strong>
                     <span class="badge badge-light"><?= count($users) ?> users</span>
                  </h3>
                  <div class="card-tools">
                     <a href="<?= base_url('users/create') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-user-plus"></i> Add User
                     </a>
                     <a href="<?= base_url('users') ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-users"></i> Manage Users
                     </a>
                  </div>
               </div>
               <div class="card-body">
                  <?php if (empty($users)): ?>
                  <div class="alert alert-info">
                     <h5><i class="icon fas fa-info"></i> No Users Found</h5>
                     No users are currently assigned to the <strong><?= $groupName ?></strong> role.
                  </div>
                  <?php else: ?>
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th>Username</th>
                              <th>Email</th>
                              <th>Status</th>
                              <th>Created</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($users as $user): ?>
                           <tr>
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
                              <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                              <td>
                                 <a href="<?= base_url('users/edit/' . $user['id']) ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
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
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<?= $this->endSection() ?>