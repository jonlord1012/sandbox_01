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
               <li class="breadcrumb-item"><a href="<?= base_url('menus/groups') ?>">Menu Groups</a></li>
               <li class="breadcrumb-item active">Create Group</li>
            </ol>
         </div>
      </div>
   </div>
</section>

<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-6">
            <div class="card card-primary">
               <div class="card-header">
                  <h3 class="card-title">Menu Group Details</h3>
               </div>
               <form method="POST" action="<?= current_url() ?>">
                  <div class="card-body">
                     <?php if (isset($validation)): ?>
                     <div class="alert alert-danger">
                        <?= $validation->listErrors() ?>
                     </div>
                     <?php endif; ?>

                     <div class="form-group">
                        <label for="name">Group Name *</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>"
                           placeholder="Enter group name" required>
                     </div>

                     <div class="form-group">
                        <label for="icon">Icon Class *</label>
                        <input type="text" class="form-control" id="icon" name="icon"
                           value="<?= old('icon', 'fas fa-circle') ?>" placeholder="e.g., fas fa-home" required>
                        <small class="form-text text-muted">
                           Use Font Awesome classes. Example: fas fa-home, fas fa-cog
                        </small>
                     </div>

                     <div class="form-group">
                        <label for="order_number">Order Number *</label>
                        <input type="number" class="form-control" id="order_number" name="order_number"
                           value="<?= old('order_number', 0) ?>" min="0" required>
                     </div>

                     <div class="form-group">
                        <div class="custom-control custom-switch">
                           <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                              checked>
                           <label class="custom-control-label" for="is_active">Active</label>
                        </div>
                     </div>
                  </div>
                  <div class="card-footer">
                     <button type="submit" class="btn btn-primary">Create Group</button>
                     <a href="<?= base_url('menus/groups') ?>" class="btn btn-default">Cancel</a>
                  </div>
               </form>
            </div>
         </div>

         <div class="col-md-6">
            <div class="card card-info">
               <div class="card-header">
                  <h3 class="card-title">Icon Reference</h3>
               </div>
               <div class="card-body">
                  <p><strong>Common Icons:</strong></p>
                  <ul class="list-unstyled">
                     <li><i class="fas fa-tachometer-alt"></i> fas fa-tachometer-alt</li>
                     <li><i class="fas fa-chart-bar"></i> fas fa-chart-bar</li>
                     <li><i class="fas fa-book"></i> fas fa-book</li>
                     <li><i class="fas fa-file-invoice-dollar"></i> fas fa-file-invoice-dollar</li>
                     <li><i class="fas fa-users"></i> fas fa-users</li>
                     <li><i class="fas fa-cogs"></i> fas fa-cogs</li>
                     <li><i class="fas fa-list"></i> fas fa-list</li>
                  </ul>
                  <small class="text-muted">
                     Visit <a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a> for more icons.
                  </small>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<?= $this->endSection() ?>