<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1 class="m-0"><?= $title ?></h1>
         </div>
         <div class="col-sm-6">
            <a href="<?= site_url('single_entry/create') ?>" class="btn btn-primary float-right">
               <i class="fas fa-plus"></i> New Transaction
            </a>
         </div>
      </div>
   </div>
</div>

<div class="content">
   <div class="container-fluid">
      <!-- Filter Form -->
      <div class="card card-info">
         <div class="card-header">
            <h3 class="card-title">Filter Transactions</h3>
         </div>
         <div class="card-body">
            <form method="get" action="<?= site_url('transactions') ?>">
               <div class="row">
                  <div class="col-md-5">
                     <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="<?= $startDate ?>">
                     </div>
                  </div>
                  <div class="col-md-5">
                     <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" value="<?= $endDate ?>">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-info btn-block">Filter</button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>

      <!-- Transactions List -->
      <div class="card">
         <div class="card-body">
            <?php if (session()->getFlashdata('message')) : ?>
            <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
            <?php endif; ?>

            <table class="table table-bordered table-striped">
               <thead>
                  <tr>
                     <th>Date</th>
                     <th>Reference</th>
                     <th>Status</th>
                     <th>Description</th>
                     <th>Entries</th>
                     <th>Total Debit</th>
                     <th>Total Credit</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($transactions as $trans) : ?>
                  <tr class="<?= $trans['total_debit'] === $trans['total_credit'] ? '' : 'table-warning' ?>">
                     <td><?= $trans['transaction_date'] ?></td>
                     <td>
                        <code><?= $trans['reference_id'] ?></code>
                        <?php if ($trans['total_debit'] !== $trans['total_credit']) : ?>
                        <span class="badge badge-danger ml-1" title="Unbalanced transaction">!</span>
                        <?php endif; ?>
                     </td>
                     <td>
                        <code><?= $trans['status'] ?></code>
                        <?php if ($trans['status'] !== 'Posted') : ?>
                        <span class="badge badge-danger ml-1" title="Un-Posted">!</span>
                        <?php endif; ?>
                     </td>
                     <td><?= esc($trans['description']) ?></td>
                     <td class="text-center"><span class="badge bg-info"><?= $trans['entry_count'] ?></span></td>
                     <td class="text-right"><?= number_format($trans['total_debit'], 2) ?></td>
                     <td class="text-right"><?= number_format($trans['total_credit'], 2) ?></td>
                     <td>
                        <div class="btn-group">
                           <a href="<?= site_url($trans['link'] . $trans['reference_id']) ?>"
                              class="btn btn-sm btn-info">
                              <i class="fas fa-eye"></i> View
                           </a>
                           <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                              <span class="sr-only">Toggle Dropdown</span>
                           </button>
                           <div class="dropdown-menu">
                              <a class="dropdown-item" href="<?= site_url($trans['link']  . $trans['reference_id']) ?>">
                                 <i class="fas fa-eye mr-2"></i>View Details
                              </a>
                              <a class="dropdown-item"
                                 href="<?= site_url('transactions/reverse/' . $trans['reference_id']) ?>">
                                 <i class="fas fa-undo mr-2"></i>Reverse Transaction
                              </a>
                           </div>
                        </div>
                     </td>
                  </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>