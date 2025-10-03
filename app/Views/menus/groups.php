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
               <li class="breadcrumb-item active">Menu Groups</li>
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
         <div class="col-12">
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">Menu Groups</h3>
                  <div class="card-tools">
                     <a href="<?= base_url('menus/groups/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Group
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
                              <th>Icon</th>
                              <th>Order</th>
                              <th>Status</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($menuGroups as $group): ?>
                           <tr>
                              <td><?= $group['id'] ?></td>
                              <td><?= $group['name'] ?></td>
                              <td><i class="<?= $group['icon'] ?>"></i> <?= $group['icon'] ?></td>
                              <td><?= $group['order_number'] ?></td>
                              <td>
                                 <span class="badge badge-<?= $group['is_active'] ? 'success' : 'danger' ?>">
                                    <?= $group['is_active'] ? 'Active' : 'Inactive' ?>
                                 </span>
                              </td>
                              <td>
                                 <a href="<?= base_url('menus/groups/edit/' . $group['id']) ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                 </a>
                                 <a href="<?= base_url('menus/groups/delete/' . $group['id']) ?>"
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