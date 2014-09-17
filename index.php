<?php
//create_cat.php
include 'connect.php';
include 'header.php';	

if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
	if($_SESSION['activation']==0)
					{
						echo 'Please activate your account first. Check your email for the activation mail. Be sure to check the Spam folder.';
						die();
					}
    else{						
		
		$sql = "SELECT
					groups.group_id,
					groups.group_name,
					groups.group_description,
					COUNT(topics.topic_id) AS topics
				FROM
					groups
				LEFT JOIN
					topics
				ON
					topics.topic_id = groups.group_id
				GROUP BY
					groups.group_name, groups.group_description, groups.group_id";

		$result = mysql_query($sql);

		if(!$result)
		{
			echo 'The categories could not be displayed, please try again later.';
		}
		else
		{
			if(mysql_num_rows($result) == 0)
			{
				echo 'No categories defined yet.';
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
else
{
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{
		/*the form hasn't been posted yet, display it
		  note that the action="" will cause the form to post to the same page it is on */
		echo '<form method="post" action="">
			Username: <input type="text" name="user_name" /><br />
			Password: <input type="password" name="user_pass"><br />
			<input type="submit" value="Sign in" />
		 </form>';
		 
		 echo "<br><br><br><a href='forget_password.php'>Forgot Password?</a>";
	}
	else
	{
		/* so, the form has been posted, we'll process the data in three steps:
			1.	Check the data
			2.	Let the user refill the wrong fields (if necessary)
			3.	Varify if the data is correct and return the correct response
		*/
		$errors = array(); /* declare the array for later use */
		
		if(!isset($_POST['user_name']))
		{
			$errors[] = 'The username field must not be empty.';
		}
		
		if(!isset($_POST['user_pass']))
		{
			$errors[] = 'The password field must not be empty.';
		}
		
		if(!empty($errors)) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
		{
			echo 'Uh-oh.. a couple of fields are not filled in correctly..<br /><br />';
			echo '<ul>';
			foreach($errors as $key => $value) /* walk through the array so all the errors get displayed */
			{
				echo '<li>' . $value . '</li>'; /* this generates a nice error list */
			}
			echo '</ul>';
		}
		else
		{
			//the form has been posted without errors, so save it
			//notice the use of mysql_real_escape_string, keep everything safe!
			//also notice the sha1 function which hashes the password
			$sql = "SELECT 
						user_id,
						user_name
					FROM
						users
					WHERE
						user_name = '" . mysql_real_escape_string($_POST['user_name']) . "'
					AND
						user_pass = '" . sha1($_POST['user_pass']) . "'";
						
			$result = mysql_query($sql);
			if(!$result)
			{
				//something went wrong, display the error
				echo 'Something went wrong while signing in. Please try again later.';
				echo mysql_error(); //debugging purposes, uncomment when needed
			}
			else
			{
				//the query was successfully executed, there are 2 possibilities
				//1. the query returned data, the user can be signed in
				//2. the query returned an empty result set, the credentials were wrong
				if(mysql_num_rows($result) == 0)
				{
					echo 'You have supplied a wrong user/password combination. Please try again.';
				}
				else
				{
					//set the $_SESSION['signed_in'] variable to TRUE
					$_SESSION['signed_in'] = true;
					
					//we also put the user_id and user_name values in the $_SESSION, so we can use it at various pages
					while($row = mysql_fetch_assoc($result))
					{
						$_SESSION['user_id'] 	= $row['user_id'];
						$_SESSION['user_name'] 	= $row['user_name'];
					}
					
					echo 'Welcome, ' . $_SESSION['user_name'] . '. <br /><a href="index.php">Proceed to the forum overview</a>.';
					 header( 'Location: index.php' ) ;
				}
			}
		}
	}
}
include 'footer.php';
?>