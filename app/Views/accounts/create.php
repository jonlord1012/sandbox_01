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
         <div class="card-body">
            <!-- Display Form Validation Errors -->
            <?php if (isset($errors)) : ?>
            <div class="alert alert-danger">
               <ul>
                  <?php foreach ($errors as $error) : ?>
                  <li><?= esc($error) ?></li>
                  <?php endforeach; ?>
               </ul>
            </div>
            <?php endif; ?>

            <form action="<?= site_url('accounts/store') ?>" method="post">
               <?= csrf_field() ?>

               <div class="form-group">
                  <label for="group_id">Group *</label>
                  <select class="form-control" name="group_id" id="group_id" required>
                     <option value="">-- Select Group --</option>
                     <?php foreach ($groups as $group) : ?>
                     <option value="<?= $group['id'] ?>" <?= old('group_id') == $group['id'] ? 'selected' : '' ?>>
                        [<?= $group['code'] ?>] <?= $group['name'] ?> (<?= $group['category'] ?>)
                     </option>
                     <?php endforeach; ?>
                  </select>
               </div>

               <div class="form-group">
                  <label for="code">Account Code *</label>
                  <input type="text" class="form-control" name="code" id="code" value="<?= old('code') ?>"
                     placeholder="e.g., A-1010" required>
               </div>

               <div class="form-group">
                  <label for="name">Account Name *</label>
                  <input type="text" class="form-control" name="name" id="name" value="<?= old('name') ?>" required>
               </div>

               <div class="form-group">
                  <label for="is_debit">Normal Balance *</label>
                  <select class="form-control" name="is_debit" id="is_debit" required>
                     <option value="1" <?= old('is_debit') == '1' ? 'selected' : '' ?>>Debit</option>
                     <option value="0" <?= old('is_debit') == '0' ? 'selected' : '' ?>>Credit</option>
                  </select>
               </div>

               <div class="form-group">
                  <label for="description">Description</label>
                  <textarea class="form-control" name="description" id="description"
                     rows="3"><?= old('description') ?></textarea>
               </div>

               <div class="form-group">
                  <div class="custom-control custom-checkbox">
                     <input class="custom-control-input" type="checkbox" name="is_active" id="is_active" value="1"
                        checked>
                     <label for="is_active" class="custom-control-label">Account is Active</label>
                  </div>
               </div>

               <div class="form-group">
                  <button type="submit" class="btn btn-primary">Create Account</button>
                  <a href="<?= site_url('accounts') ?>" class="btn btn-secondary">Cancel</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>