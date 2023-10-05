<?php

require_once SHAREDPATH . 'database.php';
require_once 'templates/header.php';

function displayCategoryTopics($categoryId)
{
    $categoryQuery = "SELECT cat_name FROM forum_categories WHERE cat_id = ?";
    $categoryResult = safe_sql_query($categoryQuery, ['s', $categoryId]);

    if (!$categoryResult || mysqli_num_rows($categoryResult) == 0) {
        return;
    }

    $categoryRow = mysqli_fetch_assoc($categoryResult);
?>
    <h2><?php echo $categoryRow['cat_name']; ?> Topics</h2>

    <?php
    $topicsQuery = "SELECT topic_id, topic_subject, topic_date FROM forum_topics WHERE topic_cat = ?";
    $topicsResult = safe_sql_query($topicsQuery, ['s', $categoryId]);

    if (!$topicsResult || mysqli_num_rows($topicsResult) == 0) {
        return;
    }
    ?>

    <table border="1">
        <tr>
            <th>Topic</th>
            <th>Created Date</th>
        </tr>

        <?php
        while ($row = mysqli_fetch_assoc($topicsResult)) {
        ?>
            <tr>
                <td class="leftpart">
                    <h3><a href="/forum/topic?id=<?php echo $row['topic_id']; ?>"><?php echo $row['topic_subject']; ?></a></h3>
                </td>
                <td class="rightpart">
                    <?php echo date('d-m-Y', strtotime($row['topic_date'])); ?>
                </td>
            </tr>
        <?php
        }
        ?>

    </table>
<?php
}

if (!isset($_GET['id'])) {
?>
    <h5>You must select a category first.</h5>
<?php
} else {
    $categoryId = $_GET['id'];
    displayCategoryTopics($categoryId);
}

require_once 'templates/footer.php';
?>
