
 <?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;
if($_POST)
{
include('../dist/includes/dbcon.php');
	$id = $_POST['id'];
	$code = $_POST['code'];
	$title =$_POST['title'];
	$units =$_POST['units'];
	$prerequisite =$_POST['prerequisite'];
	
	mysqli_query($con,"update subject set subject_code='$code',subject_title='$title', subject_units ='$units', prerequisite='$prerequisite' where subject_id='$id'")or die(mysqli_error());
	echo "<script type='text/javascript'>alert('Successfully updated a subject!');</script>";	
	echo "<script>document.location='../pages/subject.php'</script>";  
}	
	
?>
