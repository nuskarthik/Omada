<?php
//category.php
include 'connect.php';
include 'header.php';
if($_SESSION['signed_in'] == true)
{
	$approve = "UPDATE requests
				SET approved='1'
					WHERE group_id='" . mysql_real_escape_string($_GET['groupid']) . "'
					AND user_id='" . mysql_real_escape_string($_GET['idvalue']) ."'";
	$result = mysql_query($approve);
	if(!$result)
	{
		echo 'Results could not be obtained. Please try again later.' . mysql_error();
	}
	$approve = "INSERT INTO memberships (user_id, group_id, user_level)
				VALUES(" . mysql_real_escape_string($_GET['groupid']) . "',
				'" . mysql_real_escape_string($_GET['idvalue']) ."',1";
	$result = mysql_query($approve);
	if(!$result)
	{
		echo 'Results could not be obtained. Please try again later.' . mysql_error();
	}
	else
	{
		echo '<br />Requests approved for '.$_GET['username']. '. Member successfully added. <a href="'.$_GET['urlname'].'">Return to the group.</a>.';
	}
}
include 'footer.php';
?>