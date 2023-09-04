<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

$getFriends = safe_sql_query("SELECT friends FROM users WHERE username = ?", ['s', getCurrentUser()]);
$friendIds = explode(',', mysqli_fetch_array($getFriends)['friends']); //should separate the friend list by commas
$numOfFriends = count($friendIds);
//$findFriends = [];
for ($x = 0; $x < $numOfFriends; $x++) {
    $getData = safe_sql_query("SELECT username FROM users WHERE id = ?", ['i', $friendIds[$x]]);
    $findFriends[$x] = mysqli_fetch_row($getData)[0];
}

begin_html_page("SLO Shogi Friends", ['friends_page.css']);
?>

<a id="backButton" href="/user-page">≪</a>
<h1>友達</h1>
<div id="drawFriends"></div>
<br>
<br>
<a href="/friends/add-friend" class="noUnderline">
    <div class="buttonBorder">
        友達を追加
    </div>
</a>
<br>
<a href="/friends/invite-email" class="noUnderline">
    <div class="buttonBorder">
        未登録の友達をメールで招待
    </div>
</a>
<div id="friendRequests">
</div>
<script>
    let friendsArrayLength = Number(<?php echo $numOfFriends; ?>);
    let friendLinks = [];
    let counter = 0;

    //make a php foreach loop to create each name in the friendLinks array as a a element in the DOM
    <?php foreach ($findFriends as $i) : ?>

        friendLinks[counter] = document.createElement("a");
        friendLinks[counter].innerHTML = "<?= $i ?>";
        friendLinks[counter].setAttribute("href", "/friends/view-friend?friendName=<?= $i ?>");
        document.getElementById("drawFriends").appendChild(friendLinks[counter]);
        document.getElementById("drawFriends").innerHTML += "<br>";
        counter++
    <?php endforeach; ?>
</script>

<?php
end_html_page();
