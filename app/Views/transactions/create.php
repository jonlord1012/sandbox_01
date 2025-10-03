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
      <!-- Current Cart Items -->
      <div class="card">
         <div class="card-header">
            <h3 class="card-title"><?= lang('App.current_entry') ?></h3>
            <div class="card-tools">
               <button type="button" class="btn btn-tool" onclick="loadCart()">
                  <i class="fas fa-sync"></i> <?= lang('App.refresh') ?>
               </button>
            </div>
         </div>
         <div class="card-body p-0">
            <table class="table table-striped" id="cartTable">
               <thead>
                  <tr>
                     <th style="width: 5%"><?= lang('App.type') ?></th>
                     <th style="width: 30%"><?= lang('App.account') ?></th>
                     <th style="width: 50%"><?= lang('App.description') ?></th>
                     <th style="width: 10%"><?= lang('App.amount') ?></th>
                     <th style="width: 5%"><?= lang('App.actions') ?></th>
                  </tr>
               </thead>
               <tbody>
                  <!-- Cart items will be loaded here via AJAX -->
               </tbody>
               <tfoot id="cartTotals">
                  <!-- Totals will be calculated here -->
               </tfoot>
            </table>
         </div>
         <div class="card-footer">
            <form action="<?= site_url('transactions/postCart') ?>" method="post" id="postForm">
               <?= csrf_field() ?>
               <button type="submit" class="btn btn-success" id="postButton" disabled>
                  <i class="fas fa-check"></i> <?= lang('Accounting.post_transaction') ?>
               </button>

               <button type="button" class="btn btn-danger" onclick="clearAllCart()">
                  <i class="fas fa-times"></i> <?= lang('App.clear_all') ?>
               </button>
            </form>
         </div>
      </div>

      <!-- Add Entry Form -->
      <div class="card">
         <div class="card-header">
            <h3 class="card-title"><?= lang('App.add_new') ?></h3>
         </div>
         <div class="card-body">
            <form action="<?= site_url('transactions/addToCart') ?>" method="post" id="entryForm">
               <?= csrf_field() ?>

               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group">
                        <label><?= lang('App.date') ?> *</label>
                        <input type="date" name="transaction_date" class="form-control" value="<?= date('Y-m-d') ?>"
                           required>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label><?= lang('App.type') ?> *</label>
                        <select name="type" class="form-control" required>
                           <option value="debit"><?= lang('App.debit') ?></option>
                           <option value="credit"><?= lang('App.credit') ?></option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label><?= lang('App.account') ?> *</label>
                        <select name="account_id" class="form-control" required>
                           <option value="">-- <?= lang('App.select') . lang('App.account') ?> --</option>
                           <?php foreach ($accounts as $account) : ?>
                           <option value="<?= $account['id'] ?>">
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
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label><?= lang('App.reference') ?> ID</label>
                        <input type="text" name="reference_id" class="form-control" value="REF-<?= time() ?>">
                     </div>
                  </div>
               </div>

               <div class="form-group">
                  <label><?= lang('App.description') ?> *</label>
                  <textarea name="description" class="form-control" rows="2" required></textarea>
               </div>

               <button type="submit" class="btn btn-primary">
                  <i class="fas fa-plus"></i> <?= lang('App.add_transaction') ?>
               </button>
            </form>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function loadCart() {
   console.log('Loading cart...');

   fetch('<?= site_url('transactions/getCart') ?>')
      .then(response => {
         console.log('Response status:', response.status);
         if (!response.ok) {
            throw new Error('Network response was not ok');
         }
         return response.json();
      })
      .then(data => {
         console.log('Cart data received:', data);

         const tbody = document.querySelector('#cartTable tbody');
         const tfoot = document.querySelector('#cartTotals');
         const postButton = document.querySelector('#postButton');

         tbody.innerHTML = '';

         let totalDebit = 0;
         let totalCredit = 0;

         if (data.error) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">${data.error}</td></tr>`;
            return;
         }

         if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center"><?= lang('App.empty_grid') ?></td></tr>';
            if (postButton) postButton.disabled = true;
            if (tfoot) tfoot.innerHTML = '';
            return;
         }

         data.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                    <td><span class="badge bg-${item.type === 'debit' ? 'danger' : 'success'}">${item.type.toUpperCase()}</span></td>
                    <td>${item.account_code} - ${item.account_name}</td>
                    <td>${item.description}</td>
                    <td class="text-right">${parseFloat(item.amount).toFixed(2)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteCartItem(${item.id})" title="Delete this entry">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
            tbody.appendChild(row);

            if (item.type === 'debit') {
               totalDebit += parseFloat(item.amount);
            } else {
               totalCredit += parseFloat(item.amount);
            }
         });

         if (tfoot) {
            tfoot.innerHTML = `
                    <tr>
                        <th colspan="3">TOTALS</th>
                        <th class="text-right">${totalDebit.toFixed(2)}</th>
                        <th class="text-right">${totalCredit.toFixed(2)}</th>
                    </tr>
                    <tr class="${totalDebit === totalCredit ? 'table-success' : 'table-danger'}">
                        <th colspan="3">BALANCE</th>
                        <th colspan="3" class="text-center">${(totalDebit - totalCredit).toFixed(2)}</th>
                    </tr>
                `;
         }

         // Enable post button if balanced
         if (postButton) {
            postButton.disabled = totalDebit !== totalCredit;
         }
      })
      .catch(error => {
         console.error('Error loading cart:', error);
         const tbody = document.querySelector('#cartTable tbody');
         tbody.innerHTML =
            `<tr><td colspan="4" class="text-center text-danger">Error loading cart: ${error.message}</td></tr>`;
      });
}

// Function to delete individual cart item
function deleteCartItem(itemId) {
   if (!confirm('Are you sure you want to delete this entry?')) {
      return;
   }

   fetch('<?= site_url('transactions/deleteCartItem/') ?>' + itemId)
      .then(response => {
         if (!response.ok) {
            throw new Error('Failed to delete item');
         }
         return response.text();
      })
      .then(() => {
         console.log('Item deleted successfully');
         loadCart(); // Reload the cart to show updated list
      })
      .catch(error => {
         console.error('Error deleting item:', error);
         alert('Error deleting entry: ' + error.message);
      });
}

// Enhanced clear all function with confirmation
function clearAllCart() {
   if (!confirm('Are you sure you want to clear ALL entries? This cannot be undone.')) {
      return;
   }

   window.location.href = '<?= site_url('transactions/clearCart') ?>';
}

// Load cart on page load
document.addEventListener('DOMContentLoaded', function() {
   console.log('Page loaded, loading cart...');
   loadCart();
});

// Refresh cart after adding new entry
document.querySelector('#entryForm').addEventListener('submit', function(e) {
   e.preventDefault();
   console.log('Adding to cart...');

   fetch(this.action, {
         method: 'POST',
         body: new FormData(this)
      })
      .then(response => {
         if (!response.ok) {
            throw new Error('Failed to add to cart');
         }
         return response.text();
      })
      .then(text => {
         console.log('Add to cart response:', text);
         loadCart();
         this.reset();
         // Update reference ID with new timestamp
         document.querySelector('input[name="reference_id"]').value = 'REF-' + Date.now();
      })
      .catch(error => {
         console.error('Error adding to cart:', error);
         alert('Error adding entry: ' + error.message);
      });
});
</script>

<?= $this->endSection() ?>