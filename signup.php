<?php
//signup.php
include 'connect.php';
include 'header.php';

echo '<h3>Sign up</h3><br />';

if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    /*the form hasn't been posted yet, display it
	  note that the action="" will cause the form to post to the same page it is on */
    echo '<form method="post" action="">
 	 	Username: <input type="text" name="user_name" /><br />
 		Password: <input type="password" name="user_pass"><br />
		Password again: <input type="password" name="user_pass_check"><br />
		E-mail: <input type="email" name="user_email"><br />
 		<input type="submit" value="Sign up" />
 	 </form>';
}
else
{
    /* so, the form has been posted, we'll process the data in three steps:
		1.	Check the data
		2.	Let the user refill the wrong fields (if necessary)
		3.	Save the data 
	*/
	$errors = array(); /* declare the array for later use */
	
	if(isset($_POST['user_name']))
	{
		//the user name exists
		if(!ctype_alnum($_POST['user_name']))
		{
			$errors[] = 'The username can only contain letters and digits.';
		}
		if(strlen($_POST['user_name']) > 30)
		{
			$errors[] = 'The username cannot be longer than 30 characters.';
		}
		
		$check="SELECT user_name FROM users";
		$ifusernameexist = mysql_query($check);
		if(!$ifusernameexist)
		{
		echo 'Please try again later.' . mysql_error();
		}
		else
		{	
			if(mysql_num_rows($ifusernameexist) != 0)
			{	
				$flag=0;
				while($usernames = mysql_fetch_assoc($ifusernameexist) && $flag ==0 )
				{
					if(strcmp($usernames['user_name'], $_POST['user_name'])==0)
					{
						$flag=1;
						break;
					}
				}
				if($flag==1)
				{
					$errors[] = 'Username already exists.';
				}
			}
		}
	}
	else
	{
		$errors[] = 'The username field must not be empty.';
	}
	
	
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
	
	if(isset($_POST['user_email']))
	{
		$check="SELECT user_email FROM users";
		$ifuseremailexist = mysql_query($check);
		if(!$ifuseremailexist)
		{
		echo 'Please try again later.' . mysql_error();
		}
		else
		{	
			if(mysql_num_rows($ifuseremailexist) != 0)
			{	
				$flag=0;
				while($useremails = mysql_fetch_assoc($ifuseremailexist) && $flag ==0 )
				{
					if(strcmp($useremails['user_email'], $_POST['user_email'])==0)
					{
						$flag=1;
						break;
					}
				}
				if($flag==1)
				{
					$errors[] = 'An account has already been registered using this email.';
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
		//the form has been posted without, so save it
		//notice the use of mysql_real_escape_string, keep everything safe!
		//also notice the sha1 function which hashes the password
		$sql = "INSERT INTO
					users(user_name, user_pass, user_email ,user_date,activation)
				VALUES('" . mysql_real_escape_string($_POST['user_name']) . "',
					   '" . sha1($_POST['user_pass']) . "',
					   '" . mysql_real_escape_string($_POST['user_email']) . "',
						NOW(),'0')";
						
		$result = mysql_query($sql);
		if(!$result)
		{
			//something went wrong, display the error
			echo 'Something went wrong while registering. Try again later.';
			//echo mysql_error(); //debugging purposes, uncomment when needed
		}
		else
		{
			$getidquery="SELECT user_id FROM users WHERE user_name='".$_POST['user_name']."' AND user_email='".$_POST['user_email']."' LIMIT 1";
			$getidresult = mysql_query($getidquery);
			if(!$getidresult)
			{
				echo 'Something went wrong while registering. Please try again later.';
			}
			else
			{
				if(mysql_num_rows($getidresult) != 0)
				{	
					while($userids = mysql_fetch_assoc($getidresult))
					{
						$userid=$userids['user_id'];
					}
				}
			}
			$to = $_POST['user_email'];							 
			$from = "admin@assembler.comuv.com";
			$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Assembler Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;">Assembler Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$_POST['user_name'].',<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="http://www.assembler.comuv.com/activation.php?id='.$userid.'&u='.$_POST['user_name'].'&e='.$_POST['user_email'].'&p='.sha1($_POST['user_pass']) .'">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>'.$_POST['user_email'].'</b></div></body></html>';
			$subject = 'Assembler Account Activation';
			$headers = "From: $from\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";
			mail($to, $subject, $message, $headers);
			echo 'Succesfully registered. Please check your email for the activation link! :-)';
			//echo 'Succesfully registered. You can now <a href="signin.php">sign in</a> and start posting! :-)';
		}
	}
}

include 'footer.php';
?>
