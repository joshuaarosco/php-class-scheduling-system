<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;
if($_POST)
{
include('../dist/includes/dbcon.php');
	$id = $_POST['id'];
	$designation =$_POST['designation'];
	
	mysqli_query($con,"update designation set designation_name='$designation' where designation_id='$id'")or die(mysqli_error());
	$_SESSION['logs'] = nl2br( $_SESSION['logs'].date('h:i:s').'_Successfully updated a designation!&#13;&#10;');
	echo "<script type='text/javascript'>alert('Successfully updated a designation!');</script>";	
	echo "<script>document.location='../pages/designation.php'</script>";  
}	
	
?>
