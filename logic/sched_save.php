<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;
include('../dist/includes/dbcon.php');

	$start_time = $_POST['start_time'];
	$end_time = $_POST['end_time'];
	$day = $_POST['day'];
	$member_id = $_POST['teacher'];
	$subject_code = $_POST['subject'];
	$cys = $_POST['cys'];
	$room = $_POST['room'];
	$settings_id = $_SESSION['settings'];
	$encoded_by = $_SESSION['id'];

	$counter = 0;

	if($start_time > $end_time){
		$counter++;
		echo "<script type='text/javascript'>alert('Start Time must be lower than the End Time');
		document.location='../pages/home.php'</script>";
	}

	//check room availability
	$query = mysqli_query($con,"SELECT * FROM schedule 
		WHERE room = '$room' 
		AND day='$day'") or die(mysqli_error($con));

	while($row = mysqli_fetch_array($query)){
		if( ($start_time >= $row['start_time'] AND $start_time <= $row['end_time']) 
			OR ($end_time >= $row['start_time'] AND $end_time <= $row['end_time']) 
			OR ($start_time < $row['start_time'] AND $end_time > $row['end_time'])
		){
			$counter++;
			echo "<script type='text/javascript'>alert('Room $room is not available');
		  			document.location='../pages/home.php'</script>";
		}
	}

	//check member/teacher availability
	$query2 = mysqli_query($con,"SELECT * FROM schedule 
		WHERE member_id = '$member_id'
		AND day='$day'") or die(mysqli_error($con));

	while($row = mysqli_fetch_array($query2)){
		if( ($start_time >= $row['start_time'] AND $start_time <= $row['end_time']) 
			OR ($end_time >= $row['start_time'] AND $end_time <= $row['end_time']) 
			OR ($start_time < $row['start_time'] AND $end_time > $row['end_time'])
		){
			$counter++;
			echo "<script type='text/javascript'>alert('The selected teacher is not available');
		  			document.location='../pages/home.php'</script>";
		}
	}

	//check class availability
	$query3 = mysqli_query($con,"SELECT * FROM schedule 
		WHERE cys = '$cys'
		AND day='$day'") or die(mysqli_error($con));

	while($row = mysqli_fetch_array($query3)){
		if( ($start_time >= $row['start_time'] AND $start_time <= $row['end_time']) 
			OR ($end_time >= $row['start_time'] AND $end_time <= $row['end_time']) 
			OR ($start_time < $row['start_time'] AND $end_time > $row['end_time'])
		){
			$counter++;
			echo "<script type='text/javascript'>alert('Class $cys is not available');
		  			document.location='../pages/home.php'</script>";
		}
	}

	if($counter == 0){
		$member = mysqli_query($con,"SELECT * FROM member WHERE member_id = '$member_id'") or die(mysqli_error($con));
		$fetched_member = mysqli_fetch_array($member);


		mysqli_query($con,"INSERT INTO schedule(start_time,end_time,day,member_id,subject_code,cys,room,settings_id,encoded_by) 
			VALUES('$start_time','$end_time','$day','$member_id','$subject_code','$cys','$room','$settings_id','$encoded_by')")or die(mysqli_error($con));

		$_SESSION['logs'] = nl2br( $_SESSION['logs'].$start_time.' to '.$end_time.'_'.$fetched_member['member_first'].' '.$fetched_member['member_last'].'_'.$cys.'_'.$room.'_Schedule successfully added&#13;&#10;');

		$counter = 0;

		echo "<script>document.location='../pages/home.php'</script>";
	}
	
?>
