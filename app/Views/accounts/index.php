<?= $this->extend('layouts/main') ?>
<!-- We will create this layout next -->
<?= $this->section('content') ?>

<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1 class="m-0"><?= $title ?></h1>
         </div>
         <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
               <li class="breadcrumb-item"><a href="#">Home</a></li>
               <li class="breadcrumb-item active">Chart of Accounts</li>
            </ol>
         </div>
      </div>
   </div>
</div>

<div class="content">
   <div class="container-fluid">
      <!-- Flash Message -->
      <?php if (session()->getFlashdata('message')) : ?>
      <div class="alert alert-success alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         <h5><i class="icon fas fa-check"></i> Success!</h5>
         <?= session()->getFlashdata('message'); ?>
      </div>
      <?php endif; ?>

      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Account List</h3>
            <div class="card-tools">
               <a href="<?= site_url('accounts/create') ?>" class="btn btn-primary btn-sm">
                  <i class="fas fa-plus"></i> New Account
               </a>
            </div>
         </div>
         <div class="card-body p-0">
            <table class="table table-striped">
               <thead>
                  <tr>
                     <th style="width: 10%">Code</th>
                     <th style="width: 25%">Name</th>
                     <th style="width: 15%">Group</th>
                     <th style="width: 10%">Category</th>
                     <th style="width: 10%">Normal Bal.</th>
                     <th style="width: 10%">Status</th>
                     <th style="width: 20%">Actions</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($accounts as $account) : ?>
                  <tr>
                     <td><span class="badge bg-light"><?= esc($account['code']) ?></span></td>
                     <td><?= esc($account['name']) ?></td>
                     <td><span class="badge bg-info"><?= esc($account['group_name']) ?></span></td>
                     <td><span class="badge bg-secondary"><?= esc($account['category']) ?></span></td>
                     <td><span
                           class="badge bg-<?= $account['is_debit'] ? 'danger' : 'success' ?>"><?= $account['is_debit'] ? 'DEBIT' : 'CREDIT' ?></span>
                     </td>
                     <td><span
                           class="badge bg-<?= $account['is_active'] ? 'success' : 'secondary' ?>"><?= $account['is_active'] ? 'ACTIVE' : 'INACTIVE' ?></span>
                     </td>
                     <td>
                        <a href="<?= site_url('accounts/edit/' . $account['id']) ?>" class="btn btn-sm btn-warning"><i
                              class="fas fa-edit"></i> Edit</a>
                        <a href="<?= site_url('accounts/toggleStatus/' . $account['id']) ?>"
                           class="btn btn-sm btn-<?= $account['is_active'] ? 'secondary' : 'success' ?>">
                           <i class="fas fa-<?= $account['is_active'] ? 'times' : 'check' ?>"></i>
                           <?= $account['is_active'] ? 'Deactivate' : 'Activate' ?>
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