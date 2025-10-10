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
               <a href="<?= site_url('single-entry') ?>" class="btn btn-secondary">
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
               action="<?= isset($transaction) ? site_url('single-entry/update/' . $transaction['id']) : site_url('single-entry/store') ?>">
               <?= csrf_field() ?>

               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group">
                        <label><?= lang('App.date') ?> *</label>
                        <input type="date" name="transaction_date" class="form-control"
                           value="<?= old('transaction_date', isset($transaction) ? $transaction['transaction_date'] : date('Y-m-d')) ?>"
                           required>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label><?= lang('SingleEntry.transaction_type') ?> *</label>
                        <select name="type" class="form-control" id="transactionType" required>
                           <option value="">-- <?= lang('App.select') . ' ' . lang('SingleEntry.transaction_type') ?> --
                           </option>
                           <option value="income"
                              <?= old('type', isset($transaction) ? $transaction['type'] : '') == 'income' ? 'selected' : '' ?>>
                              <?= lang('SingleEntry.income') ?>
                           </option>
                           <option value="expense"
                              <?= old('type', isset($transaction) ? $transaction['type'] : '') == 'expense' ? 'selected' : '' ?>>
                              <?= lang('SingleEntry.expense') ?>
                           </option>
                           <option value="transfer"
                              <?= old('type', isset($transaction) ? $transaction['type'] : '') == 'transfer' ? 'selected' : '' ?>>
                              <?= lang('SingleEntry.transfer') ?>
                           </option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label><?= lang('App.account') ?> *</label>
                        <select name="account_id" class="form-control" required>
                           <option value="">-- <?= lang('App.select') . ' ' . lang('App.account') ?> --</option>
                           <?php foreach ($accounts as $account): ?>
                           <option value="<?= $account['id'] ?>"
                              <?= old('account_id', isset($transaction) ? $transaction['account_id'] : '') == $account['id'] ? 'selected' : '' ?>>
                              [<?= $account['code'] ?>] <?= $account['name'] ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label><?= lang('App.amount') ?> *</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01"
                           value="<?= old('amount', isset($transaction) ? $transaction['amount'] : '') ?>"
                           placeholder="0.00" required>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label><?= lang('App.category') ?></label>
                        <select name="category" class="form-control" id="categorySelect">
                           <option value="">-- <?= lang('App.select_category') ?> --</option>
                           <!-- Categories will be populated by JavaScript based on transaction type -->
                        </select>
                     </div>
                  </div>
               </div>

               <div class="form-group">
                  <label><?= lang('App.description') ?> *</label>
                  <textarea name="description" class="form-control" rows="3"
                     placeholder="<?= lang('App.enter_description') ?>"
                     required><?= old('description', isset($transaction) ? $transaction['description'] : '') ?></textarea>
               </div>

               <div class="form-group">
                  <button type="submit" class="btn btn-primary">
                     <i class="fas fa-save"></i> <?= isset($transaction) ? lang('App.update') : lang('App.save') ?>
                  </button>
                  <a href="<?= site_url('single-entry') ?>" class="btn btn-secondary">
                     <i class="fas fa-times"></i> <?= lang('App.cancel') ?>
                  </a>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Load categories based on transaction type
document.getElementById('transactionType').addEventListener('change', function() {
   const type = this.value;
   const categorySelect = document.getElementById('categorySelect');

   if (type && type !== 'transfer') {
      // Fetch categories for the selected type
      fetch(`/single-entry/categories?type=${type}&format=json`)
         .then(response => response.json())
         .then(categories => {
            categorySelect.innerHTML = '<option value="">-- <?= lang('App.select_category') ?> --</option>';
            categories.forEach(category => {
               const option = document.createElement('option');
               option.value = category.name;
               option.textContent = category.name;
               categorySelect.appendChild(option);
            });
         })
         .catch(error => {
            console.error('Error loading categories:', error);
         });
   } else {
      categorySelect.innerHTML = '<option value="">-- <?= lang('App.select_category') ?> --</option>';
   }
});

// Trigger change on page load if type is already selected
document.addEventListener('DOMContentLoaded', function() {
   const typeSelect = document.getElementById('transactionType');
   if (typeSelect.value) {
      typeSelect.dispatchEvent(new Event('change'));
   }
});
</script>
<?= $this->endSection() ?>