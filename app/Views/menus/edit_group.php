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
               <li class="breadcrumb-item active">Edit Group</li>
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
                  <h3 class="card-title">Edit Menu Group</h3>
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
                        <input type="text" class="form-control" id="name" name="name"
                           value="<?= old('name', $group['name']) ?>" required>
                     </div>

                     <div class="form-group">
                        <label for="icon">Icon Class *</label>
                        <input type="text" class="form-control" id="icon" name="icon"
                           value="<?= old('icon', $group['icon']) ?>" required>
                     </div>

                     <div class="form-group">
                        <label for="order_number">Order Number *</label>
                        <input type="number" class="form-control" id="order_number" name="order_number"
                           value="<?= old('order_number', $group['order_number']) ?>" min="0" required>
                     </div>

                     <div class="form-group">
                        <div class="custom-control custom-switch">
                           <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                              <?= old('is_active', $group['is_active']) ? 'checked' : '' ?>>
                           <label class="custom-control-label" for="is_active">Active</label>
                        </div>
                     </div>
                  </div>
                  <div class="card-footer">
                     <button type="submit" class="btn btn-primary">Update Group</button>
                     <a href="<?= base_url('menus/groups') ?>" class="btn btn-default">Cancel</a>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>
<?= $this->endSection() ?>