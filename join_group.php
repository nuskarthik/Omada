<?php
//category.php
include 'connect.php';
include 'header.php';
if($_SESSION['signed_in'] == true)
{
	$opengroup="SELECT * FROM groups WHERE group_id='" . mysql_real_escape_string($_GET['varname']) . "' AND group_level='1' LIMIT 1";
	$opengroupresult=mysql_query($opengroup);
	if(!$opengroupresult)
	{
		echo 'Please try again';
	}
	else
	{
		if(mysql_num_rows($opengroupresult)> 0)
		{
			$addmember="INSERT INTO membership(group_id,user_id,user_level,approved) 
			VALUES('" . mysql_real_escape_string($_GET['varname']) . "',
			'" . mysql_real_escape_string($_SESSION['user_id']) . "','1','1')";
			$result = mysql_query($addmember);
			if(!$result)
			{
				echo 'Results could not be updated.. Please try again later.' . mysql_error();
			}
			else
			{
				echo '<br />You have successfully joined the group. <a href="'.$_GET['urlname'].'">Return to the group.</a>.';
			}
			$changenumber="UPDATE groups SET number_of_members=number_of_members+1 WHERE group_id='".mysql_real_escape_string($_GET['varname'])."' LIMIT 1";
			$numresult = mysql_query($changenumber);
			if(!$numresult)
			{
				echo 'Results could not be updated.. Please try again later.' . mysql_error();
			}
			
		}
		else
		{
			$addmember = "INSERT INTO membership(group_id,user_id,approved,post_date)
							VALUES('" . mysql_real_escape_string($_GET['varname']) . "',
							'" . mysql_real_escape_string($_SESSION['user_id']) . "',0,NOW())";
			$result = mysql_query($addmember);
			if(!$result)
			{
				echo 'Results could not be updated.. Please try again later.' . mysql_error();
			}
			else
			{
				echo '<br />Requests sent to the admins of the group. <a href="'.$_GET['urlname'].'">Return to the group.</a>.';
			}
		}
	}
}
include 'footer.php';
?>