<?php

session_start();
//if the reset post superglobal is set, that means the reset button was clicked
if (isset($_POST['reset']) || isset($_GET['new'])) {
    session_unset();
}

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';

/*
$tsumeID = $_GET['id'];
$result = mysqli_query($link, 'SELECT * FROM tsumeshogi WHERE id = '.$gameID); //get all the current from moves
if($result->num_rows == 0 ){
    //if a game id that doesn't exist is input
    header('location: user_page.php');
    die();
}

$row = mysqli_fetch_array($result);
$setup = $row['boardSetup'];
$timeLimit = $row['timeLimit'];
$creator = $row['createdBy'];
*/

begin_html_page('Slo Tsumeshogi', ['Gameboard_style_sheet.css', 'create_tsume.css'], [], true);
?>

<div id="wholeBoard">
    <div id="boardColor">
        <a href="/slo-tsumeshogi" id="backButton">≪</a>


        <div id="whiteMochigoma"></div>

        <div id="board"></div>
        <div id="choosePieceId" class="choosePiece"></div>
        <div id="blackMochigoma"></div>

    </div>

    <div id="menuBox" onclick="showMenu()">
        <img src="/public/images/menu_button.png" id="menuButton">
        <img class="msgIcon" src="/public/images/new_message_icon.png" id="newMessage">
    </div>

    <div class="popupMenu" id="popupMenuId">
        <a href="/feedback-form?src=gameboard&id=<?= $gameID ?>">バッグ報告・Report a bug</a>
    </div>

    <div id="promptBox">
        <h3 id="playerPrompt"></h3>
    </div>

    <div id="resetNextButtons">
        <button id="reset" onClick="reset()">リセット</button>
        <button id="next" onClick="toCreatePage()">次のステップ</button>

    </div>
    <form id="resetTsume" method="post">
        <input type="hidden" name="reset" value="true">
    </form>
    <form id="configData" method="post" action="/set-tsume">
        <input type="hidden" name="boardConfig" id="boardConfig">
        <input type="hidden" name="mochigomaConfig" id="mochigomaConfig">
    </form>
</div>

<script>
    function showMenu() {
        document.getElementById("popupMenuId").classList.toggle("menuShow");
        document.getElementById("menuBox").classList.toggle("turnRed");

    }

    <?php
    if (isset($_SESSION['boardConfig'])) {
        echo "let boardConfigurationString ='" . $_SESSION['boardConfig'] . "';";
        echo "var boardConfiguration = boardConfigurationString.split(',');";
    } else {
        echo "var boardConfiguration = [];";
        echo "for(x=0;x<81;x++){ boardConfiguration[x] = 'empty';}";
    }
    ?>
    var mochiGomaConfiguration;

    <?php
    if (isset($_SESSION['mgConfig'])) {
        echo " let mgConfigString = '" . $_SESSION['mgConfig'] . "';";
        echo "mochiGomaConfiguration = mgConfigString.split(',');";
    } else {
        echo "mochiGomaConfiguration = [2,2,2,2,2,2,2,0,0,0,0,0,0,0];";
    }
    ?>
</script>
<script src="scripts/tsume_shared.js"></script>
<script src="scripts/create_tsume_script.js"></script>
