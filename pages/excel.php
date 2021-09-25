<?php
session_start();
if(empty($_SESSION['id'])):
  header('Location:../index.php');
endif;

include('../dist/includes/dbcon.php');
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
				return 'Teacher: '.$member['member_first'].' '.$member['member_last'].'; Class: '.$row['cys'].'; Subject: '.$row['subject_code'].'; Units: '.$sub['subject_units'].'; Room: '.$row['room'];
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
		return 'Class Schedule';
	}
	if($_type=='member'){
		return 'Faculty Schedule';
	}
	if($_type=='room'){
		return 'Room Schedule';
	}
}

function check($_type){
	if($_type=='class'){
		return 'Class : ';
	}
	if($_type=='member'){
		return 'Faculty : ';
	}
	if($_type=='room'){
		return 'Room : ';
	}
}

$settings = mysqli_query($con,"select * from settings where settings_id='$settings_id'")or die(mysqli_error($con));
$rows = mysqli_fetch_array($settings);

$delimiter = ","; 
$filename = "Schedule-".$id."-".date('Ymd').".csv";

$f = fopen('php://memory', 'w'); 

$fields = array(checkType($type));
fputcsv($f, $fields, $delimiter); 

$fields = array(check($type), '',getInfo($type,$id));
fputcsv($f, $fields, $delimiter); 

$fields = array('School Year : ', '',$rows['sy']);
fputcsv($f, $fields, $delimiter); 

$fields = array('Semester : ', '',$rows['sem']);
fputcsv($f, $fields, $delimiter); 

$fields = array('TIME', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'TIME');
fputcsv($f, $fields, $delimiter); 

$lineData = array('7:00-7:30',checkId('07:00','07:30','m',$type),checkId('07:00','07:30','t',$type),checkId('07:00','07:30','w',$type),checkId('07:00','07:30','th',$type),checkId('07:00','07:30','f',$type),checkId('07:00','07:30','s',$type),'7:00-7:30');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('7:30-8:00',checkId('07:30','08:00','m',$type),checkId('07:30','08:00','t',$type),checkId('07:30','08:00','w',$type),checkId('07:30','08:00','th',$type),checkId('07:30','08:00','f',$type),checkId('07:30','08:00','s',$type),'7:30-8:00');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('8:00-8:30',checkId('08:00','08:30','m',$type),checkId('08:00','08:30','t',$type),checkId('08:00','08:30','w',$type),checkId('08:00','08:30','th',$type),checkId('08:00','08:30','f',$type),checkId('08:00','08:30','s',$type),'8:00-8:30');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('8:30-9:00',checkId('08:30','09:00','m',$type),checkId('08:30','09:00','t',$type),checkId('08:30','09:00','w',$type),checkId('08:30','09:00','th',$type),checkId('08:30','09:00','f',$type),checkId('08:30','09:00','s',$type),'8:30-9:00');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('9:00-9:30',checkId('09:00','09:30','m',$type),checkId('09:00','09:30','t',$type),checkId('09:00','09:30','w',$type),checkId('09:00','09:30','th',$type),checkId('09:00','09:30','f',$type),checkId('09:00','09:30','s',$type),'9:00-9:30');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('9:30-10:00',checkId('09:30','10:00','m',$type),checkId('09:30','10:00','t',$type),checkId('09:30','10:00','w',$type),checkId('09:30','10:00','th',$type),checkId('09:30','10:00','f',$type),checkId('09:30','10:00','s',$type),'9:30-10:00');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('10:00-10:30',checkId('10:00','10:30','m',$type),checkId('10:00','10:30','t',$type),checkId('10:00','10:30','w',$type),checkId('10:00','10:30','th',$type),checkId('10:00','10:30','f',$type),checkId('10:00','10:30','s',$type),'10:00-10:30');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('10:30-11:00',checkId('10:30','11:00','m',$type),checkId('10:30','11:00','t',$type),checkId('10:30','11:00','w',$type),checkId('10:30','11:00','th',$type),checkId('10:30','11:00','f',$type),checkId('10:30','11:00','s',$type),'10:30-11:00');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('11:00-11:30',checkId('11:00','11:30','m',$type),checkId('11:00','11:30','t',$type),checkId('11:00','11:30','w',$type),checkId('11:00','11:30','th',$type),checkId('11:00','11:30','f',$type),checkId('11:00','11:30','s',$type),'11:00-11:30');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('11:30-12:00',checkId('11:30','12:00','m',$type),checkId('11:30','12:00','t',$type),checkId('11:30','12:00','w',$type),checkId('11:30','12:00','th',$type),checkId('11:30','12:00','f',$type),checkId('11:30','12:00','s',$type),'11:30-12:00');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('12:00-12:30',checkId('12:00','12:30','m',$type),checkId('12:00','12:30','t',$type),checkId('12:00','12:30','w',$type),checkId('12:00','12:30','th',$type),checkId('12:00','12:30','f',$type),checkId('12:00','12:30','s',$type),'12:00-12:30');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('12:30-1:00',checkId('12:30','01:00','m',$type),checkId('12:30','01:00','t',$type),checkId('12:30','01:00','w',$type),checkId('12:30','01:00','th',$type),checkId('12:30','01:00','f',$type),checkId('12:30','01:00','s',$type),'12:30-1:00');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('1:00-1:30',checkId('01:00','01:30','m',$type),checkId('01:00','01:30','t',$type),checkId('01:00','01:30','w',$type),checkId('01:00','01:30','th',$type),checkId('01:00','01:30','f',$type),checkId('01:00','01:30','s',$type),'1:00-1:30');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('1:30-2:00',checkId('01:30','02:00','m',$type),checkId('01:30','02:00','t',$type),checkId('01:30','02:00','w',$type),checkId('01:30','02:00','th',$type),checkId('01:30','02:00','f',$type),checkId('01:30','02:00','s',$type),'1:30-2:00');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('2:00-2:30',checkId('02:00','02:30','m',$type),checkId('02:00','02:30','t',$type),checkId('02:00','02:30','w',$type),checkId('02:00','02:30','th',$type),checkId('02:00','02:30','f',$type),checkId('02:00','02:30','s',$type),'2:00-2:30');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('2:30-3:00',checkId('02:30','03:00','m',$type),checkId('02:30','03:00','t',$type),checkId('02:30','03:00','w',$type),checkId('02:30','03:00','th',$type),checkId('02:30','03:00','f',$type),checkId('02:30','03:00','s',$type),'2:30-3:00');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('3:00-3:30',checkId('03:00','03:30','m',$type),checkId('03:00','03:30','t',$type),checkId('03:00','03:30','w',$type),checkId('03:00','03:30','th',$type),checkId('03:00','03:30','f',$type),checkId('03:00','03:30','s',$type),'3:00-3:30');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('3:30-4:00',checkId('03:30','04:00','m',$type),checkId('03:30','04:00','t',$type),checkId('03:30','04:00','w',$type),checkId('03:30','04:00','th',$type),checkId('03:30','04:00','f',$type),checkId('03:30','04:00','s',$type),'3:30-4:00');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('4:00-4:30',checkId('04:00','04:30','m',$type),checkId('04:00','04:30','t',$type),checkId('04:00','04:30','w',$type),checkId('04:00','04:30','th',$type),checkId('04:00','04:30','f',$type),checkId('04:00','04:30','s',$type),'4:00-4:30');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('4:30-5:00',checkId('04:30','05:00','m',$type),checkId('04:30','05:00','t',$type),checkId('04:30','05:00','w',$type),checkId('04:30','05:00','th',$type),checkId('04:30','05:00','f',$type),checkId('04:30','05:00','s',$type),'4:30-5:00');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('5:00-5:30',checkId('05:00','05:30','m',$type),checkId('05:00','05:30','t',$type),checkId('05:00','05:30','w',$type),checkId('05:00','05:30','th',$type),checkId('05:00','05:30','f',$type),checkId('05:00','05:30','s',$type),'5:00-5:30');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('5:30-6:00',checkId('05:30','06:00','m',$type),checkId('05:30','06:00','t',$type),checkId('05:30','06:00','w',$type),checkId('05:30','06:00','th',$type),checkId('05:30','06:00','f',$type),checkId('05:30','06:00','s',$type),'5:30-6:00');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('6:00-6:30',checkId('06:00','06:30','m',$type),checkId('06:00','06:30','t',$type),checkId('06:00','06:30','w',$type),checkId('06:00','06:30','th',$type),checkId('06:00','06:30','f',$type),checkId('06:00','06:30','s',$type),'6:00-6:30');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('6:30-7:00',checkId('06:30','07:00','m',$type),checkId('06:30','07:00','t',$type),checkId('06:30','07:00','w',$type),checkId('06:30','07:00','th',$type),checkId('06:30','07:00','f',$type),checkId('06:30','07:00','s',$type),'6:30-7:00');
fputcsv($f, $lineData, $delimiter); 

$lineData = array('7:00-7:30',checkId('07:00','07:30','m',$type),checkId('07:00','07:30','t',$type),checkId('07:00','07:30','w',$type),checkId('07:00','07:30','th',$type),checkId('07:00','07:30','f',$type),checkId('07:00','07:30','s',$type),'7:00-7:30');
fputcsv($f, $lineData, $delimiter); 


fseek($f, 0); 

// Set headers to download file rather than displayed 
header('Content-Type: text/csv'); 
header('Content-Disposition: attachment; filename="' . $filename . '";'); 

fpassthru($f);

exit;
?>