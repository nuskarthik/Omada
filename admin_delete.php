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
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{
		echo '<form method="post" action="">
		<input type="radio" name="yes" value="1"><b>YES</b>
		<input type="radio" name="yes" value="0"><b>NO</b><br/>
 		<input type="submit" value="Confirm Delete Group" />
 	 </form>';
	}
	else
	{
		if(!isset($_POST['yes']))
		{
			$errors[] = 'Delete not performed. Confirmation required.';
		}
		if(!empty($errors)) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
		{
			echo 'Uh-oh.. a couple of fields are not filled in correctly..<br /><br />';
			echo '<ul>';
			foreach($errors as $key => $value) /* walk through the array so all the errors get displayed */
			{
				echo '<li>' . $value . '</li>'; /* this generates a nice error list */
			}
			echo '</ul>';
		}
		else
		{	
			if(!empty($_POST['yes'])) {

				if(mysql_num_rows($adminresult) >0)
				{
					$removegroup = "DELETE FROM groups
										WHERE group_id =". mysql_real_escape_string($_GET['group']);
										
					$grpresult = mysql_query($removegroup);
				
					if(!$grpresult)
					{
						echo 'Results could not be updated. Please try again later.' . mysql_error();
					}
					else
					{
						echo '<br />Group successfully deleted. <a href="index.php">Return to groups.</a>.';
					}
				}
			}
			else
			{
				echo 'Group not deleted. <a href="'.$_GET['urlname'].'">Return to the group.</a>';
			}
		}
	}
}
}
include 'footer.php';
?>