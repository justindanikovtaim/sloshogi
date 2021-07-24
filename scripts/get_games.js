//create a new href for each active game
let currentGameLink = [];
let pastGameLink = [];

for(i = 0; i < currentGameIdArray.length; i ++){
    currentGameLink[i] = document.createElement("a");
    currentGameLink[i].href = "gameboard.php?id=" + currentGameIdArray[i];
    currentGameLink[i].innerHTML = "SLO" + currentGameIdArray[i] +" vs. " + currentGameOpponentArray[i];
    document.getElementById("allGames").appendChild(currentGameLink[i]);
    let lineBreak = document.createElement("br");
    document.getElementById("allGames").appendChild(lineBreak);
}

for(i = 0; i < pastGameIdArray.length; i ++){
    pastGameLink[i] = document.createElement("a");
    pastGameLink[i].href = "gameboard.php?id=" + pastGameIdArray[i];
    pastGameLink[i].innerHTML = "SLO" + pastGameIdArray[i]+" vs. " + pastGameOpponentArray[i];
    document.getElementById("finishedGames").appendChild(pastGameLink[i]);
    let lineBreak = document.createElement("br");
    document.getElementById("finishedGames").appendChild(lineBreak);
}