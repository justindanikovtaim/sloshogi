<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

function redirectToUserPage()
{
    header('location: /user-page');
    die();
}

function redirectToPrivateGame()
{
    header('Location: /private-game');
    die();
}

function fetchGameRecord($gameID)
{
    $result = safe_sql_query("SELECT * FROM gamerecord WHERE id = ?", ['i', $gameID]);
    return mysqli_fetch_array($result);
}

function validateGameID($gameRecord)
{
    if ($gameRecord['private'] != 0 && getCurrentUser() != $gameRecord['blackplayer'] && getCurrentUser() != $gameRecord['whiteplayer']) {
        redirectToPrivateGame();
    }
}

function determinePlayerColor($gameRecord)
{
    if ($gameRecord['blackplayer'] == getCurrentUser()) {
        return "black";
    } else {
        return "white";
    }
}

function determineNewChatIcon($chatSeen)
{
    if ($chatSeen == 1 || $chatSeen == 0) {
        return 1;
    } else {
        return 0;
    }
}

function fetchOpponentInfo($opponentName)
{
    $getUserInfo = safe_sql_query("SELECT rating, icon, username FROM users WHERE username = ?", ['s', $opponentName]);
    return mysqli_fetch_array($getUserInfo);
}

function fetchUserInfo($currentUser)
{
    $getUserInfo = safe_sql_query("SELECT rating, icon, username, komaSet FROM users WHERE username = ?", ['s', $currentUser]);
    return mysqli_fetch_array($getUserInfo);
}

function fetchChatHistory($gameID)
{
    $getChat = safe_sql_query("SELECT chat FROM gamerecord WHERE id = ?", ['i', $gameID]);
    $chatArray = mysqli_fetch_array($getChat);
    return explode("%%", $chatArray['chat']);
}

$gameID = $_GET['id'];
$gameRecord = fetchGameRecord($gameID);
if (!$gameRecord) {
    // If a game ID that doesn't exist is input
    redirectToUserPage();
}

validateGameID($gameRecord);

$temparray = [
    $gameRecord["moves"],
    $gameRecord["blackplayer"],
    $gameRecord["whiteplayer"],
    $gameRecord["reservation1"],
    $gameRecord["reservation2"],
    $gameRecord["reservation3"],
    $gameRecord["status"],
    $gameRecord["winner"],
    getCurrentUser(),
    $gameRecord['lastMoveTime']
];

$chatSeen = $gameRecord['chatseen'];
$playerColor = determinePlayerColor($gameRecord);
$newChatIcon = determineNewChatIcon($chatSeen);
$chatSeenNum = ($playerColor == "black") ? 2 : 1;

$opponentName = ($playerColor == "black") ? $gameRecord['whiteplayer'] : $gameRecord['blackplayer'];
$opInfo = fetchOpponentInfo($opponentName);
$userInfo = fetchUserInfo(getCurrentUser());
$chatHistory = fetchChatHistory($gameID);

begin_html_page('Slo Shogi - Gameboard', ['Gameboard_style_sheet.css'], ['track_gameboard_time.js'], true);
?>

<div id="wholeBoard">
    <div id="pNoP">
        <img src="/public/images/koma/<?= $userInfo['komaSet'] ?>/BGYOKU.png" id="promote">
        <img src="/public/images/koma/<?= $userInfo['komaSet'] ?>/BNHI.png" id="dontPromote">
    </div>

    <div id="boardColor">

        <a href="/user-page" id="backButton">≪</a>

        <div id="opInfo">
            <a href="/friends/view-friend?friendName=<?= $opInfo['username'] ?>">
                <div id="opIconBox">
                    <img src="/public/images/icons/<?= $opInfo['icon'] ?>_icon.png" id="opIcon">
                </div>
                <div id="opNameBox">
                    <h4 id="opName"><?= $opponentName ?></h4>
                </div>
            </a>
        </div>


        <div id="whiteMochigoma"></div>
        <div class="boardNum" id="topNumber1">9</div>
        <div></div>
        <div class="boardNum" id="topNumber2">8</div>
        <div></div>
        <div class="boardNum" id="topNumber3">7</div>
        <div></div>
        <div class="boardNum" id="topNumber4">6</div>
        <div></div>
        <div class="boardNum" id="topNumber5">5</div>
        <div></div>
        <div class="boardNum" id="topNumber6">4</div>
        <div></div>
        <div class="boardNum" id="topNumber7">3</div>
        <div></div>
        <div class="boardNum" id="topNumber8">2</div>
        <div></div>
        <div class="boardNum" id="topNumber9">1</div>

        <div class="boardKanji" id="kanji9">一</div>
        <div class="boardKanji" id="kanji8">二</div>
        <div class="boardKanji" id="kanji7">三</div>
        <div class="boardKanji" id="kanji6">四</div>
        <div class="boardKanji" id="kanji5">五</div>
        <div class="boardKanji" id="kanji4">六</div>
        <div class="boardKanji" id="kanji3">七</div>
        <div class="boardKanji" id="kanji2">八</div>
        <div class="boardKanji" id="kanji1">九</div>


        <div id="board">


        </div>
        <div id="blackMochigoma"></div>

        <div id="undo" onClick="window.location.reload()"><img src="/public/images/undo_button.png" id="undoImg"></div>
    </div>

    <div id="userInfo">
        <div id="userIconBox">
            <img src="/public/images/icons/<?= $userInfo['icon'] ?>_icon.png" id="userIcon">
        </div>
        <div id="userNameBox">
            <h4 id="userName"><?= getCurrentUser() ?></h4>
        </div>
    </div>

    <div id="menuBox" onclick="showMenu()">
        <img src="/public/images/menu_button.png" id="menuButton">
        <img class="msgIcon" src="/public/images/new_message_icon.png" id="newMessage">
    </div>

    <div class="popupMenu" id="popupMenuId">
        <a href="#" onClick="toggleChat()">チャット表示・View Chat</a>
        <img class="msgIcon" src="/public/images/new_message_icon.png" id="newMessageInMenu">
        <a href="#" id="resignButton" onClick="resign()">投了・Resign</a>
        <a href="/gameboard/kifu/write-kifu?id=<?= $gameID ?>">棋譜をダウンロード・Download Kifu</a>
        <a href="#" onClick="sendForTsume()">この局面を詰将棋にする</a>
        <a href="/feedback-form?src=gameboard&id=<?= $gameID ?>">バグ報告・Report a bug</a>
    </div>

    <div class="popupChat" id="popupChat">
        <button onclick="closeChat()" style="font-size: 6vw; background-color: lightgrey">✕</button>
        <div id="popupChatText">
        </div>

        <textarea id="popupChatInput" name="textToSend"></textarea>
        <button onclick="submitOnEnter('click')" id="chatSend">SEND</button>

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



    <div id="resButtons">
        <div id="resTextBox">
            <h3 id="resText">自動指し予約　Move Reservation</h3>
        </div>
        <!--
    <div id = "resBox1">
<a href="move_reservation.php?id=<?= $gameID ?>&komaSet=<?= $userInfo['komaSet'] ?>&resBox=1" ><img src = /public/images/reservation/res_1_grey.png id = "resButton1"></a>
</div>
<div id="resBox2">
<a href="move_reservation.php?id=<?= $gameID ?>&komaSet=<?= $userInfo['komaSet'] ?>&resBox=2" ><img src = /public/images/reservation/res_2_grey.png id = "resButton2"></a>
</div>
<div id="resBox3">
<a href="move_reservation.php?id=<?= $gameID ?>&komaSet=<?= $userInfo['komaSet'] ?>&resBox=3" ><img src = /public/images/reservation/res_3_grey.png id = "resButton3"></a>
</div>
-->
    </div>

</div>
<!-- this is the form to create a tsumeshogi problem -->
<form id="tsumeInfo" method="post" action="/set-tsume">
    <input type="hidden" name="boardConfig" id="boardConfig">
    <input type="hidden" name="mochigomaConfig" id="mochigomaConfig">
</form>

<script>
    var currentGameID = <?= $gameID ?>;
    var gameHistory = <?php echo json_encode($temparray); ?>;
    var phpColor = "<?= getCurrentUser() ?>";
    let colorForTime = "<?= $playerColor ?>";
    var chatNoChange = <?= $chatSeen ?>;
    var newMsgIcon = <?= $newChatIcon ?>;
    var chatSeenNum = <?= $chatSeenNum ?>;
    var msgSent = false;
    var seenNotSent = false;
    var komaSet = <?= $userInfo['komaSet'] ?>;

    if (newMsgIcon == 0) { //if there are no new messages (0 = false)
        document.getElementById("newMessage").style.visibility = "hidden";
        document.getElementById("newMessageInMenu").style.visibility = "hidden";
    }
    //write the chat contents to the hidden chat screen
    let chatHistory = <?= json_encode($chatHistory) ?>;
    let chatContents = [];
    let counter = 1;
    for (i = 0; i < chatHistory.length; i += 2) {
        chatContents[i] = document.createElement("H3");
        chatContents[i].setAttribute("class", "chatNameHeader");
        chatContents[i].innerHTML = chatHistory[i];
        chatContents[i + 1] = document.createElement("p");
        chatContents[i + 1].innerHTML = chatHistory[i + 1];
        //console.log(chatContents[i+1].scrollHeight);
        // chatContents[i+1].style.height = chatContents[i+1].scrollHeight +"px";
        if (chatHistory[i] == gameHistory[8]) {
            //if it is the user's message
            chatContents[i].style = "text-align: right";
            chatContents[i + 1].style = "text-align: right";
            //make the messages appear on the right side of the chat box
            chatContents[i + 1].setAttribute("class", "chatEntryRight");
        } else {
            chatContents[i + 1].setAttribute("class", "chatEntryLeft");

        }
        document.getElementById("popupChatText").appendChild(chatContents[i]);
        document.getElementById("popupChatText").appendChild(chatContents[i + 1]);
    }
    document.getElementById("popupChatInput").addEventListener("keypress", submitOnEnter);


    function submitOnEnter(event) { //https://stackoverflow.com/questions/8934088/how-to-make-enter-key-in-a-textarea-submit-a-form
        if ((event.which === 13 && !event.shiftKey) || event == "click") { //if the enter key was pushed
            msgSent = true;
            var ajax = new XMLHttpRequest();
            let msgToSend = JSON.stringify({
                "textToSend": document.getElementById("popupChatInput").value,
                "gameId": <?= $gameID ?>,
                "chatSeenNum": chatSeenNum
            });
            console.log(msgToSend);
            ajax.onreadystatechange = function() {
                // If ajax.readyState is 4, then the connection was successful
                // If ajax.status (the HTTP return code) is 200, the request was successful
                if (ajax.readyState == 4 && ajax.status == 200) {
                    // Use ajax.responseText to get the raw response from the server
                    //window.location.reload();//this doesn't work... need to update the chat box without reloading the page instead
                    //closeChat();
                } else {
                    console.log('Error: ' + ajax.status); // An error occurred during the request.
                }

            }
            ajax.open("POST", '/send-chat', true); //asyncronous
            ajax.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
            //make this send a json file
            ajax.send(msgToSend);

            let userName = document.createElement("H3");
            userName.setAttribute("class", "chatNameHeader");
            userName.style = "text-align: right";
            userName.innerHTML = "<?= getCurrentUser() ?>";

            let newMsg = document.createElement("p");
            newMsg.setAttribute("class", "chatEntryRight");
            newMsg.innerHTML = document.getElementById("popupChatInput").value;
            console.log(newMsg);
            document.getElementById("popupChatText").appendChild(userName);
            document.getElementById("popupChatText").appendChild(newMsg);

            document.getElementById("popupChatInput").value = ""; //empty the text input box
            document.getElementById("popupChatText").scrollTop = document.getElementById("popupChatText").scrollHeight;

        }
    }


    function showMenu() {
        document.getElementById("popupMenuId").classList.toggle("menuShow");
        document.getElementById("menuBox").classList.toggle("turnRed");
    }

    function toggleChat() {
        seenNotSent = true;
        //make the menu disapear
        showMenu();
        //make the chat appear
        document.getElementById("popupChat").classList.toggle("chatShow");
        //make it scroll to the bottom of the chat
        document.getElementById("popupChatText").scrollTop = document.getElementById("popupChatText").scrollHeight;
        document.getElementById("newMessage").style.visibility = "hidden";
        document.getElementById("newMessageInMenu").style.visibility = "hidden";
    }

    function closeChat() {
        document.getElementById("popupChat").classList.toggle("chatShow");
    }
</script>
<script src="/public/js/track_gameboard_time.js"></script>
<script src="/public/js/slo_shogi_script.js"></script>
<script src="/public/js/game_prompt.js"></script>

<?php
end_html_page();
