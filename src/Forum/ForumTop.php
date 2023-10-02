<?php

require_once SHAREDPATH . 'database.php';
require_once 'templates/header.php';

$sql = "SELECT cat_id, cat_name, cat_description FROM forum_categories";

$result = safe_sql_query($sql);

if (!$result) {
    echo 'The categories could not be displayed, please try again later.' . mysqli_error($link);
} else {
    if (mysqli_num_rows($result) == 0) {
        echo 'No categories defined yet.';
    } else {
?>
        <table border="1">
            <tr>
                <th>カテゴリー</th>
                <th>最新トピック</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $mostRecentQuery = safe_sql_query("SELECT * FROM forum_topics WHERE topic_cat = ? ORDER BY topic_id DESC LIMIT 1", ['s', $row['cat_id']]);
                $mostRecent = mysqli_fetch_array($mostRecentQuery);
                $recentTopic = $mostRecent['topic_subject'];
                $recentDate = $mostRecent['topic_date'];
                $recentId = $mostRecent['topic_id'];
            ?>
                <tr>
                    <td class="leftpart">
                        <h3><a href="/category?id=<?php echo $row['cat_id'] ?>"><?php echo $row['cat_name'] ?></a></h3><?php echo $row['cat_description'] ?>
                    </td>
                    <td class="rightpart">
                        <a href="/topic?id=<?php echo $recentId ?>"><?php echo $recentTopic ?></a> at <?php echo $recentDate ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
<?php
    }
}

require_once 'template/footer.php';

?>
