<?php
include 'connect.php';
include 'header.php';

echo '<h3>Activation</h3><br />';
if (isset($_GET['id']) && isset($_GET['u']) && isset($_GET['e']) && isset($_GET['p'])) {
	
    $id = mysql_real_escape_string($_GET['id']); 
	$u = mysql_real_escape_string($_GET['u']);
	$e = mysql_real_escape_string($_GET['e']);
	$p = mysql_real_escape_string($_GET['p']);
	
	// Evaluate the lengths of the incoming $_GET variable?
	
	$sql = "SELECT * FROM users WHERE user_id='".$id."' AND user_name='".$u."' AND user_email='".$e."' AND user_pass='".$p."' LIMIT 1";
    $query = mysql_query($sql);
	$numrows = mysql_num_rows($query);
	// Evaluate for a match in the system (0 = no match, 1 = match)
	if($numrows == 0)
	{
		//Error: possible hack attempt
	}
	else
	{
	$sql = "UPDATE users SET activation='1' WHERE user_id='".$id."' LIMIT 1";
    $query = mysql_query($sql);
	echo 'Account successfully activated.';
	}
}
?>