<?php
//create_cat.php
include 'connect.php';
include 'header.php';

$sql = "SELECT
			topic_id,
			topic_subject
		FROM
			topics
		WHERE
			topics.topic_id = " . mysql_real_escape_string($_GET['id']);
			
$result = mysql_query($sql);

if(!$result)
{
	echo 'The topic could not be displayed, please try again later.';
}
else
{
	if(mysql_num_rows($result) == 0)
	{
		echo 'This topic doesn&prime;t exist.';
	}
	else
	{
		while($row = mysql_fetch_assoc($result))
		{
			//display post data
			echo '<table class="topic" border="1">
					<tr>
						<th colspan="2">' . $row['topic_subject'] . '</th>
					</tr>';
		
			//fetch the posts from the database
			$posts_sql = "SELECT
						posts.post_id,
						posts.post_topic,
						posts.post_content,
						posts.post_date,
						posts.post_by,
						users.user_id,
						users.user_name
					FROM
						posts
					LEFT JOIN
						users
					ON
						posts.post_by = users.user_id
					WHERE
						posts.post_topic = " . mysql_real_escape_string($_GET['id']);
						
			$posts_result = mysql_query($posts_sql);
			
			if(!$posts_result)
			{
				echo '<tr><td>The posts could not be displayed, please try again later.</tr></td></table>';
			}
			else
			{
			
				while($posts_row = mysql_fetch_assoc($posts_result))
				{
					echo '<tr class="topic-post">
							<td class="user-post">' . $posts_row['user_name'] . '<br/>' . date('d-m-Y H:i', strtotime($posts_row['post_date'])) . '</td>
							<td class="post-content">' . htmlentities(stripslashes($posts_row['post_content'])) . '</td>';
						 
						  
					if($posts_row['user_id'] == $_SESSION['user_id']){
					$var_val=$posts_row['post_id'];
					$url_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					echo '<td class="post-content">
							<form method="get" action="delete_reply.php">
								<input type="hidden" name="varname" value="'.$var_val.'">
								<input type="hidden" name="urlname" value="'.$url_link.'">
								<input type="submit" value="delete_reply">
							</form>
							<form method="get" action="edit_reply.php?id=">
								<input type="hidden" name="update" value="0">
								<input type="hidden" name="id" value="'.$var_val.'">
								<input type="hidden" name="urlname" value="'.$url_link.'">
								<input type="submit" value="edit_reply">
							</form>
						 </td>';			
					}
					
					echo '</tr>';
				}
			}
			
			if(!$_SESSION['signed_in'])
			{
				echo '<tr><td colspan=2>You must be <a href="signin.php">signed in</a> to reply. You can also <a href="signup.php">sign up</a> for an account.';
			}
			else
			{
				//show reply box
				$back = "SELECT
							t.topic_cat
						FROM
							topics t
						WHERE
							t.topic_id = ".$row['topic_id'];
				$backrow=mysql_fetch_assoc(mysql_query($back));
				echo '<tr><td colspan="2"><h2>Reply:</h2><br />
					<form method="post" action="reply.php?id=' . $row['topic_id'] . '">
						<textarea name="reply-content"></textarea><br /><br />
						<input type="submit" value="Submit reply" />
					</form>';
					echo ' 
						
							<form method="post" action="category.php?id='.$backrow['topic_cat'].'">
								<input type="submit" value="Cancel">
							</form>
						</td></tr>';
			}
			
			//finish the table
			echo '</table>';
		}
	}
}

include 'footer.php';
?>