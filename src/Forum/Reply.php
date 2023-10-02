<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'session.php';
require_once 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Someone is calling the file directly, which we don't want
?>

    <p>This file cannot be called directly.</p>

    <?php
} else {
    // Check for sign in status

    // A real user posted a real reply
    $sql = "INSERT INTO forum_posts(post_content, post_date, post_topic, post_by) VALUES (?, NOW(), ?, TRIM(?))";

    $result = safe_sql_query($sql, ['sss', $_POST['reply-content'], getCurrentUser()]);

    if (!$result) {
    ?>

        <p>Your reply has not been saved, please try again later. <?php echo mysqli_error($link); ?></p>

    <?php
    } else {
    ?>

        <p>あなたの返信が保存されました。<a href="/topic?id=<?php echo htmlentities($_GET['id']); ?>">見る</a>.</p>

<?php
    }
}

require_once 'templates/footer.php';
?>
