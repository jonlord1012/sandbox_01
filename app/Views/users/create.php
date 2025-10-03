<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1 class="m-0"><?= $title ?></h1>
         </div>
         <div class="col-sm-6">
            <a href="<?= site_url('users') ?>" class="btn btn-secondary float-right">
               <i class="fas fa-arrow-left"></i> Back to Users
            </a>
         </div>
      </div>
   </div>
</div>

<div class="content">
   <div class="container-fluid">
      <div class="card">
         <div class="card-body">
            <?php if (isset($errors)) : ?>
            <div class="alert alert-danger">
               <ul class="mb-0">
                  <?php foreach ($errors as $error) : ?>
                  <li><?= esc($error) ?></li>
                  <?php endforeach; ?>
               </ul>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <?php if (session('validation')) : ?>
            <div class="alert alert-danger">
               <?= session('validation')->listErrors() ?>
            </div>
            <?php endif; ?>

            <form action="<?= site_url('users/store') ?>" method="post">
               <?= csrf_field() ?>

               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" name="username" class="form-control" value="<?= old('username') ?>"
                           placeholder="Enter username" required>
                        <small class="form-text text-muted">3-30 characters, letters and numbers only</small>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email') ?>"
                           placeholder="Enter email address" required>
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter password"
                           required>
                        <small class="form-text text-muted">Minimum 8 characters</small>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="password_confirm">Confirm Password *</label>
                        <input type="password" name="password_confirm" class="form-control"
                           placeholder="Confirm password" required>
                     </div>
                  </div>
               </div>

               <div class="form-group">
                  <label>Assign Groups</label><br>
                  <?php foreach ($allGroups as $group) : ?>
                  <div class="form-check form-check-inline">
                     <input class="form-check-input" type="checkbox" name="groups[]" value="<?= $group ?>"
                        id="group-<?= $group ?>"
                        <?= (in_array($group, old('groups', [])) || $group === 'user') ? 'checked' : '' ?>>
                     <label class="form-check-label" for="group-<?= $group ?>">
                        <span
                           class="badge badge-<?= $group === 'superadmin' ? 'danger' : ($group === 'admin' ? 'warning' : 'info') ?>">
                           <?= esc($group) ?>
                        </span>
                     </label>
                  </div>
                  <?php endforeach; ?>
               </div>

               <div class="form-group">
                  <div class="custom-control custom-checkbox">
                     <input type="checkbox" class="custom-control-input" id="send_welcome" name="send_welcome"
                        value="1">
                     <label class="custom-control-label" for="send_welcome">
                        Send welcome email with login instructions
                     </label>
                  </div>
               </div>

               <div class="form-group">
                  <button type="submit" class="btn btn-primary">
                     <i class="fas fa-user-plus"></i> Create User
                  </button>
                  <a href="<?= site_url('users') ?>" class="btn btn-secondary">Cancel</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>