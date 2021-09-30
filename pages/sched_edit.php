<?php session_start();
if(empty($_SESSION['id'])):
	header('Location:../index.php');
endif;
include('../dist/includes/dbcon.php');
$settings_id = $_SESSION['settings'];
$query_scheds = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id='$settings_id'") or die(mysqli_error($con));
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Home | <?php include('../dist/includes/title.php');?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.5 -->
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
	<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="../plugins/select2/select2.min.css">
	<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
	<link rel="stylesheet" href="../dist/css/main.css">
	<script src="../dist/js/jquery.min.js"></script>
	<link href="../plugins/fullcalendar-scheduler/main.min.css" rel="stylesheet">
	<script src="../plugins/fullcalendar-scheduler/main.min.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			var calendarEl = document.getElementById('calendar');
			var calendarE2 = document.getElementById('side-calendar');

			var calendar = new FullCalendar.Calendar(calendarEl, {
				allDaySlot: false,
				headerToolbar: false,
				initialDate: '<?php echo date('Y-m-d'); ?>',
				initialView: 'timeGridWeek',
				hiddenDays: [ 0 ],
		      navLinks: true, // can click day/week names to navigate views
		      editable: false,
		      dayMaxEvents: true, // allow "more" link when too many events
		      displayEventTime : false,
		      events: [
		      <?php while($row = mysqli_fetch_array($query_scheds)){ 
		      	$start_time =  $row['start_time']?:'00:00';
		      	$end_time =  $row['end_time']?:'01:00';

		      	$member_id = $row['member_id'];
		      	$subject_code = $row['subject_code'];

		      	$selectMember = mysqli_query($con,"SELECT * FROM member WHERE member_id = '$member_id' ") or die(mysqli_error($con));
		      	$member = mysqli_fetch_array($selectMember);

		      	$selectSub = mysqli_query($con,"SELECT * FROM subject WHERE subject_code = '$subject_code' LIMIT 1 ") or die(mysqli_error($con));
        		$sub = mysqli_fetch_array($selectSub);

		      	$day = 0;
		      	switch ($row['day']) {
		      		case 'm':
		      		$day = 1;
		      		break;
		      		case 't':
		      		$day = 2;
		      		break;
		      		case 'w':
		      		$day = 3;
		      		break;
		      		case 'th':
		      		$day = 4;
		      		break;
		      		case 'f':
		      		$day = 5;
		      		break;
		      		case 's':
		      		$day = 6;
		      		break;
		      		default:
		      		$day = 1;
		      		break;
		      	}
		      	?>
		      	{

		      		daysOfWeek: [<?php echo $day;?>],
		          //groupId: <?php echo $row['room']; ?>,
		          title: 'Teacher: <?php echo $member['member_first'].' '.$member['member_last']; ?>; Class: <?php echo $row['cys']; ?>; Subject: <?php echo $row['subject_code']; ?>; Units: <?php echo $sub['subject_units']; ?>; Room: <?php echo $row['room']; ?>',
		          startTime: '<?php  echo $start_time;?>',
		          endTime: '<?php  echo $end_time;?>',
		          url: 'sched_edit.php?id=<?php echo $row['sched_id']?>',
		        },
		      <?php } ?>
		      ],
		    });

			calendar.render();

			var sideCalendar = new FullCalendar.Calendar(calendarE2, {
				allDaySlot: false,
				headerToolbar: {
					left: 'title',
					center: '',
					right: 'prev,next'
				},
				initialDate: '<?php echo date('Y-m-d'); ?>',
		      navLinks: false, // can click day/week names to navigate views
		      editable: false,
		      dayMaxEvents: true, // allow "more" link when too many events
		      events: [],
		    });

			sideCalendar.render();
		});

	</script>
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
						<div class="col-md-3">
						</div>
						<div class="col-md-3">
							<?php include('../dist/includes/nav.php')?>
						</div>
						<div class="col-md-3"></div>
						<div class="col-md-3">
						</div>
					</div>
					<div class="row mt-13">
						<div class="col-md-3">
							<div id="side-calendar"></div>
							<div class="mt-25">
								<span class="ml-5 glyphicon glyphicon-edit"></span>
								<span class="ml-5">Log:</span>
								<div>
									<textarea rows="10" class="w-100 log border-1" readonly><?php echo $_SESSION['logs'];?></textarea>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div id="calendar" class="border-1"></div>
						</div>
						<div class="col-md-3">
							<div class="box border-1">
								<div class="box-body">
									<?php
									$sched_id = $_REQUEST['id'];
									$query=mysqli_query($con,"select * from schedule natural join member where sched_id='$sched_id'")or die(mysqli_error($con));
									$row=mysqli_fetch_array($query);
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
										case 's':
										$day = 'Saturday';
										break;

										default:
										$day = 'Monday';
										break;
									}
									?>
									<form method="post" action="../logic/sched_update.php">
										<div id="form">
											<input type="hidden" name="sched_id" value="<?php echo $sched_id;?>">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<span class="ml-5">Add Time:</span>
														<div class="row">
															<div class="col-xs-6">
																<input type="time" id="start-time" class="form-control input" name="start_time" value="<?php echo $row['start_time']?>" required>
															</div>
															<div class="col-xs-6">
																<input type="time" id="end-time" class="form-control input" name="end_time" value="<?php echo $row['end_time']?>" required>
															</div>
														</div>
													</div>
													<span class="ml-5">Add Day</span>
													<div class="form-group has-feedback">
														<select class="form-control input" name="day" required>
															<option value="<?php echo $row['day'];?>" selected><?php echo $day;?></option>
															<option value="m">Monday</option>
															<option value="t">Tuesday</option>
															<option value="w">Wednesday</option>
															<option value="th">Thursday</option>
															<option value="f">Friday</option>
															<option value="s">Saturday</option>
														</select>
													</div><!-- /.form group -->
													<div class="form-group">
														<span class="ml-5">Teacher</span>
														<select class="form-control input" name="teacher" required>
															<option value="<?php echo $row['member_id'];?>"><?php echo $row['member_last'].", ".$row['member_first'];?></option>
															<?php 
															$query2=mysqli_query($con,"select * from member order by member_last")or die(mysqli_error($con));
															while($row2=mysqli_fetch_array($query2)){
																?>
																<option value="<?php echo $row2['member_id'];?>"><?php echo $row2['member_last'].", ".$row2['member_first'];?></option>
															<?php }
															?>
														</select>
													</div><!-- /.form group -->
													<div class="form-group">
														<span class="ml-5">Subject Code & Subject Title</span>
														<?php $query3 = mysqli_query($con,"select * from subject where subject_code = '".$row['subject_code']."'")or die(mysqli_error($con));
															$row3=mysqli_fetch_array($query3);
														?>
														<select class="form-control input" name="subject" required>
															<option value="<?php echo $row['subject_code'];?>"><?php echo $row['subject_code'].' - '.$row3['subject_title'];?></option>
															<?php 
															$query2=mysqli_query($con,"select * from subject order by subject_code")or die(mysqli_error($con));
															while($row2=mysqli_fetch_array($query2)){
																?>
																<option value="<?php echo $row2['subject_code'];?>"><?php echo $row2['subject_code'].' - '.$row2['subject_title'];?></option>
															<?php }

															?>
														</select>
													</div><!-- /.form group -->
													<div class="form-group">
														<span class="ml-5">Course, Yr & Section</span>
														<select class="form-control input" name="cys" required>
															<option value="<?php echo $row['cys'];?>"><?php echo $row['cys'];?></option>
															<?php 
															$query2=mysqli_query($con,"select * from cys order by cys")or die(mysqli_error($con));
															while($row2=mysqli_fetch_array($query2)){
																?>
																<option value="<?php echo $row2['cys'];?>"><?php echo $row2['cys'];?></option>
															<?php }
															?>
														</select>	
													</div><!-- /.form group -->
													<div class="form-group">
														<span class="ml-5">Room</span>
														<select class="form-control input" name="room" required>
															<option value="<?php echo $row['room'];?>"><?php echo $row['room'];?></option>
															<?php 
															$query2=mysqli_query($con,"select * from room order by room")or die(mysqli_error($con));
															while($row2=mysqli_fetch_array($query2)){
																?>
																<option value="<?php echo $row2['room'];?>"><?php echo $row2['room'];?></option>
															<?php }
															?>
														</select>	
													</div>
												</div>
											</div>	
											<div class="row">
												<div class="col-xs-6 pr-0">
													<button type="submit" class="btn btn-primary btn-block btn-flat">Update</button>
												</div><!-- /.col -->
												<div class="col-xs-6">
													<a href="home.php" class="btn btn-block btn-flat btn-link">Cancel</a>
												</div><!-- /.col -->
											</div>
										</div><!-- /.form group -->
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
	<script>
		$(".uncheck").click(function () {
			$('input:checkbox').removeAttr('checked');
		});
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