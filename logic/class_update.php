<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;
if($_POST)
{
include('../dist/includes/dbcon.php');
	$id = $_POST['id'];
	$class =$_POST['class'];
	
	mysqli_query($con,"update cys set cys='$class' where cys_id='$id'")or die(mysqli_error());
	$_SESSION['logs'] = nl2br( $_SESSION['logs'].date('h:i:s').'_Successfully updated a class!&#13;&#10;');
	echo "<script type='text/javascript'>alert('Successfully updated a class!');</script>";	
	echo "<script>document.location='../pages/class.php'</script>";  
}	
	
?>
