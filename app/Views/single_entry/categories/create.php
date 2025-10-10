<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1 class="m-0"><?= $title ?></h1>
         </div>
         <div class="col-sm-6">
            <div class="float-sm-right">
               <a href="<?= site_url('single-entry/categories') ?>" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> <?= lang('App.back') ?>
               </a>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="content">
   <div class="container-fluid">
      <div class="card">
         <div class="card-body">
            <?php if (isset($errors)): ?>
            <div class="alert alert-danger">
               <ul class="mb-0">
                  <?php foreach ($errors as $error): ?>
                  <li><?= esc($error) ?></li>
                  <?php endforeach; ?>
               </ul>
            </div>
            <?php endif; ?>

            <form method="post"
               action="<?= isset($category) ? site_url('single-entry/categories/update/' . $category['id']) : site_url('single-entry/categories/store') ?>">
               <?= csrf_field() ?>

               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label><?= lang('App.name') ?> *</label>
                        <input type="text" name="name" class="form-control"
                           value="<?= old('name', isset($category) ? $category['name'] : '') ?>" required>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label><?= lang('SingleEntry.transaction_type') ?> *</label>
                        <select name="type" class="form-control" required <?= isset($category) ? 'disabled' : '' ?>>
                           <option value="">-- <?= lang('App.select') . ' ' . lang('SingleEntry.transaction_type') ?> --
                           </option>
                           <option value="income"
                              <?= old('type', isset($category) ? $category['type'] : '') == 'income' ? 'selected' : '' ?>>
                              <?= lang('SingleEntry.income') ?>
                           </option>
                           <option value="expense"
                              <?= old('type', isset($category) ? $category['type'] : '') == 'expense' ? 'selected' : '' ?>>
                              <?= lang('SingleEntry.expense') ?>
                           </option>
                        </select>
                        <?php if (isset($category)): ?>
                        <input type="hidden" name="type" value="<?= $category['type'] ?>">
                        <?php endif; ?>
                     </div>
                  </div>
               </div>

               <div class="form-group">
                  <label><?= lang('App.description') ?></label>
                  <textarea name="description" class="form-control"
                     rows="3"><?= old('description', isset($category) ? $category['description'] : '') ?></textarea>
               </div>

               <div class="form-group">
                  <button type="submit" class="btn btn-primary">
                     <i class="fas fa-save"></i> <?= isset($category) ? lang('App.update') : lang('App.save') ?>
                  </button>
                  <a href="<?= site_url('single-entry/categories') ?>" class="btn btn-secondary">
                     <i class="fas fa-times"></i> <?= lang('App.cancel') ?>
                  </a>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>