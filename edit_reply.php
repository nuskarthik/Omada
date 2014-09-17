<?php
//edit_reply.php

include 'connect.php';
include 'header.php';


if($_SESSION['signed_in'])
{
	if(!$_GET['update']){
	$sql = "SELECT 
				p.post_content,
				p.post_topic
			FROM
				posts p
			WHERE
				p.post_id = " . $_GET['id'];
				
	$result_row = mysql_fetch_assoc(mysql_query($sql)); 
	$url_link = $_GET['urlname'];
	echo '<table class="topic" border="1">
			<tr>
				<td colspan="2">
					<h2>
						Reply:
					</h2>
					<br />
					<form method ="get" action="edit_reply.php?">
						<textarea name="reply-content">'.$result_row['post_content'].'</textarea>
						<br />
						<br />
						<input type="hidden" name="id" value="'.$_GET['id'].'" />
						<input type="hidden" name="update" value="1" />
						<input type="hidden" name="urlname" value="'.$url_link.'">
						<input type="submit" value="Submit Reply" />
					</form>
			
					<form method="post" action="topic.php?id='.$result_row['post_topic'].'">
						<input type="submit" value="Cancel" />
					</form>
				</td>
			</tr>
		</table>';
		
					
	}
	else{
			$id = mysql_real_escape_string($_GET['id']);
			$desc = $_GET['reply-content'];
			$sql = "UPDATE posts p SET p.post_content='". $desc."',p.post_date=NOW() WHERE p.post_id=".$id;
			
			$result = mysql_query($sql);
			
			if(!$result){
				echo 'Your reply has not been saved. Please try again later.' . mysql_error();
			}
			
			else{
				echo '<br />Successfully edit the post. <a href="'.$_GET['urlname'].'">Return to the topic.</a>.';
			}
	}
}

else{
	echo 'You must be signed in to post a reply.';
}

include 'footer.php';
?>