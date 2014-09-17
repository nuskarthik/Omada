<?php
//delete account.php

include 'connect.php';
include 'header.php';

if($_SESSION['signed_in']==true){
	$quit="DELETE FROM users 
						WHERE user_id =".$_SESSION['user_id'];
			
	$result=mysql_query($quit);
	
	$_SESSION['signed_in']=false;
	
	if(!$result)
	{
		echo 'Results could not be updated.. Please try again later.' . mysql_error();
	}
	else
	{
		echo '<br />You have successfully deleted your account. <a href="index.php">Return to the home page.</a>.';
	}
}

include 'footer.php';

?>