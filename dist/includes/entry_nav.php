<?php 
$curPageName2 = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);  

$class = ['class.php'];
$room = ['room.php'];
$subject = ['subject.php'];
$teacher = ['teacher.php', 'teacher_edit.php'];
$signatories = ['signatories.php'];
?>
<div class="navi p-10">
    <a class="btn btn-flat mr-10 <?php echo in_array($curPageName2, $class)? 'btn-primary':'btn-link'; ?>" href="class.php">Class</a>
    <a class="btn btn-flat mr-10 <?php echo in_array($curPageName2, $room)? 'btn-primary':'btn-link'; ?>" href="room.php">Room</a>
    <a class="btn btn-flat mr-10 <?php echo in_array($curPageName2, $subject)? 'btn-primary':'btn-link'; ?>" href="subject.php">Subject</a>
    <a class="btn btn-flat mr-10 <?php echo in_array($curPageName2, $teacher)? 'btn-primary':'btn-link'; ?>" href="teacher.php">Teacher</a>
    <a class="btn btn-flat <?php echo in_array($curPageName2, $signatories)? 'btn-primary':'btn-link'; ?>" href="signatories.php">Signatories</a>
</div>