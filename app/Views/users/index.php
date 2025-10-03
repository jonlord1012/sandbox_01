<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1 class="m-0"><?= $title ?></h1>
         </div>
         <div class="col-sm-6">
            <a href="<?= site_url('users/create') ?>" class="btn btn-primary float-right">
               <i class="fas fa-user-plus"></i> New User
            </a>
         </div>
      </div>
   </div>
</div>

<div class="content">
   <div class="container-fluid">
      <?php if (session()->getFlashdata('message')) : ?>
      <div class="alert alert-success alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         <?= session()->getFlashdata('message'); ?>
      </div>
      <?php endif; ?>

      <div class="card">
         <div class="card-body p-0">
            <table class="table table-striped">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Username</th>

                     <th>Roles</th>
                     <th>Status</th>
                     <th>Last Login</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($users as $user) : ?>
                  <tr>
                     <td><?= $user['id'] ?></td>
                     <td><?= $user['username'] ?></td>

                     <td>
                        <?php if (!empty($user['groups'])): ?>
                        <?php foreach ($user['groups'] as $group): ?>
                        <span class="badge badge-info"><?= $group ?></span>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <span class="badge badge-secondary">No roles</span>
                        <?php endif; ?>
                     </td>
                     <td>
                        <span class="badge bg-<?= $user['active'] ? 'success' : 'secondary' ?>">
                           <?= $user['active'] ? 'ACTIVE' : 'INACTIVE' ?>
                        </span>
                     </td>
                     <td>
                        <?php if ($user['active']) : ?>
                        <small class=""><?= date('M j, Y', strtotime($user['last_active'])) ?></small>
                        <?php endif; ?>
                     </td>
                     <td>
                        <a href="<?= site_url('users/edit/' . $user['id']) ?>" class="btn btn-sm btn-warning"><i
                              class="fas fa-edit"></i> Permissions</a>
                        <a href="<?= site_url('users/toggleStatus/' . $user['id']) ?>"
                           class="btn btn-sm btn-<?= $user['active'] ? 'secondary' : 'success' ?>">
                           <i class="fas fa-<?= $user['active'] ? 'times' : 'check' ?>"></i>
                           <?= $user['active'] ? 'Deactivate' : 'Activate' ?>
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

<?= $this->endSection() ?>