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
               <li class="breadcrumb-item active">Create Role</li>
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
                  <h3 class="card-title">Create New Role</h3>
               </div>
               <form method="POST" action="<?= current_url() ?>">
                  <div class="card-body">
                     <?php if (session('error')): ?>
                     <div class="alert alert-danger">
                        <?= session('error') ?>
                     </div>
                     <?php endif; ?>

                     <div class="form-group">
                        <label for="role_name">Role Name *</label>
                        <input type="text" class="form-control" id="role_name" name="role_name"
                           value="<?= old('role_name') ?>" placeholder="Enter role name (e.g., manager, auditor)"
                           required>
                        <small class="form-text text-muted">
                           Use lowercase letters, numbers, and underscores only. Example: "financial_manager"
                        </small>
                     </div>

                     <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description"
                           placeholder="Brief description of this role's responsibilities"><?= old('description') ?></textarea>
                     </div>

                     <div class="form-group">
                        <label>Default Permissions</label>
                        <div class="custom-control custom-checkbox">
                           <input class="custom-control-input" type="checkbox" id="default_dashboard" checked disabled>
                           <label class="custom-control-label" for="default_dashboard">
                              Dashboard Access (required for all roles)
                           </label>
                        </div>
                        <small class="form-text text-muted">
                           You can add more permissions after creating the role.
                        </small>
                     </div>
                  </div>
                  <div class="card-footer">
                     <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Role
                     </button>
                     <a href="<?= base_url('roles') ?>" class="btn btn-default">Cancel</a>
                  </div>
               </form>
            </div>
         </div>

         <div class="col-md-6">
            <div class="card card-info">
               <div class="card-header">
                  <h3 class="card-title">Role Naming Guidelines</h3>
               </div>
               <div class="card-body">
                  <p><strong>Best Practices:</strong></p>
                  <ul>
                     <li>Use descriptive names (e.g., <code>financial_manager</code>)</li>
                     <li>Use lowercase letters only</li>
                     <li>Use underscores for spaces</li>
                     <li>Avoid special characters</li>
                  </ul>

                  <p><strong>Common Role Examples:</strong></p>
                  <ul>
                     <li><code>financial_manager</code> - Financial management access</li>
                     <li><code>senior_accountant</code> - Advanced accounting functions</li>
                     <li><code>auditor</code> - Read-only audit access</li>
                     <li><code>data_entry</code> - Data entry permissions only</li>
                  </ul>

                  <div class="alert alert-warning">
                     <strong>Note:</strong> After creating the role, you'll need to:
                     <ol>
                        <li>Assign permissions to the role</li>
                        <li>Assign users to the role via User Management</li>
                     </ol>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<?= $this->endSection() ?>