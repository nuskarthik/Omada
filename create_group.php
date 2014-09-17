<?php
//create_cat.php
include 'connect.php';
include 'header.php';

echo '<h2>Create a group</h2>';
if($_SESSION['signed_in'] == false)
{
	//the user is not an admin
	echo 'Sorry, you do not have sufficient rights to access this page.';
}
else
{

if($_SESSION['activation']==0)
					{
						echo 'Please activate your account first. Check your email for the activation mail. Be sure to check the Spam folder.';
						die();
					}
    else{
	//the user has admin rights
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{
		//the form hasn't been posted yet, display it
		echo '<form method="post" action="">
			Group name: <input type="text" name="group_name" /><br />
			Group description:<br /> <textarea name="group_description" /></textarea><br /><br />
			Group type: <br/> <select name="type">
			<option selected="selected" value="1">Open (No admin)</option>
			<option value="2">Closed(Admin required)</option>
			</select><br />
			Group Visibility: (appearance in search) <br /><select name="visibility">
			<option selected="selected" value="0">Visible</option>
			<option value="1">Secret</option>
			</select><br />
			Group Tags:<br /> <textarea name="group_tags" /></textarea><br /><br />
			<input type="submit" value="Create group" />
		 </form>';
	}
	else
	{
		if(isset($_POST['group_name']))
		{
		$check="SELECT group_name FROM groups";
		$ifgroupexist = mysql_query($check);
		if(!$ifgroupexist)
		{
		echo 'Please try again later.' . mysql_error();
		}
		else
		{	
			if(mysql_num_rows($ifgroupexist) != 0)
			{	
				$flag=0;
				while($group = mysql_fetch_assoc($ifgroupexist) && $flag ==0 )
				{
					if(strcmp($group['group_name'], $_POST['group_name'])==0)
					{
						$flag=1;
						break;
					}
				}
				if($flag==1)
				{
					$errors[] = 'This group name already exists.';
				}
			}
		}
	}
	else{
		$errors[] = 'Please enter a group name.';
	}
	if(!isset($_POST['group_name']))
	{
		$errors[] = 'Please enter a group description.';
	}
	if(!isset($_POST['group_tags']))
	{
		$errors[] = 'Please enter at least 1 tag.';
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
		//the form has been posted, so save it
		$sql = "INSERT INTO groups(group_name, group_description,group_level,visibility,create_datetime,founder)
		   VALUES('" . mysql_real_escape_string($_POST['group_name']) . "',
				 '" . mysql_real_escape_string($_POST['group_description']) . "',
				 '" . mysql_real_escape_string($_POST['type']) . "',
				 '" . mysql_real_escape_string($_POST['visibility']) . "',NOW(),
				 '" . $_SESSION['user_name']."')";
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
					if($_POST['type']==1)
					{
					$sql = "INSERT INTO membership(group_id,user_id,user_level)
							VALUES('" . mysql_real_escape_string($row['group_id']) . "',
							'" . mysql_real_escape_string($_SESSION['user_id']) . "',1)";
					}
					else
					{
					$sql = "INSERT INTO membership(group_id,user_id,user_level)
							VALUES('" . mysql_real_escape_string($row['group_id']) . "',
							'" . mysql_real_escape_string($_SESSION['user_id']) . "',2)";
					}
					$memberresult = mysql_query($sql);
					if(!$memberresult)
						{
							//something went wrong, display the error
							echo 'Error' . mysql_error();
						}
						else
						{
							$changenumber="UPDATE groups SET number_of_members=number_of_members+1 WHERE group_id='".mysql_real_escape_string($row['group_id'])."' LIMIT 1";
							$numresult = mysql_query($changenumber);
							if(!$result||!$numresult)
							{
								echo 'Results could not be updated.. Please try again later.' . mysql_error();
							}
							else
							{
								echo '<br>New user added to group.';
							}
							
						}	
						
					echo $_POST['group_tags'];
					$tags = explode(",", $_POST['group_tags']);
					echo count($tags);
					for ($x = 0; $x < count($tags); $x++){
					echo $tags[$x];
					$addtagquery="INSERT INTO tags(group_id,tag) VALUES('".$row['group_id']."','".$tags[$x]."')";
					$tagresult=mysql_query($addtagquery);
					if(!$tagresult)
					{
						echo "Something went wrong entering ".$tags[x].". Please try again later.". mysql_error();;
					}
					}
				}
			}
		}
	}
	}
}
}

include 'footer.php';
?>
