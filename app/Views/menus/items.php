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
               <li class="breadcrumb-item"><a href="<?= base_url('menus') ?>">Menu Management</a></li>
               <li class="breadcrumb-item active">Menu Items</li>
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
         <div class="col-12">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">Menu Items</h3>
                  <div class="card-tools">
                     <a href="<?= base_url('menus/items/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Menu Item
                     </a>
                  </div>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Name</th>
                              <th>Group</th>
                              <th>URL</th>
                              <th>Permission</th>
                              <th>Parent</th>
                              <th>Order</th>
                              <th>Status</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($menuItems as $item): ?>
                           <tr>
                              <td><?= $item['id'] ?></td>
                              <td>
                                 <i class="<?= $item['icon'] ?>"></i>
                                 <?= $item['name'] ?>
                              </td>
                              <td>
                                 <i class="<?= $item['group_icon'] ?>"></i>
                                 <?= $item['group_name'] ?>
                              </td>
                              <td><code><?= $item['url'] ?></code></td>
                              <td><small class="badge badge-info"><?= $item['permission'] ?></small></td>
                              <td>
                                 <?php if ($item['parent_id']): ?>
                                 <span class="badge badge-secondary">Child</span>
                                 <?php else: ?>
                                 <span class="badge badge-primary">Parent</span>
                                 <?php endif; ?>
                              </td>
                              <td><?= $item['order_number'] ?></td>
                              <td>
                                 <span class="badge badge-<?= $item['is_active'] ? 'success' : 'danger' ?>">
                                    <?= $item['is_active'] ? 'Active' : 'Inactive' ?>
                                 </span>
                              </td>
                              <td>
                                 <a href="<?= base_url('menus/items/edit/' . $item['id']) ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                 </a>
                                 <a href="<?= base_url('menus/items/delete/' . $item['id']) ?>"
                                    class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i> Delete
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
      </div>
   </div>
</section>

<?= $this->endSection() ?>