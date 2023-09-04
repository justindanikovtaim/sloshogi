<?php
    setcookie('tsume_score', $_POST['solvedTime'], time() + (86400 * 30), "/");//set the cookie so the highscore can be remembered
    setcookie('tsume_problem', $_POST['problemId'], time() + (86400 * 30), "/");

    header('location: new_account.html');
?>