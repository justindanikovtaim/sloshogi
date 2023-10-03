<?php

require_once SHAREDPATH . 'database.php';

$getActiveGames = safe_sql_query("SELECT id FROM gamerecord WHERE
(blackplayer = ? OR whiteplayer = ?)
AND (status = 1 OR status = 2)", ['s', getCurrentUser(), getCurrentUser()]);

$activeGameCount = 0;
while ($sqlArrayHolder = mysqli_fetch_array($getActiveGames)) { //this loop goes though the user's active games and increments the counter for each
    $activeGameCount++;
}

if (safe_sql_query("SELECT blackplayer FROM gamerecord WHERE id = ?", ['i', $_GET['id']]) == "NULL") {
    //if blackplayer is null, the opponent is the other color, so the user should become blackplayer
    $joinGameCommand = 'UPDATE gamerecord SET status = 2, dateStarted = CURRENT_DATE(), blackplayer = ? WHERE id = ?';
} else {
    $joinGameCommand = 'UPDATE gamerecord SET status = 2, dateStarted = CURRENT_DATE(), whiteplayer = ? WHERE id = ?';
}

begin_html_page('');

?>

<h1>
    <?php
    if ($activeGameCount < 5) {

        if (safe_sql_query($joinGameCommand)) {
            //upon success, redirect to the gameboard page
            echo "<script>location.href='/gameboard?id=" . $_GET['id'] . "';</script>";
        } else {
            //otherwise, redirect to the previous page
            echo "ERROR: Not able to execute $joinGameCommand. " . mysqli_error($link);
            echo "<script>location.href='/new-game/join-game';</script>";
        }
    } else {
        echo "既に５つの対戦に参加しているため、新しい対戦に参加することはできません";
    }
    ?>
</h1>

<?php end_html_page() ?>
