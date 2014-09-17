<?php

if (isset($_POST['text'])) {

$con = mysql_connect("localhost","root","Chennai12");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("assembler", $con);

$ddd=$_POST['text'];
	$query = "INSERT INTO message (message) VALUES ('$ddd')";
	
}

?>