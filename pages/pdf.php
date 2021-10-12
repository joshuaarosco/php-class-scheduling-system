<?php
session_start();
if(empty($_SESSION['id'])):
  header('Location:../index.php');
endif;

include('../dist/includes/dbcon.php');
require_once '../dist/dompdf_0-8-3/dompdf/autoload.inc.php';
unset($_SESSION["sched_ids"]);
$settings_id = $_SESSION['settings'];
$_SESSION['sched_ids'][0] = 0;
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
			if(array_key_exists($row['sched_id'], $_SESSION['sched_ids'] )){
				//echo 'found';
			}else{
				$_SESSION['sched_ids'][$row['sched_id']] = $row['sched_id'];
				return '<div style="max-height: 10px; height: 10px; overflow: visible; z-index: 1000px;">Teacher: <strong>'.$member['member_first'].' '.$member['member_last'].'</strong>; Class: <strong>'.$row['cys'].'</strong>; Subject: <strong>'.$row['subject_code'].' - '.$sub['subject_title'].'</strong>; Units: <strong>'.$sub['subject_units'].'</strong>; Room: <strong>'.$row['room'].'</strong></div>';
			}
		}
	}
} 

function getInfo($_type, $_id){
	if($_type == 'class'){
		return $_id;
	}
	if($_type == 'member'){
		include('../dist/includes/dbcon.php');
		$scheds = mysqli_query($con,"SELECT * FROM member WHERE member_id='$_id' ") or die(mysqli_error($con));
		$member = mysqli_fetch_array($scheds);
		return $member['member_first'].' '.$member['member_last'];
	}
	if($_type == 'room'){
		return $_id;
	}
}

function checkType($_type){
	if($_type=='class'){
		return '<h2>Class Schedule</h2>
		<span>Class : </span>';
	}
	if($_type=='member'){
		return '<h2>Faculty Schedule</h2>
		<span>Faculty : </span>';
	}
	if($_type=='room'){
		return '<h2>Room Schedule</h2>
		<span>Room : </span>';
	}
}

$settings = mysqli_query($con,"select * from settings where settings_id='$settings_id'")or die(mysqli_error($con));
$rows = mysqli_fetch_array($settings);

$html = '<html style="margin: 0px;"><body style="font-family: Helvetica,Arial,sans-serif; text-align: center; margin: 0px; margin-right: 15px; margin-left: 15px;">'.checkType($type).'
								<strong style="margin-right: 10px;">
								'.getInfo($type,$id).'
								</strong>
								<span>School Year :</span>
								<strong style="margin-right: 10px;">
								'.$rows['sy'].'
								</strong>
								<span>Semester : </span>
								<strong style="margin-right: 10px;">
								'.$rows['sem'].'
								</strong>
							</div>
							<table style="width: 100%; border: 1px solid #000000; z-index: 100px; margin-top: 10px;" cellspacing="0">
								<tr>
									<td style="color:#000; font-size: 14px; border: 1px dotted #000; text-align: center; padding: 1px; width: 12.5%;"><small><strong>TIME</strong></small></td>
									<td style="color:#000; font-size: 14px; border: 1px dotted #000; text-align: center; padding: 1px; width: 12.5%;"><small><strong>MONDAY</strong></small></td>
									<td style="color:#000; font-size: 14px; border: 1px dotted #000; text-align: center; padding: 1px; width: 12.5%;"><small><strong>TUESDAY</strong></small></td>
									<td style="color:#000; font-size: 14px; border: 1px dotted #000; text-align: center; padding: 1px; width: 12.5%;"><small><strong>WEDNESDAY</strong></small></td>
									<td style="color:#000; font-size: 14px; border: 1px dotted #000; text-align: center; padding: 1px; width: 12.5%;"><small><strong>THURSDAY</strong></small></td>
									<td style="color:#000; font-size: 14px; border: 1px dotted #000; text-align: center; padding: 1px; width: 12.5%;"><small><strong>FRIDAY</strong></small></td>
									<td style="color:#000; font-size: 14px; border: 1px dotted #000; text-align: center; padding: 1px; width: 12.5%;"><small><strong>SATURDAY</strong></small></td>
									<td style="color:#000; font-size: 14px; border: 1px dotted #000; text-align: center; padding: 1px; width: 12.5%;"><small><strong>TIME</strong></small></td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">7:00-7:30</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('07:00','07:30','m',$type).'">'.checkId('07:00','07:30','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('07:00','07:30','t',$type).'">'.checkId('07:00','07:30','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('07:00','07:30','w',$type).'">'.checkId('07:00','07:30','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('07:00','07:30','th',$type).'">'.checkId('07:00','07:30','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('07:00','07:30','f',$type).'">'.checkId('07:00','07:30','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('07:00','07:30','s',$type).'">'.checkId('07:00','07:30','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">7:00-7:30</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">7:30-8:00</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('07:30','08:00','m',$type).'">'.checkId('07:30','08:00','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('07:30','08:00','t',$type).'">'.checkId('07:30','08:00','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('07:30','08:00','w',$type).'">'.checkId('07:30','08:00','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('07:30','08:00','th',$type).'">'.checkId('07:30','08:00','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('07:30','08:00','f',$type).'">'.checkId('07:30','08:00','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('07:30','08:00','s',$type).'">'.checkId('07:30','08:00','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">7:30-8:00</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">8:00-8:30</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('08:00','08:30','m',$type).'">'.checkId('08:00','08:30','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('08:00','08:30','t',$type).'">'.checkId('08:00','08:30','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('08:00','08:30','w',$type).'">'.checkId('08:00','08:30','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('08:00','08:30','th',$type).'">'.checkId('08:00','08:30','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('08:00','08:30','f',$type).'">'.checkId('08:00','08:30','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('08:00','08:30','s',$type).'">'.checkId('08:00','08:30','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">8:00-8:30</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">8:30-9:00</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('08:30','09:00','m',$type).'">'.checkId('08:30','09:00','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('08:30','09:00','t',$type).'">'.checkId('08:30','09:00','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('08:30','09:00','w',$type).'">'.checkId('08:30','09:00','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('08:30','09:00','th',$type).'">'.checkId('08:30','09:00','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('08:30','09:00','f',$type).'">'.checkId('08:30','09:00','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('08:30','09:00','s',$type).'">'.checkId('08:30','09:00','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">8:30-9:00</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">9:00-9:30</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('09:00','09:30','m',$type).'">'.checkId('09:00','09:30','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('09:00','09:30','t',$type).'">'.checkId('09:00','09:30','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('09:00','09:30','w',$type).'">'.checkId('09:00','09:30','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('09:00','09:30','th',$type).'">'.checkId('09:00','09:30','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('09:00','09:30','f',$type).'">'.checkId('09:00','09:30','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('09:00','09:30','s',$type).'">'.checkId('09:00','09:30','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">9:00-9:30</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">9:30-10:00</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('09:30','10:00','m',$type).'">'.checkId('09:30','10:00','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('09:30','10:00','t',$type).'">'.checkId('09:30','10:00','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('09:30','10:00','w',$type).'">'.checkId('09:30','10:00','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('09:30','10:00','th',$type).'">'.checkId('09:30','10:00','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('09:30','10:00','f',$type).'">'.checkId('09:30','10:00','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('09:30','10:00','s',$type).'">'.checkId('09:30','10:00','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">9:30-10:00</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">10:00-10:30</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('10:00','10:30','m',$type).'">'.checkId('10:00','10:30','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('10:00','10:30','t',$type).'">'.checkId('10:00','10:30','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('10:00','10:30','w',$type).'">'.checkId('10:00','10:30','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('10:00','10:30','th',$type).'">'.checkId('10:00','10:30','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('10:00','10:30','f',$type).'">'.checkId('10:00','10:30','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('10:00','10:30','s',$type).'">'.checkId('10:00','10:30','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">10:00-10:30</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">10:30-11:00</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('10:30','11:00','m',$type).'">'.checkId('10:30','11:00','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('10:30','11:00','t',$type).'">'.checkId('10:30','11:00','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('10:30','11:00','w',$type).'">'.checkId('10:30','11:00','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('10:30','11:00','th',$type).'">'.checkId('10:30','11:00','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('10:30','11:00','f',$type).'">'.checkId('10:30','11:00','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('10:30','11:00','s',$type).'">'.checkId('10:30','11:00','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">10:30-11:00</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">11:00-11:30</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('11:00','11:30','m',$type).'">'.checkId('11:00','11:30','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('11:00','11:30','t',$type).'">'.checkId('11:00','11:30','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('11:00','11:30','w',$type).'">'.checkId('11:00','11:30','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('11:00','11:30','th',$type).'">'.checkId('11:00','11:30','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('11:00','11:30','f',$type).'">'.checkId('11:00','11:30','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('11:00','11:30','s',$type).'">'.checkId('11:00','11:30','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">11:00-11:30</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">11:30-12:00</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('11:30','12:00','m',$type).'">'.checkId('11:30','12:00','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('11:30','12:00','t',$type).'">'.checkId('11:30','12:00','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('11:30','12:00','w',$type).'">'.checkId('11:30','12:00','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('11:30','12:00','th',$type).'">'.checkId('11:30','12:00','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('11:30','12:00','f',$type).'">'.checkId('11:30','12:00','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('11:30','12:00','s',$type).'">'.checkId('11:30','12:00','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">11:30-12:00</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">12:00-12:30</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('12:00','12:30','m',$type).'">'.checkId('12:00','12:30','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('12:00','12:30','t',$type).'">'.checkId('12:00','12:30','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('12:00','12:30','w',$type).'">'.checkId('12:00','12:30','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('12:00','12:30','th',$type).'">'.checkId('12:00','12:30','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('12:00','12:30','f',$type).'">'.checkId('12:00','12:30','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('12:00','12:30','s',$type).'">'.checkId('12:00','12:30','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">12:00-12:30</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">12:30-1:00</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('12:30','13:00','m',$type).'">'.checkId('12:30','13:00','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('12:30','13:00','t',$type).'">'.checkId('12:30','13:00','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('12:30','13:00','w',$type).'">'.checkId('12:30','13:00','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('12:30','13:00','th',$type).'">'.checkId('12:30','13:00','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('12:30','13:00','f',$type).'">'.checkId('12:30','13:00','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('12:30','13:00','s',$type).'">'.checkId('12:30','13:00','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">12:30-1:00</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">1:00-1:30</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('13:00','13:30','m',$type).'">'.checkId('13:00','13:30','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('13:00','13:30','t',$type).'">'.checkId('13:00','13:30','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('13:00','13:30','w',$type).'">'.checkId('13:00','13:30','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('13:00','13:30','th',$type).'">'.checkId('13:00','13:30','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('13:00','13:30','f',$type).'">'.checkId('13:00','13:30','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('13:00','13:30','s',$type).'">'.checkId('13:00','13:30','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">1:00-1:30</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">1:30-2:00</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('13:30','14:00','m',$type).'">'.checkId('13:30','14:00','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('13:30','14:00','t',$type).'">'.checkId('13:30','14:00','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('13:30','14:00','w',$type).'">'.checkId('13:30','14:00','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('13:30','14:00','th',$type).'">'.checkId('13:30','14:00','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('13:30','14:00','f',$type).'">'.checkId('13:30','14:00','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('13:30','14:00','s',$type).'">'.checkId('13:30','14:00','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">1:30-2:00</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">2:00-2:30</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('14:00','14:30','m',$type).'">'.checkId('14:00','14:30','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('14:00','14:30','t',$type).'">'.checkId('14:00','14:30','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('14:00','14:30','w',$type).'">'.checkId('14:00','14:30','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('14:00','14:30','th',$type).'">'.checkId('14:00','14:30','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('14:00','14:30','f',$type).'">'.checkId('14:00','14:30','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('14:00','14:30','s',$type).'">'.checkId('14:00','14:30','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">2:00-2:30</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">2:30-3:00</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('14:30','15:00','m',$type).'">'.checkId('14:30','15:00','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('14:30','15:00','t',$type).'">'.checkId('14:30','15:00','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('14:30','15:00','w',$type).'">'.checkId('14:30','15:00','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('14:30','15:00','th',$type).'">'.checkId('14:30','15:00','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('14:30','15:00','f',$type).'">'.checkId('14:30','15:00','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('14:30','15:00','s',$type).'">'.checkId('14:30','15:00','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">2:30-3:00</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">3:00-3:30</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('15:00','15:30','m',$type).'">'.checkId('15:00','15:30','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('15:00','15:30','t',$type).'">'.checkId('15:00','15:30','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('15:00','15:30','w',$type).'">'.checkId('15:00','15:30','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('15:00','15:30','th',$type).'">'.checkId('15:00','15:30','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('15:00','15:30','f',$type).'">'.checkId('15:00','15:30','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('15:00','15:30','s',$type).'">'.checkId('15:00','15:30','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">3:00-3:30</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">3:30-4:00</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('15:30','16:00','m',$type).'">'.checkId('15:30','16:00','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('15:30','16:00','t',$type).'">'.checkId('15:30','16:00','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('15:30','16:00','w',$type).'">'.checkId('15:30','16:00','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('15:30','16:00','th',$type).'">'.checkId('15:30','16:00','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('15:30','16:00','f',$type).'">'.checkId('15:30','16:00','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('15:30','16:00','s',$type).'">'.checkId('15:30','16:00','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">3:30-4:00</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">4:00-4:30</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('16:00','16:30','m',$type).'">'.checkId('16:00','16:30','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('16:00','16:30','t',$type).'">'.checkId('16:00','16:30','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('16:00','16:30','w',$type).'">'.checkId('16:00','16:30','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('16:00','16:30','th',$type).'">'.checkId('16:00','16:30','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('16:00','16:30','f',$type).'">'.checkId('16:00','16:30','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('16:00','16:30','s',$type).'">'.checkId('16:00','16:30','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">4:00-4:30</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">4:30-5:00</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('16:30','17:00','m',$type).'">'.checkId('16:30','17:00','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('16:30','17:00','t',$type).'">'.checkId('16:30','17:00','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('16:30','17:00','w',$type).'">'.checkId('16:30','17:00','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('16:30','17:00','th',$type).'">'.checkId('16:30','17:00','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('16:30','17:00','f',$type).'">'.checkId('16:30','17:00','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('16:30','17:00','s',$type).'">'.checkId('16:30','17:00','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">4:30-5:00</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">5:00-5:30</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('17:00','17:30','m',$type).'">'.checkId('17:00','17:30','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('17:00','17:30','t',$type).'">'.checkId('17:00','17:30','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('17:00','17:30','w',$type).'">'.checkId('17:00','17:30','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('17:00','17:30','th',$type).'">'.checkId('17:00','17:30','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('17:00','17:30','f',$type).'">'.checkId('17:00','17:30','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('17:00','17:30','s',$type).'">'.checkId('17:00','17:30','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">5:00-5:30</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">5:30-6:00</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('17:30','18:00','m',$type).'">'.checkId('17:30','18:00','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('17:30','18:00','t',$type).'">'.checkId('17:30','18:00','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('17:30','18:00','w',$type).'">'.checkId('17:30','18:00','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('17:30','18:00','th',$type).'">'.checkId('17:30','18:00','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('17:30','18:00','f',$type).'">'.checkId('17:30','18:00','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('17:30','18:00','s',$type).'">'.checkId('17:30','18:00','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">5:30-6:00</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">6:00-6:30</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('18:00','18:30','m',$type).'">'.checkId('18:00','18:30','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('18:00','18:30','t',$type).'">'.checkId('18:00','18:30','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('18:00','18:30','w',$type).'">'.checkId('18:00','18:30','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('18:00','18:30','th',$type).'">'.checkId('18:00','18:30','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('18:00','18:30','f',$type).'">'.checkId('18:00','18:30','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('18:00','18:30','s',$type).'">'.checkId('18:00','18:30','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">6:00-6:30</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">6:30-7:00</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('18:30','19:00','m',$type).'">'.checkId('18:30','19:00','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('18:30','19:00','t',$type).'">'.checkId('18:30','19:00','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('18:30','19:00','w',$type).'">'.checkId('18:30','19:00','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('18:30','19:00','th',$type).'">'.checkId('18:30','19:00','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('18:30','19:00','f',$type).'">'.checkId('18:30','19:00','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('18:30','19:00','s',$type).'">'.checkId('18:30','19:00','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">6:30-7:00</td>
								</tr>
								<tr>
									<td style="text-align: center; border: 1px dotted #000;">7:00-7:30</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('19:00','19:30','m',$type).'">'.checkId('19:00','19:30','m',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('19:00','19:30','t',$type).'">'.checkId('19:00','19:30','t',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('19:00','19:30','w',$type).'">'.checkId('19:00','19:30','w',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('19:00','19:30','th',$type).'">'.checkId('19:00','19:30','th',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('19:00','19:30','f',$type).'">'.checkId('19:00','19:30','f',$type).'</td>
									<td style="color: #000; text-align: left; font-size:  12px;'.checkScheds('19:00','19:30','s',$type).'">'.checkId('19:00','19:30','s',$type).'</td>
									<td style="text-align: center; border: 1px dotted #000;">7:00-7:30</td>
								</tr>
							</table></body></html>';

// // reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

$pdf = $dompdf->output();

$dompdf->stream('schedule-'.$id.'-'.date('Ymd'));

$savein = 'uploads/policy_doc/';

//file_put_contents($savein.str_replace("/","-",$filename), $pdf);    // save the pdf file on server
unset($html);
unset($dompdf);

// Output the generated PDF to Browser
?>