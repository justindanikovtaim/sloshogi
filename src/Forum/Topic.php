<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'session.php';
require_once 'templates/header.php';

$sql = "SELECT topic_id, topic_subject FROM forum_topics WHERE forum_topics.topic_id = ?";
$result = safe_sql_query($sql, ['s', $_GET['id']]);

if (!$result || mysqli_num_rows($result) == 0) { ?>

	<h2>This topic doesn't exist.</h2>

<?php } else { ?>

	<?php while ($row = mysqli_fetch_assoc($result)) { ?>

		<h2><?php echo $row['topic_subject']; ?></h2>

		<table class="topic" border="1">
			<?php
			$posts_sql = "SELECT post_topic, post_content, post_date, post_by FROM forum_posts WHERE post_topic = ?";

			$posts_result = safe_sql_query($posts_sql, $_GET['id']);

			if (!$posts_result) {
				echo '<tr><td>The posts could not be displayed, please try again later.</tr></td></table>';
			} else {
				while ($posts_row = mysqli_fetch_assoc($posts_result)) {
			?>
					<tr class="topic-post">
						<td class="user-post">
							<a style="font-size: 3vw" href="/view-friend?friendName=<?php echo $posts_row['post_by']; ?>"><?php echo $posts_row['post_by']; ?></a><br />
							<?php echo date('d-m-Y H:i', strtotime($posts_row['post_date'])); ?>
						</td>
						<td class="post-content">
							<?php echo htmlentities(stripslashes($posts_row['post_content'])); ?>
						</td>
					</tr>
			<?php
				}
			}
			?>

			<!-- Reply Form -->
			<tr>
				<td colspan="2">
					<p id="reply">返事:</p>
					<form method="post" action="/reply?id=<?php echo $row['topic_id']; ?>">
						<textarea name="reply-content" required></textarea><br /><br />
						<input type="submit" value="送信" />
					</form>
				</td>
			</tr>

		</table>

<?php
	}
}

require_once 'templates/footer.php';

?>
