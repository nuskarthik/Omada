<?php
//signin.php
include 'connect.php';
include 'header.php';

echo '<h3>Forgot Password</h3><br />';
if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
	echo 'You are already signed in, you can <a href="signout.php">sign out</a> if you want.';
}
else
{	
	if(isset($_GET['u']) && isset($_GET['p'])){
		$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
		$temppasshash = preg_replace('#[^a-z0-9]#i', '', $_GET['p']);
		if(strlen($temppasshash) < 10){
			exit();
		}
		$changepass="UPDATE users SET user_pass='".$temppasshash."' WHERE user_email='".$_POST['user_email']."' LIMIT 1";
		$changepassresult = mysql_query($changepass);
		if(!$changepassresult)
		{
			echo 'Something went wrong. Please try again later.';
			exit();
		}
		else
		{
			header("location: login.php");
			exit();
		}
    }
	
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{
		echo '<form method="post" action="">
			Enter Email: <br><br><input type="text" name="user_email" /><br />
			<input type="submit" value="Email Temporary Password" />
		 </form>';
	}
	else
	{
		$errors = array();
		
		if(!isset($_POST['user_email']))
		{
			$errors[] = 'The email field must not be empty.';
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
			$sql = "SELECT 
						user_name
					FROM
						users
					WHERE
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
				}
				else
				{			
							$emailcut = substr($_POST['user_email'], 0, 4);
							$randNum = rand(10000,99999);
							$tempPass = $emailcut.$randNum;
							$hashTempPass = sha1($tempPass);
					while($row = mysql_fetch_assoc($result))
					{			
								$to = $_POST['user_email'];							 
								$from = "auto_responder@assembler.comuv.com";
								$message = '<h2>Hello '.$row['user_name'].'</h2><p>This is an automated message from yoursite. If you did not recently initiate the Forgot Password process, please disregard this email.</p><p>You indicated that you forgot your login password. We can generate a temporary password for you to log in with, then once logged in you can change your password to anything you like.</p><p>After you click the link below your password to login will be:<br /><b>'.$tempPass.'</b></p><p><a href="localhost:81/forget_password.php?u='.$row['user_name'].'&p='.$hashTempPass.'">Click here now to apply the temporary password shown below to your account</a></p><p>If you do not click the link in this email, no changes will be made to your account. In order to set your login password to the temporary password you must click the link above.</p>';
								$subject = 'Assembler Account Activation';
								$headers = "From: $from\n";
								$headers .= "MIME-Version: 1.0 \r\n";
								$headers .= "Content-type: text/html; charset=iso-8859-1\n";
								mail($to, $subject, $message, $headers);
								echo 'Temporary password sent. Please check your email for the link. ';
					}
				}
			}
		}
	}
}

include 'footer.php';
?>