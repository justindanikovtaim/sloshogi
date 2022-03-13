<?php
//create_cat.php
include '../connect.php';
include 'header.php';

$sql = "SELECT
			topic_id,
			topic_subject
		FROM
			forum_topics
		WHERE
			forum_topics.topic_id = " . mysqli_real_escape_string($link, $_GET['id']);
			
$result = mysqli_query($link, $sql);

if(!$result)
{
	echo 'The topic could not be displayed, please try again later.';
}
else
{
	if(mysqli_num_rows($result) == 0)
	{
		echo 'This topic doesn&prime;t exist.';
	}
	else
	{
		while($row = mysqli_fetch_assoc($result))
		{
			//display post data
			echo '<table class="topic" border="1">
					<tr>
						<th colspan="2">' . $row['topic_subject'] . '</th>
					</tr>';
		
			//fetch the posts from the database
			$posts_sql = "SELECT
						forum_posts.post_topic,
						forum_posts.post_content,
						forum_posts.post_date,
						forum_posts.post_by,
						users.username
					FROM
						forum_posts
					LEFT JOIN
						users
					ON
						forum_posts.post_by = users.username
					WHERE
						forum_posts.post_topic = " . mysqli_real_escape_string($link, $_GET['id']);
						
			$posts_result = mysqli_query($link, $posts_sql);
			
			if(!$posts_result)
			{
				echo '<tr><td>The posts could not be displayed, please try again later.</tr></td></table>';
			}
			else
			{
			
				while($posts_row = mysqli_fetch_assoc($posts_result))
				{
					echo '<tr class="topic-post">
							<td class="user-post"><a style="font-size: 3vw" href="../view_friend.php?friendName='. $posts_row['username'].'">' . $posts_row['username'] . '</a><br/>' . date('d-m-Y H:i', strtotime($posts_row['post_date'])) . '</td>
							<td class="post-content">' . htmlentities(stripslashes($posts_row['post_content'])) . '</td>
						  </tr>';
				}
			}
			
				//show reply box
				echo '<tr><td colspan="2"><p class="post-content">リプライ:</p><br />
					<form method="post" action="reply.php?id=' . $row['topic_id'] . '">
						<textarea name="reply-content"></textarea><br /><br />
						<input type="submit" value="送信" />
					</form></td></tr>';
			
			
			//finish the table
			echo '</table>';
		}
	}
}

include 'footer.php';
?>