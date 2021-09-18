<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;
include("../dist/includes/dbcon.php");

$id=$_REQUEST['id'];

$result=mysqli_query($con,"DELETE FROM schedule WHERE sched_id ='$id'")
	or die(mysqli_error());
	echo "<script type='text/javascript'>alert('Successfully deleted a schedule!');</script>";	
			
	echo "<script>document.location='../pages/sched_display.php'</script>";  
?>