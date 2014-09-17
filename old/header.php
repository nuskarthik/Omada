<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
<head>
 	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 	<meta name="description" content="A short description." />
 	<meta name="keywords" content="put, keywords, here" />
 	<title>Assembler</title>
	<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<h1>Assembler</h1>
	<div id="wrapper">
	<div id="menu">
		<a class="item" href="index.php">Home</a> -
		<a class="item" href="create_topic.php">Post to a group</a> -
		<a class="item" href="create_group.php">Create a group</a> -	
		<a class="item" href="find_group.php">Find groups</a>
		
		<div id="userbar">
		<?php
		if($_SESSION['signed_in'])
		{
			echo 'Hello <b>' . htmlentities($_SESSION['user_name']) . '</b>. Not you? <a class="item" href="signout.php">Sign out</a>';
		}
		else
		{
			echo '<a class="item" href="signin.php">Sign in</a> or <a class="item" href="signup.php">create an account</a>';
		}
		?>
		</div>
	</div>
		<div id="content">