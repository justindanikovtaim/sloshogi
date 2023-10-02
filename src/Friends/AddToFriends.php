<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

$userVar = getCurrentUser();
$friendName = $_GET['name'];
$getFriendId = safe_sql_query("SELECT id FROM users WHERE username = ?", ['s', $friendName]);
$friendIdArray = mysqli_fetch_array($getFriendId);
$friendId = $friendIdArray['id'];

$query = safe_sql_query("UPDATE users SET friends = CONCAT(friends, ?) WHERE id = ?", ['si', $friendId . ",", $userVar]);

begin_html_page("SLO Shogi Add Friends", ['friends_page.css']);
?>

<a id="backButton" href="/fiends/view-friend?friendName=<?= $friendName ?>">≪</a>
<br><br>
<h3>
    <?php
    if ($query) {
        echo "友達が追加されました！ Friend successfully added!";
    } else {
        echo "エラーが発生しました。やり直してください。<br>There was an error. Please try again";
    }
    ?>
</h3>

<?php
end_html_page();
