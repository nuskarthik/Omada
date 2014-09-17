<?php
//category.php
include 'connect.php';
include 'header.php';
if($_SESSION['signed_in'] == true)
{
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{
		//the form hasn't been posted yet, display it
		echo '<form method="post" action="">
			Search: <input type="text" name="search_field" /><br />
			<br /><br />
			<input type="submit" value="Search" />
		 </form>';
	}
	else
	{
		echo "Showing results for '" . $_POST['search_field']."'";
		$sql = "SELECT
					groups.group_id,
					groups.group_name,
					groups.group_description
				FROM
					groups
				WHERE
					groups.group_name LIKE '%". mysql_real_escape_string($_POST['search_field']) . "%'";

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
						//prepare the table
				echo '<table border="1">
					  <tr>
						<th>Category</th>
						<th>Last topic</th>
					  </tr>';	
					
				while($row = mysql_fetch_assoc($result))
				{				
					echo '<tr>';
						echo '<td class="leftpart">';
							echo '<h3><a href="category.php?id=' . $row['group_id'] . '">' . $row['group_name'] . '</a></h3>' . $row['group_description'];
						echo '</td>';
						echo '<td class="rightpart">';
						
						//fetch last topic for each cat
							$topicsql = "SELECT
											topic_id,
											topic_subject,
											topic_date,
											topic_cat
										FROM
											topics
										WHERE
											topic_cat = " . $row['group_id'] . "
										ORDER BY
											topic_date
										DESC
										LIMIT
											1";
										
							$topicsresult = mysql_query($topicsql);
						
							if(!$topicsresult)
							{
								echo 'Last topic could not be displayed.';
							}
							else
							{
								if(mysql_num_rows($topicsresult) == 0)
								{
									echo 'no topics';
								}
								else
								{
									while($topicrow = mysql_fetch_assoc($topicsresult))
									echo '<a href="topic.php?id=' . $topicrow['topic_id'] . '">' . $topicrow['topic_subject'] . '</a> at ' . date('d-m-Y', strtotime($topicrow['topic_date']));
								}
							}
						echo '</td>';
					echo '</tr>';
				}
			}
		}
	}
}
include 'footer.php';
?>
