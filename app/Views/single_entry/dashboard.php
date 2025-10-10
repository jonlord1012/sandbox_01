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
               <a href="<?= site_url('single-entry/create') ?>" class="btn btn-primary">
                  <i class="fas fa-plus"></i> <?= lang('SingleEntry.create_single_entry') ?>
               </a>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="content">
   <div class="container-fluid">
      <!-- Summary Cards -->
      <div class="row">
         <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
               <div class="inner">
                  <h3><?= number_format($summary['income'], 2) ?></h3>
                  <p><?= lang('SingleEntry.total_income') ?></p>
               </div>
               <div class="icon">
                  <i class="fas fa-money-bill-wave"></i>
               </div>
               <a href="<?= site_url('single-entry?type=income') ?>" class="small-box-footer">
                  <?= lang('App.view_details') ?> <i class="fas fa-arrow-circle-right"></i>
               </a>
            </div>
         </div>
         <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
               <div class="inner">
                  <h3><?= number_format($summary['expense'], 2) ?></h3>
                  <p><?= lang('SingleEntry.total_expense') ?></p>
               </div>
               <div class="icon">
                  <i class="fas fa-receipt"></i>
               </div>
               <a href="<?= site_url('single-entry?type=expense') ?>" class="small-box-footer">
                  <?= lang('App.view_details') ?> <i class="fas fa-arrow-circle-right"></i>
               </a>
            </div>
         </div>
         <div class="col-lg-3 col-6">
            <div class="small-box bg-<?= $summary['net'] >= 0 ? 'info' : 'warning' ?>">
               <div class="inner">
                  <h3><?= number_format($summary['net'], 2) ?></h3>
                  <p><?= lang('SingleEntry.net_income') ?></p>
               </div>
               <div class="icon">
                  <i class="fas fa-chart-line"></i>
               </div>
               <a href="<?= site_url('single-entry') ?>" class="small-box-footer">
                  <?= lang('App.view_all') ?> <i class="fas fa-arrow-circle-right"></i>
               </a>
            </div>
         </div>
         <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
               <div class="inner">
                  <h3><?= count($recentTransactions) ?></h3>
                  <p><?= lang('SingleEntry.unposted_transactions') ?></p>
               </div>
               <div class="icon">
                  <i class="fas fa-file-invoice"></i>
               </div>
               <a href="<?= site_url('single-entry?is_posted=0') ?>" class="small-box-footer">
                  <?= lang('App.view_details') ?> <i class="fas fa-arrow-circle-right"></i>
               </a>
            </div>
         </div>
      </div>

      <!-- Filter Form -->
      <div class="card">
         <div class="card-header">
            <h3 class="card-title"><?= lang('App.filter') ?></h3>
         </div>
         <div class="card-body">
            <form method="get" action="<?= site_url('single-entry/dashboard') ?>">
               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group">
                        <label><?= lang('App.start_date') ?></label>
                        <input type="date" name="start_date" class="form-control" value="<?= $filters['start_date'] ?>">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label><?= lang('App.end_date') ?></label>
                        <input type="date" name="end_date" class="form-control" value="<?= $filters['end_date'] ?>">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                           <i class="fas fa-filter"></i> <?= lang('App.filter') ?>
                        </button>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>&nbsp;</label>
                        <a href="<?= site_url('single-entry/dashboard') ?>" class="btn btn-secondary btn-block">
                           <i class="fas fa-redo"></i> <?= lang('App.reset') ?>
                        </a>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>

      <!-- Recent Transactions -->
      <div class="card">
         <div class="card-header">
            <h3 class="card-title"><?= lang('SingleEntry.recent_transactions') ?></h3>
            <div class="card-tools">
               <a href="<?= site_url('single-entry') ?>" class="btn btn-sm btn-primary">
                  <?= lang('App.view_all') ?>
               </a>
            </div>
         </div>
         <div class="card-body p-0">
            <?php if (empty($recentTransactions)): ?>
            <div class="p-3 text-center"><?= lang('App.no_data_found') ?></div>
            <?php else: ?>
            <table class="table table-striped">
               <thead>
                  <tr>
                     <th><?= lang('App.date') ?></th>
                     <th><?= lang('App.description') ?></th>
                     <th><?= lang('SingleEntry.transaction_type') ?></th>
                     <th><?= lang('App.account') ?></th>
                     <th class="text-right"><?= lang('App.amount') ?></th>
                     <th><?= lang('App.status') ?></th>
                     <th><?= lang('App.actions') ?></th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($recentTransactions as $transaction): ?>
                  <tr>
                     <td><?= date('d/m/Y', strtotime($transaction['transaction_date'])) ?></td>
                     <td><?= esc($transaction['description']) ?></td>
                     <td>
                        <span
                           class="badge bg-<?= $transaction['type'] == 'income' ? 'success' : ($transaction['type'] == 'expense' ? 'danger' : 'info') ?>">
                           <?= lang('SingleEntry.' . $transaction['type']) ?>
                        </span>
                     </td>
                     <td><?= $transaction['account_code'] ?> - <?= $transaction['account_name'] ?></td>
                     <td class="text-right"><?= number_format($transaction['amount'], 2) ?></td>
                     <td>
                        <?php if ($transaction['is_posted']): ?>
                        <span class="badge bg-success"><?= lang('SingleEntry.posted') ?></span>
                        <?php else: ?>
                        <span class="badge bg-warning"><?= lang('SingleEntry.draft') ?></span>
                        <?php endif; ?>
                     </td>
                     <td>
                        <div class="btn-group">
                           <?php if (!$transaction['is_posted']): ?>
                           <a href="<?= site_url('single-entry/post/' . $transaction['id']) ?>"
                              class="btn btn-sm btn-success" title="<?= lang('SingleEntry.post_to_double_entry') ?>"
                              onclick="return confirm('<?= lang('SingleEntry.post_transaction') ?>?')">
                              <i class="fas fa-check"></i>
                           </a>
                           <a href="<?= site_url('single-entry/edit/' . $transaction['id']) ?>"
                              class="btn btn-sm btn-primary">
                              <i class="fas fa-edit"></i>
                           </a>
                           <a href="<?= site_url('single-entry/delete/' . $transaction['id']) ?>"
                              class="btn btn-sm btn-danger"
                              onclick="return confirm('<?= lang('App.confirm_delete') ?>')">
                              <i class="fas fa-trash"></i>
                           </a>
                           <?php else: ?>
                           <span class="text-muted"><?= lang('App.no_actions') ?></span>
                           <?php endif; ?>
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