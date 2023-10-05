//create a new href for each active game
let pastGameLink = [];

//finished games (status 3)
for (i = 0; i < pastGameIdArray.length; i++) {
    pastGameLink[i] = document.createElement('a');
    pastGameLink[i].setAttribute('class', 'gameURL');
    pastGameLink[i].href = '/gameboard?id=' + pastGameIdArray[i];
    pastGameLink[i].innerHTML =
        'SLO' + pastGameIdArray[i] + ' vs. ' + pastGameOpponentArray[i];
    document.getElementById('finishedGames').appendChild(pastGameLink[i]);
    let lineBreak = document.createElement('br');
    document.getElementById('finishedGames').appendChild(lineBreak);
}
