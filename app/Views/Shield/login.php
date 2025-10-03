<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Login - BJS Accounting</title>
   <link rel="icon" href="<?= base_url('binjava_kufi.ico') ?>" />
   <!-- AdminLTE Assets -->
   <link rel="stylesheet" href="<?= base_url('template/css/jakartasans.css') ?>">
   <link rel="stylesheet" href="<?= base_url('template/plugins/fontawesome-free/css/all.min.css') ?>">
   <!-- Theme style -->
   <link rel="stylesheet" href="<?= base_url('template/css/adminlte.min.css') ?>">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="<?= base_url('template/css/custom.css') ?>">
</head>

<body class="hold-transition login-page">
   <div class="login-box">
      <div class="login-logo">
         <a href="<?= site_url('/') ?>"><b>BJS</b>Accounting</a>
      </div>

      <div class="card">
         <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <?php if (session('error') !== null) : ?>
            <div class="alert alert-danger" role="alert"><?= esc(session('error')) ?></div>
            <?php elseif (session('errors') !== null) : ?>
            <div class="alert alert-danger" role="alert">
               <?php if (is_array(session('errors'))) : ?>
               <?php foreach (session('errors') as $error) : ?>
               <?= esc($error) ?>
               <br>
               <?php endforeach ?>
               <?php else : ?>
               <?= esc(session('errors')) ?>
               <?php endif ?>
            </div>
            <?php endif ?>

            <?php if (session('message') !== null) : ?>
            <div class="alert alert-success"><?= session('message') ?></div>
            <?php endif ?>

            <form action="<?= site_url('login') ?>" method="post">
               <?= csrf_field() ?>

               <div class="input-group mb-3">
                  <input type="text" name="username" class="form-control" placeholder="Username or Email"
                     value="<?= old('username') ?>" required>
                  <div class="input-group-append">
                     <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                     </div>
                  </div>
                  <?php if (session('errors.login')) : ?>
                  <div class="invalid-feedback"><?= session('errors.login') ?></div>
                  <?php endif ?>
               </div>

               <div class="input-group mb-3">
                  <input type="password" name="password" class="form-control" placeholder="Password" required>
                  <div class="input-group-append">
                     <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                     </div>
                  </div>
                  <?php if (session('errors.password')) : ?>
                  <div class="invalid-feedback"><?= session('errors.password') ?></div>
                  <?php endif ?>
               </div>

               <div class="row">
                  <div class="col-8">
                     <?php if (setting('Auth.sessionConfig')['allowRemembering']) : ?>
                     <div class="icheck-primary">
                        <input type="checkbox" id="remember" name="remember" <?= old('remember') ? 'checked' : '' ?>>
                        <label for="remember">Remember Me</label>
                     </div>
                     <?php endif; ?>
                  </div>

                  <div class="col-4">
                     <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                  </div>
               </div>
            </form>

            <?php if (setting('Auth.allowRegistration')) : ?>
            <p class="mb-1">
               <a href="<?= url_to('register') ?>">Register a new membership</a>
            </p>
            <?php endif; ?>
         </div>
      </div>
   </div>

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

</html>