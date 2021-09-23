
<?php
session_start();
include("../dist/includes/dbcon.php");
$id=$_REQUEST['id'];
$result=mysqli_query($con,"DELETE FROM subject WHERE subject_id ='$id'")
	or die(mysqli_error());
	$_SESSION['logs'] = nl2br( $_SESSION['logs'].date('h:i:s').'_Successfully deleted a subject!&#13;&#10;');
	echo "<script type='text/javascript'>alert('Successfully deleted a subject!');</script>";	
	echo "<script>document.location='../pages/subject.php'</script>";  
?>