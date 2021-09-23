<?php
include("../dist/includes/dbcon.php");
$id=$_REQUEST['id'];
$result=mysqli_query($con,"DELETE FROM program WHERE prog_id ='$id'")
	or die(mysqli_error());
	$_SESSION['logs'] = nl2br( $_SESSION['logs'].date('h:i:s').'_Successfully deleted a program!&#13;&#10;');
	echo "<script type='text/javascript'>alert('Successfully deleted a program!');</script>";	
			
	echo "<script>document.location='../pages/program.php'</script>";  
?>