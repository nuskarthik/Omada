<?php
//category.php
include 'connect.php';
include 'header.php';
if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
$groupexistquery="SELECT * FROM groups WHERE group_id='".mysql_real_escape_string($_GET['id'])."'";
$groupexist=mysql_query($groupexistquery);
if(!$groupexist)
{
	echo 'Experiencing some difficulties loading the group. Try again later.';
}
else
{
if(mysql_num_rows($groupexist)==0)
{
	echo 'This group does not exist.';
}
else
{

$areyouamemberquery="SELECT * FROM membership WHERE user_id='".mysql_real_escape_string($_SESSION['user_id'])."' AND group_id='".mysql_real_escape_string($_GET['id'])."' LIMIT 1";
$areyouamember=mysql_query($areyouamemberquery);
if(!$areyouamember)
{
	echo 'Something went wrong. Please wait and reload.';
	exit();
}
else
{
	if(mysql_num_rows($areyouamember)>0)
	{
		$getgroupdetails="SELECT create_datetime,founder,number_of_members from groups where group_id='".mysql_real_escape_string($_GET['id'])."' LIMIT 1";
			$groupdetails=mysql_query($getgroupdetails);
			if(!$groupdetails)
			{
				echo'Unable to retrieve group details at the moment. Please try again later.<br/>';
			}
			else
			{
				while($gd = mysql_fetch_assoc($groupdetails))
				{				
					echo '<a href="'.$_GET['urlname'].'">Return to the group</a><br/>';
					echo 'GROUP FOUNDED ON: '.$gd['create_datetime'].'<br/>';
					echo 'GROUP FOUNDED BY: '.$gd['founder'].'<br/>';
					echo 'CURRENT NUMBER OF MEMBERS: '.$gd['number_of_members'].'<br/>';
				}
			}
				

			$admin="SELECT * FROM membership WHERE user_id='".$_SESSION['user_id']."' AND group_id='".mysql_real_escape_string($_GET['id'])."' AND user_level='2'";
			$adminresult=mysql_query($admin);
			if(!$adminresult)
			{
				echo 'Please try again later.';
			}
			else
			{
				if(mysql_num_rows($adminresult) >0)
				{
					$url_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					echo '<form method="get" action="edit_group.php">
											<input type="hidden" name="group" value="'.$_GET['id'].'">
											<input type="hidden" name="urlname" value="'.$url_link.'">
											<input type="submit" value="Edit Group">
											</form>';
					echo '<form method="get" action="admin_delete.php">
											<input type="hidden" name="group" value="'.$_GET['id'].'">
											<input type="hidden" name="urlname" value="'.$url_link.'">
											<input type="submit" value="Delete Group">
											</form>';
					echo '<form method="get" action="manage_request.php">
											<input type="hidden" name="group" value="'.$_GET['id'].'">
											<input type="hidden" name="urlname" value="'.$url_link.'">
											<input type="submit" value="Manage Requests">
											</form>';
				}
			}
				
				
						//prepare the table
			echo '<table border="1">
				  <tr>
					<th>Members</th>
					<th>Contact</th>
				  </tr>';
				  $memberquery="SELECT user_id,user_name,user_email FROM users WHERE user_id IN(SELECT user_id FROM membership WHERE group_id=".mysql_real_escape_string($_GET['id']).")";	
					$memberresult = mysql_query($memberquery);
					
					if(!$memberresult)
					{
						echo 'Members could not be retrieved. Please try again later.';
					}
					else
					{
						if(mysql_num_rows($memberresult) == 0)
						{
							echo 'There are no users in this group.';
						}
						else
						{
							while($memberrow = mysql_fetch_assoc($memberresult))
							{	
							echo '<tr>';
								echo '<td class="leftpart">';
									echo '<h3><a href="user.php?name=' . $memberrow['user_name'] . '">' . $memberrow['user_name'] . '</a><br /><h3>';
								echo '</td>';
								echo '<td class="rightpart">';
								echo $memberrow['user_email'];
								if(mysql_num_rows($adminresult) >0)
								{
									$url_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
									echo '<form method="get" action="admin_kick.php">
									<input type="hidden" name="varname" value="'.$memberrow['user_id'].'">
									<input type="hidden" name="group" value="'.$_GET['id'].'">
									<input type="hidden" name="urlname" value="'.$url_link.'">
									<input type="submit" value="Kick user">
									</form>';
									echo '<form method="get" action="admin_invite.php">
									<input type="hidden" name="varname" value="'.$memberrow['user_id'].'">
									<input type="hidden" name="group" value="'.$_GET['id'].'">
									<input type="hidden" name="urlname" value="'.$url_link.'">
									<input type="submit" value="Add as Admin">
									</form>';
								}
								echo '</td>';
							echo '</tr>';
							}
						}
					}
	}
	else
	{
		echo 'You are not a member of this group.';
		$var_value=$_GET['id'];
							$url_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
							echo '<form method="get" action="join_group.php">
							<input type="hidden" name="varname" value="'.$var_value.'">
							<input type="hidden" name="urlname" value="'.$url_link.'">
							<input type="submit" value="Join group">
							</form>';
	}
}

}
}
}
else
{
	echo 'Please <a href="signin.php">sign in</a> to access the groups.';
}
include 'footer.php';
?>