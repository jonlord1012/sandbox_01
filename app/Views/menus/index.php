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
               <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?= lang('dashboard') ?></a></li>
               <li class="breadcrumb-item active">Menu Management</li>
            </ol>
         </div>
      </div>
   </div>
</section>

<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-4">
            <div class="card card-primary">
               <div class="card-header">
                  <h3 class="card-title">Quick Actions</h3>
               </div>
               <div class="card-body">
                  <a href="<?= base_url('menus/groups') ?>" class="btn btn-primary btn-block mb-2">
                     <i class="fas fa-layer-group"></i> Manage Menu Groups
                  </a>
                  <a href="<?= base_url('menus/items') ?>" class="btn btn-success btn-block mb-2">
                     <i class="fas fa-bars"></i> Manage Menu Items
                  </a>
                  <a href="<?= base_url('roles') ?>" class="btn btn-info btn-block mb-2">
                     <i class="fas fa-users-cog"></i> Group Access Control
                  </a>
               </div>
            </div>

            <div class="card card-info">
               <div class="card-header">
                  <h3 class="card-title">System Info</h3>
               </div>
               <div class="card-body">
                  <small class="text-muted">
                     <strong>Total Groups:</strong> <?= count($menuGroups) ?><br>
                     <strong>Total Menu Items:</strong> <?= count($menuItems) ?><br>
                     <strong>Shield Integration:</strong> Active
                  </small>
               </div>
            </div>
         </div>

         <div class="col-md-8">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">Current Menu Structure</h3>
               </div>
               <div class="card-body p-0">
                  <div class="table-responsive">
                     <table class="table table-striped">
                        <thead>
                           <tr>
                              <th>Group</th>
                              <th>Menu Item</th>
                              <th>URL</th>
                              <th>Permission</th>
                              <th>Status</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($menuItems as $item): ?>
                           <tr>
                              <td>
                                 <i class="<?= $item['group_icon'] ?>"></i>
                                 <?= $item['group_name'] ?>
                              </td>
                              <td>
                                 <?= str_repeat('&nbsp;&nbsp;&nbsp;', $item['level'] ?? 0) ?>
                                 <i class="<?= $item['icon'] ?>"></i>
                                 <?= $item['name'] ?>
                              </td>
                              <td><code><?= $item['url'] ?></code></td>
                              <td><small class="badge badge-info"><?= $item['permission'] ?></small></td>
                              <td>
                                 <span class="badge badge-<?= $item['is_active'] ? 'success' : 'danger' ?>">
                                    <?= $item['is_active'] ? 'Active' : 'Inactive' ?>
                                 </span>
                              </td>
                           </tr>
                           <?php endforeach; ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<?= $this->endSection() ?>