
 <?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;

include('../dist/includes/dbcon.php');

	$salut = $_POST['salut'];	
	$last = $_POST['last'];	
	$first = $_POST['first'];	
	$rank = $_POST['rank'];	
	$dept = $_POST['dept'];	
	$designation = $_POST['designation'];	
	$username =preg_replace('/\s+/','',$first).$dept;	
	$username=strtolower($username);
	$password = preg_replace('/\s+/','',$last);	
	$password=strtolower($password);	
	$status = $_POST['status'];	
					
		$query=mysqli_query($con,"select * from member where member_last='$last' and  member_first='$first'")or die(mysqli_error($con));
				$count=mysqli_num_rows($query);
				if ($count>0)
				{
					echo "<script type='text/javascript'>alert('Member already exist');</script>";
					echo "<script>document.location='../pages/teacher.php'</script>";  
				}	
				else{
					mysqli_query($con,"INSERT INTO member(member_salut,member_last,member_first,member_rank,dept_code,designation_id,username,password,status) 
					VALUES('$salut','$last','$first','$rank','$dept','$designation','$username','$password','$status')")or die(mysqli_error($con));
					
					$_SESSION['logs'] = nl2br( $_SESSION['logs'].date('h:i:s').'_Successfuly added new member!&#13;&#10;');
					echo "<script type='text/javascript'>alert('Successfuly added new member');</script>";	
					echo "<script>document.location='../pages/teacher.php'</script>";  
				}
				  
	
?>