<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;
if($_POST)
{
include('../dist/includes/dbcon.php');
	$id = $_POST['id'];
	$room =$_POST['room'];
	
	mysqli_query($con,"update room set room='$room' where room_id='$id'")or die(mysqli_error());
	$_SESSION['logs'] = nl2br( $_SESSION['logs'].date('h:i:s').'_Successfully updated a room!&#13;&#10;');
	echo "<script type='text/javascript'>alert('Successfully updated a room!');</script>";	
	echo "<script>document.location='../pages/room.php'</script>";  
}	
	
?>
