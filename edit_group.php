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
				if($_SERVER['REQUEST_METHOD'] != 'POST')
				{
					$getgroupquery="SELECT * from groups where group_id='".$_GET['group']."' LIMIT 1";
					$getgroupresult=mysql_query($getgroupquery);
					if(!$getgroupresult)
					{
						echo 'Something went wrong. Please try later.';
					}
					else
					{
					while($getgrouprow = mysql_fetch_assoc($getgroupresult))
					{
						$gettags="SELECT * from tags where group_id='".$_GET['group']."'";
						$tags=mysql_query($gettags);
						if(!$tags)
						{
							echo 'Something went wrong.<br/> Please try again later.';
							exit();
						}
						else
						{
							echo '<form method="post" action="">
								Group name: <input type="text" name="group_name" value='.$getgrouprow['group_name'].'/><br />
								Group description:<br /> <textarea name="group_description" />'.$getgrouprow['group_description'].'</textarea><br /><br />
								Group type: <br/> <select name="type">
								<option selected="selected" value="1">Open (No admin)</option>
								<option value="2">Closed(Admin required)</option>
								</select><br />
								Group Visibility: (appearance in search) <br /><select name="visibility">
								<option selected="selected" value="0">Visible</option>
								<option value="1">Secret</option>
								</select><br />';
								while($tagrow = mysql_fetch_assoc($tags))
								{
								echo 'Group Tags:<br /><textarea name="group_tags" />'.$tagrow['tag'].', '.'</textarea><br /><br />';
								}
								echo '<input type="submit" value="Create group" />
							 </form>';
						}
					 }
					 }
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
					$sql = "UPDATE groups SET group_name='" . mysql_real_escape_string($_POST['group_name']) . "',
							group_description='" . mysql_real_escape_string($_POST['group_description']) . "',
							group_level='" . mysql_real_escape_string($_POST['type']) . "',
							visibility='" . mysql_real_escape_string($_POST['visibility']) ."'";				 
					$result = mysql_query($sql);
					if(!$result)
					{
						//something went wrong, display the error
						echo 'Error' . mysql_error();
					}
					else
					{
						echo 'Group successfully updated.';
					}
					
					$tags = explode(",", $_POST['group_tags']);
					for ($x = 0; $x < count($tags); $x++){
					$addtagquery="INSERT IGNORE INTO tags(group_id,tag) VALUES('".$row['group_id']."',".$tags[x].")";
					$tagresult=mysql_query($addtagquery);
					if(!$tagresult)
					{
						echo "Something went wrong entering ".$tags[x].". Please try again later.";
					}
					}
				}
				}
			}
	//the user has admin rights
		else
		{
			echo 'Admin privelege required to edit group details.';
		}
	}
}

include 'footer.php';
?>
	