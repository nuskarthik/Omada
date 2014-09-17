<?php
//create_topic.php
include 'connect.php';
include 'header.php';

echo '<h2>Create a topic</h2>';
if($_SESSION['signed_in'] == false)
{
	//the user is not signed in
	echo 'Sorry, you have to be <a href="signin.php">signed in</a> to create a topic.';
}
else
{


if($_SESSION['activation']==0)
					{
						echo 'Please activate your account first. Check your email for the activation mail. Be sure to check the Spam folder.';
						die();
					}
    else{
	//the user is signed in
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{	
		//the form hasn't been posted yet, display it
		//retrieve the categories from the database for use in the dropdown
		$sql ="SELECT
					group_id
				FROM
					membership
				WHERE
					user_id='".$_SESSION['user_id']."'";
		
		$result = mysql_query($sql);
		
		if(!$result)
		{
			//the query failed, uh-oh :-(
			echo 'Error while selecting from database. Please try again later.';
		}
		else
		{
			if(mysql_num_rows($result) == 0)
			{
				//there are no categories, so a topic can't be posted
					echo 'You have not created categories yet.';
			}
			else
			{
				echo '<form method="post" action="">
					Subject: <input type="text" name="topic_subject" /><br />
					Group:'; 
				
				echo '<select name="topic_cat">';
					while($row = mysql_fetch_assoc($result))
					{
					
					$groupnames="SELECT group_name FROM groups WHERE group_id='".$row['group_id']."'";
					
					$groupsresult = mysql_query($groupnames);
					if(!$groupsresult)
					{
						//the query failed, uh-oh :-(
						echo 'Error while selecting from database. Please try again later.';
					}
					else
					{
						if(mysql_num_rows($result) == 0)
						{
							echo 'NO GROUPS AVAILABLE';
						}
						else
						{
							while($groupnames=mysql_fetch_assoc($groupsresult))
							{
								echo '<option value="' . $row['group_id'] . '">' . $groupnames['group_name'] . '</option>';
							}
						}
					}
					}
				echo '</select><br />';	
					
				echo 'Message: <br /><textarea name="post_content" /></textarea><br /><br />
					<input type="submit" value="Create topic" />
				 </form>';
			}
		}
	}
	else
	{
		//start the transaction
		$query  = "BEGIN WORK;";
		$result = mysql_query($query);
		
		if(!$result)
		{
			//Damn! the query failed, quit
			echo 'An error occured while creating your topic. Please try again later.';
		}
		else
		{
	
			//the form has been posted, so save it
			//insert the topic into the topics table first, then we'll save the post into the posts table
			$sql = "INSERT INTO 
						topics(topic_subject,
							   topic_date,
							   topic_cat,
							   topic_by)
				   VALUES('" . mysql_real_escape_string($_POST['topic_subject']) . "',
							   NOW(),
							   " . mysql_real_escape_string($_POST['topic_cat']) . ",
							   " . $_SESSION['user_id'] . "
							   )";
					 
			$result = mysql_query($sql);
			if(!$result)
			{
				//something went wrong, display the error
				echo 'An error occured while inserting your data. Please try again later.<br /><br />' . mysql_error();
				$sql = "ROLLBACK;";
				$result = mysql_query($sql);
			}
			else
			{
				//the first query worked, now start the second, posts query
				//retrieve the id of the freshly created topic for usage in the posts query
				$topicid = mysql_insert_id();
				
				$sql = "INSERT INTO
							posts(post_content,
								  post_date,
								  post_topic,
								  post_by)
						VALUES
							('" . mysql_real_escape_string($_POST['post_content']) . "',
								  NOW(),
								  " . $topicid . ",
								  " . $_SESSION['user_id'] . "
							)";
				$result = mysql_query($sql);
				
				if(!$result)
				{
					//something went wrong, display the error
					echo 'An error occured while inserting your post. Please try again later.<br /><br />' . mysql_error();
					$sql = "ROLLBACK;";
					$result = mysql_query($sql);
				}
				else
				{
					$sql = "COMMIT;";
					$result = mysql_query($sql);
					
					//after a lot of work, the query succeeded!
					echo 'You have succesfully created <a href="topic.php?id='. $topicid . '">your new topic</a>.';
				}
			}
		}
	}
}
}
include 'footer.php';
?>
