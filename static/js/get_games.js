//create a new href for each active game
let currentGameLink = [];
let challengesGameLink = [];

//active games (status 2)
let a = 0;
let sendNotification = false;
for(i = 0; i < currentGameIdArray.length; i ++){
    currentGameLink[i] = document.createElement("a");
    if(currentGameOpponentArray[a+1] == 1){
        currentGameLink[i].style.color = "grey";
        currentGameLink[i].innerHTML = "SLO" + currentGameIdArray[i] + " | " + currentGameOpponentArray[a] + " to play"; 
    }else{
        currentGameLink[i].innerHTML = "SLO" + currentGameIdArray[i] +" vs. " + currentGameOpponentArray[a] + " | Your turn"; 
        sendNotification = true;
    }
    currentGameLink[i].setAttribute("class", "gameURL");
    currentGameLink[i].href = "gameboard.php?id=" + currentGameIdArray[i];
    //a since every other array place is filled with 0 or 1 to show if it's the player's turn
    a+=2; //add two to a
    document.getElementById("allGames").appendChild(currentGameLink[i]);
    let lineBreak = document.createElement("br");
    document.getElementById("allGames").appendChild(lineBreak);
}

//challenges (status 1)
for(i = 0; i < newChallengesArray.length; i ++){
    challengesGameLink[i] = document.createElement("a");
    challengesGameLink[i].setAttribute("class", "gameURL");
    challengesGameLink[i].href = "view_challenge.php?id=" + newChallengesArray[i];
    challengesGameLink[i].innerHTML = "From " + challengesOpponentArray[i];
    document.getElementById("newChallenges").appendChild(challengesGameLink[i]);
    let lineBreak = document.createElement("br");
    document.getElementById("newChallenges").appendChild(lineBreak);
}

if(Notification.permission == 'granted'){//only run this if the user has granted permission

if(sendNotification){
    //send a notification that it's the players turn
    var img = 'images/BGYOKU.png';
    var text = 'Your move in SLO Shogi';
    var notification = new Notification('SLO Shogi', { body: text, icon: img });
}else{
    //otherwise, keep refreshing the page every 30 seconds
    setTimeout("location.reload();",5000); 
}
}
