<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

function getUserInfo($username)
{
    return safe_sql_query("SELECT rating, hitokoto, record, icon, id FROM users WHERE username = ?", ['s', $username]);
}

function getFriendInfo($username)
{
    $result = safe_sql_query("SELECT friends, id FROM users WHERE username = ?", ['s', $username]);
    return mysqli_fetch_array($result);
}

function isUserInFriendList($userId, $friendIds)
{
    return array_search($userId, $friendIds) !== false;
}

function displayAddFriendLink($friendName, $userInfoArray, $friendIds, $userId)
{
    if (!isUserInFriendList($userInfoArray['id'], $friendIds) && $userInfoArray['id'] !== $userId) {
        echo "<a href='/friends/add-to-friends?name=" . $friendName . "'>友達に追加　Add to friends</a>";
    }
}

$username = $_GET['friendName'];
$userInfoArray = mysqli_fetch_array(getUserInfo($username));
$friendInfoArray = getFriendInfo(getCurrentUser());
$userId = $friendInfoArray['id'];
$friendIds = explode(',', $friendInfoArray['friends']);

begin_html_page("SLO Shogi Friends", ['user_page.css']);
?>

<a id="backButton" href="/friends">≪</a>
<br>
<br>
<div id="all">
    <div id="nameIconRating">
        <h1 id="userName"><?= $username ?></h1>
        <h2 id="rating">段級: <?= $userInfoArray['rating'] ?></h1>
            <h2 id="record">勝敗レコード: <?= $userInfoArray['record'] ?> </h1>
                <p id="hitokotoInput">"<?= $userInfoArray['hitokoto'] ?>"</p>
                <div id="iconBox">
                    <img src="/public/images/icons/<?= $userInfoArray['icon'] ?>_icon.png" id="userIcon">
                </div>
    </div>
</div>

<?php
displayAddFriendLink($username, $userInfoArray, $friendIds, $userId);
?>

<?php
end_html_page();
?>
