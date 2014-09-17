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
	//the user is signed in
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{	
	echo '<form method="post" action="">
	Username  of person to be invited: <input type="text" name="invite_member" /><br />
	<input type="submit" value="Create topic" />
				 </form>'; 
	}
	else
	{
	
	
	}
}
include 'footer.php';
?>