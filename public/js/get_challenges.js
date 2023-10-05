let sentChallengesGameLink = [];
let recievedChallengesGameLink = [];

//sent challenges (status 1)
for (i = 0; i < sentChallengesArray.length; i++) {
    sentChallengesGameLink[i] = document.createElement('a');
    sentChallengesGameLink[i].href = '#';
    sentChallengesGameLink[i].innerHTML =
        'To ' + sentChallengesOpponentArray[i];
    document
        .getElementById('sentChallenges')
        .appendChild(sentChallengesGameLink[i]);
    let lineBreak = document.createElement('br');
    document.getElementById('sentChallenges').appendChild(lineBreak);
}

function deleteChallenge() {}
