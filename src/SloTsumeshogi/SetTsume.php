<?php

require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

$_SESSION['boardConfig'] = $_POST['boardConfig'];
$_SESSION['mgConfig'] = $_POST['mochigomaConfig'];

begin_html_page('Setup Slo tsumeshogi', ['tsume_style_sheet.css', 'create_tsume.css']);
?>

<div id="wholeBoard">
    <div id="boardColor">
        <a href="/slotsumeshogi/initialize-tsume" id="backButton">≪</a>
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
        <a href="/feedback-form?src=set_tsume">バグ報告・Report a bug</a>
    </div>

    <div id="promptBox">
        <h3 id="playerPrompt"></h3>
    </div>

    <div id="skipButtons">
        <button id="next" onClick="toSavePage()">保存</button>

    </div>
    <!--if the problem name session variable is set, it means the problem is be re-edited, so the save url is different-->
    <form id="tsumeData" method="post" action="<?php isset($_SESSION['problemName']) ? '/slotsumeshogi/save-tsume?reSave=' . $_SESSION['problemId'] : '/slotsumeshogi/save-tsume' ?>">
        <label for="problemName">問題の名前を入力してください</label>
        <input type="text" name="problemName" id="problemName" value="<?php isset($_SESSION['problemName']) && $_SESSION['problemName'] ?>">
        <br>
        <label for="timelimit">タイマー（秒単位)</label>
        <input type="number" name="timeLimit" id="timeLimit">）
        <input type="submit" value="問題を保存">
        <input type="hidden" name="boardConfig" id="boardConfig" value="<?php echo $_SESSION['boardConfig'] ?>">
        <input type="hidden" name="mochigomaConfig" id="mochigomaConfig" value="<?php echo $_SESSION['mgConfig'] ?>">
        <input type="hidden" name="moveSequence" id="moveSequence">
    </form>

    <script>
        let tempstring = "<?php echo $_POST['boardConfig'] ?>";
        var gameState = tempstring.split(",");
        tempstring = "<?php echo $_POST['mochigomaConfig'] ?>";
        var mochiGomaArray = tempstring.split(",");

        function showMenu() {
            document.getElementById("popupMenuId").classList.toggle("menuShow");
            document.getElementById("menuBox").classList.toggle("turnRed");

        }
    </script>

    <script src="/public/js/tsume_shared.js"></script>
    <script src="/public/js/tsume_set.js"></script>

    <?php
    end_html_page();
