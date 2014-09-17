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
		<h3>Advanced Search</h3>
			Search by group: <input type="text" name="search_field" autocomplete="off"/><br />
			Search by category: <input type="text" name="cat_search" autocomplete="off"/><br />
			Search by topic: <input type="text" name="topic_search" autocomplete="off"/><br />
			Search by username: <input type="text" name="user_search" autocomplete="off"/><br />
			<br />
			<input type="submit" value="Search" />
		 </form>';
	}
	else
	{
		echo "Showing results for ";
		
		$sql="SELECT g.group_id from groups g";
		
		if(!empty($_POST['search_field'])&&empty($_POST['cat_search'])&&empty($_POST['topic_search'])&&empty($_POST['user_search']))
		{
		echo ' GROUP='.$_POST['search_field'];
		$sql.= " WHERE g.group_name LIKE '%". mysql_real_escape_string($_POST['search_field']) ."%' ";
		}
		
		if(!empty($_POST['cat_search'])){
		echo ' TAG='.$_POST['cat_search'];
		$sql.=" INNER JOIN tags t
				ON g.group_id=t.group_id 
				WHERE t.tag LIKE '%".mysql_real_escape_string($_POST['cat_search'])."%'";
		}
		
		if(!empty($_POST['topic_search'])){
		echo ' TOPIC='.$_POST['topic_search'];
		$sql.=" INNER JOIN topics top
				ON top.topic_cat=g.group_id 
				WHERE top.topic_subject LIKE '%". mysql_real_escape_string($_POST['topic_search']) . "%'";
		}
		
		if(!empty($_POST['user_search'])){
		echo 'USER='.$_POST['user_search'];
		$sql.=" INNER JOIN membership m
				ON m.group_id=g.group_id
				WHERE m.user_id IN (SELECT user_id from users where user_name LIKE '%". mysql_real_escape_string($_POST['user_search']) . "%')";
		}
		
		if(!empty($_POST['search_field'])){
		echo ' GROUP='.$_POST['search_field'];
		$sql.= " AND g.group_name LIKE '%". mysql_real_escape_string($_POST['search_field']) ."%' ";
		}
		
		if(strcmp($sql,"SELECT group_id from groups")<>0)
		{
				echo $sql;
				$result=mysql_query($sql);
				//gives all the group ids that satisfy the search parameters
				
				if(!$result)
				{
					echo 'Please try again later.' . mysql_error();
				}
				else
				{
					if(mysql_num_rows($result) == 0)
					{
						echo 'No results could be found.';
					}
					else
					{
					
								//prepare the table
						echo '<table border="1">
							  <tr>
								<th>Category</th>
								<th>Last topic</th>
							  </tr>';	
							
						while($idrow = mysql_fetch_assoc($result))
						{				
								$getgroup="SELECT * from groups where group_id='".$idrow['group_id']."' and visibility='0'";
								$groupresult=mysql_query($getgroup);
								if(!$groupresult)
								{
									echo 'Please try again later.' . mysql_error();
								}
								else
								{
									if(mysql_num_rows($groupresult) == 0)
									{
										echo 'No results could be found.';
									}
									else
									{
										while($row = mysql_fetch_assoc($groupresult))
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
				}
		}
	}
}
include 'footer.php';
?>
