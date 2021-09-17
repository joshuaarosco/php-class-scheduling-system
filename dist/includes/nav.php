<?php 
$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);  

$dahboard = ['home.php', 'settings.php', 'profile.php', 'sched_edit.php'];
$entry = ['class.php', 'room.php', 'subject.php', 'teacher.php', 'teacher_edit.php', 'signatories.php'];
$display = [];
$maintenance = [];
?>

<div class="navi p-10">
	<a class="btn btn-flat mr-10 <?php echo in_array($curPageName, $dahboard)? 'btn-primary':'btn-link'; ?>" href="home.php">Dashboard</a>
	<a class="btn btn-flat mr-10 <?php echo in_array($curPageName, $entry)? 'btn-primary':'btn-link'; ?>" href="class.php">Entry</a>
	<a class="btn btn-flat mr-10 <?php echo in_array($curPageName, $display)? 'btn-primary':'btn-link'; ?>" >Display Schedule</a>
</div>