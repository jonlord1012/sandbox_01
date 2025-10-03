<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>BJS Accounting - <?= $title ?? 'Dashboard' ?></title>
   <link rel="icon" href="<?= base_url('binjava_kufi.ico') ?>" />

   <!-- Google Font: Source Sans Pro -->
   <link rel="stylesheet" href="<?= base_url('template/css/jakartasans.css') ?>">

   <!-- Font Awesome Icons -->
   <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
   <link rel="stylesheet" href="<?= base_url('template/plugins/fontawesome-free/css/all.min.css') ?>">
   <!-- Theme style -->
   <link rel="stylesheet" href="<?= base_url('template/css/adminlte.min.css') ?>">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="<?= base_url('template/css/custom.css') ?>">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
   <div class="wrapper">

      <!-- Navbar -->
      <?= $this->include('layouts/partials/navbar') ?>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <?= $this->include('layouts/partials/sidebar') ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
         <!-- We will output the main page content here -->
         <?= $this->renderSection('content') ?>
      </div>
      <!-- /.content-wrapper -->

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
         <!-- Control sidebar content goes here -->
         <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
         </div>
      </aside>
      <!-- /.control-sidebar -->

      <!-- Main Footer -->
      <?= $this->include('layouts/partials/footer') ?>
   </div>
   <!-- ./wrapper -->

   <!-- REQUIRED SCRIPTS -->
   <!-- jQuery -->
   <script src="<?= base_url('template/plugins/jquery/jquery.min.js') ?>"></script>
   <!-- Bootstrap 4 -->
   <script src="<?= base_url('template/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
   <!-- AdminLTE App -->
   <script src="<?= base_url('template/js/adminlte.min.js') ?>"></script>
   <!-- Custom JS -->
   <script src="<?= base_url('template/js/custom.js') ?>"></script>
</body>
<?= $this->renderSection('scripts') ?>

</html>