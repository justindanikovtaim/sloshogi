//create a new href for each active game
let currentGameLink = [];
let pastGameLink = [];
let challengesGameLink = [];

//active games (status 2)
let a = 0;
for(i = 0; i < currentGameIdArray.length; i ++){
    currentGameLink[i] = document.createElement("a");
    if(currentGameOpponentArray[a+1] == 1){
        //currentGameLink[i].setAttribute("id", "notTurn");//make the link to any game where it isn't the users turn grey
        currentGameLink[i].style.color = "grey";
        currentGameLink[i].innerHTML = "SLO" + currentGameIdArray[i] + " | " + currentGameOpponentArray[a] + " to play"; 
    }else{
        currentGameLink[i].innerHTML = "SLO" + currentGameIdArray[i] +" vs. " + currentGameOpponentArray[a] + "| Your turn"; 
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

//finished games (status 3)
for(i = 0; i < pastGameIdArray.length; i ++){
    pastGameLink[i] = document.createElement("a");
    pastGameLink[i].setAttribute("class", "gameURL");
    pastGameLink[i].href = "gameboard.php?id=" + pastGameIdArray[i];
    pastGameLink[i].innerHTML = "SLO" + pastGameIdArray[i]+" vs. " + pastGameOpponentArray[i];
    document.getElementById("finishedGames").appendChild(pastGameLink[i]);
    let lineBreak = document.createElement("br");
    document.getElementById("finishedGames").appendChild(lineBreak);
}