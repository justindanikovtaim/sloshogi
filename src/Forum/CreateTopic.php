<?php

require_once SHAREDPATH . 'database.php';
require_once 'templates/header.php';

?>

<h2>トピックを作成</h2>

<?php

function displayTopicForm()
{
    $sql = "SELECT cat_id, cat_name FROM forum_categories";
    $result = safe_sql_query($sql);

    if (!$result) {
?>

        <p>Error while selecting from database. Please try again later.</p>

    <?php
        return;
    }
    ?>

    <form method="post" action="">
        <label for="topic_subject">サブジェクト:</label>
        <input type="text" id="topic_subject" name="topic_subject" required /><br>
        <label for="topic_cat">カテゴリー:</label>
        <select id="topic_cat" name="topic_cat" required>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <option value="<?php echo $row['cat_id']; ?>"><?php echo $row['cat_name']; ?></option>
            <?php
            }
            ?>
        </select><br><br>
        <label for="post_content">メッセージ</label><br>
        <textarea id="post_content" name="post_content" required></textarea><br>
        <input type="submit" value="トピックを追加" />
    </form>

    <?php
}

function saveTopic()
{
    // Insert topic into topics table
    $sql = "INSERT INTO forum_topics(topic_subject, topic_date, topic_cat, topic_by) VALUES(?, NOW(), ?, TRIM(?))";
    $result = safe_sql_query($sql, ['ss', $_POST['topic_subject'], $_POST['topic_cat'], getCurrentUser()]);

    if (!$result) {
    ?>

        <p>An error occurred while inserting your data. Please try again later.</p>

    <?php
        return false;
    }

    // you can specify the auto_increment value in the database
    // $topicId = mysqli_insert_id($link);

    // Insert post into posts table
    $sql = "INSERT INTO forum_posts(post_content, post_date, post_topic, post_by) VALUES(?, NOW(), ?)";
    $result = safe_sql_query($sql, ['sss', $_POST['post_content'], getCurrentUser()]);

    if (!$result) {
    ?>

        <p>An error occurred while inserting your post. Please try again later.</p>

    <?php
        return false;
    }

    // return $result['id'];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Display the form
    displayTopicForm();
} else {
    // Handle form submission
    $topicId = saveTopic();

    if ($topicId) {
    ?>

        <p><a href="/forum/topic?id=<?php echo $topicId; ?>">あなたの新しいトピック</a>が追加されました</p>

<?php
    }
}

require_once 'templates/footer.php';
?>
