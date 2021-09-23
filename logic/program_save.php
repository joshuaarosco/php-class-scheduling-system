<?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;

if($_POST)
{
include('../dist/includes/dbcon.php');

	$code = $_POST['code'];		
	$name = $_POST['name'];			
					
		$query=mysqli_query($con,"select * from program where prog_code='$code'")or die(mysqli_error());
			$count=mysqli_num_rows($query);		
			if ($count>0)
			{
				echo "<script type='text/javascript'>alert('Program already added!');</script>";	
				echo "<script>document.location='../pages/program.php'</script>";  
			}	
			else
			{	
			mysqli_query($con,"INSERT INTO program(prog_code,prog_title) 
				VALUES('$code','$name')")or die(mysqli_error());
				$_SESSION['logs'] = nl2br( $_SESSION['logs'].date('h:i:s').'_Successfully added a program!&#13;&#10;');
				echo "<script type='text/javascript'>alert('Successfully added a program!');</script>";	
				echo "<script>document.location='../pages/program.php'</script>";  
			}	
	
}					  
	
?>