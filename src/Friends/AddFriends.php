<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

if (isset($_POST['unOrEmail'])) {
    $getResult =  safe_sql_query("SELECT * FROM users WHERE username = ?", ['s', $_POST['unOrEmail']]);

    if ($getResult->num_rows == 0) { //if no matches in username
        $getResult =  safe_sql_query("SELECT * FROM users WHERE email =  ?", ['s', $_POST['unOrEmail']]);
    }

    if ($getResult->num_rows == 0) { //if still no match
        $resultId = 0;
    } else {
        $resultArray = mysqli_fetch_array($getResult);
        $resultId = $resultArray['id'];
        $result = $resultArray['username'];
        $resultRating = $resultArray['rating'];
    }
}

begin_html_page("SLO Shogi Add Friends");
?>

<a id="backButton" href="friends.php">≪</a><br><br>
<h3>友達ユーザー名またはメールアドレス<br>Friend's username or email </h3>
<form action="/friends/add-friends" method="post">
    <input type="text" name="unOrEmail"><br>
    <input type="submit" value="検索 Search">
</form>
<br>
<div id="result">
    <h1>検索結果　Result</h1>
    <h5>
        <?php
        if (isset($resultId)) {
            if ($resultId == 0) {
                echo "検索結果なし No Results";
            } else {
                echo "<a href='/friends/view-friend?friendName=" . $result . "'>" . $result . " " . $resultRating . "</a>";
            }
        }
        ?>
    </h5>
</div>

<?php
end_html_page();
