<?php session_start();
if(empty($_SESSION['id'])):
  header('Location:../index.php');
endif;?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Account Details | <?php include('../dist/includes/title.php');?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="../dist/css/main.css">
 </head>
 <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
 <body class="hold-transition skin-yellow layout-top-nav">
  <div class="wrapper">
    <?php include('../dist/includes/header.php');
    include('../dist/includes/dbcon.php');
    ?>
    <!-- Full Width Column -->
    <div class="content-wrapper">
      <div class="container">
        <?php
        $id=$_SESSION['id'];
        $query=mysqli_query($con,"select * from member where member_id='$id'")or die(mysqli_error());
        $row=mysqli_fetch_array($query);
        ?>	
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-3">
              <?php include('../dist/includes/nav.php')?>
            </div>
          </div>
          <div class="row mt-13">
            <div class="col-md-2"></div>
            <div class="col-md-8">
            <div class="box border-1">
              <div class="row mt-13">
                <a href="home.php" class="text-black float-right"> <span class="glyphicon glyphicon-circle-arrow-left"></span> BACK</a>
              </div>
              <div class="row text-center">
                <img src="../dist/img/user.png" class="img-user"><br>
                <span class="section-title"><?php echo $row['member_rank']?></span><br>
                <span><?php echo $row['member_salut']?> <?php echo $row['member_first']?> <?php echo $row['member_last']?></span>
              </div>
              <div class="box-header">
                <span class="section-title">Update Account Details:</span>
              </div>
              <div class="box-body">
                <!-- Date range -->
                <form method="post" action="profile_update.php">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3">
                        <span>Full Name</span>
                      </div>
                      <div class="col-md-5">
                        <div class="input-group col-md-12">
                          <input type="text" class="form-control border-1 pull-right" value="<?php echo $row['member_first']." ".$row['member_last'];?>" name="name" placeholder="Full Name" required readonly>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3">
                        <span>Username</span>
                      </div>
                      <div class="col-md-5">
                        <div class="input-group col-md-12">
                          <input type="text" class="form-control border-1 pull-right" value="<?php echo $row['username'];?>" name="username" placeholder="Username" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3">
                        <span>Change Password</span>
                      </div>
                      <div class="col-md-5">
                        <div class="input-group col-md-12">
                          <input type="password" class="form-control border-1 pull-right" id="date" name="password" placeholder="Type new password">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3">
                        <span>Confirm New Password</span>
                      </div>
                      <div class="col-md-5">
                        <div class="input-group col-md-12">
                          <input type="password" class="form-control border-1 pull-right" id="date" name="new" placeholder="Type new password">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3">
                        <span>Enter Old Password to confirm changes</span>
                      </div>
                      <div class="col-md-5">
                        <div class="input-group col-md-12">
                          <input type="password" class="form-control border-1 pull-right" id="date" name="passwordold" placeholder="Type old password" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-1 pr-0">
                      <button class="btn btn-primary btn-block btn-flat">Save</button>
                    </div>
                    <div class="col-xs-1 pr-0">
                      <button class="btn btn-block btn-flat" type="reset">Clear</button>
                    </div>
                  </div>
                </form>	
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div><!-- /.col (right) -->
        </div><!-- /.row -->
      </section><!-- /.content -->
    </div><!-- /.container -->
  </div><!-- /.content-wrapper -->
  <?php include('../dist/includes/footer.php');?>
</div><!-- ./wrapper -->

<!-- jQuery 2.1.4 -->
<script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
</body>
</html>
