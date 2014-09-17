<?php
//create_cat.php
include 'connect.php';
include 'header.php';

echo '<h2>Create a group</h2>';
if($_SESSION['signed_in'] == false | $_SESSION['user_level'] != 1 )
{
	//the user is not an admin
	echo 'Sorry, you do not have sufficient rights to access this page.';
}
else
{
	//the user has admin rights
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{
		//the form hasn't been posted yet, display it
		echo '<form method="post" action="">
			Group name: <input type="text" name="group_name" /><br />
			Group description:<br /> <textarea name="group_description" /></textarea><br /><br />
			<input type="submit" value="Create group" />
		 </form>';
	}
	else
	{
		//the form has been posted, so save it
		$sql = "INSERT INTO groups(group_name, group_description)
		   VALUES('" . mysql_real_escape_string($_POST['group_name']) . "',
				 '" . mysql_real_escape_string($_POST['group_description']) . "')";
		$result = mysql_query($sql);
		if(!$result)
		{
			//something went wrong, display the error
			echo 'Error' . mysql_error();
		}
		else
		{
			echo 'New group succesfully added.';
		}
		$searchsql="SELECT g.group_id FROM groups g
					WHERE g.group_name =\"". mysql_real_escape_string($_POST['group_name']) ."\"";
		$result = mysql_query($searchsql);
		if(!$result)
		{
		echo 'Please try again later.' . mysql_error();
		}
		else
		{	
			//Only 2 cases either nothing is found
			if(mysql_num_rows($result) == 0)
			{
				echo 'This category does not exist.';
			}
			//Or only one is found- this is because the group name is a unique index in the table
			else
			{
				error_reporting(0);//get a warning here despite having a clear resource.
				while($row = mysql_fetch_assoc($result))
				{
					$sql = "INSERT INTO membership(group_id,user_id)
							VALUES('" . mysql_real_escape_string($row['group_id']) . "',
							'" . mysql_real_escape_string($_SESSION['user_id']) . "')";
							$result = mysql_query($sql);
					if(!$result)
						{
							//something went wrong, display the error
							echo 'Error' . mysql_error();
						}
						else
						{
							echo '<br>New user added to group.';
						}			
				}
			}
		}
	}
}

include 'footer.php';
?>
