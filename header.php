<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
<head>
 	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 	<meta name="description" content="A short description." />
 	<meta name="keywords" content="put, keywords, here" />
 	<title>Omáda</title>
	<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<?php
		
		
echo '<h1><a href="index.php"><img src="images/omada.jpg" align="middle" height="100"></a></h1>
	<div id="wrapper">';
	if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
		{
	echo '<div id="search">
	<form method="post" action="find_group.php"><input type="text" name="search_field"  autocomplete="off"/>
			<input type="submit" style="display:none"/>
		 </form>
	</div>';
	}
	echo '<div id="menu">';
	if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
		{
		echo '<a class="item" href="index.php">Home</a> -
		<a class="item" href="create_topic.php">Post to a group</a> -
		<a class="item" href="create_group.php">Create a group</a> -
		<a class="item" href="find_group.php">Find groups</a> -
		
		<div id="userbar">';
		//<?php
		//if($_SESSION['signed_in'])
		
			echo 'Hello <b> <a href="user.php?id='.$_SESSION['user_id'].'" > '. $_SESSION['user_name']. '</a></b>. Not you? <a class="item" href="signout.php">Sign out</a>';
		}
		else
		{
			echo '<a class="item" href="signin.php">Sign in</a> or <a class="item" href="signup.php">create an account</a>';
			echo '<div id="userbar">';
		}
		?>
		</div>
		
	</div>
		<div id="content">