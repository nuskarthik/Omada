<?php
//category.php
include 'connect.php';
include 'header.php';
if($_SESSION['signed_in'] == true)
{
	$removemember = "DELETE FROM membership
						WHERE group_id =". mysql_real_escape_string($_GET['varname'])."
						AND user_id =". mysql_real_escape_string($_SESSION['user_id']);
	$result = mysql_query($removemember);
	if(!$result)
	{
		echo 'Results could not be updated.. Please try again later.' . mysql_error();
	}
	else
	{
		echo '<br />Successfully left the group. <a href="'.$_GET['urlname'].'">Return to the group.</a>.';
	}
}
include 'footer.php';
?>