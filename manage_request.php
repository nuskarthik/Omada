<?php
//create_topic.php
include 'connect.php';
include 'header.php';

echo '<h2>Member Request Admin Page</h2>';
if($_SESSION['signed_in'] == false)
	{
	//the user is not signed in
	echo 'Sorry, you have to be <a href="signin.php">signed in</a> to view this page.';
}
else
{
$admin="SELECT * FROM membership WHERE user_id='".$_SESSION['user_id']."' AND group_id='".mysql_real_escape_string($_GET['group'])."' AND user_level='2'";
$adminresult=mysql_query($admin);
if(!$adminresult)
{
	echo 'Please try again later.';
}
else
{
			if(mysql_num_rows($adminresult) >0)
			{
				{				
					$request="SELECT 
						user_id,
						post_date
					FROM
						membership
					WHERE
						approved='0'
						AND group_id='".$_GET['group']."'";
						
					$requests = mysql_query($request);
						
							if(!$requests)
							{
								echo 'Results could not be retreived. Please try again later.'.mysql_error();
							}
							else
							{
								if(mysql_num_rows($requests) == 0)
								{
											echo "No requests from group.<br>";
								}
								else
								{
									//prepare the table
									echo '<table border="1">
										  <tr>
											<th>Request</th>
											<th>Action</th>
										  </tr>';	
									echo '<tr>';
									echo '<td class="leftpart">';
									error_reporting(E_ERROR);
									$username;
									while($requestsrow = mysql_fetch_assoc($requests))
									{
										$namequery="SELECT user_name from users where user_id=".$requestsrow['user_id']." LIMIT 1";
										$name=mysql_query($namequery);
										if(!name)
										{
											echo 'Results could not be retreived. Please try again later.'.mysql_error();
										}
										while($namerow = mysql_fetch_assoc($name))
										{
											$username=$namerow['user_name'];
										}
										
											echo '<h3>' . $username . '</h3> Request sent on ' . $requestsrow['post_date'];
											echo '</td>';
											echo '<td class="rightpart">';
											$name_value= $username;
											$id_value= $requestsrow['user_id'];
											$group_value= $_GET['group'];
											$url_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
											echo '<form method="get" action="approve_request.php">
											<input type="hidden" name="username" value="'.$name_value.'">
											<input type="hidden" name="groupid" value="'.$group_value.'">
											<input type="hidden" name="idvalue" value="'.$id_value.'">
											<input type="hidden" name="urlname" value="'.$url_link.'">
											<input type="submit" value="Approve Request">
											</form>';
										
									}
								
								}
							}
						echo '</td>';
					echo '</tr>';
				}
			}
	}
}

include 'footer.php';
?>