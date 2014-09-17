<?php
//category.php
include 'connect.php';
include 'header.php';
if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
if($_SESSION['activation']==0)
					{
						echo 'Please activate your account first. Check your email for the activation mail. Be sure to check the Spam folder.';
						die();
					}
    else{

$groupexistquery="SELECT * FROM groups WHERE group_id='".mysql_real_escape_string($_GET['id'])."'";
$groupexist=mysql_query($groupexistquery);
if(!$groupexist)
{
	echo 'Experiencing some difficulties loading the group. Try again later.';
}
else
{
if(mysql_num_rows($groupexist)==0)
{
	echo 'This group does not exist.';
}
else
{

$areyouamemberquery="SELECT * FROM membership WHERE user_id='".mysql_real_escape_string($_SESSION['user_id'])."' AND group_id='".mysql_real_escape_string($_GET['id'])."' LIMIT 1";
$areyouamember=mysql_query($areyouamemberquery);
if(!$areyouamember)
{
	echo 'Something went wrong. Please wait and reload.';
	exit();
}
else
{
	if(mysql_num_rows($areyouamember)>0)
	{
			//first select the category based on $_GET['cat_id']
			$sql = "SELECT
						groups.group_id,
						groups.group_name,
						groups.group_description
					FROM
						groups
					WHERE
						groups.group_id = " . mysql_real_escape_string($_GET['id']);

			$result = mysql_query($sql);

			if(!$result)
			{
				echo 'The category could not be displayed, please try again later.' . mysql_error();
			}
			else
			{
				
				$var_value=$_GET['id'];
				$url_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				echo '<form method="get" action="leave_group.php">
				<input type="hidden" name="varname" value="'.$var_value.'">
				<input type="hidden" name="urlname" value="'.$url_link.'">
				<input type="submit" value="Leave group">
				</form>';
				
				
				echo '<form method="get" action="invite_member.php">
				<input type="hidden" name="varname" value="'.$var_value.'">
				<input type="hidden" name="urlname" value="'.$url_link.'">
				<input type="submit" value="Invite member">
				</form>';
				
				echo '<form method="get" action="group_details.php">
				<input type="hidden" name="id" value="'.$var_value.'">
				<input type="hidden" name="urlname" value="'.$url_link.'">
				<input type="submit" value="Group Details">
				</form>';
			}

					
						
			//display category data
			while($row = mysql_fetch_assoc($result))
			{
				echo '<h2>Topics in &prime;' . $row['group_name'] . '&prime; group</h2><br />';
			}

			//do a query for the topics
			$topicsql = "SELECT	
						topic_id,
						topic_subject,
						topic_date,
						topic_cat
					FROM
						topics
					WHERE
						topic_cat = " . mysql_real_escape_string($_GET['id']);
			
			$topicresult = mysql_query($topicsql);
			
			if(!$topicresult)
			{
				echo 'The topics could not be displayed, please try again later.';
			}
			else
			{
				if(mysql_num_rows($topicresult) == 0)
				{
					echo 'There are no topics in this category yet.';
				}
				else
				{
					//prepare the table
					echo '<table border="1">
						  <tr>
							<th>Topic</th>
							<th>Created at</th>
						  </tr>';	
						
					while($topicrow = mysql_fetch_assoc($topicresult))
					{				
						echo '<tr>';
							echo '<td class="leftpart">';
								echo '<h3><a href="topic.php?id=' . $topicrow['topic_id'] . '">' . $topicrow['topic_subject'] . '</a><br /><h3>';
							echo '</td>';
							echo '<td class="rightpart">';
								echo date('d-m-Y', strtotime($topicrow['topic_date']));
							echo '</td>';
						echo '</tr>';
					}	
				}
			}

	}
	else
	{
		echo 'You are not a member of this group.';
		$var_value=$_GET['id'];
							$url_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
							echo '<form method="get" action="join_group.php">
							<input type="hidden" name="varname" value="'.$var_value.'">
							<input type="hidden" name="urlname" value="'.$url_link.'">
							<input type="submit" value="Join group">
							</form>';
	}

}
}
}
}
}
else
{
	echo 'Please <a href="signin.php">sign in</a> to access the groups.';
}
include 'footer.php';
?>
