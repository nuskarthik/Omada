<?php
//signout.php
include 'connect.php';
include 'header.php';

echo '<h2>Sign out</h2>';

//check if user if signed in
if($_SESSION['signed_in'] == true)
{
	//unset all variables
	$_SESSION['signed_in'] = NULL;
	$_SESSION['user_name'] = NULL;
	$_SESSION['user_id']   = NULL;

	echo 'Succesfully signed out, thank you for visiting.';
	echo '<br><a class="item" href="signin.php">Sign in</a> or <a class="item" href="signup.php">create an account</a>';
	header('Location: index.php');
}
else
{
	echo 'You are not signed in. Would you <a href="signin.php">like to</a>?';
	header('Location: signin.php');
}

include 'footer.php';
?>