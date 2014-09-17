<?php
//category.php
include 'connect.php';
include 'header.php';
if($_SESSION['signed_in'] == true)
{
	$addmember = "INSERT INTO membership(group_id,user_id)
							VALUES('" . mysql_real_escape_string($_GET['varname']) . "',
							'" . mysql_real_escape_string($_SESSION['user_id']) . "')";
	$result = mysql_query($addmember);
	if(!$result)
	{
		echo 'Results could not be updated.. Please try again later.' . mysql_error();
	}
	else
	{
		echo '<br />Added successfully to the group. <a href="'.$_GET['urlname'].'">Return to the group.</a>.';
	}
}
include 'footer.php';
?>