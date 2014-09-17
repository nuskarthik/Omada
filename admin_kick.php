<?php
//category.php
include 'connect.php';
include 'header.php';
if($_SESSION['signed_in'] == true)
{
$admin="SELECT * FROM membership WHERE user_id='".$_SESSION['user_id']."' AND group_id='".mysql_real_escape_string($_GET['group'])."' AND user_level='2'";
$adminresult=mysql_query($admin);
if(!$adminresult)
{
	echo 'Please try again later.';
}
else
{
			if(mysql_num_rows($adminresult) >0)
			{
				$removemember = "DELETE FROM membership
									WHERE group_id =". mysql_real_escape_string($_GET['group'])."
									AND user_id ='". mysql_real_escape_string($_GET['varname'])."'";
				$result = mysql_query($removemember);
				
				if(!$result)
				{
					echo 'Results could not be updated. Please try again later.' . mysql_error();
				}
				else
				{
					echo '<br />User removed from group. <a href="'.$_GET['urlname'].'">Return to the group.</a>.';
				}
				$changenumber="UPDATE groups SET number_of_members=number_of_members-1 WHERE group_id='".mysql_real_escape_string($_GET['group'])."' LIMIT 1";
				$numresult = mysql_query($changenumber);
				
				if(!$numresult)
				{
					echo 'Results could not be updated. Please try again later.' . mysql_error();
				}
				else
				{
					echo '<br />User removed from group. <a href="'.$_GET['urlname'].'">Return to the group.</a>.';
				}
			}
}
}
include 'footer.php';
?>