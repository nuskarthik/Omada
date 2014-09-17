<?php
//category.php
include 'connect.php';
include 'header.php';
if($_SESSION['signed_in'] == true)
{
 if(isset($_POST['submit']))
{
		$message=$_POST['message'];
		mysql_query("INSERT INTO message(message, sender) VALUES('$message', '".$_SESSION['user_name']."')");
}

?>
<script language="javascript" src="jquery-1.2.6.min.js"></script>
<script language="javascript" src="jquery.timers-1.0.0.js"></script>
<script type="text/javascript">

$(document).ready(function(){
   var j = jQuery.noConflict();
	j(document).ready(function()
	{
		j(".refresh").everyTime(1000,function(i){
			j.ajax({
			  url: "refresh.php",
			  cache: false,
			  success: function(html){
				j(".refresh").html(html);
			  }
			})
		})
		
	});
	j(document).ready(function() {
			j('#post_button').click(function() {
				$text = $('#post_text').val();
				j.ajax({
					type: "POST",
					cache: false,
					url: "save.php",
					data: "text="+$text,
					success: function(data) {
						alert('data has been stored to database');
					}
				});
			});
		});
   j('.refresh').css({color:"green"});
});
</script>
<?php
echo'<div id="tabContainer">
    <div id="tabs">
      <ul>
        <li id="tabHeader_1">TOPICS</li>
        <li id="tabHeader_2">CHAT</li>
      </ul>
    </div>';
    echo '<div id="tabscontent">
      <div class="tabpage" id="tabpage_1">';
	  
//first select the category based on $_GET['cat_id']
$sql = "SELECT
			groups.group_id,
			groups.group_name,
			groups.group_description
		FROM
			groups
		WHERE
			groups.group_id = " . mysql_real_escape_string($_GET['id']);

$result = mysql_query($sql);

if(!$result)
{
	echo 'The category could not be displayed, please try again later.' . mysql_error();
}
else
{
	if(mysql_num_rows($result) == 0)
	{
		echo 'This category does not exist.';
	}
	else
	{	
		$member="SELECT
			user_id, group_id
		FROM
			membership
		WHERE
			user_id = " . $_SESSION['user_id'] . " AND " .
			"group_id = " . mysql_real_escape_string($_GET['id']);
	
		$isamember= mysql_query($member);
		if(!$isamember)
		{
			echo 'Results could not be retreived. Please try again later.' . mysql_error();
		}
		else
		{
			if(mysql_num_rows($isamember) == 0)
			{
				$var_value=$_GET['id'];
				$url_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				echo '<form method="get" action="join_group.php">
				<input type="hidden" name="varname" value="'.$var_value.'">
				<input type="hidden" name="urlname" value="'.$url_link.'">
				<input type="submit" value="Join group">
				</form>';
			}
			else
			{
				$var_value=$_GET['id'];
				$url_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				echo '<form method="get" action="leave_group.php">
				<input type="hidden" name="varname" value="'.$var_value.'">
				<input type="hidden" name="urlname" value="'.$url_link.'">
				<input type="submit" value="Leave group">
				</form>';
				
				
				echo '<form method="get" action="invite_member.php">
				<input type="hidden" name="varname" value="'.$var_value.'">
				<input type="hidden" name="urlname" value="'.$url_link.'">
				<input type="submit" value="Invite member">
				</form>';
			}

		}
			
		//display category data
		while($row = mysql_fetch_assoc($result))
		{
			echo '<h2>Topics in &prime;' . $row['group_name'] . '&prime; category</h2><br />';
		}
	
		//do a query for the topics
		$sql = "SELECT	
					topic_id,
					topic_subject,
					topic_date,
					topic_cat
				FROM
					topics
				WHERE
					topic_cat = " . mysql_real_escape_string($_GET['id']);
		
		$result = mysql_query($sql);
		
		if(!$result)
		{
			echo 'The topics could not be displayed, please try again later.';
		}
		else
		{
			if(mysql_num_rows($result) == 0)
			{
				echo 'There are no topics in this category yet.';
			}
			else
			{
				//prepare the table
				echo '<table border="1">
					  <tr>
						<th>Topic</th>
						<th>Created at</th>
					  </tr>';	
					
				while($row = mysql_fetch_assoc($result))
				{				
					echo '<tr>';
						echo '<td class="leftpart">';
							echo '<h3><a href="topic.php?id=' . $row['topic_id'] . '">' . $row['topic_subject'] . '</a><br /><h3>';
						echo '</td>';
						echo '<td class="rightpart">';
							echo date('d-m-Y', strtotime($row['topic_date']));
						echo '</td>';
					echo '</tr>';
				}
			}
		}
	}
}
?>
</div>
<div class="tabpage" id="tabpage_2">

<?php
echo '<form method="POST" name="" action="">';
?>
<div class="refresh">
<?php
	$result1 = mysql_query("SELECT * FROM message ORDER BY id DESC");
	while($row1 = mysql_fetch_array($result1))
  {
  echo '<p>'.'<span>'.$row1['sender'].'</span>'. '&nbsp;&nbsp;' . $row1['message'].'</p>';
  }
?>
</div>
<input name="message" type="text" id="textb"/>
<input name="submit" type="submit" value="Chat" id="post_button" />
</form>
</div>
<script src="tabs_old.js"></script>
<?php
}
include 'footer.php';
?>
