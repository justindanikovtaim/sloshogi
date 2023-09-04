<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

$getUserInfo = safe_sql_query("SELECT rating, hitokoto, record, icon, id FROM users WHERE username = ?", ['s', $_GET['friendName']]);
$userInfoArray = mysqli_fetch_array($getUserInfo);

//get the user's friend list to see whether the curerntly-viewed player is on it or not
$getFriends =  safe_sql_query("SELECT friends, id FROM users WHERE username = ?", ['s', getCurrentUser()]);
$friendsArray = mysqli_fetch_array($getFriends);
$userId = $friendsArray['id'];
$friendIds = explode(',', $friendsArray['friends']); //should separate the friend list by commas

begin_html_page("SLO Shogi Friends", ['user_page.css']);
?>

<a id="backButton" href="friends.php">≪</a>
<br>
<br>
<div id="all">
    <div id="nameIconRating">
        <h1 id="userName"><?= $_GET['friendName'] ?></h1>
        <h2 id="rating">段級: <?= $userInfoArray['rating'] ?></h1>
            <h2 id="record">勝敗レコード: <?= $userInfoArray['record'] ?> </h1>
                <p id="hitokotoInput">"<?= $userInfoArray['hitokoto'] ?>"</p>
                <div id="iconBox">
                    <img src="/public/images/icons/<?= $userInfoArray['icon'] ?>_icon.png" id="userIcon">
                </div>
    </div>
</div>
<?php
if (
    array_search($userInfoArray['id'], $friendIds) === false    // using === false is important https://stackoverflow.com/questions/2581619/php-what-does-array-search-return-if-nothing-was-found
    && $userInfoArray['id'] !== $userId
) {
    //if the currently viewed friend isn't in the user's friend list and it isn't your own page
    echo "<a href='/friends/add-to-friends?name=" . $_GET['friendName'] . "'>友達に追加　Add to friends</a>";
}

?>
<?php
end_html_page();
