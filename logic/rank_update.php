<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;
if($_POST)
{
include('../dist/includes/dbcon.php');
	$id = $_POST['id'];
	$rank =$_POST['rank'];
	
	mysqli_query($con,"update rank set rank='$rank' where rank_id='$id'")or die(mysqli_error());
	
	$_SESSION['logs'] = nl2br( $_SESSION['logs'].date('h:i:s').'_Successfully updated a rank!&#13;&#10;');
	echo "<script type='text/javascript'>alert('Successfully updated a rank!');</script>";	
			echo "<script>document.location='../pages/rank.php'</script>";  
}	
	
?>
