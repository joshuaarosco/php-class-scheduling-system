<?php
session_start();
include("../dist/includes/dbcon.php");
$id=$_REQUEST['id'];
$result=mysqli_query($con,"DELETE FROM signatories WHERE sign_id ='$id'")
	or die(mysqli_error());
	$_SESSION['logs'] = nl2br( $_SESSION['logs'].date('h:i:s').'_Successfully deleted a signatory!&#13;&#10;');
	echo "<script type='text/javascript'>alert('Successfully deleted a signatory!');</script>";	
	echo "<script>document.location='../pages/signatories.php'</script>";  
?>