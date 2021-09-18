 <?php session_start();
 if(empty($_SESSION['id'])):
  header('Location:../index.php');
endif;
error_reporting(0);
include('../dist/includes/dbcon.php');

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Class | <?php include('../dist/includes/title.php');?></title>
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
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-1 mr-50">
            </div>
            <div class="col-md-3">
              <?php include('../dist/includes/nav.php')?>
            </div>
          </div>
          <div class="row mt-13">
            <div class="col-md-1 mr-50">
            </div>
            <div class="col-md-7">
              <div class="box border-1">
                <div class="box-body">
                  <div class="row">
                   <div class="col-md-12">
                    <table id="example1" class="table table-bordered table-striped border-1 result" style="margin-right:-10px">
                      <thead>
                        <tr>
                          <th>Time</th>
                          <th>Day</th>
                          <th>Teacher</th>
                          <th>Class</th>
                          <th>Subject</th>
                          <th>Room</th>
                          <th>Department</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <?php
                      $set_id = $_SESSION['settings'];
                      $query = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id = '$set_id' ORDER BY start_time")or die(mysqli_error($con));

                      if($_REQUEST['id']){
                        $set_id = $_REQUEST['id'];
                        $query = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id = '$set_id' ORDER BY start_time")or die(mysqli_error($con));
                      }

                      if($_REQUEST['member_id']){
                        $mem_id = $_REQUEST['member_id'];
                        $query = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id = '$set_id' AND member_id = '$mem_id' ORDER BY start_time")or die(mysqli_error($con));
                      }

                      if($_REQUEST['class_id']){
                        $class_id = $_REQUEST['class_id'];
                        $query = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id = '$set_id' AND cys = '$class_id' ORDER BY start_time")or die(mysqli_error($con));
                      }

                      if($_REQUEST['room_id']){
                        $room_id = $_REQUEST['room_id'];
                        $query = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id = '$set_id' AND room = '$room_id' ORDER BY start_time")or die(mysqli_error($con));
                      }

                      if($_REQUEST['subject_code']){
                        $sub_code = $_REQUEST['subject_code'];
                        $query = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id = '$set_id' AND subject_code = '$sub_code' ORDER BY start_time")or die(mysqli_error($con));
                      }

                      while($row=mysqli_fetch_array($query)){

                        $subject_code = $row['subject_code'];
                        $sub = mysqli_query($con,"SELECT * FROM subject WHERE subject_code = '$subject_code' LIMIT 1")or die(mysqli_error($con));
                        $subj = mysqli_fetch_array($sub);

                        $member_id = $row['member_id'];
                        $selMem = mysqli_query($con,"SELECT * FROM member WHERE member_id = '$member_id' LIMIT 1")or die(mysqli_error($con));
                        $member = mysqli_fetch_array($selMem);

                        $dept_code = $member['dept_code'];
                        $selDept = mysqli_query($con,"SELECT * FROM dept WHERE dept_code = '$dept_code' LIMIT 1")or die(mysqli_error($con));
                        $dept = mysqli_fetch_array($selDept);

                        $day = '';
                        switch ($row['day']) {
                          case 'm':
                          $day = 'Monday';
                          break;
                          case 't':
                          $day = 'Tuesday';
                          break;
                          case 'w':
                          $day = 'Wednesday';
                          break;
                          case 'th':
                          $day = 'Thursday';
                          break;
                          case 'f':
                          $day = 'Friday';
                          break;

                          default:
                          $day = 'Monday';
                          break;
                        }

                        $id = $row['sched_id'];
                        $teacher = $member['member_first'].' '.$member['member_last'];
                        $class = $row['cys'];
                        $time = $row['start_time'].' - '.$row['end_time'];
                        $subject = $subj['subject_title'];
                        $room = $row['room'];
                        $department = $dept['dept_name'];

                        ?>
                        <tr>
                          <td><?php echo $time;?></td>
                          <td><?php echo $day;?></td>
                          <td><?php echo $teacher;?></td>
                          <td><?php echo $class;?></td>
                          <td><?php echo $subject;?></td>
                          <td><?php echo $room;?></td>
                          <td><?php echo $department;?></td>
                          <td>
                            <a id="click" class="mr-5" href="../pages/sched_edit.php?id=<?php echo $id;?>">
                              <i class="glyphicon glyphicon-edit text-black"></i>
                              <span class="text-black">Edit</span>
                            </a>
                            <a id="removeme" href="../logic/sched_del.php?id=<?php echo $id;?>">
                              <i class="glyphicon glyphicon-remove-circle text-black"></i>
                              <span class="text-black">Delete</span>
                            </a>
                          </td>
                        </tr>
                      <?php }?>         
                    </table>
                  </div><!--col end -->
                  <div class="col-md-6">
                  </div><!--col end-->           
                </div><!--row end-->        
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div><!-- /.col (right) -->
          <div class="col-md-2">
            <div class="box border-1">
              <div class="box-body">
                <form method="post" action="../logic/class_save.php">
                  <!-- Date range -->
                  <div id="form">
                    <div class="row">
                      <div class="col-md-12">
                       <span>Display Schedule</span><br>

                       <?php 
                       $selMember = mysqli_query($con,"SELECT * FROM member") or die(mysqli_error($con));
                       $member_id = '';
                       if($_REQUEST['member_id'] != null){
                        $member_id = $_REQUEST['member_id'];
                      }
                      ?>
                      <select class="form-control border-1 member mt-5">
                        <option>Teacher</option>
                        <?php
                        while($member = mysqli_fetch_array($selMember)){?>
                          <option value="<?php echo $member['member_id'];?>" <?php echo $member['member_id'] == $member_id? 'selected':''; ?>>
                            <?php echo $member['member_first'];?> <?php echo $member['member_last'];?>
                          </option>
                        <?php }?>
                      </select>

                      <?php 
                      $selCys = mysqli_query($con,"SELECT * FROM cys") or die(mysqli_error($con));
                      $class_id = '';
                      if($_REQUEST['class_id'] != null){
                        $class_id = $_REQUEST['class_id'];
                      }
                      ?>
                      <select class="form-control border-1 cys mt-5">
                        <option>Class</option>
                        <?php
                        while($cys = mysqli_fetch_array($selCys)){?>
                          <option value="<?php echo $cys['cys'];?>" <?php echo $cys['cys'] == $class_id? 'selected':''; ?>>
                            <?php echo $cys['cys'];?>
                          </option>
                        <?php }?>
                      </select>

                      <?php 
                      $selRoom = mysqli_query($con,"SELECT * FROM room") or die(mysqli_error($con));
                      $room_id = '';
                      if($_REQUEST['room_id'] != null){
                        $room_id = $_REQUEST['room_id'];
                      }
                      ?>
                      <select class="form-control border-1 room mt-5">
                        <option>Room</option>
                        <?php
                        while($room = mysqli_fetch_array($selRoom)){?>
                          <option value="<?php echo $room['room'];?>" <?php echo $room['room'] == $room_id ? 'selected':''; ?>>
                            <?php echo $room['room'];?>
                          </option>
                        <?php }?>
                      </select>

                      <?php 
                      $selSubject = mysqli_query($con,"SELECT * FROM subject") or die(mysqli_error($con));
                      $subject_code = '';
                      if($_REQUEST['subject_code'] != null){
                        $subject_code = $_REQUEST['subject_code'];
                      }
                      ?>
                      <select class="form-control border-1 subject mt-5">
                        <option>Subject</option>
                        <?php
                        while($subject = mysqli_fetch_array($selSubject)){?>
                          <option value="<?php echo $subject['subject_code'];?>" <?php echo $subject['subject_code'] == $subject_code ? 'selected':''; ?>>
                            <?php echo $subject['subject_title'];?>
                          </option>
                        <?php }?>
                      </select>

                    </div>
                  </div>	
                </div><!-- /.form group -->
              </form>	
            </div><!-- /.box-body -->
          </div><!-- /.box -->
          <div class="box border-1">
            <div class="box-body">
              <h4>Term, Semester & School Year</h4>
              <span>This field will show the schedules from the previous Term, Semester & School Year.</span><br/><br/>
              <?php 
              $selSet = mysqli_query($con,"SELECT * FROM settings") or die(mysqli_error($con));
              $set_id = $_SESSION['settings'];
              if($_REQUEST['id'] != null){
                $set_id = $_REQUEST['id'];
              }
              ?>
              <select class="form-control border-1 school-year">
                <?php
                while($set = mysqli_fetch_array($selSet)){?>
                  <option value="<?php echo $set['settings_id'];?>"  <?php echo $set['settings_id'] == $set_id? 'selected':''; ?>>
                    <?php echo $set['term'];?>, <?php echo $set['sem'];?> & <?php echo $set['sy'];?>
                  </option>
                <?php }?>
              </select>
            </div>
          </div>
        </div><!-- /.col (right) -->
      </div><!-- /.row -->
    </section><!-- /.content -->
  </div><!-- /.container -->
</div><!-- /.content-wrapper -->
<?php include('../dist/includes/footer.php');?>
</div>
<script type="text/javascript">
</script>
<script type="text/javascript" src="autosum.js"></script>
<!-- jQuery 2.1.4 -->
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
    $(".school-year").on('change', function(){
      //document.location='../pages/sched_display.php?id='+$(this).val();
      let val = $(this).val();
      console.log(val);
      document.location='../pages/sched_display.php?id='+val;
    });

    $(".member").on('change', function(){
      //document.location='../pages/sched_display.php?id='+$(this).val();
      let val = $(this).val();
      console.log(val);
      document.location='../pages/sched_display.php?member_id='+val;
    });

    $(".cys").on('change', function(){
      //document.location='../pages/sched_display.php?id='+$(this).val();
      let val = $(this).val();
      console.log(val);
      document.location='../pages/sched_display.php?class_id='+val;
    });

    $(".room").on('change', function(){
      //document.location='../pages/sched_display.php?id='+$(this).val();
      let val = $(this).val();
      console.log(val);
      document.location='../pages/sched_display.php?room_id='+val;
    });

    $(".subject").on('change', function(){
      //document.location='../pages/sched_display.php?id='+$(this).val();
      let val = $(this).val();
      console.log(val);
      document.location='../pages/sched_display.php?subject_code='+val;
    });


    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
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
