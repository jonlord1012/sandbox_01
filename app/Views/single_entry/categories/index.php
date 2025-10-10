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
               <a href="<?= site_url('single-entry/categories/create') ?>" class="btn btn-primary">
                  <i class="fas fa-plus"></i> <?= lang('SingleEntry.create_category') ?>
               </a>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="content">
   <div class="container-fluid">
      <!-- Filter -->
      <div class="card">
         <div class="card-header">
            <h3 class="card-title"><?= lang('App.filter') ?></h3>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-md-3">
                  <a href="<?= site_url('single-entry/categories') ?>"
                     class="btn btn-<?= !$filterType ? 'primary' : 'outline-primary' ?>">
                     <?= lang('App.all') ?>
                  </a>
                  <a href="<?= site_url('single-entry/categories?type=income') ?>"
                     class="btn btn-<?= $filterType == 'income' ? 'success' : 'outline-success' ?>">
                     <?= lang('SingleEntry.income') ?>
                  </a>
                  <a href="<?= site_url('single-entry/categories?type=expense') ?>"
                     class="btn btn-<?= $filterType == 'expense' ? 'danger' : 'outline-danger' ?>">
                     <?= lang('SingleEntry.expense') ?>
                  </a>
               </div>
            </div>
         </div>
      </div>

      <!-- Categories Table -->
      <div class="card">
         <div class="card-body p-0">
            <?php if (empty($categories)): ?>
            <div class="p-3 text-center"><?= lang('App.no_data_found') ?></div>
            <?php else: ?>
            <table class="table table-striped">
               <thead>
                  <tr>
                     <th><?= lang('App.name') ?></th>
                     <th><?= lang('SingleEntry.transaction_type') ?></th>
                     <th><?= lang('App.description') ?></th>
                     <th><?= lang('App.status') ?></th>
                     <th><?= lang('App.actions') ?></th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($categories as $category): ?>
                  <tr>
                     <td><?= esc($category['name']) ?></td>
                     <td>
                        <span class="badge bg-<?= $category['type'] == 'income' ? 'success' : 'danger' ?>">
                           <?= lang('SingleEntry.' . $category['type']) ?>
                        </span>
                     </td>
                     <td><?= esc($category['description']) ?: '-' ?></td>
                     <td>
                        <?php if ($category['is_active']): ?>
                        <span class="badge bg-success"><?= lang('App.active') ?></span>
                        <?php else: ?>
                        <span class="badge bg-danger"><?= lang('App.inactive') ?></span>
                        <?php endif; ?>
                     </td>
                     <td>
                        <div class="btn-group">
                           <a href="<?= site_url('single-entry/categories/edit/' . $category['id']) ?>"
                              class="btn btn-sm btn-primary">
                              <i class="fas fa-edit"></i>
                           </a>
                           <a href="<?= site_url('single-entry/categories/toggle-status/' . $category['id']) ?>"
                              class="btn btn-sm btn-<?= $category['is_active'] ? 'warning' : 'success' ?>"
                              onclick="return confirm('<?= $category['is_active'] ? lang('App.confirm_deactivate') : lang('App.confirm_activate') ?>')">
                              <i class="fas fa-<?= $category['is_active'] ? 'times' : 'check' ?>"></i>
                           </a>
                        </div>
                     </td>
                  </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
            <?php endif; ?>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>