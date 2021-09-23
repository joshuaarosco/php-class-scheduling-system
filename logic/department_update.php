<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;
if($_POST)
{
include('../dist/includes/dbcon.php');
	$id = $_POST['id'];
	$code =$_POST['code'];
	$name =$_POST['name'];
	
	mysqli_query($con,"update dept set dept_code='$code',dept_name='$name' where dept_id='$id'")or die(mysqli_error());
	$_SESSION['logs'] = nl2br( $_SESSION['logs'].date('h:i:s').'_Successfully updated a department!&#13;&#10;');
	echo "<script type='text/javascript'>alert('Successfully updated a department!');</script>";	
	echo "<script>document.location='../pages/department.php'</script>";  
}	
	
?>
