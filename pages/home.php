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
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
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
		      hiddenDays: [ 0, 6 ],
		      navLinks: true, // can click day/week names to navigate views
		      editable: false,
		      dayMaxEvents: true, // allow "more" link when too many events
		      events: [
		      <?php while($row = mysqli_fetch_array($query_scheds)){ 
		      	$start_time =  $row['start_time']?:'00:00';
		      	$end_time =  $row['end_time']?:'01:00';

		      	$member_id = $row['member_id'];

		      	$selectMember = mysqli_query($con,"SELECT * FROM member WHERE member_id = '$member_id' ") or die(mysqli_error($con));
		      	$member = mysqli_fetch_array($selectMember);


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
		      		default:
		      			$day = 1;
		      			break;
		      	}
		      	?>
		        {
		          daysOfWeek: [<?php echo $day;?>],
		          //groupId: <?php echo $row['room']; ?>,
		          title: 'Teacher: <?php echo $member['member_first'].' '.$member['member_last']; ?>; Class: <?php echo $row['cys']; ?>; Subject: <?php echo $row['subject_code']; ?>; Room: <?php echo $row['room']; ?>',
		          startTime: '<?php  echo $start_time;?>',
		          endTime: '<?php  echo $end_time;?>',
		          url: 'sched_edit.php?id=<?php echo $row['sched_id']?>',
		        },
		       <?php } ?>
		      ]
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
    			<div class="home-container">
					<!-- Main content -->
    				<section class="content">
    					<div class="row">
    						<div class="col-md-3">
    						</div>
    						<div class="col-md-3">
    							<?php include('../dist/includes/nav.php')?>
    						</div>
    						<div class="col-md-3">
    							<div class="row mt-25">
    								<div class="col-md-6 text-right pr-0 mt-5">
    									<span>Print Schedule: </span>
    								</div>
    								<div class="col-md-6">
		    							<select class="form-control" name="teacher" required>
		    								<option value="class">Class</option>
		    								<option value="teacher">Teacher</option>
		    								<option value="room">Room</option>
		    							</select>
    								</div>
    							</div>
    						</div>
    						<div class="col-md-3">
    							<div class="row mt-25">
    								<div class="col-md-6">
    									<button class="btn btn-primary btn-flat btn-block" name="print" default>Print</button>
    								</div>
    							</div>
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
    						</div><!-- /.col (right) -->
    						<div class="col-md-3">
    							<div class="box border-1">
    								<div class="box-body">
    									<form method="post" id="sched-save" action="../logic/sched_save.php">
    										<!-- Date range -->
    										<div id="form1">
    											<div class="row">
    												<div class="col-md-12">
    													<div class="form-group">
    														<span class="ml-5">Add Time:</span>
    														<div class="row">
    															<div class="col-xs-6">
    															<span class="ml-15 glyphicon glyphicon-plus form-control-feedback float-left left-0"></span>
    																<input type="time" id="start-time" class="form-control input-has-feedback input" name="start_time" required>
    															</div>
    															<div class="col-xs-6">
    															<span class="ml-15 glyphicon glyphicon-plus form-control-feedback float-left left-0"></span>
    																<input type="time" id="end-time" class="form-control input-has-feedback input" name="end_time" required>
    															</div>
    														</div>
    													</div>
    													<span class="ml-5">Add Day</span>
    													<div class="form-group has-feedback">
    														<span class="glyphicon glyphicon-plus form-control-feedback float-left left-0"></span>
    														<select class="form-control input-has-feedback input" name="day" required>
    															<option value="m">Monday</option>
    															<option value="t">Tuesday</option>
    															<option value="w">Wednesday</option>
    															<option value="th">Thursday</option>
    															<option value="f">Friday</option>
    														</select>
    													</div><!-- /.form group -->
    													<span class="ml-5">Add Teacher</span>
    													<div class="form-group has-feedback">
    														<span class="glyphicon glyphicon-plus form-control-feedback float-left left-0"></span>
    														<select class="form-control input-has-feedback input" name="teacher" required>
    															<option value="">Choose a Teacher</option>
    															<?php 
    															$query2=mysqli_query($con,"select * from member order by member_last")or die(mysqli_error($con));
    															while($row=mysqli_fetch_array($query2)){
    																?>
    																<option value="<?php echo $row['member_id'];?>"><?php echo $row['member_last'].", ".$row['member_first'];?></option>
    																<?php }
    															?>
    														</select>
    													</div><!-- /.form group -->
    													<span class="ml-5">Add Subject</span>
    													<div class="form-group has-feedback">
    														<span class="glyphicon glyphicon-plus form-control-feedback float-left left-0"></span>
    														<select class="form-control input-has-feedback input" name="subject" required>
    															<option value="">Choose a Subject</option>
    															<?php 
    															$query2=mysqli_query($con,"select * from subject order by subject_code")or die(mysqli_error($con));
    															while($row=mysqli_fetch_array($query2)){
    																?>
    																<option><?php echo $row['subject_code'];?></option>
    																<?php }
    															?>
    														</select>
    													</div><!-- /.form group -->
    													<span class="ml-5">Add Course, Yr & Section</span>
    													<div class="form-group has-feedback">
    														<span class="glyphicon glyphicon-plus form-control-feedback float-left left-0"></span>
    														<select class="form-control input-has-feedback input" name="cys" required>
    														<option value="">Choose a Course, Yr & Section</option>
    														<?php 
	    														$query2=mysqli_query($con,"select * from cys order by cys")or die(mysqli_error($con));
	    														while($row=mysqli_fetch_array($query2)){
	    															?>
	    															<option><?php echo $row['cys'];?></option>
	    															<?php }
    															?>
    														</select>	
    													</div><!-- /.form group -->
    													<span class="ml-5">Add Room</span>
    													<div class="form-group has-feedback">
    														<span class="glyphicon glyphicon-plus form-control-feedback float-left left-0"></span>
    														<select class="form-control input-has-feedback input" name="room" required>
    															<option value="">Choose a Room</option>
    															<?php 
    															$query2=mysqli_query($con,"select * from room order by room")or die(mysqli_error($con));
    															while($row=mysqli_fetch_array($query2)){
    																?>
    																<option><?php echo $row['room'];?></option>
    																<?php }
    															?>
    														</select>	
    													</div><!-- /.form group -->
    													<!-- <div class="form-group">
    														<span class="ml-5">Add Remarks</span><br>
    														<textarea name="remarks" cols="30" placeholder="enclose remarks with parenthesis()"></textarea>
    													</div> -->
    												</div>
    											</div>	
    											<div class="row">
    												<div class="col-xs-6 pr-0">
    													<button type="submit" class="btn btn-primary btn-block btn-flat">Save</button>
    												</div><!-- /.col -->
    												<div class="col-xs-6">
    													<button type="reset" class="btn btn-block btn-flat">Cancel</button>
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

    	<div id="searcht" class="modal fade in" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    		<div class="modal-dialog">
    			<div class="modal-content" style="height:auto">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    					<span aria-hidden="true">×</span></button>
    					<h4 class="modal-title">Search Faculty Schedule</h4>
    				</div>
    				<div class="modal-body">
    					<form class="form-horizontal" method="post" action="faculty_sched.php" target="_blank">

    						<div class="form-group">
    							<label class="control-label col-lg-2" for="name">Faculty</label>
    							<div class="col-lg-10">
    								<select class="select2" name="faculty" style="width:90%!important" required>
    									<?php 

    									$query2=mysqli_query($con,"select * from member order by member_last")or die(mysqli_error($con));
    									while($row=mysqli_fetch_array($query2)){
    										?>
    										<option value="<?php echo $row['member_id'];?>"><?php echo $row['member_last'].", ".$row['member_first'];?></option>
    									<?php }

    									?>
    								</select>
    							</div>
    						</div> 
    					</form>
    				</div><hr>
    				<div class="modal-footer">
    					<button type="submit" name="search" class="btn btn-primary">Display Schedule</button>
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    				</div>
    			</div>
    		</div><!--end of modal-dialog-->
    	</div><!--end of modal--> 

    	<div id="searchclass" class="modal fade in" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    		<div class="modal-dialog">
    			<div class="modal-content" style="height:auto">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    						<span aria-hidden="true">×</span>
    					</button>
    					<h4 class="modal-title">Search Class Schedule</h4>
    				</div>
    				<div class="modal-body">
    					<form class="form-horizontal" method="post" action="class_sched.php" target="_blank">
    						<div class="form-group">
    							<label class="control-label col-lg-2" for="name">Class</label>
    							<div class="col-lg-10">
    								<select class="select2" name="class" style="width:90%!important" required>
    									<?php 

    									$query2=mysqli_query($con,"select * from cys order by cys")or die(mysqli_error($con));
    									while($row=mysqli_fetch_array($query2)){
    										?>
    										<option><?php echo $row['cys'];?></option>
    									<?php }

    									?>
    								</select>
    							</div>
    						</div> 
    					</form>
    				</div><hr>
    				<div class="modal-footer">
    					<button type="submit" name="search" class="btn btn-primary">Display Schedule</button>
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    				</div>
    			</div>
    		</div><!--end of modal-dialog-->
    	</div><!--end of modal--> 

    	<div id="searchroom" class="modal fade in" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    		<div class="modal-dialog">
    			<div class="modal-content" style="height:auto">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    						<span aria-hidden="true">×</span>
    					</button>
    					<h4 class="modal-title">Search Room Schedule</h4>
    				</div>
    				<div class="modal-body">
    					<form class="form-horizontal" method="post" action="room_sched.php" target="_blank">

    						<div class="form-group">
    							<label class="control-label col-lg-2" for="name">Room</label>
    							<div class="col-lg-10">
    								<select class="select2" name="room" style="width:90%!important" required>
    									<?php 

    									$query2=mysqli_query($con,"select * from room order by room")or die(mysqli_error($con));
    									while($row=mysqli_fetch_array($query2)){
    										?>
    										<option><?php echo $row['room'];?></option>
    									<?php }

    									?>
    								</select>
    							</div>
    						</div> 
    					</form>
    				</div><hr>
    				<div class="modal-footer">
    					<button type="submit" name="search" class="btn btn-primary">Display Schedule</button>
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    				</div>
    			</div>

    		</div><!--end of modal-dialog-->
    	</div><!--end of modal--> 
    	<script type="text/javascript">
			$(document).on('submit', '#reg-form', function()
    		{  
    			$.post('submit.php', $(this).serialize(), function(data){
    				$(".result").html(data);  
    				$("#form1")[0].reset();
		  			// $("#check").reset();
				});
    			return false;
    		})

			$(document).on('submit', '#sched-save', function(event)
    		{  
    			let start = $("#start-time").val();
    			let end = $("#end-time").val();
    			if(start>end){
    				event.preventDefault();
    				alert('Start Time must be lower than the End Time');
    			}
    	})

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
