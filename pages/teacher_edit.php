
<?php session_start();
if(empty($_SESSION['id'])):
	header('Location:../index.php');
endif;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Member | <?php include('../dist/includes/title.php');?></title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
	<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="../plugins/select2/select2.min.css">
	<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
	<link rel="stylesheet" href="../dist/css/main.css">
	<script src="../dist/js/jquery.min.js"></script>
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-yellow layout-top-nav" onload="myFunction()">
	<div class="wrapper">
		<?php include('../dist/includes/header.php');?>
		<div class="content-wrapper">
			<div class="container">
				<!-- Content Header (Page header) -->

				
				<!-- Main content -->
				<section class="content">
					<div class="row">
            <div class="col-md-1 mr-50">
            </div>
            <div class="col-md-3">
              <?php include('../dist/includes/nav.php')?>
            </div>
          </div>
          <div class="row mt-13">
            <div class="col-md-2">
            </div>
            <div class="col-md-6">
              <?php include('../dist/includes/entry_nav.php')?>
            </div>
          </div>
					<div class="row mt-13">
						<div class="col-md-2"></div>
						<div class="col-md-7">
							<div class="box border-1">
								<div class="box-body">
									<div class="row">
										<div class="col-md-12">
											<table id="example1" class="table table-bordered table-striped border-1" style="margin-right:-10px">
												<thead>
													<tr>
														<th></th>
														<th>Last Name</th>
														<th>First Name</th>
														<th>Rank</th>
														<th>Department</th>
														<th>Designation</th>
														<th>Username</th>
														<th>Status</th>
														<th>Action</th>
													</tr>
												</thead>
												<?php
												include('../dist/includes/dbcon.php');
												$query=mysqli_query($con,"select * from member left outer join designation on member.designation_id=designation.designation_id order by member_last")or die(mysqli_error());

												while($row=mysqli_fetch_array($query)){
													$id=$row['member_id'];
													$last=$row['member_last'];
													$first=$row['member_first'];
													$rank=$row['member_rank'];
													$salut=$row['member_salut'];
													$dept=$row['dept_code'];
													$designation=$row['designation_name'];
													$username=$row['username'];
													$password=$row['password'];
													$status=$row['status'];
													?>
													<tr>
														<td><?php echo $salut;?></td>
														<td><?php echo $last;?></td>
														<td><?php echo $first;?></td>
														<td><?php echo $rank;?></td>
														<td><?php echo $dept;?></td>
														<td><?php echo $designation;?></td>
														<td><?php echo $username;?></td>

														<td><?php echo $status;?></td>
														<td>
															<a id="click" class="mr-5" href="teacher_edit.php?id=<?php echo $id;?>">
																<i class="glyphicon glyphicon-edit text-black"></i>
																<span class="text-black">Edit</span>
															</a>
															<a id="removeme" href="../logic/teacher_del.php?id=<?php echo $id;?>">
																<i class="glyphicon glyphicon-remove-circle text-black"></i>
																<span class="text-black">Delete</span>
															</a>
														</td>
													</tr>
												<?php }?>           
											</table>  
										</div><!--col end -->      
									</div><!--row end-->   
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col (right) -->
						<div class="col-md-2">
							<?php 
							include('../dist/includes/dbcon.php');
							$id=$_REQUEST['id'];
							$query=mysqli_query($con,"select * from member left outer join designation on member.designation_id=designation.designation_id where member_id='$id'")or die(mysqli_error($con));
							$row=mysqli_fetch_array($query);
							?>
							<div class="box border-1">
								<form method="post" action="../logic/teacher_update.php">
									<div class="box-body">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<span for="date">Update Teacher</span>
													<input type="hidden" class="form-control border-1" name="id" value="<?php echo $row['member_id'];?>" required>
													<select class="form-control border-1 mt-5" name="salut" required>
														<option><?php echo $row['member_salut'];?></option>
														<?php 
														$query2=mysqli_query($con,"select * from salut order by salut")or die(mysqli_error($con));
														while($row2=mysqli_fetch_array($query2)){
															?>
															<option><?php echo $row2['salut'];?></option>
														<?php }
														?>
													</select>
													<input type="text" class="form-control border-1 mt-13" name="first" placeholder="First Name" value="<?php echo $row['member_first'];?>" required>	
													<input type="text" class="form-control border-1 mt-13" name="last" placeholder="Last Name" value="<?php echo $row['member_last'];?>" required>
													<select class="form-control border-1 mt-13" name="rank" required>
														<option><?php echo $row['member_rank'];?></option>
														<?php 
														$query2=mysqli_query($con,"select * from rank order by rank")or die(mysqli_error($con));
														while($row2=mysqli_fetch_array($query2)){
															?>
															<option><?php echo $row2['rank'];?></option>
														<?php }

														?>
													</select>	
													<select class="form-control border-1 mt-13" name="dept" required>
														<option><?php echo $row['dept_code'];?></option>
														<?php 
														$query2=mysqli_query($con,"select * from dept order by dept_code")or die(mysqli_error($con));
														while($row2=mysqli_fetch_array($query2)){
															?>
															<option><?php echo $row2['dept_code'];?></option>
														<?php }

														?>
													</select>
													<select class="form-control border-1 mt-13" name="designation" required>
														<option value="<?php echo $row['designation_id'];?>"><?php echo $row['designation_name'];?></option>
														<?php 
														$query2=mysqli_query($con,"select * from designation order by designation_name")or die(mysqli_error($con));
														while($row2=mysqli_fetch_array($query2)){
															?>
															<option value="<?php echo $row2['designation_id'];?>"><?php echo $row2['designation_name'];?></option>
														<?php }

														?>
													</select>
													<select class="form-control border-1 mt-13" name="status" required>
														<option><?php echo $row['status'];?></option>
														<option>admin</option>
														<option>user</option>
													</select>
												</div>
											</div>
										</div>	
										<div class="btn-group w-100" role="group" aria-label="First group">
											<button class="btn w-50 btn-flat btn-primary btn-secondary" id="daterange-btn" name="save" type="submit">Save Changes</button>
											<a class="btn w-50 btn-flat btn-link btn-secondary " id="daterange-btn" href="teacher.php">Cancel</a>
										</div>
									</div><!-- /.form group -->
								</form>	
							</div><!--col end -->
							<div class="mt-13">
								<span class="ml-5 glyphicon glyphicon-edit"></span>
								<span class="ml-5">Log:</span>
								<div>
									<textarea rows="10" class="w-100 log border-1" readonly><?php echo $_SESSION['logs'];?></textarea>
								</div>
							</div>
						</div><!-- /.box-body -->
					</div><!-- /.box -->
				</div><!-- /.col (right) -->
			</div><!-- /.row -->
		</section><!-- /.content -->
	</div><!-- /.container -->
</div><!-- /.content-wrapper -->
<?php include('../dist/includes/footer.php');?>
</div><!-- ./wrapper -->
<script type="text/javascript" src="autosum.js"></script>
<!-- jQuery 2.1.4 -->
<script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="../dist/js/jquery.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../plugins/select2/select2.full.min.js"></script>
<!-- SlimScroll -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>

<script>
	$(function () {
		$("#example1").DataTable();
		$('#example2').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": false,
			"ordering": true,
			"info": true,
			"autoWidth": false
		});
	});
</script>
</body>
</html>
