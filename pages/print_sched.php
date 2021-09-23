<?php session_start();
if(empty($_SESSION['id'])):
	header('Location:../index.php');
endif;
include('../dist/includes/dbcon.php');
$_SESSION['sched_ids'] = [];
$settings_id = $_SESSION['settings'];
$type = '';
$id = '';

if(!empty($_REQUEST['class'])){
	$type = 'class';
	$class = $_REQUEST['class'];
	$id = $_REQUEST['class'];
	$query_scheds = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id='$settings_id' AND cys='$class'") or die(mysqli_error($con));
}

if(!empty($_REQUEST['member'])){
	$type = 'member';
	$member = $_REQUEST['member'];
	$id = $_REQUEST['member'];
	$query_scheds = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id='$settings_id' AND member_id='$member'") or die(mysqli_error($con));
}

if(!empty($_REQUEST['room'])){
	$type = 'room';
	$room = $_REQUEST['room'];
	$id = $_REQUEST['room'];
	$query_scheds = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id='$settings_id' AND room='$room'") or die(mysqli_error($con));
}


function checkScheds($_start_time, $_end_time, $_day, $_type){

	include('../dist/includes/dbcon.php');
	$settings_id = $_SESSION['settings'];

	$scheds = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id='$settings_id' AND day='$_day'") or die(mysqli_error($con));

	if($_type == 'class'){
		$class = $_REQUEST['class'];
		$scheds = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id='$settings_id' AND day='$_day' AND cys='$class'") or die(mysqli_error($con));
	}
	if($_type == 'member'){
		$member = $_REQUEST['member'];
		$scheds = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id='$settings_id' AND day='$_day' AND member_id='$member'") or die(mysqli_error($con));
	}
	if($_type == 'room'){
		$room = $_REQUEST['room'];
		$scheds = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id='$settings_id' AND day='$_day' AND room='$room'") or die(mysqli_error($con));
	}

	$counter=0;

	while ($row = mysqli_fetch_array($scheds)) {
		if(($_start_time >= $row['start_time'] AND $_start_time < $row['end_time']) 
			OR ($_end_time > $row['start_time'] AND $_end_time <= $row['end_time']) 
			OR ($_start_time < $row['start_time'] AND $_end_time > $row['end_time'])){
			$counter++;
		}
	}

	if($counter>0){
		return 'border-left: 1px dotted #000; border-right: 1px dotted #000;';
	}else{
		return 'border: 1px dotted #000;';
	}
}


function checkId($_start_time, $_end_time, $_day, $_type)
{ 	
	include('../dist/includes/dbcon.php');
	$settings_id = $_SESSION['settings'];

	$scheds = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id='$settings_id' AND day='$_day'") or die(mysqli_error($con));

	if($_type == 'class'){
		$class = $_REQUEST['class'];
		$scheds = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id='$settings_id' AND day='$_day' AND cys='$class'") or die(mysqli_error($con));
	}
	if($_type == 'member'){
		$member = $_REQUEST['member'];
		$scheds = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id='$settings_id' AND day='$_day' AND member_id='$member'") or die(mysqli_error($con));
	}
	if($_type == 'room'){
		$room = $_REQUEST['room'];
		$scheds = mysqli_query($con,"SELECT * FROM schedule WHERE settings_id='$settings_id' AND day='$_day' AND room='$room'") or die(mysqli_error($con));
	}

	while ($row = mysqli_fetch_array($scheds)) {

		$member_id = $row['member_id'];
		$subject_code = $row['subject_code'];

		$selectMember = mysqli_query($con,"SELECT * FROM member WHERE member_id = '$member_id' ") or die(mysqli_error($con));
		$member = mysqli_fetch_array($selectMember);

		$selectSub = mysqli_query($con,"SELECT * FROM subject WHERE subject_code = '$subject_code' LIMIT 1 ") or die(mysqli_error($con));
		$sub = mysqli_fetch_array($selectSub);

		if(($_start_time >= $row['start_time'] AND $_start_time < $row['end_time']) 
			OR ($_end_time > $row['start_time'] AND $_end_time <= $row['end_time']) 
			OR ($_start_time < $row['start_time'] AND $_end_time > $row['end_time'])){
			if(in_array($_SESSION['sched_ids'][$row['sched_id']], $_SESSION['sched_ids'])){
				//echo 'found';
			}else{
				$_SESSION['sched_ids'][$row['sched_id']] = $row['sched_id'];
				echo '<div style="max-height: 20px;">Teacher: <strong>'.$member['member_first'].' '.$member['member_last'].'</strong>; Class: <strong>'.$row['cys'].'</strong>; Subject: <strong>'.$row['subject_code'].'</strong>; Units: <strong>'.$sub['subject_units'].'</strong>; Room: <strong>'.$row['room'].'</strong></div>';
			}
		}
	}
} 

function getInfo($_type, $_id){
	if($_type == 'class'){
		echo $_id;
	}
	if($_type == 'member'){
		include('../dist/includes/dbcon.php');
		$scheds = mysqli_query($con,"SELECT * FROM member WHERE member_id='$_id' ") or die(mysqli_error($con));
		$member = mysqli_fetch_array($scheds);
		echo $member['member_first'].' '.$member['member_last'];
	}
	if($_type == 'room'){
		echo $_id;
	}
}

unset($_SESSION['sched_ids']);

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
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-yellow layout-top-nav" onload="myFunction()">
	<div class="wrapper">
		<div class="content-wrapper">
			<div class="container">
				<section class="content">
					<div class="row mt-13">
						<div class="col-md-2">
						</div>
						<div class="col-md-8">
							<?php
							$settings=mysqli_query($con,"select * from settings where settings_id='$settings_id'")or die(mysqli_error($con));
							$rows=mysqli_fetch_array($settings);
							?>
							<div class="print-header p-10">
								<?php
								if($type=='class'){
									echo '<h2>Class Schedule</h2>
										<span>Class : </span>';
								}
								if($type=='member'){
									echo '<h2>Faculty Schedule</h2>
										<span>Faculty : </span>';
								}
								if($type=='room'){
									echo '<h2>Room Schedule</h2>
										<span>Room : </span>';
								}
								?>
								<strong class="mr-10">
									<?php getInfo($type, $id);?>
								</strong>
								<span>School Year :</span>
								<strong class="mr-10">
									<?php echo $rows['sy']; ?>
								</strong>
								<span>Semester : </span>
								<strong class="mr-10">
									<?php echo $rows['sem']; ?> 
								</strong>
							</div>
							<table class="table-calendar border-1">
								<tr>
									<td style="border: 1px dotted #000;"><small><strong>TIME</strong></small></td>
									<td style="border: 1px dotted #000;"><small><strong>MONDAY</strong></small></td>
									<td style="border: 1px dotted #000;"><small><strong>TUESDAY</strong></small></td>
									<td style="border: 1px dotted #000;"><small><strong>WEDNESDAY</strong></small></td>
									<td style="border: 1px dotted #000;"><small><strong>THURSDAY</strong></small></td>
									<td style="border: 1px dotted #000;"><small><strong>FRIDAY</strong></small></td>
									<td style="border: 1px dotted #000;"><small><strong>SATURDAY</strong></small></td>
									<td style="border: 1px dotted #000;"><small><strong>TIME</strong></small></td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">7:00-7:30</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('07:00','07:30','m',$type);?>"><?php checkId('07:00','07:30','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('07:00','07:30','t',$type);?>"><?php checkId('07:00','07:30','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('07:00','07:30','w',$type);?>"><?php checkId('07:00','07:30','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('07:00','07:30','th',$type);?>"><?php checkId('07:00','07:30','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('07:00','07:30','f',$type);?>"><?php checkId('07:00','07:30','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('07:00','07:30','s',$type);?>"><?php checkId('07:00','07:30','s',$type);?></td>
									<td style="border: 1px dotted #000;">7:00-7:30</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">7:30-8:00</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('07:30','08:00','m',$type);?>"><?php checkId('07:30','08:00','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('07:30','08:00','t',$type);?>"><?php checkId('07:30','08:00','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('07:30','08:00','w',$type);?>"><?php checkId('07:30','08:00','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('07:30','08:00','th',$type);?>"><?php checkId('07:30','08:00','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('07:30','08:00','f',$type);?>"><?php checkId('07:30','08:00','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('07:30','08:00','s',$type);?>"><?php checkId('07:30','08:00','s',$type);?></td>
									<td style="border: 1px dotted #000;">7:30-8:00</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">8:00-8:30</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('08:00','08:30','m',$type);?>"><?php checkId('08:00','08:30','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('08:00','08:30','t',$type);?>"><?php checkId('08:00','08:30','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('08:00','08:30','w',$type);?>"><?php checkId('08:00','08:30','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('08:00','08:30','th',$type);?>"><?php checkId('08:00','08:30','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('08:00','08:30','f',$type);?>"><?php checkId('08:00','08:30','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('08:00','08:30','s',$type);?>"><?php checkId('08:00','08:30','s',$type);?></td>
									<td style="border: 1px dotted #000;">8:00-8:30</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">8:30-9:00</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('08:30','09:00','m',$type);?>"><?php checkId('08:30','09:00','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('08:30','09:00','t',$type);?>"><?php checkId('08:30','09:00','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('08:30','09:00','w',$type);?>"><?php checkId('08:30','09:00','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('08:30','09:00','th',$type);?>"><?php checkId('08:30','09:00','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('08:30','09:00','f',$type);?>"><?php checkId('08:30','09:00','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('08:30','09:00','s',$type);?>"><?php checkId('08:30','09:00','s',$type);?></td>
									<td style="border: 1px dotted #000;">8:30-9:00</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">9:00-9:30</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('09:00','09:30','m',$type);?>"><?php checkId('09:00','09:30','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('09:00','09:30','t',$type);?>"><?php checkId('09:00','09:30','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('09:00','09:30','w',$type);?>"><?php checkId('09:00','09:30','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('09:00','09:30','th',$type);?>"><?php checkId('09:00','09:30','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('09:00','09:30','f',$type);?>"><?php checkId('09:00','09:30','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('09:00','09:30','s',$type);?>"><?php checkId('09:00','09:30','s',$type);?></td>
									<td style="border: 1px dotted #000;">9:00-9:30</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">9:30-10:00</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('09:30','10:00','m',$type);?>"><?php checkId('09:30','10:00','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('09:30','10:00','t',$type);?>"><?php checkId('09:30','10:00','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('09:30','10:00','w',$type);?>"><?php checkId('09:30','10:00','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('09:30','10:00','th',$type);?>"><?php checkId('09:30','10:00','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('09:30','10:00','f',$type);?>"><?php checkId('09:30','10:00','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('09:30','10:00','s',$type);?>"><?php checkId('09:30','10:00','s',$type);?></td>
									<td style="border: 1px dotted #000;">9:30-10:00</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">10:00-10:30</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('10:00','10:30','m',$type);?>"><?php checkId('10:00','10:30','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('10:00','10:30','t',$type);?>"><?php checkId('10:00','10:30','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('10:00','10:30','w',$type);?>"><?php checkId('10:00','10:30','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('10:00','10:30','th',$type);?>"><?php checkId('10:00','10:30','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('10:00','10:30','f',$type);?>"><?php checkId('10:00','10:30','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('10:00','10:30','s',$type);?>"><?php checkId('10:00','10:30','s',$type);?></td>
									<td style="border: 1px dotted #000;">10:00-10:30</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">10:30-11:00</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('10:30','11:00','m',$type);?>"><?php checkId('10:30','11:00','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('10:30','11:00','t',$type);?>"><?php checkId('10:30','11:00','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('10:30','11:00','w',$type);?>"><?php checkId('10:30','11:00','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('10:30','11:00','th',$type);?>"><?php checkId('10:30','11:00','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('10:30','11:00','f',$type);?>"><?php checkId('10:30','11:00','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('10:30','11:00','s',$type);?>"><?php checkId('10:30','11:00','s',$type);?></td>
									<td style="border: 1px dotted #000;">10:30-11:00</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">11:00-11:30</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('11:00','11:30','m',$type);?>"><?php checkId('11:00','11:30','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('11:00','11:30','t',$type);?>"><?php checkId('11:00','11:30','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('11:00','11:30','w',$type);?>"><?php checkId('11:00','11:30','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('11:00','11:30','th',$type);?>"><?php checkId('11:00','11:30','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('11:00','11:30','f',$type);?>"><?php checkId('11:00','11:30','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('11:00','11:30','s',$type);?>"><?php checkId('11:00','11:30','s',$type);?></td>
									<td style="border: 1px dotted #000;">11:00-11:30</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">11:30-12:00</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('11:30','12:00','m',$type);?>"><?php checkId('11:30','12:00','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('11:30','12:00','t',$type);?>"><?php checkId('11:30','12:00','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('11:30','12:00','w',$type);?>"><?php checkId('11:30','12:00','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('11:30','12:00','th',$type);?>"><?php checkId('11:30','12:00','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('11:30','12:00','f',$type);?>"><?php checkId('11:30','12:00','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('11:30','12:00','s',$type);?>"><?php checkId('11:30','12:00','s',$type);?></td>
									<td style="border: 1px dotted #000;">11:30-12:00</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">12:00-12:30</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('12:00','12:30','m',$type);?>"><?php checkId('12:00','12:30','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('12:00','12:30','t',$type);?>"><?php checkId('12:00','12:30','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('12:00','12:30','w',$type);?>"><?php checkId('12:00','12:30','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('12:00','12:30','th',$type);?>"><?php checkId('12:00','12:30','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('12:00','12:30','f',$type);?>"><?php checkId('12:00','12:30','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('12:00','12:30','s',$type);?>"><?php checkId('12:00','12:30','s',$type);?></td>
									<td style="border: 1px dotted #000;">12:00-12:30</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">12:30-1:00</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('12:30','13:00','m',$type);?>"><?php checkId('12:30','13:00','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('12:30','13:00','t',$type);?>"><?php checkId('12:30','13:00','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('12:30','13:00','w',$type);?>"><?php checkId('12:30','13:00','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('12:30','13:00','th',$type);?>"><?php checkId('12:30','13:00','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('12:30','13:00','f',$type);?>"><?php checkId('12:30','13:00','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('12:30','13:00','s',$type);?>"><?php checkId('12:30','13:00','s',$type);?></td>
									<td style="border: 1px dotted #000;">12:30-1:00</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">1:00-1:30</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('13:00','13:30','m',$type);?>"><?php checkId('13:00','13:30','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('13:00','13:30','t',$type);?>"><?php checkId('13:00','13:30','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('13:00','13:30','w',$type);?>"><?php checkId('13:00','13:30','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('13:00','13:30','th',$type);?>"><?php checkId('13:00','13:30','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('13:00','13:30','f',$type);?>"><?php checkId('13:00','13:30','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('13:00','13:30','s',$type);?>"><?php checkId('13:00','13:30','s',$type);?></td>
									<td style="border: 1px dotted #000;">1:00-1:30</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">1:30-2:00</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('13:30','14:00','m',$type);?>"><?php checkId('13:30','14:00','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('13:30','14:00','t',$type);?>"><?php checkId('13:30','14:00','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('13:30','14:00','w',$type);?>"><?php checkId('13:30','14:00','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('13:30','14:00','th',$type);?>"><?php checkId('13:30','14:00','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('13:30','14:00','f',$type);?>"><?php checkId('13:30','14:00','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('13:30','14:00','s',$type);?>"><?php checkId('13:30','14:00','s',$type);?></td>
									<td style="border: 1px dotted #000;">1:30-2:00</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">2:00-2:30</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('14:00','14:30','m',$type);?>"><?php checkId('14:00','14:30','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('14:00','14:30','t',$type);?>"><?php checkId('14:00','14:30','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('14:00','14:30','w',$type);?>"><?php checkId('14:00','14:30','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('14:00','14:30','th',$type);?>"><?php checkId('14:00','14:30','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('14:00','14:30','f',$type);?>"><?php checkId('14:00','14:30','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('14:00','14:30','s',$type);?>"><?php checkId('14:00','14:30','s',$type);?></td>
									<td style="border: 1px dotted #000;">2:00-2:30</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">2:30-3:00</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('14:30','15:00','m',$type);?>"><?php checkId('14:30','15:00','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('14:30','15:00','t',$type);?>"><?php checkId('14:30','15:00','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('14:30','15:00','w',$type);?>"><?php checkId('14:30','15:00','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('14:30','15:00','th',$type);?>"><?php checkId('14:30','15:00','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('14:30','15:00','f',$type);?>"><?php checkId('14:30','15:00','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('14:30','15:00','s',$type);?>"><?php checkId('14:30','15:00','s',$type);?></td>
									<td style="border: 1px dotted #000;">2:30-3:00</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">3:00-3:30</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('15:00','15:30','m',$type);?>"><?php checkId('15:00','15:30','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('15:00','15:30','t',$type);?>"><?php checkId('15:00','15:30','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('15:00','15:30','w',$type);?>"><?php checkId('15:00','15:30','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('15:00','15:30','th',$type);?>"><?php checkId('15:00','15:30','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('15:00','15:30','f',$type);?>"><?php checkId('15:00','15:30','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('15:00','15:30','s',$type);?>"><?php checkId('15:00','15:30','s',$type);?></td>
									<td style="border: 1px dotted #000;">3:00-3:30</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">3:30-4:00</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('15:30','16:00','m',$type);?>"><?php checkId('15:30','16:00','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('15:30','16:00','t',$type);?>"><?php checkId('15:30','16:00','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('15:30','16:00','w',$type);?>"><?php checkId('15:30','16:00','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('15:30','16:00','th',$type);?>"><?php checkId('15:30','16:00','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('15:30','16:00','f',$type);?>"><?php checkId('15:30','16:00','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('15:30','16:00','s',$type);?>"><?php checkId('15:30','16:00','s',$type);?></td>
									<td style="border: 1px dotted #000;">3:30-4:00</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">4:00-4:30</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('16:00','16:30','m',$type);?>"><?php checkId('16:00','16:30','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('16:00','16:30','t',$type);?>"><?php checkId('16:00','16:30','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('16:00','16:30','w',$type);?>"><?php checkId('16:00','16:30','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('16:00','16:30','th',$type);?>"><?php checkId('16:00','16:30','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('16:00','16:30','f',$type);?>"><?php checkId('16:00','16:30','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('16:00','16:30','s',$type);?>"><?php checkId('16:00','16:30','s',$type);?></td>
									<td style="border: 1px dotted #000;">4:00-4:30</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">4:30-5:00</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('16:30','17:00','m',$type);?>"><?php checkId('16:30','17:00','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('16:30','17:00','t',$type);?>"><?php checkId('16:30','17:00','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('16:30','17:00','w',$type);?>"><?php checkId('16:30','17:00','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('16:30','17:00','th',$type);?>"><?php checkId('16:30','17:00','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('16:30','17:00','f',$type);?>"><?php checkId('16:30','17:00','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('16:30','17:00','s',$type);?>"><?php checkId('16:30','17:00','s',$type);?></td>
									<td style="border: 1px dotted #000;">4:30-5:00</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">5:00-5:30</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('17:00','17:30','m',$type);?>"><?php checkId('17:00','17:30','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('17:00','17:30','t',$type);?>"><?php checkId('17:00','17:30','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('17:00','17:30','w',$type);?>"><?php checkId('17:00','17:30','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('17:00','17:30','th',$type);?>"><?php checkId('17:00','17:30','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('17:00','17:30','f',$type);?>"><?php checkId('17:00','17:30','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('17:00','17:30','s',$type);?>"><?php checkId('17:00','17:30','s',$type);?></td>
									<td style="border: 1px dotted #000;">5:00-5:30</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">5:30-6:00</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('17:30','18:00','m',$type);?>"><?php checkId('17:30','18:00','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('17:30','18:00','t',$type);?>"><?php checkId('17:30','18:00','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('17:30','18:00','w',$type);?>"><?php checkId('17:30','18:00','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('17:30','18:00','th',$type);?>"><?php checkId('17:30','18:00','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('17:30','18:00','f',$type);?>"><?php checkId('17:30','18:00','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('17:30','18:00','s',$type);?>"><?php checkId('17:30','18:00','s',$type);?></td>
									<td style="border: 1px dotted #000;">5:30-6:00</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">6:00-6:30</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('18:00','18:30','m',$type);?>"><?php checkId('18:00','18:30','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('18:00','18:30','t',$type);?>"><?php checkId('18:00','18:30','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('18:00','18:30','w',$type);?>"><?php checkId('18:00','18:30','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('18:00','18:30','th',$type);?>"><?php checkId('18:00','18:30','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('18:00','18:30','f',$type);?>"><?php checkId('18:00','18:30','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('18:00','18:30','s',$type);?>"><?php checkId('18:00','18:30','s',$type);?></td>
									<td style="border: 1px dotted #000;">6:00-6:30</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">6:30-7:00</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('18:30','19:00','m',$type);?>"><?php checkId('18:30','19:00','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('18:30','19:00','t',$type);?>"><?php checkId('18:30','19:00','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('18:30','19:00','w',$type);?>"><?php checkId('18:30','19:00','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('18:30','19:00','th',$type);?>"><?php checkId('18:30','19:00','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('18:30','19:00','f',$type);?>"><?php checkId('18:30','19:00','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('18:30','19:00','s',$type);?>"><?php checkId('18:30','19:00','s',$type);?></td>
									<td style="border: 1px dotted #000;">6:30-7:00</td>
								</tr>
								<tr>
									<td style="border: 1px dotted #000;">7:00-7:30</td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('19:00','19:30','m',$type);?>"><?php checkId('19:00','19:30','m',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('19:00','19:30','t',$type);?>"><?php checkId('19:00','19:30','t',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('19:00','19:30','w',$type);?>"><?php checkId('19:00','19:30','w',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('19:00','19:30','th',$type);?>"><?php checkId('19:00','19:30','th',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('19:00','19:30','f',$type);?>"><?php checkId('19:00','19:30','f',$type);?></td>
									<td style="color: #000; text-align: left; font-size:  12px; <?php echo checkScheds('19:00','19:30','s',$type);?>"><?php checkId('19:00','19:30','s',$type);?></td>
									<td style="border: 1px dotted #000;">7:00-7:30</td>
								</tr>
							</table>

					        <a class="btn btn-flat btn-danger mt-13 mr-10" id="btn-pdf" target="_blank" href="pdf.php?<?php echo $type;?>=<?php echo $id;?>"><span class="glyphicon glyphicon-download-alt mr-10"></span> Download as PDF</a>
					        <a class="btn btn-flat btn-success mt-13" id="btn-excel" href="excel.php?<?php echo $type;?>=<?php echo $id;?>"><span class="glyphicon glyphicon-download-alt mr-10"></span> Download as Excel</a>
						</div>
						<div class="col-md-2">
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
    </script>
  </body>
  </html>