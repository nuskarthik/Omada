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
	
	$changenumber="UPDATE groups SET number_of_members=number_of_members-1 WHERE group_id=".mysql_real_escape_string($_GET['varname']);
	$numresult = mysql_query($changenumber);
	
	if(!$result)
	{
		echo 'Membership could not be removed. Please try again later.' . mysql_error();
	}
	else
	{
		if(!$numresult)
		{
			echo 'Number of members could not be decreased. Please try again later.' . mysql_error();
		}
		echo '<br />Successfully left the group. <a href="'.$_GET['urlname'].'">Return to the group.</a>.';
	}
	
}
include 'footer.php';
?>