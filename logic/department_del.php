<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;
include("../dist/includes/dbcon.php");
$id=$_REQUEST['id'];
$result=mysqli_query($con,"DELETE FROM dept WHERE dept_id ='$id'")
	or die(mysqli_error());
	$_SESSION['logs'] = nl2br( $_SESSION['logs'].date('h:i:s').'_Successfully deleted a department!&#13;&#10;');
	echo "<script type='text/javascript'>alert('Successfully deleted a department!');</script>";	
	echo "<script>document.location='../pages/department.php'</script>";  
?>