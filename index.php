<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login - <?php include('dist/includes/title.php');?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="dist/css/main.css">

  </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="hold-transition login-page" style="background:#ffffff">
   
    <div class="login-box">
      <div class="login-logo">
        <h1>Class Scheduling<br>System STI</h1>
      </div><!-- /.login-logo -->
      <div class="login-box-body border-1">
        <!-- <p class="login-box-msg">Sign in to start your session</p> -->
        <form action="login.php" method="post">
          <span>Username</span>
          <div class="form-group has-feedback">
            <input type="text" class="form-control input" placeholder="Username" name="username">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <span>Password</span>
          <div class="form-group has-feedback">
            <input type="password" class="form-control input" placeholder="Password" name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-6">
              <button type="submit" class="btn btn-primary btn-block btn-flat" name="login" default>Sign In</button>
            </div><!-- /.col -->
            <div class="col-xs-6">
              <button type="reset" class="btn btn-block btn-flat">Clear</button>
            </div><!-- /.col -->
          </div>
        </form>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
      
    <img src="dist/img/logo.jpg" class="img-logo">
   
<!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
  </body>
</html>
