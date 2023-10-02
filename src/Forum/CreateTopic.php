<?php

require_once SHAREDPATH . 'database.php';
require_once 'templates/header.php';

?>

<h2>トピックを作成</h2>

<?php

function displayTopicForm($link)
{
    $sql = "SELECT cat_id, cat_name FROM forum_categories";
    $result = mysqli_query($link, $sql);

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

function saveTopic($link)
{
    // Insert topic into topics table
    $sql = "INSERT INTO forum_topics(topic_subject, topic_date, topic_cat, topic_by)
            VALUES('" . mysqli_real_escape_string($link, $_POST['topic_subject']) . "',
                   NOW(),
                   " . mysqli_real_escape_string($link, $_POST['topic_cat']) . ",
                   TRIM('" . $_COOKIE['current_user_cookie'] . "'))";
    $result = mysqli_query($link, $sql);

    if (!$result) {
    ?>

        <p>An error occurred while inserting your data. Please try again later. <?php echo mysqli_error($link); ?></p>

    <?php
        return false;
    }

    $topicId = mysqli_insert_id($link);

    // Insert post into posts table
    $sql = "INSERT INTO forum_posts(post_content, post_date, post_topic, post_by)
            VALUES('" . mysqli_real_escape_string($link, $_POST['post_content']) . "',
                   NOW(),
                   $topicId,
                   '" . $_COOKIE['current_user_cookie'] . "')";
    $result = mysqli_query($link, $sql);

    if (!$result) {
    ?>

        <p>An error occurred while inserting your post. Please try again later. <?php echo mysqli_error($link); ?></p>

    <?php
        return false;
    }

    mysqli_commit($link);
    return $topicId;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // Display the form
    displayTopicForm($link);
} else {
    // Handle form submission
    $topicId = saveTopic($link);

    if ($topicId) {
    ?>

        <p><a href="topic.php?id=<?php echo $topicId; ?>">あなたの新しいトピック</a>が追加されました</p>

<?php
    }
}

require_once 'templates/footer.php';
?>
