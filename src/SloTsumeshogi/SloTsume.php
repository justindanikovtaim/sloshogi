<?php
require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'session.php';
require_once SHAREDPATH . 'session.php';

if (getCurrentUser()) {
    //redirect to the normal tsumeshogi page if it is a registered user
    header('location: /slotsumeshogi/tsume?id=' . $_GET['id']);
}

$tsumeID = $_GET['id'];
$result = safe_sql_query('SELECT * FROM tsumeshogi WHERE id = "' . $tsumeID . '" AND published = 1', ['i', $tsumeID]); //get all the current from moves
if ($result->num_rows == 0) {
    //if a problem id that doesn't exist is input go back to Sloshogi main page
    header('location: /');
    die();
}

$row = mysqli_fetch_array($result);
$setup = $row['boardSetup'];
$mgSetup = $row['mochigomaSetup'];
$timeLimit = $row['timeLimit'];
$creator = $row['createdBy'];
$sequence = $row['mainSequence'];
$problemName = $row['problemName'];
$scoreBoard = explode(";", $row['scoreBoard']);
$leaderBoard = "";
$place = 1;
for ($i = 0; $i < (sizeof($scoreBoard) - 1); $i += 2) {
    $seconds = ($scoreBoard[$i + 1] % 60);
    if ($seconds < 10) {
        $seconds = "0" . $seconds; //add a zero in the tens place if a single digit
    }
    $minutes = intVal($scoreBoard[$i + 1] / 60);
    $leaderBoard .= "<h1>" . $place . "." . $scoreBoard[$i] . ": " . $minutes . ":" . $seconds . "</h1>";
    $place++;
}
//get the chat history
$getChat = safe_sql_query("SELECT chat FROM tsumeshogi WHERE id = ?", ['i', $tsumeID]);
$chatArray = mysqli_fetch_array($getChat);
$chatHistory = explode("%%", $chatArray['chat']);

begin_html_page('Slo Tsumeshogi', ['tsume_style_sheet.css']);
?>

<div id="wholeBoard">
    <div id="boardColor">
        <a href="slo_tsumeshogi.php" id="backButton">≪</a>


        <div id="whiteMochigoma"></div>

        <div id="board"></div>
        <div id="blackMochigoma"></div>

    </div>


    <div id="menuBox" onclick="showMenu()">
        <img src="/public/images/menu_button.png" id="menuButton">
    </div>

    <div class="popupMenu" id="popupMenuId">
        <a href="#" id="leaderBoard" onClick="toggleLeaderBoard()">トップスコア・Top Scores</a>
        <a href="#" onClick="toggleChat()">チャット表示・View Chat</a>
        <a href="/feedback-form?src=gameboard&id=<?php echo $gameID ?>">バグ報告・Report a bug</a>
    </div>
    <div class="popupChat" id="popupChat">
        <button onclick="closeChat()" style="font-size: 6vw; background-color: lightgrey">✕</button>
        <div id="popupChatText">
            <textarea id="popupChatInput" name="textToSend"></textarea>
            <button onclick="submitOnEnter('click')" id="chatSend">SEND</button>
        </div>
    </div>

    <div class="popupChat" id="scoreBoard">
        <button onclick="closeLeaderBoard()" style="font-size: 6vw; background-color: lightgrey">✕</button>
        <div id="scoreBoardText">
        </div>
    </div>

    <div id="waitingMsg">
        <h2>AI検討中</h2>
    </div>
    <div id="promptBox">
        <h3 id="playerPrompt"></h3>
    </div>


    <h1 id="timer"></h1>
    <div id="skipButtons">
        <button class="skipButton" id="fullBack" onClick="resetTsume(<?php echo $timeLimit ?>)">リセット</button>

        <a href="/slotsumeshogi/tsume?id=<?php echo $tsumeID + 1 ?>"><button class="skipButton" id="fullForward">≫</button></a>

    </div>

    <form id="newAccountForm" method="post" action="/slotsumeshogi/make-account-tsume">
        <input type="hidden" id="solvedTimeNewAccount" name="solvedTime">
        <input type="hidden" name="problemId" value="<?php echo $_GET['id'] ?>">
    </form>

    </body>

    <script>
        let playerName = "Guest";
        var currentGameID = <?php echo $tsumeID ?>;
        let setup = "<?php echo $setup ?>";
        var gameState = setup.split(",");
        let mgArray = "<?php echo $mgSetup ?>";
        var mochiGomaArray = mgArray.split(",");
        let sequence = "<?php echo $sequence ?>";
        var mainSequence = sequence.split(",");
        var problemName = "<?php echo $problemName ?>";
        var isGuest = true;
        //check below!
        var originalTimeLimit = "<?php if (isset($_COOKIE[$tsumeID . 'timeLimit'])) {
                                        echo $_COOKIE[$tsumeID . 'timeLimit'];
                                    } else {
                                        echo $timeLimit;
                                    } ?>";
        var timeLimit = originalTimeLimit;

        document.getElementById("scoreBoardText").innerHTML = "<?php echo $leaderBoard ?>";
        //set the timerand update it
        let minutes = timeLimit / 60;
        let seconds = timeLimit % 60;
        var timerSet = setInterval(function() {
            updateTimer();
        }, 1000);

        function updateTimer() {
            if (timeLimit > -1) {
                minutes = parseInt(timeLimit / 60);
                seconds = (timeLimit % 60);
                if (seconds < 10) {
                    seconds = "0" + seconds; //add a zero in the tens place if a single digit
                }
                document.getElementById("timer").innerHTML = minutes + ":" + seconds;
                timeLimit--;
            } else {
                clearInterval(timerSet);
                timeUp();
            }
        }

        //record the current remaining time whenever the page is navigated away from
        document.addEventListener("visibilitychange", function logTime() {
            if (document.visibilityState === 'hidden') {
                let $data = JSON.stringify({
                    id: currentGameID,
                    seconds: timeLimit
                });
                navigator.sendBeacon("/slotsumeshogi/tsume-time", $data);
            }
        });

        function timeUp() {
            setMessage("時間です！Time's Up! Try Again");
            disableAll();
        }

        function showMenu() {
            document.getElementById("popupMenuId").classList.toggle("menuShow");
            document.getElementById("menuBox").classList.toggle("turnRed");
        }



        //write the chat contents to the hidden chat screen
        let chatHistory = <?php echo json_encode($chatHistory) ?>;
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
            if (chatHistory[i] == playerName) {
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
                    "gameId": <?php echo $tsumeID ?>
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
                ajax.open("POST", 'send_tsume_chat.php', true); //asyncronous
                ajax.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
                //make this send a json file
                ajax.send(msgToSend);

                let userName = document.createElement("H3");
                userName.setAttribute("class", "chatNameHeader");
                userName.style = "text-align: right";
                userName.innerHTML = "Guest";

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


        function toggleChat() {
            //make the menu disapear
            showMenu();
            //make the chat appear
            document.getElementById("popupChat").classList.toggle("chatShow");
            //make it scroll to the bottom of the chat
            document.getElementById("popupChatText").scrollTop = document.getElementById("popupChatText").scrollHeight;
        }

        function toggleLeaderBoard() {
            document.getElementById("scoreBoard").classList.toggle("chatShow");
        }

        function closeChat() {
            document.getElementById("popupChat").classList.toggle("chatShow");
        }

        function closeLeaderBoard() {
            document.getElementById("scoreBoard").classList.toggle("chatShow");

        }
    </script>
    <script src="/public/js/tsume_shared.js"></script>
    <script src="/public/js/tsume_script.js"></script>
    <script src="/public/js/tsume_ai.js"></script>

    <?php end_html_page() ?>
