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
               <li class="breadcrumb-item active">Group Access</li>
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
                  <h3 class="card-title">Shield Groups</h3>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th>Group Name</th>
                              <th>Description</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($groups as $group): ?>
                           <tr>
                              <td>
                                 <strong><?= $group['group'] ?></strong>
                              </td>
                              <td>
                                 <?php
                                    $descriptions = [
                                       'superadmin' => 'Full system access',
                                       'admin' => 'Administrative access',
                                       'user' => 'Standard user access',
                                       'accountant' => 'Accounting department access',
                                       'viewer' => 'Read-only access'
                                    ];
                                    echo $descriptions[$group['group']] ?? 'User group';
                                    ?>
                              </td>
                              <td>
                                 <a href="<?= base_url('menus/group-access/edit/' . $group['group']) ?>"
                                    class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i> Manage Access
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