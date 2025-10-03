<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1 class="m-0"><?= $title ?></h1>
         </div>
      </div>
   </div>
</div>

<div class="content">
   <div class="container-fluid">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Manage Groups for <strong><?= esc($user->username) ?></strong></h3>
         </div>
         <div class="card-body">
            <form action="<?= site_url('users/update/' . $user->id) ?>" method="post">
               <?= csrf_field() ?>

               <div class="form-group">
                  <label>User Groups</label><br>
                  <?php foreach ($allGroups as $group) : ?>
                  <div class="form-check">
                     <input class="form-check-input" type="checkbox" name="groups[]" value="<?= $group ?>"
                        id="group-<?= $group ?>" <?= in_array($group, $userGroups) ? 'checked' : '' ?>>
                     <label class="form-check-label" for="group-<?= $group ?>">
                        <?= esc($group) ?>
                     </label>
                  </div>
                  <?php endforeach; ?>
               </div>

               <div class="form-group">
                  <button type="submit" class="btn btn-primary">Update User</button>
                  <a href="<?= site_url('users') ?>" class="btn btn-secondary">Cancel</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>