let sentChallengesGameLink = [];
let recievedChallengesGameLink = [];


//sent challenges (status 1)
for(i = 0; i < sentChallengesArray.length; i ++){
sentChallengesGameLink[i] = document.createElement("a");
sentChallengesGameLink[i].href = "#";
sentChallengesGameLink[i].innerHTML = "To " + sentChallengesOpponentArray[i];
    document.getElementById("sentChallenges").appendChild(sentChallengesGameLink[i]);
    let lineBreak = document.createElement("br");
    document.getElementById("sentChallenges").appendChild(lineBreak);
}

//recieved challenges (status 1)
for(i = 0; i < recievedChallengesArray.length; i ++){
    recievedChallengesGameLink[i] = document.createElement("a");
    recievedChallengesGameLink[i].href = "view_challenge.php?id=" + recievedChallengesArray[i];
    recievedChallengesGameLink[i].innerHTML = "From " + recievedChallengesOpponentArray[i];
        document.getElementById("recievedChallenges").appendChild(recievedChallengesGameLink[i]);
        let lineBreak = document.createElement("br");
        document.getElementById("recievedChallenges").appendChild(lineBreak);
    }
    