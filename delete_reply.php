<?php

include 'connect.php';
include 'header.php';

if($_SESSION['signed_in']==true){
	$delete_reply=" DELETE FROM posts
					WHERE post_id=".mysql_real_escape_string($_GET['varname']);
	
	$result=mysql_query($delete_reply);
	
	if(!$result){
		echo 'Results could not be updated.. Please try again later.' . mysql_error();
	}
	else{
		echo '<br />Successfully removed the post. <a href="'.$_GET['urlname'].'">Return to the topic.</a>.';
	}
}
include 'footer.php';
?>