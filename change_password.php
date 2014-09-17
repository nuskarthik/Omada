<?php
//signin.php
include 'connect.php';
include 'header.php';

echo '<h3>Forgot Password</h3><br />';

//first, check if the user is already signed in. If that is the case, there is no need to display this page
if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == false)
{
	echo 'You are already signed in, you can <a href="signout.php">sign out</a> if you want.';
}
else
{
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{
		/*the form hasn't been posted yet, display it
		  note that the action="" will cause the form to post to the same page it is on */
		echo '<form method="post" action="">
 	 	Enter old password: <input type="password" name="old_pass" /><br />
 		Enter new password: <input type="password" name="user_pass"><br />
		Enter new password again: <input type="password" name="user_pass_check"><br />
 		<input type="submit" value="Change Password" />
 	 </form>';
	}
	else
	{
		if(isset($_POST['user_pass']))
		{
			if($_POST['user_pass'] != $_POST['user_pass_check'])
			{
				$errors[] = 'The two passwords did not match.';
			}
		}
		else
		{
			$errors[] = 'The password field cannot be empty.';
		}
		if(isset($_POST['old_pass']))
		{
			$check="SELECT user_pass FROM users WHERE user_name='".$_SESSION['user_name']."' LIMIT 1";
			$ifuseremailexist = mysql_query($check);
			if(!$ifuseremailexist)
			{
			echo 'Please try again later.' . mysql_error();
			}
			else
			{	
				if(mysql_num_rows($ifuseremailexist) != 0)
				{	
					while($useremails = mysql_fetch_assoc($ifuseremailexist) )
					{
						if(strcmp($useremails['user_pass'], sha1($_POST['old_pass']))!=0)
						{
							$errors[] = 'Incorrect Password';
						}
					}
				}
			}
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
			$check="UPDATE users SET user_pass='".sha1($_POST['user_pass'])."' WHERE user_id='".$_SESSION['user_id']."'";
			$result = mysql_query($check);
			if(!$result)
			{
				echo 'Something went wrong while signing in. Please try again later.';
				echo mysql_error(); 
			}
			else
			{
				echo 'Password updated. Please be sure to use this password to login next time.';
			}
		}
	}
}
include 'footer.php';
?>