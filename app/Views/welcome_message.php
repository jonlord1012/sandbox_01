<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-12 text-center">
            <h1 class="m-0">Welcome to BJS Accounting System</h1>
            <p class="lead">Simple yet powerful accounting for your neighbourhood</p>
         </div>
      </div>
   </div>
</div>

<div class="content">
   <div class="container-fluid">
      <div class="row justify-content-center">
         <div class="col-lg-8 text-center">

            <!-- Call to Action Buttons -->
            <div class="mb-5">
               <a href="<?= site_url('register') ?>" class="btn btn-primary btn-lg mr-3">
                  <i class="fas fa-user-plus mr-2"></i> Get Started
               </a>
               <a href="<?= site_url('login') ?>" class="btn btn-outline-secondary btn-lg">
                  <i class="fas fa-sign-in-alt mr-2"></i> Sign In
               </a>
            </div>

            <!-- Feature Highlights -->
            <div class="row">
               <div class="col-md-4 mb-4">
                  <div class="card">
                     <div class="card-body text-center">
                        <i class="fas fa-book fa-3x text-primary mb-3"></i>
                        <h5>Double-Entry Accounting</h5>
                        <p class="text-muted">Professional accounting rules built right in.</p>
                     </div>
                  </div>
               </div>
               <div class="col-md-4 mb-4">
                  <div class="card">
                     <div class="card-body text-center">
                        <i class="fas fa-chart-pie fa-3x text-success mb-3"></i>
                        <h5>Financial Reports</h5>
                        <p class="text-muted">Generate Balance Sheets and Income Statements.</p>
                     </div>
                  </div>
               </div>
               <div class="col-md-4 mb-4">
                  <div class="card">
                     <div class="card-body text-center">
                        <i class="fas fa-users fa-3x text-info mb-3"></i>
                        <h5>Multi-User</h5>
                        <p class="text-muted">Manage permissions for different users.</p>
                     </div>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>