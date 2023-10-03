<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'session.php';
require_once SHAREDPATH . 'template.php';

$currentUser = getCurrentUser();

$getActiveGames = safe_sql_query("SELECT id FROM gamerecord WHERE
(blackplayer = ? OR whiteplayer = ?)
AND (status = 1 OR status = 2)", ['ss', $currentUser, $currentUser]);

$activeGameCount = 0;
while ($sqlArrayHolder = mysqli_fetch_array($getActiveGames)) { //this loop goes though the user's active games and increments the counter for each
    $activeGameCount++;
}

$getUserLevel = safe_sql_query("SELECT user_level FROM users WHERE username = ?", ['s', $currentUser]);
$userLevel = mysqli_fetch_array($getUserLevel);

if ($activeGameCount < 5 || $userLevel['user_level'] > 0) {



    if (safe_sql_query("UPDATE gamerecord SET status = 2, dateStarted = CURRENT_DATE() WHERE id = ?", ['i', $_GET['id']])) {
        header('Location: /gameboard?id=' . $_GET['id']);
    } else {
        echo "There was an error. Please try again";
    }
} else {
    echo "既に５つの対戦に参加しているため、新しい対戦に参加することはできません<br> You already have 5 active games/challenges. You cannot join a new game.";
}

begin_html_page('Slo Shogi Challenge');
?>

<a id="backButton" href="/view-challenge?id=<?php echo $_GET['id'] ?>">≪</a>

<?php
end_html_page();
