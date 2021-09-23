
 <?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;
include("../dist/includes/dbcon.php");

$id=$_REQUEST['id'];
$result=mysqli_query($con,"DELETE FROM sy WHERE sy_id ='$id'")
	or die(mysqli_error());
	$_SESSION['logs'] = nl2br( $_SESSION['logs'].date('h:i:s').'_Successfully deleted a school year!&#13;&#10;');
	echo "<script type='text/javascript'>alert('Successfully deleted a school year!');</script>";	
	echo "<script>document.location='../pages/sy.php'</script>";  
?>