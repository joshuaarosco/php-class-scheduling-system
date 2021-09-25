<?php 
session_start();
if(empty($_SESSION['id'])):
	header('Location:../index.php');
endif;

include('../dist/includes/dbcon.php');
$settings_id = $_SESSION['settings'];
$member_id = $_SESSION['id'];
$query_scheds = mysqli_query($con,"SELECT * FROM schedule WHERE member_id = '$member_id' AND settings_id = '$settings_id'") or die(mysqli_error($con));

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
		          endTime: '<?php  echo $end_time;?>'
		        },
		      <?php } ?>
		      ]
		    });

			calendar.render();
		});
	</script>
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-yellow layout-top-nav" onload="myFunction()">
	<div class="wrapper">
		<?php include('../dist/includes/header_faculty.php');?>
		<!-- Full Width Column -->
		<div class="content-wrapper">
			<div class="container">
				<section class="content">
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-6">
							<span class="section-title"><?php echo date('F d, Y');?></span>
							<div id="calendar" class="border-1"></div>
							<a target="_blank" href="print_sched.php?member=<?php echo $member_id;?>" class="btn btn-flat btn-primary mt-13">Print Schedule</a>
						</div><!-- /.col (right) -->
						<div class="col-md-3"></div>
					</div><!-- /.row -->
				</section><!-- /.content -->
			</div><!-- /.container -->
		</div><!-- /.content-wrapper -->
		<?php include('../dist/includes/footer.php');?>
	</div><!-- ./wrapper -->
</div>
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
