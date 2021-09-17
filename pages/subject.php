 <?php session_start();
 if(empty($_SESSION['id'])):
  header('Location:../index.php');
endif;
//error_reporting(0);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Subject | <?php include('../dist/includes/title.php');?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../plugins/select2/select2.min.css">
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="../dist/css/main.css">
  <script src="../dist/js/jquery.min.js"></script>

</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-yellow layout-top-nav" onload="myFunction()">
  <div class="wrapper">
    <?php include('../dist/includes/header.php');?>
    <!-- Full Width Column -->
    <div class="content-wrapper">
      <div class="container">
        <section class="content">
          <div class="row">
            <div class="col-md-1 mr-50">
            </div>
            <div class="col-md-3">
              <?php include('../dist/includes/nav.php')?>
            </div>
          </div>
          <div class="row mt-13">
            <div class="col-md-2">
            </div>
            <div class="col-md-6">
              <?php include('../dist/includes/entry_nav.php')?>
            </div>
          </div>
          <div class="row mt-13">
           <div class="col-md-2"></div>
           <div class="col-md-6">
            <div class="box border-1">
              <div class="box-body">
                <div class="row">
                 <div class="col-md-12">
                  <table id="example1" class="table table-bordered table-striped border-1" style="margin-right:-10px">
                    <thead>
                      <tr>
                        <th>Subject Code</th>
                        <th>Subject Title</th>
                        <th>Units</th>
                        <th>Prerequisite</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <?php
                    session_start();
                    $member=$_SESSION['id'];
                    include('../dist/includes/dbcon.php');
                    $query=mysqli_query($con,"select * from subject order by subject_code")or die(mysqli_error());

                    while($row=mysqli_fetch_array($query)){
                      $id = $row['subject_id'];
                      $code = $row['subject_code'];
                      $title = $row['subject_title'];
                      $units = $row['subject_units'];
                      $prerequisite = $row['prerequisite'];
                      $mid = $row['member_id'];
                      ?>
                      <tr>
                        <td><?php echo $code;?></td>
                        <td><?php echo $title;?></td> 
                        <td><?php echo $units;?></td> 
                        <td><?php echo $prerequisite;?></td> 
                        <td>
                          <?php 
                          if ($member==$mid)  
                          {
                          ?>
                            <a id="click" class="mr-5" href="subject.php?id=<?php echo $id;?>&code=<?php echo $code;?>&title=<?php echo $title;?>&units=<?php echo $units;?>&prerequisite=<?php echo $prerequisite;?>">
                              <i class="glyphicon glyphicon-edit text-black"></i>
                              <span class="text-black">Edit</span>
                            </a>
                            <a id="removeme" href="../logic/subject_del.php?id=<?php echo $id;?>">
                              <i class="glyphicon glyphicon-remove-circle text-black"></i>
                              <span class="text-black">Delete</span>
                            </a>
                          <?php
                          }
                          ?>
                        </td>
                      </tr>
                    <?php }?>           
                  </table>
                </div><!--col end -->   
              </div><!--row end-->     
            </div><!-- /.box-body -->
          </div><!-- /.box -->
        </div><!-- /.col (right) -->
        <div class="col-md-2">
          <div class="box border-1">
            <div class="box-body">
              <form method="post" action="../logic/subject_save.php">
                <div id="form">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                       <span>Add Subject</span><br>
                       <input type="text" class="form-control border-1 mt-5" name="code" placeholder="Subject Code" required>
                       <input type="text" class="form-control border-1 mt-13" name="title" placeholder="Subject Title" required>
                       <input type="text" class="form-control border-1 mt-13" name="units" placeholder="Subject Units" required>
                       <input type="text" class="form-control border-1 mt-13" name="prerequisite" placeholder="Subject Prerequisite" required>
                     </div><!-- /.form group -->
                   </div>
                 </div>	
                 <div class="form-group">
                  <div class="btn-group w-100" role="group" aria-label="First group">
                    <button class="btn w-50 btn-flat btn-primary btn-secondary" id="daterange-btn" name="save" type="submit">Save</button>
                    <button class="btn w-50 btn-flat btn-secondary" id="daterange-btn" type="reset">Cancel</button>
                  </div>
               </div>
             </div><!-- /.form group -->
           </form>	
         </div><!-- /.box-body -->
         <div class="box-body" style="" id="displayform">
          <form method="post" action="../logic/subject_update.php">
            <div id="form">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                   <span>Update Subject</span><br>
                   <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_REQUEST['id'];?>">
                   <input type="text" class="form-control border-1 mt-5" id="code" name="code" value="<?php echo $_REQUEST['code'];?>" placeholder="Subject Code">
                   <input type="text" class="form-control border-1 mt-13" id="class" name="title" value="<?php echo $_REQUEST['title'];?>" placeholder="Subject Title" required>
                   <input type="text" class="form-control border-1 mt-5" id="units" name="units" value="<?php echo $_REQUEST['units'];?>" placeholder="Subject units">
                   <input type="text" class="form-control border-1 mt-13" id="prerequisite" name="prerequisite" value="<?php echo $_REQUEST['prerequisite'];?>" placeholder="Subject prerequisite" required>
                 </div><!-- /.form group -->
               </div>
             </div>	
              <button class="btn btn-block btn-flat btn-primary" id="daterange-btn" name="save" type="submit">Update</button>
          </div><!-- /.form group -->
        </form>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <div class="mt-13">
      <span class="ml-5 glyphicon glyphicon-edit"></span>
      <span class="ml-5">Log:</span>
      <div>
        <textarea rows="10" class="w-100 log border-1" readonly><?php echo $_SESSION['logs'];?></textarea>
      </div>
    </div>
  </div><!-- /.col (right) -->
</div><!-- /.row -->
</section><!-- /.content -->
</div><!-- /.container -->
</div><!-- /.content-wrapper -->
<?php include('../dist/includes/footer.php');?>
</div><!-- ./wrapper -->

<script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="../dist/js/jquery.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../plugins/select2/select2.full.min.js"></script>
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
<script>
  $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();

        //Datemask dd/mm/yyyy
        $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
        //Datemask2 mm/dd/yyyy
        $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
        //Money Euro
        $("[data-mask]").inputmask();

        //Date range picker
        $('#reservation').daterangepicker();
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
        //Date range as a button
        $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        );

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });

        //Colorpicker
        $(".my-colorpicker1").colorpicker();
        //color picker with addon
        $(".my-colorpicker2").colorpicker();

        //Timepicker
        $(".timepicker").timepicker({
          showInputs: false
        });
      });
    </script>
  </body>
  </html>
