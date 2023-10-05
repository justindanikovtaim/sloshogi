<?php
require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'session.php';

$tsumeID = $_GET['id'];
$result = safe_sql_query('SELECT * FROM tsumeshogi WHERE id = ? AND published = 1', ['s', $tsumeID]); //get all the current from moves
if ($result->num_rows == 0) {
    $tryUserName = safe_sql_query('SELECT * FROM tsumeshogi WHERE id = ? AND createdBy = ?', ['ss', $tsumeID, getCurrentUser()]); //search based on the user's name in case it's an unpubllished problem
    if ($tryUserName->num_rows == 0) {
        //if a problem id that doesn't exist is input go back to tsumeshogi main page
        header('location: /slotsumeshogi');
        die();
    } else {
        $result = $tryUserName; //if it's an unpublished problem, this line is needed to make the rest of the code work
    }
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
$getChat = safe_sql_query('SELECT chat FROM tsumeshogi WHERE id = ?', ['s', $tsumeID]);
$chatArray = mysqli_fetch_array($getChat);
$chatHistory = explode("%%", $chatArray['chat']);

$result = safe_sql_query('SELECT id FROM tsumeshogi WHERE published = 1'); //get all the published problems
$publishedProblemsArray = [];
while ($row = mysqli_fetch_array($result)) {
    array_push($publishedProblemsArray, $row['id']);
}
if (isset($publishedProblemsArray[array_search($tsumeID, $publishedProblemsArray) + 1])) {
    $nextProblem = $publishedProblemsArray[array_search($tsumeID, $publishedProblemsArray) + 1]; //find the location of the current problem in the array and go to the next problem

} else {
    $nextProblem = $tsumeID + 1; //if there is no next problem to go to, set it to nonexistant problem that will take the user back to the tsume shogi screen

}

begin_html_page('Slo Tsumeshogi', ['tsume_style_sheet.css'], [], true);
?>

<div id="wholeBoard">
    <div id="boardColor">
        <a href="/slotsumeshogi" id="backButton">≪</a>
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
        <a href="#" onClick="copyToClipboard(<?php echo $tsumeID ?>)">共有URLを発行</a>
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
        <a href="/slotsumeshogi/tsume?id=<?php echo $nextProblem ?>"><button class="skipButton" id="fullForward">≫</button></a>
    </div>
    <?php
    if ($creator == 'kayanokoithi') { //if it's neko-san's problem
        echo "<br><br><br><br><br><br><h2>この問題は「榧野香一の榧香る３手詰にゃ」という詰将棋本よりです。";
        echo "作者のTwitterは<a style='color: blue; font-size: 5vw' href = 'https://twitter.com/kayanokoithi' target='_blank' rel='noopener noreferrer'> @kayanokoithi</a><br>";
        echo "<a style='font-size: 5vw; color:chocolate;' href='https://www.amazon.co.jp/%E6%A6%A7%E9%87%8E%E9%A6%99%E4%B8%80%E3%81%AE%E6%A6%A7%E9%A6%99%E3%82%8B%EF%BC%93%E6%89%8B%E8%A9%B0%E3%81%AB%E3%82%83-%E5%9D%82%E7%94%B0%E6%85%8E%E5%90%BE/dp/B09PHF9BM8'target='_blank' rel='noopener noreferrer'>アマゾンの購入はこちら</a></h2>";
    }
    ?>
</div>

<script>
    let playerName = "<?php echo getCurrentUser() ?>";
    var currentGameID = <?php echo $tsumeID ?>;
    let setup = "<?php echo $setup ?>";
    var gameState = setup.split(",");
    let mgArray = "<?php echo $mgSetup ?>";
    var mochiGomaArray = mgArray.split(",");
    var phpColor = "<?php echo getCurrentUser() ?>";
    let sequence = "<?php echo $sequence ?>";
    var mainSequence = sequence.split(",");
    var problemName = "<?php echo $problemName ?>";
    var isGuest = false;
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
            navigator.sendBeacon("/sloshogi/tsume-time", $data); //UPDATE!
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
            userName.innerHTML = "<?php echo getCurrentUser() ?>";

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

    function copyToClipboard(id) {
        navigator.clipboard.writeText("https://www.sloshogi.com/slotsumeshogi/slo-tsume?id=" + id);
        alert("クリップボードにコピーされた (注意！他人は非公開の詰将棋が見れないので、共有する前に公開されていることをチェックしましょう！");
    }
</script>
<script src="/public/js/tsume_shared.js"></script>
<script src="/public/js/tsume_script.js"></script>
<script src="/public/js/tsume_ai.js"></script>
<?php end_html_page() ?>
