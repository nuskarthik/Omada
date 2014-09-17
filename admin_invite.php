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
				$adminmember = "UPDATE membership
								SET user_level='2'
									WHERE group_id =". mysql_real_escape_string($_GET['group'])."
									AND user_id =". mysql_real_escape_string($_GET['varname']);
				$result = mysql_query($adminmember);
				
				if(!$result)
				{
					echo 'Results could not be updated.. Please try again later.' . mysql_error();
				}
				else
				{
					echo '<br />Successfully added '.$_GET['varname'].' as an admin of the group. <a href="'.$_GET['urlname'].'">Return to the group.</a>.';
				}
			}
}
}
include 'footer.php';
?>