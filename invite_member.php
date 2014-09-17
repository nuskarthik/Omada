<?php
//create_topic.php
include 'connect.php';
include 'header.php';

echo '<h2>Invite a Member</h2>';
if($_SESSION['signed_in'] == false)
{
	//the user is not signed in
	echo 'Sorry, you have to be <a href="signin.php">signed in</a> to create a topic.';
}
else
{	
	if(isset($_GET['varname']) && isset($_GET['urlname']))
	{
			$groupid=$_GET['varname'];
			$returnlink=$_GET['urlname'];
	}
	//the user is signed in
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{	
	echo '<form method="post" action="">
	Username or Email address of person to be invited : <br><br><input type="text" name="user_email" /><br />
	<input type="submit" value="Invite member" />
				 </form>'; 
	}
	else
	{
		$sql = "SELECT 	
						user_email
					FROM
						users
					WHERE
						user_name = '". mysql_real_escape_string($_POST['user_email']) ."'
						OR
						user_email = '" . mysql_real_escape_string($_POST['user_email']) . "' LIMIT 1";
						
			$result = mysql_query($sql);
			if(!$result)
			{
				echo 'Something went wrong while signing in. Please try again later.';
				echo mysql_error(); 
			}
			else
			{
				if(mysql_num_rows($result) == 0)
				{
					echo 'No such user exists.';
					exit();
				}
				else
				{
					while($row = mysql_fetch_assoc($result))
					{
							$groupnamequery="SELECT group_name FROM groups WHERE group_id='".$groupid."'";
							$groupnames=mysql_query($groupnamequery);
							
							if(!$groupnames)
							{
								echo 'Something went wrong. Please try again later.';
								exit();
							}
							else
							{
								while($grouprow=mysql_fetch_assoc($groupnames))
								{
									$groupnametobeemailed=$grouprow['group_name'];
								}
							}

								$to = $row['user_email'];							 
								$from = "admin@assembler.comuv.com";
								$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Assembler Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;">Omada Invitation</div><div style="padding:24px; font-size:17px;"><h2>Hello '.$row['user_name'].'</h2><p>This is an automated message from Assembler, the group social network you are a user of!</p><p>You have been cordially invited to join the group, '.$groupnametobeemailed.' , by '.$_SESSION['user_name'].'! </p><p>If you wish to join the group, please click the following link: <br /></p><p><a href="http://www.assembler.comuv.com/category.php?id='.$groupid.'">Click here to be directed to the Group to join! </a></p><p>After clicking "Join Group", you must wait for approval by the group admins.</p></div></body></html>';
								$subject = 'Omada Invitation';
								$headers = "From: $from\n";
								$headers .= "MIME-Version: 1.0\n";
								$headers .= "Content-type: text/html; charset=iso-8859-1\n";
								mail($to, $subject, $message, $headers);
								echo 'Email delivered. <a href="'.$returnlink.'"> Return  to the group </a>';
							
					}
				}
			}
	}
}
include 'footer.php';
?>