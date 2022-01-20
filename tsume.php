<?php 
require 'connect.php';

$tsumeID = $_GET['id'];
$result = mysqli_query($link, 'SELECT * FROM tsumeshogi WHERE id = "'.$tsumeID.'"'); //get all the current from moves
if($result->num_rows == 0 ){
    //if a problem id that doesn't exist is input go back to tsumeshogi main page
    header('location: slo_tsumeshogi.php');
    die();
}

$row = mysqli_fetch_array($result);
$setup = $row['boardSetup'];
$mgSetup = $row['mochigomaSetup'];
$timeLimit = $row['timeLimit'];
$creator = $row['createdBy'];
$sequence =$row['mainSequence'];
$problemName = $row['problemName'];
$scoreBoard = explode(";", $row['scoreBoard']);
$leaderBoard = "";
$place = 1;
for($i=0;$i<(sizeof($scoreBoard)-1);$i+=2){
     $seconds = ($scoreBoard[$i+1] % 60);
            if($seconds < 10){
                $seconds = "0" . $seconds; //add a zero in the tens place if a single digit
            }
     $minutes = intVal($scoreBoard[$i+1] / 60);
    $leaderBoard.= "<h1>".$place.".".$scoreBoard[$i].": ".$minutes.":".$seconds."</h1>";
    $place ++;
}
//get the chat history 
$getChat = mysqli_query($link, 'SELECT chat FROM tsumeshogi WHERE id = "'.$tsumeID.'"');
$chatArray = mysqli_fetch_array($getChat);
$chatHistory = explode("%%", $chatArray['chat']);


?>

<!DOCTYPE HTML>
<html onload ="drawBoard()">
<head>
    <meta charset="utf-8">
    <meta name="format-detection" content ="telephone=no">
    <title>Slo Tsumeshogi</title>
    <link href="CSS/all_pages.css" rel="stylesheet">
    <link href="CSS/tsume_style_sheet.css" rel="stylesheet">

</head>

<body>
<div id = "wholeBoard">
<div id ="boardColor">
<a href = "slo_tsumeshogi.php"id = "backButton">≪</a>


    <div id = "whiteMochigoma"></div>

    <div id = "board"></div>
    <div id = "blackMochigoma"></div>
 
</div>


<div id="menuBox" onclick = "showMenu()">
<img src="images/menu_button.png"  id = "menuButton">
</div>

<div class = "popupMenu" id="popupMenuId">
    <a href="#" id ="leaderBoard" onClick = "toggleLeaderBoard()">トップスコア・Top Scores</a>
    <a href="#" onClick = "toggleChat()">チャット表示・View Chat</a>
    <a href="feedback_form.php?src=gameboard&id=<?=$gameID?>">バグ報告・Report a bug</a>
</div>
<div class = "popupChat" id ="popupChat">
    <button onclick = "closeChat()" style = "font-size: 6vw; background-color: lightgrey">✕</button>
        <div id ="popupChatText">
        <textarea id = "popupChatInput" name = "textToSend"></textarea>
        <button onclick = "submitOnEnter('click')" id ="chatSend">SEND</button>
        </div>
</div>

<div class = "popupChat" id ="scoreBoard">
<button onclick = "closeLeaderBoard()" style = "font-size: 6vw; background-color: lightgrey">✕</button>
<div id ="scoreBoardText">
</div>
</div>

<div id = "waitingMsg">
    <h2>AI検討中</h2>
</div>
<div id = "promptBox">
 <h3 id = "playerPrompt"></h3> 
</div>


<h1 id = "timer"></h1>
 <div id = "skipButtons">
 <button class = "skipButton" id = "fullBack" onClick = "resetTsume(<?=$timeLimit?>)">リセット</button>

 <a href = "tsume.php?id=<?=$tsumeID + 1?>"><button class = "skipButton" id = "fullForward">≫</button></a>
 
</div>

</body>
 
 <script>
     let playerName = "<?=$_COOKIE['current_user_cookie']?>";
 var currentGameID = <?=$tsumeID?>;
   let setup = "<?=$setup?>";
   var gameState = setup.split(",");
   let mgArray = "<?=$mgSetup?>";
   var mochiGomaArray = mgArray.split(",");
   var phpColor = "<?=$_COOKIE['current_user_cookie']?>";
   let sequence = "<?=$sequence?>";
   var mainSequence = sequence.split(",");
   var problemName = "<?=$problemName?>";
   //check below!
   var originalTimeLimit = "<?php if(isset($_COOKIE[$tsumeID.'timeLimit'])){echo $_COOKIE[$tsumeID.'timeLimit'];}else {echo $timeLimit;}?>";
    var timeLimit = originalTimeLimit;
    
    document.getElementById("scoreBoardText").innerHTML = "<?=$leaderBoard?>";
    //set the timerand update it
    let minutes = timeLimit / 60;
    let seconds = timeLimit % 60;
    var timerSet = setInterval(function(){updateTimer();}, 1000);
    function updateTimer(){
            if(timeLimit > -1){
            minutes = parseInt(timeLimit / 60);
            seconds = (timeLimit % 60);
            if(seconds < 10){
                seconds = "0" + seconds; //add a zero in the tens place if a single digit
            }
            document.getElementById("timer").innerHTML = minutes +":"+seconds;
            timeLimit --;
        }else{
            clearInterval(timerSet);
            timeUp();
        }
    }

    //record the current remaining time whenever the page is navigated away from
    document.addEventListener("visibilitychange", function logTime() {
  if (document.visibilityState === 'hidden') {
    let $data = JSON.stringify({id: currentGameID, seconds: timeLimit});
        navigator.sendBeacon("/sloshogi/tsume_time.php", $data);
  }
});

    function timeUp(){
        setMessage("時間です！Time's Up! Try Again");
        disableAll();
    }
   function showMenu(){
    document.getElementById("popupMenuId").classList.toggle("menuShow");
    document.getElementById("menuBox").classList.toggle("turnRed");
   }



   //write the chat contents to the hidden chat screen
   let chatHistory = <?=json_encode($chatHistory)?>;
   let chatContents = [];
   let counter = 1;
   for(i=0; i<chatHistory.length; i+=2){
       chatContents[i] = document.createElement("H3");
       chatContents[i].setAttribute("class", "chatNameHeader");
       chatContents[i].innerHTML = chatHistory[i];
       chatContents[i+1] = document.createElement("p");
       chatContents[i+1].innerHTML = chatHistory[i+1];
       //console.log(chatContents[i+1].scrollHeight);
      // chatContents[i+1].style.height = chatContents[i+1].scrollHeight +"px";
       if(chatHistory[i] == playerName){
           //if it is the user's message
            chatContents[i].style = "text-align: right";
            chatContents[i+1].style = "text-align: right";
            //make the messages appear on the right side of the chat box
            chatContents[i+1].setAttribute("class", "chatEntryRight");
       }else{
        chatContents[i+1].setAttribute("class", "chatEntryLeft");

       }
       document.getElementById("popupChatText").appendChild(chatContents[i]);
       document.getElementById("popupChatText").appendChild(chatContents[i+1]);
   }
   document.getElementById("popupChatInput").addEventListener("keypress", submitOnEnter);

   
   function submitOnEnter(event){ //https://stackoverflow.com/questions/8934088/how-to-make-enter-key-in-a-textarea-submit-a-form
    if((event.which === 13  && !event.shiftKey) || event == "click"){//if the enter key was pushed
        msgSent = true;
        var ajax = new XMLHttpRequest();
        let msgToSend =  JSON.stringify({"textToSend": document.getElementById("popupChatInput").value,
             "gameId": <?=$tsumeID?> });
        console.log(msgToSend);
    ajax.onreadystatechange = function()
  {
    // If ajax.readyState is 4, then the connection was successful
    // If ajax.status (the HTTP return code) is 200, the request was successful
    if(ajax.readyState == 4 && ajax.status == 200)
    {
      // Use ajax.responseText to get the raw response from the server
           //window.location.reload();//this doesn't work... need to update the chat box without reloading the page instead
           //closeChat();
    }else {
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
    userName.innerHTML = "<?=$_COOKIE['current_user_cookie']?>";

    let newMsg = document.createElement("p");
    newMsg.setAttribute("class", "chatEntryRight");
    newMsg.innerHTML = document.getElementById("popupChatInput").value;
    console.log(newMsg);
    document.getElementById("popupChatText").appendChild(userName);
    document.getElementById("popupChatText").appendChild(newMsg);

    document.getElementById("popupChatInput").value = "";//empty the text input box
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
   function toggleLeaderBoard(){
    document.getElementById("scoreBoard").classList.toggle("chatShow");
   }

   function closeChat(){
    document.getElementById("popupChat").classList.toggle("chatShow");
   }
   function closeLeaderBoard(){
    document.getElementById("scoreBoard").classList.toggle("chatShow");

   }
    </script>
    <script src= "scripts/tsume_shared.js"></script>
<script src= "scripts/tsume_script.js"></script>
<script src= "scripts/tsume_ai.js"></script>


