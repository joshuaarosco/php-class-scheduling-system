
 <?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;

if($_POST)
{
include('../dist/includes/dbcon.php');

	$code = $_POST['code'];			
	$title = $_POST['title'];	
	$units = $_POST['units'];			
	$prerequisite = $_POST['prerequisite'];					
	$member=$_SESSION['id'];

	$query=mysqli_query($con,"select * from subject where subject_code='$code'")or die(mysqli_error());
			$count=mysqli_num_rows($query);		
			if ($count>0)
				{
					echo "<script type='text/javascript'>alert('Subject already added!');</script>";	
				echo "<script>document.location='../pages/subject.php'</script>";  
				}
			else
			{	
				mysqli_query($con,"INSERT INTO subject(subject_code,subject_title,subject_units,prerequisite,member_id) 
				VALUES('$code','$title','$units','$prerequisite','$member')")or die(mysqli_error());
				
				echo "<script type='text/javascript'>alert('Successfully added a subject!');</script>";	
				echo "<script>document.location='../pages/subject.php'</script>";  
			}
}					  
	
?>