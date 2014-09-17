<?php
//user.php

include 'connect.php';
include 'header.php';

if($_SESSION['signed_in']==true){

if($_SESSION['activation']==0)
					{
						echo 'Please activate your account first. Check your email for the activation mail. Be sure to check the Spam folder.';
						die();
					}
    else{	
	$sql = "SELECT
				u.user_name,
				u.user_email
			FROM
				users u
			WHERE
				u.user_id=".$_GET['id'];
	$result = mysql_query($sql);
	echo mysql_error();
	$row  = mysql_fetch_assoc($result);
	
	echo '<h2>'.$row['user_name'].'</h2>';
	echo '<h4 class="email"> ' .$row['user_email'].'</h4>';
	
	if($_SESSION['user_id'] == $_GET['id']){
		echo '<br/>
		<form method="get" action="change_password.php">
				<input type="submit" value="Change Password">
			  </form>
		<form method="get" action="delete_user.php">
				<input type="submit" value="Delete account">
			  </form>
			  <br/>
			  <br/>';
	}
	$sql = "SELECT
				u.user_name,
				u.user_email,
				g.group_name,
				m.group_id
			FROM
				users u,
				groups g,
				membership m
			WHERE
				u.user_id=".$_GET['id']." AND u.user_id=m.user_id AND m.group_id=g.group_id ";
	$result = mysql_query($sql);
	echo mysql_error();
	
	if(mysql_num_rows($result)==0){
		echo '<h4>This User is not part of any groups</h4>';
	}
	else{
	echo '<table border ="1">
			<tr>
			<th>
				Groups
			</th>
			<th>
				Last post
			</th>
			</tr>';
	while($row = mysql_fetch_assoc($result)){
	
		$sql = "SELECT 
					p.post_content,
					p.post_date,
					t.topic_id,
					t.topic_subject
				FROM
					posts p,
					topics t
				WHERE
					p.post_by=".$_GET['id'].
				" AND 
					p.post_topic=t.topic_id
				AND
					t.topic_cat=".$row['group_id']."
				ORDER BY
					post_date
				LIMIT
					1";
		$result2=mysql_query($sql);
		echo mysql_error();
		$row2=mysql_fetch_assoc($result2);
		
		echo '	<tr>
					<td class="leftpart">
						<a href="category.php?id='.$row['group_id'].'">
						<h3>
						'.$row['group_name'].'
						</h3>
						</a>
					</td>
					<td class="rightpart">
						'.$row2['post_content'].' on <a href="topic.php?id=' . $row2['topic_id'] . '">' . $row2['topic_subject'] . '</a> at ' . date('d-m-Y', strtotime($row2['post_date'])) . '
					</td>	
				</tr>';
	}
	echo '</table>';
	}
	
}
}
else{
	echo '<tr><td colspan=2>You must be <a href="signin.php">signed in</a> to view this. You can also <a href="signup.php">sign up</a> for an account.';
}

include 'footer.php';
?>