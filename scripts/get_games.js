//create a new href for each active game
let gameLink = [];

for(i = 0; i < gameIdArray.length; i ++){
    gameLink[i] = document.createElement("a");
    gameLink[i].href = "gameboard.php?id=" + gameIdArray[i];
    gameLink[i].innerHTML = "Game " +gameIdArray[i];
    document.getElementById("allGames").appendChild(gameLink[i]);
    let lineBreak = document.createElement("br");
    document.getElementById("allGames").appendChild(lineBreak);
}