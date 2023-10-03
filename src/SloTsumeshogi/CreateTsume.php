<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
/*
$tsumeID = $_GET['id'];
$result = safe_sql_query('SELECT * FROM tsumeshogi WHERE id = ?', ['i', $tsumeID]); //get all the current from moves
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

begin_html_page('Slo Tsumeshogi', ['Gameboard_style_sheet.css'], [], true);
?>

<div id="wholeBoard">
    <div id="boardColor">
        <a href="/slo-tsumeshogi" id="backButton">≪</a>


        <div id="whiteMochigoma"></div>

        <div id="board"></div>
        <div id="blackMochigoma"></div>

    </div>


    <div id="menuBox" onclick="showMenu()">
        <img src="/public/images/menu_button.png" id="menuButton">
        <img class="msgIcon" src="/public/images/new_message_icon.png" id="newMessage">
    </div>
    <div class="popupMenu" id="popupMenuId">
        <a href="/feedback-form?src=gameboard&id=<?php echo $gameID ?>">バッグ報告・Report a bug</a>
    </div>

    <div id="promptBox">
        <h3 id="playerPrompt"></h3>
    </div>

    <div id="skipButtons">
        <button class="skipButton" id="fullBack" onClick="skipBack()">≪</button>
        <button class="skipButton" id="oneBack" onClick="stepBack()">
            < </button>
                <button class="skipButton" id="oneForward" onClick="stepForward()"> > </button>
                <button class="skipButton" id="fullForward" onClick="skipForward()">≫</button>

    </div>
</div>

<script>
    function showMenu() {
        document.getElementById("popupMenuId").classList.toggle("menuShow");
        document.getElementById("menuBox").classList.toggle("turnRed");
    }
</script>
<script src="/public/js/create_tsume_script.js"></script>

<?php end_html_page(); ?>
