<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;

if($_POST)
{
include('../dist/includes/dbcon.php');

	$designation = $_POST['designation'];		

		$query=mysqli_query($con,"select * from designation where designation_name='$designation'")or die(mysqli_error());
			$count=mysqli_num_rows($query);		
			if ($count>0)
			{
				echo "<script type='text/javascript'>alert('Designation already added!');</script>";	
				echo "<script>document.location='../pages/designation.php'</script>";  
			}	
			else
			{		
			mysqli_query($con,"INSERT INTO designation(designation_name) 
				VALUES('$designation')")or die(mysqli_error());
				$_SESSION['logs'] = nl2br( $_SESSION['logs'].date('h:i:s').'_Successfully added a designation!&#13;&#10;');
				echo "<script type='text/javascript'>alert('Successfully added a designation!');</script>";	
				echo "<script>document.location='../pages/designation.php'</script>";  
			}	
			
	
}					  
	
?>