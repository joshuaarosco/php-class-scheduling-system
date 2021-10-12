<?php
$con = mysqli_connect("localhost","root","dev123","scheduling");

if ($con->connect_error) { 
  die("Connection failed: " . $con->connect_error); 
}

