//Slow Shogi by Christopher DeLong
//copyright 2021
//This is an image-based shogi program
let selectedPiece = null; //the currently highlighted piece 
let possibleMoves = []; //the possible moves for the currently selected piece
let turn = 1; //odd turns = black, even turns = white
let realTurn;
let viewTurn; //variable for keeping track of the turn being viewed with the forward and back buttons
let forward; //used for storing the forward direction of a piece
let board1Row = [0, 9, 18, 27, 36, 45, 54, 63, 72]; //all of the squares that are on the right edge
let board2Row = [1, 10, 19, 28, 37, 46, 55, 64, 73];
let board3Row = [2, 11, 20, 29, 38, 47, 56, 65, 74];
let board4Row = [3, 12, 21, 30, 39, 48, 57, 66, 75];
let board5Row = [4, 13, 22, 31, 40, 49, 58, 67, 76];
let board6Row = [5, 14, 23, 32, 41, 50, 59, 68, 77];
let board7Row = [6, 15, 24, 33, 42, 51, 60, 69, 78];
let board8Row = [7, 16, 25, 34, 43, 52, 61, 70, 79];
let board9Row = [8, 17, 26, 35, 44, 53, 62, 71, 80]; //all of the squares that are on the left edge
let allBoardRows = [board1Row, board2Row, board3Row, board4Row, board5Row, board6Row, board7Row, board8Row, board9Row];
let boardTopEdge = [0, 1, 2, 3, 4, 5, 6, 7, 8];
let boardBottomEdge = [72, 73, 74, 75, 76, 77, 78, 79, 80];
let mochiGomaArray = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; //array for black mochi goma 
//(Wfu, WKo, Wkei, Wgin, Wkin, Wkaku, Whi, Bfu, BKo, Bkei, Bgin, Bkin, Bkaku, Bhi)
let mochiGomaAlreadySelected = false;
let isCheck = null; //keep track of if it is check or not
let checkingPieces = [];
let move = [];
let boardSquare = [];
let rowCounter = 13.5;
let columnCounter = 5;
let sC = 0; //square counter
let sendToDatabase; //an object used to pass JSON data of the move made to PHP
let reservationArray = gameHistory[3].split(";"); //get the reserved moves and put each sequence into its own space in an array
console.log(reservationArray); //REMOVE
//initialize gameboard
let playerColor;
let opponentColor;
if(gameHistory[1] == phpColor){//blackplayer is stored in gameHistory[1]
    playerColor = "B";
    opponentColor = "W";
}else{
    playerColor = "W";
    opponentColor = "B";
}
let usersTurn;//defined after gamestate is loaded
let flipped;   
   if(playerColor== "W"){
       flipped = true;
   }else{
       flipped = false;
   }
let movesHistory;
if(gameHistory[0] != ""){
     movesHistory = gameHistory[0].split(","); //break the moves into an array 
}


for (i = 0; i < 9; i++) {
    for (x = 0; x < 9; x++) {
        boardSquare[sC] = document.createElement("img"); //create each of the 81 squares as an image in the document
        boardSquare[sC].src = "images/empty.png"; //temporarily set image source 
        boardSquare[sC].style.width = "10vw"; //scale to fit board
        boardSquare[sC].style.position = "absolute";
        boardSquare[sC].style.right = columnCounter + "vw"; //set the distance from the right side of the board
        boardSquare[sC].style.top = rowCounter + "vw"; //set the distance from the top
        boardSquare[sC].setAttribute("id", sC);
        boardSquare[sC].setAttribute("onclick", "pieceClick(Number(this.id))"); //run the piececlick funtion when clicked
        document.getElementById("board").appendChild(boardSquare[sC]); //add the image to the screen

        columnCounter += 10; //add space between the right side for the next piece
        sC++; //move to the next square
    }
    rowCounter += 10; //add space between the top for the next row
    columnCounter = 5; // start back at the right side of the board
    spacer = 0; //reset the spacer for the first piece in the row
}
mochiGoma = [];
mochiGomaAmmount = [];
spacer = 75;

mochiGomaOrder = ["MWF", "MWKO", "MWKEI", "MWGIN", "MWKIN", "MWKAKU", "MWHI",
    "MBF", "MBKO", "MBKEI", "MBGIN", "MBKIN", "MBKAKU", "MBHI"];
if(playerColor == "B"){
     
    for (jupiter = 0; jupiter < 2; jupiter++) { // initialize the mochigoma on the board
        for (x = 0; x < 7; x++) {
            if (jupiter === 0) { //if it's the first time through, we are drawing the white mochigoma
                mochiGoma[x] = document.createElement("img");//create a new img element for each mochigoma type
                mochiGoma[x].src = "images/" + mochiGomaOrder[x] + ".png";
                mochiGoma[x].setAttribute("id", mochiGomaOrder[x]);
                mochiGoma[x].setAttribute("onClick", "placePiece(this.id)");
                mochiGoma[x].style.width = "9vw";
                mochiGoma[x].style.position = "absolute";
                mochiGoma[x].style.right = spacer + "vw";
                mochiGoma[x].style.top = "3vw";
                document.getElementById("blackMochigoma").appendChild(mochiGoma[x]);
                mochiGomaAmmount[x] = document.createElement("img");
                mochiGomaAmmount[x].src = "images/mochiGomaNum2.png";
                mochiGomaAmmount[x].style.width = "3vw";
                mochiGomaAmmount[x].style.position = "absolute";
                mochiGomaAmmount[x].style.right = spacer + "vw"; //offset it from the piece
                mochiGomaAmmount[x].style.top = "3vw";
                document.getElementById("blackMochigoma").appendChild(mochiGomaAmmount[x]);
            } else {//otherwise it's the second time through, so we are drawing the black mochigoma
                mochiGoma[x + 7] = document.createElement("img");//create a new img element for each mochigoma type
                mochiGoma[x + 7].src = "images/" + mochiGomaOrder[x + 7] + ".png";
                mochiGoma[x + 7].setAttribute("id", mochiGomaOrder[x + 7]);
                mochiGoma[x + 7].setAttribute("onClick", "placePiece(this.id)");
                mochiGoma[x + 7].style.width = "9vw";
                mochiGoma[x + 7].style.position = "absolute";
                mochiGoma[x + 7].style.right = spacer + "vw";
                mochiGoma[x + 7].style.top = "105vw";
                document.getElementById("whiteMochigoma").appendChild(mochiGoma[x + 7]);
                mochiGomaAmmount[x + 7] = document.createElement("img");
                mochiGomaAmmount[x + 7].src = "images/mochiGomaNum2.png";
                mochiGomaAmmount[x + 7].style.width = "3vw";
                mochiGomaAmmount[x + 7].style.position = "absolute";
                mochiGomaAmmount[x + 7].style.right = spacer + "vw"; //offset it from the piece
                mochiGomaAmmount[x + 7].style.top = "105vw";
                document.getElementById("blackMochigoma").appendChild(mochiGomaAmmount[x + 7]);
            }
            spacer -= 10;
        }
        spacer = 75;
    }
}else{

    for (jupiter = 0; jupiter < 2; jupiter++) { // initialize the mochigoma on the board
        for (x = 0; x < 7; x++) {
            if (jupiter === 0) { //if it's the first time through, we are drawing the white mochigoma
                mochiGoma[x] = document.createElement("img");//create a new img element for each mochigoma type
                mochiGoma[x].src = "images/" + mochiGomaOrder[x + 7] + ".png";
                mochiGoma[x].setAttribute("id", mochiGomaOrder[x]);
                mochiGoma[x].setAttribute("onClick", "placePiece(this.id)");
                mochiGoma[x].style.width = "9vw";
                mochiGoma[x].style.position = "absolute";
                mochiGoma[x].style.right = spacer + "vw";
                mochiGoma[x].style.top = "105vw"; //draw them at the bottom since the white player is playing
                document.getElementById("blackMochigoma").appendChild(mochiGoma[x]);
                mochiGomaAmmount[x] = document.createElement("img");
                mochiGomaAmmount[x].src = "images/mochiGomaNum2.png";
                mochiGomaAmmount[x].style.width = "3vw";
                mochiGomaAmmount[x].style.position = "absolute";
                mochiGomaAmmount[x].style.right = spacer + "vw"; //offset it from the piece
                mochiGomaAmmount[x].style.top = "105vw";
                document.getElementById("blackMochigoma").appendChild(mochiGomaAmmount[x]);
            } else {//otherwise it's the second time through, so we are drawing the black mochigoma
                mochiGoma[x + 7] = document.createElement("img");//create a new img element for each mochigoma type
                mochiGoma[x + 7].src = "images/" + mochiGomaOrder[x] + ".png";
                mochiGoma[x + 7].setAttribute("id", mochiGomaOrder[x + 7]);
                mochiGoma[x + 7].setAttribute("onClick", "placePiece(this.id)");
                mochiGoma[x + 7].style.width = "9vw";
                mochiGoma[x + 7].style.position = "absolute";
                mochiGoma[x + 7].style.right = spacer + "vw";
                mochiGoma[x + 7].style.top = "3vw";//draw them at the top since the white player is playing
                document.getElementById("whiteMochigoma").appendChild(mochiGoma[x + 7]);
                mochiGomaAmmount[x + 7] = document.createElement("img");
                mochiGomaAmmount[x + 7].src = "images/mochiGomaNum2.png";
                mochiGomaAmmount[x + 7].style.width = "3vw";
                mochiGomaAmmount[x + 7].style.position = "absolute";
                mochiGomaAmmount[x + 7].style.right = spacer + "vw"; //offset it from the piece
                mochiGomaAmmount[x + 7].style.top = "3vw";
                document.getElementById("blackMochigoma").appendChild(mochiGomaAmmount[x + 7]);
            }
            spacer -= 10;
        }
        spacer = 75;
    }
}
let gameState = [];

function resetGameState(){
    gameState = ["WKO", "WKEI", "WGIN", "WKIN", "WGYOKU", "WKIN", "WGIN", "WKEI", "WKO",
    "empty", "WKAKU", "empty", "empty", "empty", "empty", "empty", "WHI", "empty",
    "WF", "WF", "WF", "WF", "WF", "WF", "WF", "WF", "WF",
    "empty", "empty", "empty", "empty", "empty", "empty", "empty", "empty", "empty",
    "empty", "empty", "empty", "empty", "empty", "empty", "empty", "empty", "empty",
    "empty", "empty", "empty", "empty", "empty", "empty", "empty", "empty", "empty",
    "BF", "BF", "BF", "BF", "BF", "BF", "BF", "BF", "BF",
    "empty", "BHI", "empty", "empty", "empty", "empty", "empty", "BKAKU", "empty",
    "BKO", "BKEI", "BGIN", "BKIN", "BGYOKU", "BKIN", "BGIN", "BKEI", "BKO", "empty"];
// set each square in initial gamestate plus one extra at the end for mochigoma placements
 mochiGomaArray = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
    turn = 1;
}

resetGameState();

let tempGameState = [];
loadGameState(1);
realTurn = turn; //needed to make sure that the user can't play when looking at a previous game state
drawBoard();
drawMochigoma();
document.getElementById("toReservation").style.visibility = "hidden";
if(!usersTurn || gameHistory[4] == "3"){//if not the user's turn or the game has ended
    disableAll();
    document.getElementById("toReservation").style.visibility = "visible";

}
let backArrow = document.createElement("a");
backArrow.id = "backButton";
backArrow.href = "user_page.php";
backArrow.innerHTML="≪";
document.getElementById("backArrow").appendChild(backArrow);

let opIcon = document.createElement("img");
opIcon.src = "images/icons/" + opIconName + "_icon.png";
opIcon.id = "opIcon";
document.getElementById("opInfo").appendChild(opIcon);

let opName = document.createElement("h4");
opName.innerHTML = OpNameRating;
opName.id = "opName";
document.getElementById("opInfo").appendChild(opName);


function loadGameState(placeCalled){//loads the current game state from the database (slo Shogi v.1)
    if(movesHistory != undefined){

    for(g = 0; g < movesHistory.length; g+= 3){
        if(movesHistory[g] == "81"){
            //if the piece is a mochigoma
                let mochigomaPlace = mochiGomaOrder.indexOf("M" + movesHistory[g+2]); //find the place where it is
                mochiGomaArray[mochigomaPlace]--; //remove a piece from the array
        
            gameState[movesHistory[g+1]] = movesHistory[g+2]; //move the piece to the new square
        }else{
            //otherwise, if it's a piece on the board
            if (gameState[movesHistory[g+1]].charAt(0) !== "e") { //if capturing a piece
                addToMochiGoma(gameState[movesHistory[g+1]]);//add it to the proper place in mochigoma array
            }
                gameState[movesHistory[g+1]] = movesHistory[g+2]; //move the piece to the new square
                gameState[movesHistory[g]] = "empty"; //make the space where the piece moved from empty
        }
        turn++;
    }
    let redSquare1;
    let redSquare2;
    if(playerColor == "W"){ //flip the move indicator position if the white player is viewing
        redSquare1 = 80 - movesHistory[movesHistory.length -3]; 
       redSquare2 = 80 - movesHistory[movesHistory.length-2];
    }else{
        redSquare1 = movesHistory[movesHistory.length -3];
        redSquare2 =  movesHistory[movesHistory.length-2];
    }

    boardSquare[redSquare2].style.background = "red";
    if(movesHistory[movesHistory.length -3] != 81){
        boardSquare[redSquare1].style.background = "red";
    }

}
if((turn %2 != 0 && playerColor == "B") || (turn % 2 == 0 && playerColor == "W")){
    //set whether or not it is the user's turn
    usersTurn = true;
}else{
    usersTurn = false;
}

if(playerColor == "W" && placeCalled == 1){
    //If it is the white player, flip the borard around so they're not playing upside down
    let flipGamestate = [];
    for(f = 0; f <81; f++){
        flipGamestate[f] = gameState[80 - f];
    }
    gameState = flipGamestate; //put the flipped gamestate into gameState
}

if(gameHistory[5] != null){//if a winner has been set
    document.getElementById("playerPrompt").innerHTML = gameHistory[5] + " が勝ちました";

}else if(turn % 2 == 0){    //update the prompt showing which player's turn it is
//White's turn
    document.getElementById("playerPrompt").innerHTML = gameHistory[2] + " to play";

}
else{
    //black's turn
    document.getElementById("playerPrompt").innerHTML = gameHistory[1] + " to play";
}
viewTurn = turn - 1; // viewing the current game state
document.getElementById("undo").style.visibility = "hidden";
}

function sendMoveData(){
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function()
  {
    // If ajax.readyState is 4, then the connection was successful
    // If ajax.status (the HTTP return code) is 200, the request was successful
    if(ajax.readyState == 4 && ajax.status == 200)
    {
      // Use ajax.responseText to get the raw response from the server
      console.log(ajax.responseText);
    }else {
        console.log('Error: ' + ajax.status); // An error occurred during the request.
    }
  }
    ajax.open("POST", 'send.php', true); //asyncronous
    ajax.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
    ajax.send(sendToDatabase);//(sendToDatabase);
}

function resetGame(){
    let confirmreset = confirm("本当にリセットする？");
    if(confirmreset){

    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function()
  {
    // If ajax.readyState is 4, then the connection was successful
    // If ajax.status (the HTTP return code) is 200, the request was successful
    if(ajax.readyState == 4 && ajax.status == 200)
    {
      // Use ajax.responseText to get the raw response from the server
      console.log(ajax.responseText);
    }else {
        console.log('Error: ' + ajax.status); // An error occurred during the request.
    }
  }
  let json = JSON.stringify({
    id: currentGameID,
  });
console.log(json);
    ajax.open("POST", 'reset.php', true); //asyncronous
    ajax.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
    ajax.send(json);//(sendToDatabase);
}
loadGameState(1);
}
function resign(){
    let confirmresign = confirm("本当に投了しますか？");
    if(confirmresign){

    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function()
  {
    // If ajax.readyState is 4, then the connection was successful
    // If ajax.status (the HTTP return code) is 200, the request was successful
    if(ajax.readyState == 4 && ajax.status == 200)
    {
      // Use ajax.responseText to get the raw response from the server
      console.log(ajax.responseText);
      window.location.reload();
    }else {
        console.log('Error: ' + ajax.status); // An error occurred during the request.
    }
  }
  let winnerName;
  let loserName;
  //set the winner and loser by finding the usernames of the players from the gamehistory array
  if(playerColor == "B"){
      winnerName = gameHistory[2];
      loserName = gameHistory[1];
  }else{
      winnerName = gameHistory[1];
      loserName = gameHistory[2];
  }

  let json = JSON.stringify({
    id: currentGameID, winner: winnerName, loser: loserName
  });

console.log(json);
    ajax.open("POST", 'resign.php', true); //asyncronous
    ajax.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
    ajax.send(json);//(sendToDatabase);
}
}
//Starting formation
function drawBoard() {
    for (i = 0; i < 81; i++) {
        if(playerColor == "W"){
            if(gameState[i].charAt(0) == "B"){
                //switch the B with a W for display purposes
                boardSquare[i].src = "images/W" + gameState[i].substr(1, gameState[i].length) + ".png";
            }else if(gameState[i].charAt(0) == "W"){
                //switch the W with B for display purposes
                boardSquare[i].src = "images/B" + gameState[i].substr(1, gameState[i].length) + ".png";
            }else{
                boardSquare[i].src = "images/" + gameState[i] + ".png"; //empty square
            }
        }else{

        boardSquare[i].src = "images/" + gameState[i] + ".png"; //set each of the urls to match the image
        }
    }
}

function addToMochiGoma(gamePiece) {
    let gamePieceColor;
    if (gamePiece.charAt(1) === "N") {//if it's a promoted piece
        gamePiece = gamePiece.replace("N", ""); //remove the N
    }
    if (gamePiece.charAt(0) === "B") {
        gamePieceColor = 0;
    } else {
        gamePieceColor = 7; //if it's a white piece, start at the 7th array spot
    }
    switch (gamePiece.substr(1, gamePiece.length)) { // return the piece name minus thethe color 

        case "F":
            mochiGomaArray[0 + gamePieceColor] += 1; //add a fu to the fu place
            break;

        case "KO":
            mochiGomaArray[1 + gamePieceColor] += 1;//add a ko to the ko place
            break;

        case "KEI":
            mochiGomaArray[2 + gamePieceColor] += 1;//add a kei to the kei place
            break;

        case "GIN":
            mochiGomaArray[3 + gamePieceColor] += 1;//add a gin to the gin place
            break;

        case "KIN":
            mochiGomaArray[4 + gamePieceColor] += 1;//add a kin to the kin place
            break;

        case "KAKU":
            mochiGomaArray[5 + gamePieceColor] += 1;//add a kaku to the kaku place
            break;

        case "HI":
            mochiGomaArray[6 + gamePieceColor] += 1;//add a hi to the hi place
            break;
        default:
            console.log("piece name is incorrect");
            break;

    }
}

function drawMochigoma() {
    let blackOrWhite = 0;
    for (i = 0; i < 8; i += 7) {
        for (x = 0; x < 7; x++) {
            if (mochiGomaArray[x + i] > 1) {//if there is/are mochigoma of that type
                mochiGoma[x + i].style.visibility = "visible";
                mochiGomaAmmount[x + i].style.visibility = "visible";
                mochiGomaAmmount[x + i].src = ("images/mochiGomaNum" + mochiGomaArray[x + i] + ".png"); //make it display the correct number 
            } else if (mochiGomaArray[x + i] === 1) {
                mochiGoma[x + i].style.visibility = "visible"; //show the piece
                mochiGomaAmmount[x + i].style.visibility = "hidden";//but no number
            } else {
                mochiGoma[x + i].style.visibility = "hidden"; //otherwise hide it from view
                mochiGomaAmmount[x + i].style.visibility = "hidden";//and hide the number
            }
        }
        blackOrWhite = 1;
    }
}
function pieceClick(id) {
    
    //first, make sure that the piece cicked is your own
   if(!usersTurn && !justChecking){
       deselectAll();
   } else if ((((turn % 2 == 0) && gameState[id].charAt(0) != "W") || ((turn % 2 !== 0) && gameState[id].charAt(0) != "B")) &&
        justChecking === false && boardSquare[id].style.background.substr(0,7) != "rgb(230"){
        deselectAll();
        //do nothing
    } else {



        if (justChecking === false) {
            console.log(id);
        }
        if (boardSquare[id].style.background.substr(0,7) == "rgb(230") { 
            //if the clicked square is highlighted as a possible move
            movePiece(id);

        } else if (!justChecking && selectedPiece !== null && (id === selectedPiece ||
            id !== selectedPiece && boardSquare[id].style.background.substr(0,7) != "rgb(230")) {
            //if the same piece is clicked again or another unrelated place is clicked
            deselectAll();
            selectedPiece = null;

        } else { //otherwise, highlight the possible moves
            
            let komaColor;
            if ((turn % 2 == 0 && playerColor == "B") || (turn % 2 != 0 && playerColor == "W")){
                komaColor = "W";
               if(playerColor== "W"){
                   flipped = true;
               }else{
                   flipped = false;
               }
            } else{
                komaColor = "B";
            }
            if (justChecking === false) {
                selectedPiece = id; // define the selected piece
                boardSquare[id].style.filter = "brightness(1.5)"; //highlight the selected piece only if not checking for checkmate
            }

            if ((turn % 2 != 0 && playerColor == "B") 
                || turn % 2 == 0 && playerColor == "W") { //if it's the player's turn and it's their turn
                forward = -1; //forward direction is negative
            } else {
                forward = 1; //otherwise it's white, so forward is positive
            }

            switch (gameState[id].substr(1, 5)) { //take the string minus the first letter (eg. the B or W)
                case "F": //if the clicked piece is a black fu
                    showMoveF(id, komaColor); //0 for black
                    break;

                case "HI":
                    showMoveHI(id, komaColor);
                    break;

                case "KAKU":
                    showMoveKAKU(id, komaColor);
                    break;

                case "KO":
                    showMoveKO(id, komaColor);
                    break;

                case "KEI":
                    showMoveKEI(id, komaColor);
                    break;

                case "GIN":
                    showMoveGIN(id, komaColor);
                    break;


                case "KIN": //all of the pieces that have the same movement as Kin
                case "NGIN":
                case "NKEI":
                case "NKO":
                case "NF":
                    showMoveKIN(id, komaColor);
                    break;
                case "NHI":
                    showMoveNHI(id, komaColor);
                    break;
                case "NKAKU":
                    showMoveNKAKU(id, komaColor);
                    break;
                case "GYOKU":
                    showMoveGYOKU(id, komaColor);
                    break;

                default:
                    selectedPiece = null; //if an empty space was clicked, set selectedPiece to be null 
                    break;
            }

        }
    }
}
function showMoveF(square, color) { 
    if(flipped){
        if(color == "B"){
            color = "W";
        }else{
            color = "B";
        }
    }
    if (gameState[square + (forward * 9)].charAt(0) !== color) {
        move[0] = square + (forward * 9); //set the only possible move
    }

    eliminateIllegalMoves(color); //will remove all moves from move array that would result in check to own gyoku
    if (justChecking === false) {
        for (i = move.length - 1; i > -1; i--) {
            if (move[i] !== null) {
                boardSquare[move[i]].style.background = "rgb(230, 197, 11)";//highlight each possible square to move into
            }
        }
    }
}

function showMoveKEI(square, color) {


    if ((board9Row.includes(square) && color === "B") ||// if kei is black and on on the left edge
        board1Row.includes(square) && color === "W") { //or white and on the right edge
        move = [square + (forward * 19)];
    } else if ((board9Row.includes(square) && color === "W") || //if kei is white and on the left edge
        board1Row.includes(square) && color === "B") {// or black and on the right edge
        move = [square + (forward * 17)]; // if kei is on the left edge, it can only move to one place
    } else {
        move = [square + (forward * 19), square + (forward * 17)]; //otherwise, it can move to either space
    }
    if(flipped){
        if(color == "B"){
            color = "W";
        }else{
            color = "B";
        }
    }

    eliminateIllegalMoves(color); //will remove all moves from move array that would result in check to own gyoku

    if (justChecking === false) {
        for (i = move.length - 1; i > -1; i--) {
            if (move[i] !== null) {
                if ((gameState[move[i]].charAt(0) !== color)) {  //check the first character to see if it the opposite color or empty

                    boardSquare[move[i]].style.background = "rgb(230, 197, 11)";//highlight each possible square to move into
                }
            }
        }
    }

}

function showMoveKO(square, color) {

    let pieceBlocking = false;
    let advanceRow = 0;
    if(flipped){
        if(color == "B"){
            color = "W";
        }else{
            color = "B";
        }
    }
    while (pieceBlocking === false) {
        if ((square + (9 * forward) + advanceRow) < 81 &&
            (square + (9 * forward) + advanceRow) > -1) {//if square is on the board

            if (gameState[square + (9 * forward) + advanceRow].charAt(0) === color) {//if space to move into contains own piece
                pieceBlocking = true;
            } else if (gameState[square + (9 * forward) + advanceRow].charAt(0) === "e") { // if space to move into is empty
                move.push(square + (9 * forward) + advanceRow); //add space to possible moves
                advanceRow = advanceRow + (forward * 9); //move to the next space
            } else { //if space contains enemy piece
                move.push(square + (9 * forward) + advanceRow);//add space to possible moves
                pieceBlocking = true;
            }
        }
    }
    eliminateIllegalMoves(color); //will remove all moves from move array that would result in check to own gyoku

    if (justChecking === false) {
        for (i = move.length - 1; i > -1; i--) {
            if (move[i] !== null) {
                boardSquare[move[i]].style.background = "rgb(230, 197, 11)";//highlight each possible square to move into
            }
        }
    }
}

function showMoveKIN(square, color) {

    let onEdge = ""; //a string to hold a code showing which edges the piece is on
    let kinMoves;

    onEdge += color; //add the color as the first part of the string ######## changed

    if (board9Row.includes(square)) {//fill string with 1 for yes, 0 for no
        onEdge += "1";
    } else {
        onEdge += "0";
    }
    if (boardTopEdge.includes(square)) {
        onEdge += "1";
    } else {
        onEdge += "0";
    }
    if (board1Row.includes(square)) {
        onEdge += "1";
    } else {
        onEdge += "0";
    }
    if (boardBottomEdge.includes(square)) {
        onEdge += "1";
    } else {
        onEdge += "0";
    }

    switch (onEdge) { // set the potential spaces that the pice can move into based on its location
        case "B1000": //black on the left edge
        case "W0010"://white on right edge
            kinMoves = [square + (forward * 10), square + (forward * -9),
            square + (forward * 1), square + (forward * 9)];
            break;

        case "B1100": //black in top left corner
        case "W0011": //white in bottom right corner
            kinMoves = [square + (forward * 1), square + (forward * -9)];
            break;

        case "B0100"://Black on top row
        case "W0001": //white on bottom row
            kinMoves = [square + (forward * 1), square + (forward * -1), square + (forward * -9)];
            break;

        case "B0110": //black in top right corner
        case "W1001": //white in bottom left corner
            kinMoves = [square + (forward * -1), square + (forward * -9)];
            break;

        case "B0010"://black on right edge
        case "W1000"://white on left edge
            kinMoves = [square + (forward * 8), square + (forward * -9),
            square + (forward * -1), square + (forward * 9)];
            break;

        case "B0011"://black in bottom right corner
        case "W1100"://White in top left corner
            kinMoves = [square + (forward * 8),
            square + (forward * -1), square + (forward * 9)];
            break;

        case "B0001": //black on bottom row
        case "W0100"://white on top row
            kinMoves = [square + (forward * 1), square + (forward * 8), square + (forward * 10),
            square + (forward * -1), square + (forward * 9)];
            break;

        case "B1001": //black in bottom left corner
        case "W0110": //white in top right corner
            kinMoves = [square + (forward * 10), square + (forward * -9),
            square + (forward * -1), square + (forward * 9)];
            break;

        default:
            kinMoves = [square + (forward * 1), square + (forward * 8), square + (forward * -9), square + (forward * 10),
            square + (forward * -1), square + (forward * 9)];
            break;
    }
    if(flipped){
        if(color == "B"){
            color = "W";
        }else{
            color = "B";
        }
    }
    for (i = kinMoves.length - 1; i > -1; i--) {
        if (gameState[kinMoves[i]].charAt(0) === color) {//if own piece is in the square
            //do nothing
        } else {//if the square is empty or has enemy piece
            move.push(kinMoves[i]);//add the space to the array of possible moves
        }
    }

    eliminateIllegalMoves(color); //will remove all moves from move array that would result in check to own gyoku

    if (justChecking === false) {
        for (i = move.length - 1; i > -1; i--) {
            if (move[i] !== null) {
                if ((gameState[move[i]].charAt(0) !== color)) {  //check the first character to see if it the opposite color or empty

                    boardSquare[move[i]].style.background = "rgb(230, 197, 11)";//highlight each possible square to move into
                }
            }
        }
    }

}

function showMoveGIN(square, color) {
    let onEdge = ""; //a string to hold a code showing which edges the piece is on
    let ginMoves;

    onEdge += color; //add the color as the first part of the string

    if (board9Row.includes(square)) {//fill string with 1 for yes, 0 for no
        onEdge += "1";
    } else {
        onEdge += "0";
    }
    if (boardTopEdge.includes(square)) {
        onEdge += "1";
    } else {
        onEdge += "0";
    }
    if (board1Row.includes(square)) {
        onEdge += "1";
    } else {
        onEdge += "0";
    }
    if (boardBottomEdge.includes(square)) {
        onEdge += "1";
    } else {
        onEdge += "0";
    }

    switch (onEdge) { // set the potential spaces that the pice can move into based on its location
        case "B1000": //black on the left edge
        case "W0010": //white on right edge
            ginMoves = [square + (forward * 10), square + (forward * 9), square + (forward * -8)];
            break;

        case "B1100": //black in top left corner
        case "W0011": //white in bottom right corner
            ginMoves = [square + (forward * -8)];
            break;

        case "B0100"://Black on top row
        case "W0001": //white on bottom row
            ginMoves = [square + (forward * -10), square + (forward * -8)];
            break;

        case "B0110": //black in top right corner
        case "W1001": //white in bottom left corner
            ginMoves = [square + (forward * -10)];
            break;

        case "B0010"://black on right edge
        case "W1000"://white on left edge
            ginMoves = [square + (forward * 8), square + (forward * -10), square + (forward * 9)];
            break;

        case "B0011"://black in bottom right corner
        case "W1100"://White in top left corner
            ginMoves = [square + (forward * 8), square + (forward * 9)];
            break;

        case "B0001": //black on bottom row
        case "W0100"://white on top row
            ginMoves = [square + (forward * 8), square + (forward * 10), square + (forward * 9)];
            break;

        case "B1001": //black in bottom left corner
        case "W0110": //white in top right corner
            ginMoves = [square + (forward * 10), square + (forward * -9), square + (forward * 9)];
            break;

        default:
            ginMoves = [square + (forward * -8), square + (forward * 8), square + (forward * -10),
            square + (forward * 10), square + (forward * 9)];
            break;
    }
    if(flipped){
        if(color == "B"){
            color = "W";
        }else{
            color = "B";
        }
    }

    for (i = ginMoves.length - 1; i > -1; i--) {
        if (gameState[ginMoves[i]].charAt(0) === color) {//if own piece is in the square
            //do nothing
        } else {//if the square is empty or has enemy piece
            move.push(ginMoves[i]);//add the space to the array of possible moves
        }
    }

    eliminateIllegalMoves(color); //will remove all moves from move array that would result in check to own gyoku


    if (justChecking === false) {
        for (i = move.length - 1; i > -1; i--) {
            if (move[i] !== null) {
                if ((gameState[move[i]].charAt(0) !== color)) {  //check the first character to see if it the opposite color or empty

                    boardSquare[move[i]].style.background = "rgb(230, 197, 11)";//highlight each possible square to move into
                }
            }
        }
    }

}

function showMoveHI(square, color) {
    let pieceBlocking = false;
    let advanceRow = 0;
    if(flipped){
        if(color == "B"){
            color = "W";
        }else{
            color = "B";
        }
    }
    while (pieceBlocking === false) {//check the forward row
        if ((square + (9 * forward) + advanceRow) < 81 &&
            (square + (9 * forward) + advanceRow) > -1) {//if square is on the board

            if (gameState[square + (9 * forward) + advanceRow].charAt(0) === color) {//if space to move into contains own piece
                pieceBlocking = true;
            } else if (gameState[square + (9 * forward) + advanceRow].charAt(0) === "e") { // if space to move into is empty
                move.push(square + (9 * forward) + advanceRow); //add space to possible moves
                advanceRow = advanceRow + (forward * 9); //move to the next space
            } else { //if space contains enemy piece
                move.push(square + (9 * forward) + advanceRow);//add space to possible moves
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true;//set to true to prevent infinite loop
        }
    }
    advanceRow = 0; //reset the row counter
    pieceBlocking = false; //reset piece blocking tracker

    while (pieceBlocking === false) {//check the backward row
        if ((square + (-9 * forward) + advanceRow) < 81 &&
            (square + (-9 * forward) + advanceRow) > -1) {//if square is on the board

            if (gameState[square + (-9 * forward) + advanceRow].charAt(0) === color) {//if space to move into contains own piece
                pieceBlocking = true;
            } else if (gameState[square + (-9 * forward) + advanceRow].charAt(0) === "e") { // if space to move into is empty
                move.push(square + (-9 * forward) + advanceRow); //add space to possible moves
                advanceRow = advanceRow + (forward * -9); //move to the next space
            } else { //if space contains enemy piece
                move.push(square + (-9 * forward) + advanceRow);//add space to possible moves
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true;//set to true to prevent infinite loop
        }
    }

    advanceRow = 0; //reset the row counter

    if (board1Row.includes(square)){//if it is  on right edge
        pieceBlocking = true;//skip next section (it can't move anywhere, anyway)
    } else {
        pieceBlocking = false;
    }

    while (pieceBlocking === false) {//check the right row

        if (gameState[square + (1 * forward) + advanceRow].charAt(0) === color) {//if space to move into contains own piece
            pieceBlocking = true;
        } else if (gameState[square + (1 * forward) + advanceRow].charAt(0) === "e") { // if space to move into is empty
            move.push(square + (1 * forward) + advanceRow); //add space to possible moves
            advanceRow = advanceRow + (forward * 1); //move to the next space
        } else { //if space contains enemy piece
            move.push(square + (1 * forward) + advanceRow);//add space to possible moves
            pieceBlocking = true;
        }

        if (board1Row.includes(square + advanceRow) ||
            board9Row.includes(square + advanceRow)) {//if reached the edge of the board
            pieceBlocking = true;
        }
    }

    advanceRow = 0; //reset the row counter

    if (board9Row.includes(square)){//if on the left edge
        pieceBlocking = true;//skip next section (it can't move anywhere, anyway)
    } else {
        pieceBlocking = false;
    }

    while (pieceBlocking === false) {//check the left row
        if (gameState[square + (-1 * forward) + advanceRow].charAt(0) === color) {//if space to move into contains own piece
            pieceBlocking = true;
        } else if (gameState[square + (-1 * forward) + advanceRow].charAt(0) === "e") { // if space to move into is empty
            move.push(square + (-1 * forward) + advanceRow); //add space to possible moves
            advanceRow = advanceRow + (forward * -1); //move to the next space
        } else { //if space contains enemy piece
            move.push(square + (-1 * forward) + advanceRow);//add space to possible moves
            pieceBlocking = true;
        }
        if (board1Row.includes(square + advanceRow) ||
            board9Row.includes(square + advanceRow)) {//if reached the edge of the board
            pieceBlocking = true;
        }
    }
    eliminateIllegalMoves(color); //will remove all moves from move array that would result in check to own gyoku


    if (justChecking === false) {
        for (i = move.length - 1; i > -1; i--) {
            if (move[i] !== null) {
                if ((gameState[move[i]].charAt(0) !== color)) {  //check the first character to see if it the opposite color or empty

                    boardSquare[move[i]].style.background = "rgb(230, 197, 11)";//highlight each possible square to move into
                }
            }
        }
    }
}

function showMoveKAKU(square, color) {
    let pieceBlocking = false;
    let advanceRow = 0;
    if(flipped){
        if(color == "B"){
            color = "W";
        }else{
            color = "B";
        }
    }
    if (board1Row.includes(square)) {//if on the right edge
        pieceBlocking = true;//skip next section (it can't move anywhere, anyway)
    } else {
        pieceBlocking = false;
    }
    while (pieceBlocking === false) {//check the forward right diagonal
        if ((square + (10 * forward) + advanceRow) < 81 &&
            (square + (10 * forward) + advanceRow) > -1) {//if square is on the board

            if (gameState[square + (10 * forward) + advanceRow].charAt(0) === color) {//if space to move into contains own piece
                pieceBlocking = true;
            } else if (gameState[square + (10 * forward) + advanceRow].charAt(0) === "e") { // if space to move into is empty
                move.push(square + (10 * forward) + advanceRow); //add space to possible moves
                advanceRow = advanceRow + (forward * 10); //move to the next space
            } else { //if space contains enemy piece
                move.push(square + (10 * forward) + advanceRow);//add space to possible moves
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true;//set to true to prevent infinite loop
        }

        if (board1Row.includes(square + advanceRow) ||
            board9Row.includes(square + advanceRow)) {//if reached the edge of the board
            pieceBlocking = true;
        }
    }
    advanceRow = 0; //reset the row counter

    if (board1Row.includes(square)){//if on the right edge
        pieceBlocking = true;//skip next section (it can't move anywhere, anyway)
    } else {
        pieceBlocking = false;
    }

    while (pieceBlocking === false) {//check the backward right diagonal
        if ((square + (-8 * forward) + advanceRow) < 81 &&
            (square + (-8 * forward) + advanceRow) > -1) {//if square is on the board

            if (gameState[square + (-8 * forward) + advanceRow].charAt(0) === color) {//if space to move into contains own piece
                pieceBlocking = true;
            } else if (gameState[square + (-8 * forward) + advanceRow].charAt(0) === "e") { // if space to move into is empty
                move.push(square + (-8 * forward) + advanceRow); //add space to possible moves
                advanceRow = advanceRow + (forward * -8); //move to the next space
            } else { //if space contains enemy piece
                move.push(square + (-8 * forward) + advanceRow);//add space to possible moves
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true;//set to true to prevent infinite loop
        }
        if (board1Row.includes(square + advanceRow) ||
            board9Row.includes(square + advanceRow)) {//if reached the edge of the board
            pieceBlocking = true;
        }
    }
    advanceRow = 0; //reset the row counter

    if (board9Row.includes(square)) {//if on the left edge
        pieceBlocking = true;//skip next section (it can't move anywhere, anyway)
    } else {
        pieceBlocking = false;
    }
    while (pieceBlocking === false) {//check the forward left diagonal
        if ((square + (8 * forward) + advanceRow) < 81 &&
            (square + (8 * forward) + advanceRow) > -1) {//if square is on the board

            if (gameState[square + (8 * forward) + advanceRow].charAt(0) === color) {//if space to move into contains own piece
                pieceBlocking = true;
            } else if (gameState[square + (8 * forward) + advanceRow].charAt(0) === "e") { // if space to move into is empty
                move.push(square + (8 * forward) + advanceRow); //add space to possible moves
                advanceRow = advanceRow + (forward * 8); //move to the next space
            } else { //if space contains enemy piece
                move.push(square + (8 * forward) + advanceRow);//add space to possible moves
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true;//set to true to prevent infinite loop
        }
        if (board1Row.includes(square + advanceRow) ||
            board9Row.includes(square + advanceRow)) {//if reached the edge of the board
            pieceBlocking = true;
        }
    }
    advanceRow = 0; //reset the row counter

    if (board9Row.includes(square)) {//if on the left edge
        pieceBlocking = true;//skip next section (it can't move anywhere, anyway)
    } else {
        pieceBlocking = false;
    }

    while (pieceBlocking === false) {//check the backward right diagonal
        if ((square + (-10 * forward) + advanceRow) < 81 &&
            (square + (-10 * forward) + advanceRow) > -1) {//if square is on the board

            if (gameState[square + (-10 * forward) + advanceRow].charAt(0) === color) {//if space to move into contains own piece
                pieceBlocking = true;
            } else if (gameState[square + (-10 * forward) + advanceRow].charAt(0) === "e") { // if space to move into is empty
                move.push(square + (-10 * forward) + advanceRow); //add space to possible moves
                advanceRow = advanceRow + (forward * -10); //move to the next space
            } else { //if space contains enemy piece
                move.push(square + (-10 * forward) + advanceRow);//add space to possible moves
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true;//set to true to prevent infinite loop
        }
        if (board1Row.includes(square + advanceRow) ||
            board9Row.includes(square + advanceRow)) {//if reached the edge of the board
            pieceBlocking = true;
        }
    }

    eliminateIllegalMoves(color); //will remove all moves from move array that would result in check to own gyoku


    if (justChecking === false) {
        for (i = move.length - 1; i > -1; i--) {
            if (move[i] !== null) {
                if ((gameState[move[i]].charAt(0) !== color)) {  //check the first character to see if it the opposite color or empty

                    boardSquare[move[i]].style.background = "rgb(230, 197, 11)";//highlight each possible square to move into
                }
            }
        }
    }
}

function showMoveNHI(square, color) {
    showMoveGYOKU(square, color);
    showMoveHI(square, color);
}

function showMoveNKAKU(square, color) {
    showMoveGYOKU(square, color);
    showMoveKAKU(square, color);
}

function showMoveGYOKU(square, color) {
    let onEdge = ""; //a string to hold a code showing which edges the piece is on
    let ouMoves;

    onEdge += color; //add the color as the first part of the string

    if (board9Row.includes(square)) {//fill string with 1 for yes, 0 for no
        onEdge += "1";
    } else {
        onEdge += "0";
    }
    if (boardTopEdge.includes(square)) {
        onEdge += "1";
    } else {
        onEdge += "0";
    }
    if (board1Row.includes(square)) {
        onEdge += "1";
    } else {
        onEdge += "0";
    }
    if (boardBottomEdge.includes(square)) {
        onEdge += "1";
    } else {
        onEdge += "0";
    }

    switch (onEdge) { // set the potential spaces that the pice can move into based on its location
        case "B1000": //black on the left edge
        case "W0010"://white on right edge
            ouMoves = [square + (forward * -9), square + (forward * 10), square + (forward * -8),
            square + (forward * 1), square + (forward * 9)];
            break;

        case "B1100": //black in top left corner
        case "W0011": //white in bottom right corner
            ouMoves = [square + (forward * 1), square + (forward * -8), square + (forward * -9)];
            break;

        case "B0100"://Black on top row
        case "W0001": //white on bottom row
            ouMoves = [square + (forward * 1), square + (forward * -1), square + (forward * -9),
            square + (forward * -10), square + (forward * -8)];
            break;

        case "B0110": //black in top right corner
        case "W1001": //white in bottom left corner
            ouMoves = [square + (forward * -1), square + (forward * -9), square + (forward * -10)];
            break;

        case "B0010"://black on right edge
        case "W1000"://white on left edge
            ouMoves = [square + (forward * 8), square + (forward * -9),
            square + (forward * -1), square + (forward * 9), square + (forward * -10)];
            break;

        case "B0011"://black in bottom right corner
        case "W1100"://White in top left corner
            ouMoves = [square + (forward * 8),
            square + (forward * -1), square + (forward * 9)];
            break;

        case "B0001": //black on bottom row
        case "W0100"://white on top row
            ouMoves = [square + (forward * 1), square + (forward * 8), square + (forward * 10),
            square + (forward * -1), square + (forward * 9)];
            break;

        case "B1001": //black in bottom left corner
        case "W0110": //white in top right corner
            ouMoves = [square + (forward * 9), square + (forward * 10),
            square + (forward * 1)];
            break;

        default:
            ouMoves = [square + (forward * 1), square + (forward * 8), square + (forward * 9), square + (forward * 10),
            square + (forward * -1), square + (forward * -8), square + (forward * -9), square + (forward * -10)];
            break;
    }
    if(flipped){
        if(color == "B"){
            color = "W";
        }else{
            color = "B";
        }
    }
    for (i = ouMoves.length - 1; i > -1; i--) {
        if (gameState[ouMoves[i]].charAt(0) === color) {//if own piece is in the square
            //do nothing
        } else {//if the square is empty or has enemy piece
            move.push(ouMoves[i]);//add the space to the array of possible moves
        }
    }

    eliminateIllegalMoves(color); //will remove all moves from move array that would result in check to own gyoku


    if (justChecking === false) {
        
        for (i = move.length - 1; i > -1; i--) {
            if (move[i] !== null) {
                if ((gameState[move[i]].charAt(0) !== color)) {  //check the first character to see if it the opposite color or empty

                    boardSquare[move[i]].style.background = "rgb(230, 197, 11)";//highlight each possible square to move into
                }
            }
        }
    }
}

function movePiece(id) {
    let isMochiGoma;
    let moveFromSend;
    let moveToSend;

    gameState[82] = gameState[id];//a temporary placeholder for the clicked place
    if (selectedPiece < 81) { //if it's other than the mochigoma
        //see if piece can promote
        if ((gameState[selectedPiece].charAt(1) !== "N") && //if the piece isn't already promoted (the second letter isn't N)
            id < 27 ||// or if it is an odd turn and the piece will move into the third row or less
            selectedPiece < 27) { //or the piece is already within the first 3 rows

            promotePiece();
        }
        if (gameState[id].charAt(0) !== "e") { //if capturing a piece
            addToMochiGoma(gameState[id]);
        }

    }

    if (selectedPiece === 81) {//if it is a mochigoma
        let mochigomaPlace = mochiGomaOrder.indexOf("M" + gameState[selectedPiece]); //find the place where it is
        mochiGomaArray[mochigomaPlace]--; //remove a piece from the array
        isMochiGoma = gameState[81];
    }

    let tempMoveForGameHistory;
    if(turn === 1){
        //on the first turn, we don't want to start by sending a comma in the data
        sendToDatabase = JSON.stringify({"newmoves": selectedPiece.toString() + "," 
            + id.toString() + "," + gameState[selectedPiece], "gameId": currentGameID, "turn": turn });//make the move into JSON object

            //this is for the forward and back buttons
            tempMoveForGameHistory = selectedPiece.toString() + ","  + id.toString() + "," + gameState[selectedPiece];
    }else{
        //otherwise, check if it is white and flip the move if it is
        
        if(turn % 2 == 0){
            if(selectedPiece == 81){
                moveFromSend = 81; //mochi goma will be 81 no matter what
            }else{
                moveFromSend = 80 - selectedPiece;
            }
            moveToSend = 80 - id;
        }else{
            moveFromSend = selectedPiece
            moveToSend = id;
        }
        //also, start by sending a comma to separate the move from the last one stored
        sendToDatabase = JSON.stringify({"newmoves": "," + moveFromSend.toString() + "," 
        + moveToSend.toString() + "," + gameState[selectedPiece], "gameId": currentGameID, "turn": turn });//make the move into JSON object

        //for Forward and Back buttons
        tempMoveForGameHistory = "," + moveFromSend.toString() + "," + moveToSend.toString() + "," + gameState[selectedPiece];
        
    }
    
        
    gameState[id] = gameState[selectedPiece]; //move the piece to the new square
    gameState[selectedPiece] = "empty"; //make the space where the piece moved from empty
    

    drawBoard();     
    
    drawMochigoma();

    disableAll();
     //only the piece that was moved can be clicked, and it will trigger the confirmMove function and pass it the needed variables
    boardSquare[id].setAttribute("onclick", "confirmMove(" + moveFromSend+ ","+ moveToSend+ ", '"+ tempMoveForGameHistory+"'," + id+")");  
    document.getElementById("undo").style.visibility="visible";
    document.getElementById("playerPrompt").innerHTML="再度クリックで承認｜Click again to confirm";



}
function confirmMove(moveFromSend, moveToSend, tempMoveForGameHistory, currentPlace){
        turn++; //increase the turn counter
        
        gameHistory[0] += tempMoveForGameHistory; //for forward and back buttons
        movesHistory = gameHistory[0].split(","); //break the moves into an array 

            if(turn > 1){

            if(handleReservations(moveFromSend, moveToSend, gameState[currentPlace]) == false){ //if handleReservations returns false
                disableAll();
                //send move to database
                 sendMoveData();
                document.getElementById("toReservation").style.visibility = "visible";
                
            }
        }

                deselectAll();
                resetGameState();
                loadGameState(1);
                if(checkForMate(opponentColor)){
                    endGame();
                }
                drawBoard();
                drawMochigoma();
        selectedPiece = null;
       
    
}

function handleReservations(movedFrom, movedTo, movedPiece){
    if(reservationArray.length <2){//if there are no moves reserved
        return false;
    }else{
        let reservedMoves = [];
        for(r = 1; r < reservationArray.length; r++){ //starts at 1 since the first space in the array is always empty
            reservedMoves[r - 1] = reservationArray[r].split(",");//nested array of each move sequence
        }

        if(reservedMoves[0][0] == movedFrom && reservedMoves[0][1] == movedTo && reservedMoves[0][2] == movedPiece){
            //if the reservation perfectly macthes the move made
            alert("Reserved move triggered");
            movesHistory.push(movedFrom.toString(), movedTo.toString(), movedPiece, reservedMoves[1][0], reservedMoves[1][1], reservedMoves[1][2]);

            sendToDatabase = JSON.stringify({"newmoves": "," + movedFrom +"," + movedTo + "," + movedPiece + "," +
            reservedMoves[1][0] + "," + reservedMoves[1][1] + "," + reservedMoves[1][2], "gameId": currentGameID });
             //send the user's move and the triggerd move together
            sendMoveData();

            reservationArray.splice(1,2);
            return true;
        }else{
            return false;
        }

    }
}

function disableAll(){
    for(i=0; i<81; i++){
        boardSquare[i].setAttribute("onClick", null);
    }
    for(x = 0; x<14; x ++){
        mochiGoma[x].setAttribute("onClick", null);
    }
    if(gameHistory[4] == "3"){//if the game is finished
        document.getElementById("resignButton").style.visibility = "hidden";
    }
}
function promotePiece() {
    if (gameState[selectedPiece].charAt(1) !== "N" && //if the piece is not promoted yet
        gameState[selectedPiece].substr(1, 3) !== "KIN" &&
        gameState[selectedPiece].substr(1, 5) !== "GYOKU") { //and not a kin or Gyoku
        let yesNo = confirm("Promote?");
        if (yesNo) {
            gameState[selectedPiece] = gameState[selectedPiece].substr(0, 1) + "N" + gameState[selectedPiece].substr(1, 4); // add an N for nari after the first character

        }
    }
    return;
}

function deselectAll() {

    for (i = 0; i < 81; i++) { //cycle through every square and remove the background highlight color
        boardSquare[i].style.background = "none";
        boardSquare[i].style.filter = "none";

    }
    for (i = 0; i < 14; i++) {
        mochiGoma[i].style.filter = "none";
    }
    move = [];
    isCheck = null;
    checkingPieces = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
}

function placePiece(piece) {
    if ((mochiGoma[mochiGomaOrder.indexOf(piece)].style.filter === "brightness(1.5)") && justChecking === false
        || piece.charAt(1) != playerColor) { //if the currently selected piece is clicked again
        deselectAll();
        mochiGomaAlreadySelected = false;
    } else if(mochiGomaAlreadySelected){
        //if a mochigoma is already selected, this makes sure that multiple pieces can't be highlighted at the same time
        deselectAll();
        mochiGomaAlreadySelected = false;
    }else{
        selectedPiece = 81; //set selected piece to number outside of the board
        gameState[81] = piece.substr(1, piece.length); //put the piece in the 81st spot of the gameState array
        mochiGomaAlreadySelected = true;
        let startingPlace = 0;
        let endAfter;
        let MGColor = piece.charAt(1);

        if (justChecking === false) {
            let mochigomaPlace = mochiGomaOrder.indexOf("M" + gameState[selectedPiece]); //find the place where it is
            mochiGoma[mochigomaPlace].style.filter = "brightness(1.5)"; //highlight the selected piece 
        }

        switch (piece.substr(2, piece.length)) {//fu have special rules about place ment
            case "F":
                let possibleFuRows = [0, 0, 0, 0, 0, 0, 0, 0, 0];
                let fuStartingPlace;
                for (i = 0; i < 9; i++) {
                    let yesNoFu = false;
                    for (x = 0; x < 9; x++) {
                        if (gameState[allBoardRows[i][x]] === MGColor + "F") {//if there's already a fu in that space
                            yesNoFu = true; //set the variable to true so that the row won't be included
                        }
                    }
                    if (yesNoFu === false) {//if there is no other fu in the row
                        possibleFuRows[i] = 1;
                    } else {
                        possibleFuRows[i] = 0; //otherwise there is a fu already, so it is set to 0
                    }
                }
                fuStartingPlace = 1;

                for (i = 0; i < 9; i++) {//go through to find all the possible rows again
                    if (possibleFuRows[i] === 1) {
                        for (x = fuStartingPlace; x < (fuStartingPlace + 8); x++) {
                            if (gameState[allBoardRows[i][x]] === "empty") {
                                move.push(allBoardRows[i][x]); //add all the empty spaces to the move array
                            }
                        }
                    }
                }
                break;

            case "KO"://Ko can't be placed on last row
                endAfter = 72; //count for 72 squares (all but the last row)
                startingPlace = 9;

            case "KEI": //kei can't be placed in the last 2 rows since they couldn't move

                startingPlace = 18;
                endAfter = 63;


                for (i = startingPlace; i < startingPlace + endAfter; i++) {//cycle through each square in the board that is possible for that color
                    if (gameState[i] === "empty") {
                        move.push(i);//add all empty squares to the list of possible moves
                    }
                }
                break;

            default:
                for (i = 0; i < 81; i++) {
                    if (gameState[i] === "empty") {
                        move.push(i);//add all empty squares to the list of possible moves
                    }
                }
        }

        eliminateIllegalMoves(MGColor); //will remove all moves from move array that would result in check to own gyoku

        if (justChecking === false) {
            for (i = move.length - 1; i > -1; i--) {
                if (move[i] !== null) {
                    boardSquare[move[i]].style.background = "rgb(230, 197, 11)";//highlight each possible square to move into
                }
            }
        }
    }

}
function removeMG(gamePiece){
    let gamePieceColor;
    if (gamePiece.charAt(1) === "N") {//if it's a promoted piece
        gamePiece = gamePiece.replace("N", ""); //remove the N
    }
    if (gamePiece.charAt(0) === "B") {
        gamePieceColor = 0;
    } else {
        gamePieceColor = 7; //if it's a white piece, start at the 7th array spot
    }
    switch (gamePiece.substr(1, gamePiece.length)) { // return the piece name minus thethe color 

        case "F":
            mochiGomaArray[0 + gamePieceColor] -= 1; //add a fu to the fu place
            break;

        case "KO":
            mochiGomaArray[1 + gamePieceColor] -= 1;//add a ko to the ko place
            break;

        case "KEI":
            mochiGomaArray[2 + gamePieceColor] -= 1;//add a kei to the kei place
            break;

        case "GIN":
            mochiGomaArray[3 + gamePieceColor] -= 1;//add a gin to the gin place
            break;

        case "KIN":
            mochiGomaArray[4 + gamePieceColor] -= 1;//add a kin to the kin place
            break;

        case "KAKU":
            mochiGomaArray[5 + gamePieceColor] -= 1;//add a kaku to the kaku place
            break;

        case "HI":
            mochiGomaArray[6 + gamePieceColor] -= 1;//add a hi to the hi place
            break;
        default:
            console.log("piece name is incorrect");
            break;

    } 
}

// 15   8    9     
//   7  0  1
// 14 6 玉 2 10    check each potential checking square / angle in this order and return an array of all checking pieces' squares
//    5 4  3
// 13   12   11
function checkForCheck(gyokuColor) {
    let gyokuPosition = gameState.indexOf(gyokuColor + "GYOKU");//get the location of the gyoku being checked
    let gyokuForward;
    let gyokuOnTopRow;
    let gyokuOnBottomRow;
    let gyokuOnRightColumn;
    let gyokuOnLeftColumn;

    if (!justChecking) {
        gyokuForward = -1; //black ou moves negatively to go forward
        //this will check to see if the gyoku is on any of the edges of the board and set the corresponding spot
        //in the checkingPieces array to 2, which will prevent it from being checked in the next part

        if (boardTopEdge.includes(gyokuPosition)) {//if on top row, there can't be any pieces above it
            checkingPieces[0] = 2;
            checkingPieces[1] = 2;
            checkingPieces[7] = 2;
            gyokuOnTopRow = true;
        }
        if (boardBottomEdge.includes(gyokuPosition)) { // if on bottom row, there can't be any pieces below it
            checkingPieces[5] = 2;
            checkingPieces[4] = 2;
            checkingPieces[3] = 2;
            gyokuOnBottomRow = true;
        }
        if (board1Row.includes(gyokuPosition)) { //no pieces to right side
            checkingPieces[1] = 2;
            checkingPieces[2] = 2;
            checkingPieces[3] = 2;
            gyokuOnRightColumn = true;
        }
        if (board9Row.includes(gyokuPosition)) {//none to the left
            checkingPieces[5] = 2;
            checkingPieces[6] = 2;
            checkingPieces[7] = 2;
            gyokuOnLeftColumn = true;
        }
    } else {
        gyokuForward = 1; //white ou moves positively to go forward

        if (boardTopEdge.includes(gyokuPosition)) {//if on bottom row, there can't be any pieces below it
            checkingPieces[5] = 2;
            checkingPieces[4] = 2;
            checkingPieces[3] = 2;
            gyokuOnBottomRow = true;
        }
        if (boardBottomEdge.includes(gyokuPosition)) { //if on top row, there can't be any pieces above it
            checkingPieces[0] = 2;
            checkingPieces[1] = 2;
            checkingPieces[7] = 2;
            gyokuOnTopRow = true;
        }
        if (board1Row.includes(gyokuPosition)) {//none to the left
            checkingPieces[5] = 2;
            checkingPieces[6] = 2;
            checkingPieces[7] = 2;
            gyokuOnLeftColumn = true;
        }
        if (board9Row.includes(gyokuPosition)) {//none to the right
            checkingPieces[1] = 2;
            checkingPieces[2] = 2;
            checkingPieces[3] = 2;
            gyokuOnRightColumn = true;
        }
    }

    //check the square in front of the gyoku
    if (checkingPieces[0] !== 2 && gameState[gyokuPosition + (gyokuForward * 9)].charAt(0) != "e" &&
        gameState[gyokuPosition + (gyokuForward * 9)].charAt(0) != gyokuColor) { // if it's an enemy piece
        switch (gameState[gyokuPosition + (gyokuForward * 9)].substr(1, gameState[gyokuPosition + (gyokuForward * 9)].length)) {//check the square right in front of the gyoku
            case "mpty":
            case "KEI":
            case "KAKU":
                checkingPieces[0] = 0; //none of these pieces can check the gyoku fron the front
                break;
            default:
                checkingPieces[0] = gyokuPosition + (gyokuForward * 9); //the square contains a checking piece
                //so it's square is added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[0] = 0; //own piece is there, so it's not check
    }

    //check the forward right diagonal square

    if (checkingPieces[1] !== 2 && gameState[gyokuPosition + (gyokuForward * 10)].charAt(0) != "e" &&
        gameState[gyokuPosition + (gyokuForward * 10)].charAt(0) != gyokuColor) {
        switch (gameState[gyokuPosition + (gyokuForward * 10)].substr(1, gameState[gyokuPosition + (gyokuForward * 10)].length)) {
            case "F":
            case "KO":
            case "KEI":
            case "HI":
            case "mpty":
                checkingPieces[1] = 0; //none of these pieces can check the gyoku fron the side 
                break;
            default:
                checkingPieces[1] = gyokuPosition + (gyokuForward * 10);
                //added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[1] = 0;// own piece is in the square, so no check
    }

    //check the square to the Gyoku's right
    if (checkingPieces[2] !== 2 && gameState[gyokuPosition + (gyokuForward * 1)].charAt(0) != "e" &&
        gameState[gyokuPosition + (gyokuForward * 1)].charAt(0) != gyokuColor) {
        switch (gameState[gyokuPosition + (gyokuForward * 1)].substr(1, gameState[gyokuPosition + (gyokuForward * 1)].length)) {
            case "F":
            case "KO":
            case "GIN":
            case "KAKU":
            case "KEI":
            case "mpty":
                checkingPieces[2] = 0; //none of these pieces can check the gyoku fron the side 
                break;
            default:
                checkingPieces[2] = gyokuPosition + (gyokuForward * 1);
                //added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[2] = 0;// own piece is in the square, so no check
    }

    //check the back right diagonal square

    if (checkingPieces[3] !== 2 && gameState[gyokuPosition + (gyokuForward * -8)].charAt(0) != "e" &&
        gameState[gyokuPosition + (gyokuForward * -8)].charAt(0) != gyokuColor) {
        switch (gameState[gyokuPosition + (gyokuForward * -8)].substr(1, gameState[gyokuPosition + (gyokuForward * -8)].length)) {
            case "F":
            case "KO":
            case "KIN":
            case "KEI":
            case "HI":
            case "NF":
            case "NGIN":
            case "NKEI":
            case "NKO":
            case "mpty":
                checkingPieces[3] = 0; //none of these pieces can check the gyoku fron the back diagonal
                break;
            default:
                checkingPieces[3] = gyokuPosition + (gyokuForward * -8);
                //added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[3] = 0;// own piece is in the square, so no check
    }

    //check the square behind the gyoku
    if (checkingPieces[4] !== 2 && gameState[gyokuPosition + (gyokuForward * -9)].charAt(0) != "e" &&
        gameState[gyokuPosition + (gyokuForward * -9)].charAt(0) != gyokuColor) { // if it's an enemy piece
        switch (gameState[gyokuPosition + (gyokuForward * -9)].substr(1, gameState[gyokuPosition + (gyokuForward * -9)].length)) {//check the square right in front of the gyoku
            case "mpty":
            case "KEI":
            case "GIN":
            case "KO":
            case "FU":
            case "KAKU":
                checkingPieces[4] = 0; //none of these pieces can check the gyoku fron the front
                break;
            default:
                checkingPieces[4] = gyokuPosition + (gyokuForward * -9); //the square contains a checking piece
                //so it's square is added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[4] = 0; //own piece is there, so it's not check
    }
    //check the back left diagonal square

    if (checkingPieces[5] !== 2 && gameState[gyokuPosition + (gyokuForward * -10)].charAt(0) != "e" &&
        gameState[gyokuPosition + (gyokuForward * -10)].charAt(0) != gyokuColor) {
        switch (gameState[gyokuPosition + (gyokuForward * -10)].substr(1, gameState[gyokuPosition + (gyokuForward * -10)].length)) {
            case "F":
            case "KO":
            case "KIN":
            case "KEI":
            case "HI":
            case "NF":
            case "NGIN":
            case "NKEI":
            case "NKO":
            case "mpty":
                checkingPieces[5] = 0; //none of these pieces can check the gyoku fron the back diagonal
                break;
            default:
                checkingPieces[5] = gyokuPosition + (gyokuForward * -10);
                //added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[5] = 0;// own piece is in the square, so no check
    }

    //check the square to the Gyoku's left
    if (checkingPieces[6] !== 2 && gameState[gyokuPosition + (gyokuForward * -1)].charAt(0) != "e" &&
        gameState[gyokuPosition + (gyokuForward * -1)].charAt(0) != gyokuColor) {
        switch (gameState[gyokuPosition + (gyokuForward * -1)].substr(1, gameState[gyokuPosition + (gyokuForward * -1)].length)) {
            case "F":
            case "KO":
            case "GIN":
            case "KAKU":
            case "KEI":
            case "mpty":
                checkingPieces[6] = 0; //none of these pieces can check the gyoku fron the side 
                break;
            default:
                checkingPieces[6] = gyokuPosition + (gyokuForward * -1);
                //added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[6] = 0;// own piece is in the square, so no check
    }

    //check the forward left diagonal square

    if (checkingPieces[7] !== 2 && gameState[gyokuPosition + (gyokuForward * 8)].charAt(0) != "e" &&
        gameState[gyokuPosition + (gyokuForward * 8)].charAt(0) != gyokuColor) {
        switch (gameState[gyokuPosition + (gyokuForward * 8)].substr(1, gameState[gyokuPosition + (gyokuForward * 8)].length)) {
            case "F":
            case "KO":
            case "KEI":
            case "HI":
            case "mpty":
                checkingPieces[7] = 0; //none of these pieces can check the gyoku fron the side 
                break;
            default:
                checkingPieces[7] = gyokuPosition + (gyokuForward * 8);
                //added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[7] = 0;// own piece is in the square, so no check
    }

    // check the forward row
    let checkingPosition = gyokuPosition + (gyokuForward * 9);
    let pieceBlocking = false;
    while (pieceBlocking === false) {//check the forward row
        if (checkingPosition < 81 &&
            checkingPosition > -1) {//if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) { //if it is an enemy piece or empty
                switch (gameState[checkingPosition].substr(1, gameState[checkingPosition].length)) {
                    case "KO":
                    case "HI":
                    case "NHI":
                        checkingPieces[8] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case "mpty":
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[8] = 0; //none of the other pieces can check the gyoku from a distance 
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[8] = 0;// own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[8] = 0; //no checking pieces in the row since it went all the way to the edge
        }
        checkingPosition += (9 * gyokuForward); //increment the counter

    }
    pieceBlocking = false; //reset piece blocking tracker

    //check the forward right diagonal
    if (gyokuOnRightColumn) {
        pieceBlocking = true;
        checkingPieces[9] = 0;
    } else {
        pieceBlocking = false;
    }
    checkingPosition = gyokuPosition + (gyokuForward * 10);
    while (pieceBlocking === false) {//check the forward row
        if (checkingPosition < 81 &&
            checkingPosition > -1) {//if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) { //if it is an enemy piece or empty
                switch (gameState[checkingPosition].substr(1, gameState[checkingPosition].length)) {
                    case "KAKU":
                    case "NKAKU":
                        checkingPieces[9] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case "mpty":
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[9] = 0; //none of the other pieces can check the gyoku from a distance 
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[9] = 0;// own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[9] = 0; //no checking pieces in the row since it went all the way to the edge
        }
        if (board1Row.includes(checkingPosition) || board9Row.includes(checkingPosition)) {
            //if the last square checked was on the edge of the board
            pieceBlocking = true;
        }
        else {
            checkingPosition += (10 * gyokuForward); //increment the counter
        }

    }
    pieceBlocking = false; //reset piece blocking tracker

    //check the right row
    checkingPosition = gyokuPosition + (gyokuForward * 1);
    if ((gyokuColor === "B" && board1Row.includes(checkingPosition)) ||//if it is black and on right edge
        gyokuForward === "W" && board9Row.includes(checkingPosition)) {//or white and on left edge
        pieceBlocking = true;//skip next section (it can't move anywhere, anyway)
        checkingPieces[10] = 0;
    } else {
        pieceBlocking = false;
    }

    while (pieceBlocking === false) {
        if (checkingPosition < 81 &&
            checkingPosition > -1) {//if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) { //if it is an enemy piece or empty
                switch (gameState[checkingPosition].substr(1, gameState[checkingPosition].length)) {
                    case "HI":
                    case "NHI":
                        checkingPieces[10] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case "mpty":
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[10] = 0; //none of the other pieces can check the gyoku from a distance 
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[10] = 0;// own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[10] = 0; //no checking pieces in the row since it went all the way to the edge
        }

        if (board1Row.includes(checkingPosition) ||
            board9Row.includes(checkingPosition)) {//if reached the edge of the board
            pieceBlocking = true;
            if (checkingPieces[10] === undefined) {
                checkingPieces[10] = 0; //no checking pieces in the row since it went all the way to the edge
            }
        }
        checkingPosition += (1 * gyokuForward); //increment the counter

    }
    pieceBlocking = false; //reset piece blocking tracker

    //check the back right diagonal
    if (gyokuOnRightColumn) {
        pieceBlocking = true;
        checkingPieces[11] = 0;
    } else {
        pieceBlocking = false;
    }

    checkingPosition = gyokuPosition + (gyokuForward * -8);
    while (pieceBlocking === false) {//check the forward row
        if (checkingPosition < 81 &&
            checkingPosition > -1) {//if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) { //if it is an enemy piece or empty
                switch (gameState[checkingPosition].substr(1, gameState[checkingPosition].length)) {
                    case "KAKU":
                    case "NKAKU":
                        checkingPieces[11] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case "mpty":
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[11] = 0; //none of the other pieces can check the gyoku from a distance 
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[11] = 0;// own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[11] = 0; //no checking pieces in the row since it went all the way to the edge
        }
        if (board1Row.includes(checkingPosition) || board9Row.includes(checkingPosition)) {
            //if the last square checked was on the edge of the board
            pieceBlocking = true;
        }
        else {
            checkingPosition += (-8 * gyokuForward); //increment the counter
        }
    }
    pieceBlocking = false; //reset piece blocking tracker

    // check the back row
    checkingPosition = gyokuPosition + (gyokuForward * -9);
    while (pieceBlocking === false) {
        if (checkingPosition < 81 &&
            checkingPosition > -1) {//if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) { //if it is an enemy piece or empty
                switch (gameState[checkingPosition].substr(1, gameState[checkingPosition].length)) {
                    case "KO":
                    case "HI":
                    case "NHI":
                        checkingPieces[12] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case "mpty":
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[12] = 0; //none of the other pieces can check the gyoku from a distance 
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[12] = 0;// own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[12] = 0; //no checking pieces in the row since it went all the way to the edge
        }
        checkingPosition += (-9 * gyokuForward); //increment the counter

    }
    pieceBlocking = false; //reset piece blocking tracker

    //check the back left diagonal
    if (gyokuOnLeftColumn) {
        pieceBlocking = true;
        checkingPieces[13] = 0;
    } else {
        pieceBlocking = false;
    }
    checkingPosition = gyokuPosition + (gyokuForward * -10);
    while (pieceBlocking === false) {//check the forward row
        if (checkingPosition < 81 &&
            checkingPosition > -1) {//if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) { //if it is an enemy piece or empty
                switch (gameState[checkingPosition].substr(1, gameState[checkingPosition].length)) {
                    case "KAKU":
                    case "NKAKU":
                        checkingPieces[13] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case "mpty":
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[13] = 0; //none of the other pieces can check the gyoku from a distance 
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[13] = 0;// own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[13] = 0; //no checking pieces in the row since it went all the way to the edge
        }
        if (board1Row.includes(checkingPosition) || board9Row.includes(checkingPosition)) {
            //if the last square checked was on the edge of the board
            pieceBlocking = true;
        }
        else {
            checkingPosition += (-10 * gyokuForward); //increment the counter
        }

    }
    pieceBlocking = false; //reset piece blocking tracker

    //check the left row
    checkingPosition = gyokuPosition + (gyokuForward * -1);
    if ((gyokuColor === "W" && board1Row.includes(checkingPosition)) ||//if it is black and on right edge
        gyokuForward === "B" && board9Row.includes(checkingPosition)) {//or white and on left edge
        pieceBlocking = true;//skip next section (it can't move anywhere, anyway)
        checkingPieces[10] = 0;
    } else {
        pieceBlocking = false;
    }

    while (pieceBlocking === false) {
        if (checkingPosition < 81 &&
            checkingPosition > -1) {//if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) { //if it is an enemy piece or empty
                switch (gameState[checkingPosition].substr(1, gameState[checkingPosition].length)) {
                    case "HI":
                    case "NHI":
                        checkingPieces[14] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case "mpty":
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[14] = 0; //none of the other pieces can check the gyoku from a distance 
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[14] = 0;// own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[14] = 0; //no checking pieces in the row since it went all the way to the edge
        }

        if (board1Row.includes(checkingPosition) ||
            board9Row.includes(checkingPosition)) {//if reached the edge of the board
            pieceBlocking = true;
            if (checkingPieces[14] === undefined) {//it will be defined if another piece is in the square
                checkingPieces[14] = 0; //no checking pieces in the row since it went all the way to the edge
            }
        }
        checkingPosition += (-1 * gyokuForward); //increment the counter

    }
    pieceBlocking = false; //reset piece blocking tracker

    //check the forward left diagonal
    if (gyokuOnLeftColumn) {
        pieceBlocking = true;
        checkingPieces[15] = 0;
    } else {
        pieceBlocking = false;
    }
    checkingPosition = gyokuPosition + (gyokuForward * 8);
    while (pieceBlocking === false) {//check the forward row
        if (checkingPosition < 81 &&
            checkingPosition > -1) {//if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) { //if it is an enemy piece or empty
                switch (gameState[checkingPosition].substr(1, gameState[checkingPosition].length)) {
                    case "KAKU":
                    case "NKAKU":
                        checkingPieces[15] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case "mpty":
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[15] = 0; //none of the other pieces can check the gyoku from a distance 
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[15] = 0;// own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[15] = 0; //no checking pieces in the row since it went all the way to the edge
        }
        if (board1Row.includes(checkingPosition) || board9Row.includes(checkingPosition)) {
            //if the last square checked was on the edge of the board
            pieceBlocking = true;
        }
        else {
            checkingPosition += (8 * gyokuForward); //increment the counter
        }
    }
    for (i = 0; i < checkingPieces.length; i++) {
        if (checkingPieces[i] > 0) {
            isCheck = gyokuColor;
        }
    }
    return isCheck;
    //return checkingPieces; //need to change this to return isCheck
}

function eliminateIllegalMoves(color) {

    let moveFromHolder;
    let moveToHolder;
    for (c = 0; c < move.length; c++) { //go through each potential move
        moveFromHolder = gameState[selectedPiece];
        moveToHolder = gameState[move[c]];

        gameState[move[c]] = gameState[selectedPiece]; //test executing the move
        gameState[selectedPiece] = "empty";


        if (checkForCheck(color) === color) {//if the move would result in check
            gameState[move[c]] = moveToHolder; //reset move to how it was before
            move[c] = null;
        } else {
            gameState[move[c]] = moveToHolder; //reset the move to how it was before
        }

        gameState[selectedPiece] = moveFromHolder; //reset selectedpiece square to how it was before
        checkingPieces = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        isCheck = null; //needs to be reset, otherwise if previous spots check returned true, it will still return true
    }

    //if there are any null values in the array, they need to be removed:
    let tempMoveArray = [];
    for(i = 0; i < move.length; i++){
        if(move[i] != null){
          tempMoveArray.push(move[i]);
        }
    }
    move = tempMoveArray;
    console.log(move);
}
let justChecking = false;
let isCheckMate = false;
function checkForMate(color) {
    let counterForMove = 0;
    justChecking = true; //this will affect all the functions called
    for (s = 0; s < 81; s++) {

        if (gameState[s].charAt(0) === color) {//if it's an own piece
            selectedPiece = s;
            pieceClick(s);//call the piececlick function to get the moves

            for (b = 0; b < move.length; b++) {
                counterForMove += move[b];
            }
            if (counterForMove > 0) { //if there are possible moves
                isCheckMate = false;
                break; //can break the loop
            } else {
                isCheckMate = true;
            }
        }
    }
    if (isCheckMate === true) {//if none of the pieces on the board can be moved
        let startCountingMG;
        if (color === "B") {
            startCountingMG = 7;//black pieces in the mochigoma array start at the 7th spot
        } else {
            startCountingMG = 0;//at the beginning for the white pieces
        }
        for (v = startCountingMG; v < startCountingMG + 7; v++) {
            if (mochiGomaArray[v] > 0) {//if there is actually a mochigoma in that spot
                placePiece(mochiGomaOrder[v]);

                for (b = 0; b < move.length; b++) {
                    counterForMove += move[b];
                }
                if (counterForMove > 0) { //if there are possible moves
                    isCheckMate = false;
                    break; //can break the loop
                } else {
                    isCheckMate = true;
                }
            }
        }
    }
    justChecking = false;
    //move = [];
    deselectAll();
    return isCheckMate;
}

function disableSubmit(){
    document.getElementById("submitmovebutton").style.visibility = "hidden";
}

function stepForward(){
    //if it's not the first move and it's not displaying the current turn
    if(realTurn > 1 && viewTurn < turn ){
        viewTurn ++;
        movesHistory = gameHistory[0].split(",");
        movesHistory.splice(3 * viewTurn, movesHistory.length - (3 * viewTurn));
        resetGameState();
        deselectAll();
        loadGameState(1);
        drawBoard();
        drawMochigoma();
    }

}
function skipForward(){
    viewTurn = realTurn - 1;
    movesHistory = gameHistory[0].split(",");
    resetGameState();
        deselectAll();
        loadGameState(1);
        drawBoard();
        drawMochigoma();
}

function stepBack(){
    //if it's not the first move and it's not displaying the first move
    if(turn > 1 && viewTurn > 1){
        viewTurn --; //go back one turn 
        movesHistory = gameHistory[0].split(",");
        movesHistory.splice(3 * viewTurn, movesHistory.length - (3 * viewTurn));
        resetGameState();
        deselectAll();
        loadGameState(1);
        drawBoard();
        drawMochigoma();
    }

}
function skipBack(){
    viewTurn = 0;
    movesHistory = undefined;
    resetGameState();
        deselectAll();
        loadGameState(1);
        drawBoard();
        drawMochigoma();
}

function endGame(){
    alert("勝ちました。おめでとう！");
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function()
  {
    // If ajax.readyState is 4, then the connection was successful
    // If ajax.status (the HTTP return code) is 200, the request was successful
    if(ajax.readyState == 4 && ajax.status == 200)
    {
      // Use ajax.responseText to get the raw response from the server
      console.log(ajax.responseText);
      window.location.reload();
    }else {
        console.log('Error: ' + ajax.status); // An error occurred during the request.
    }
  }
  let winnerName;
  let loserName;
  //set the winner and loser by finding the usernames of the players from the gamehistory array
  if(playerColor == "W"){
      winnerName = gameHistory[2];
      loserName = gameHistory[1];
  }else{
      winnerName = gameHistory[1];
      loserName = gameHistory[2];
  }

  let json = JSON.stringify({
    id: currentGameID, winner: winnerName, loser: loserName
  });

console.log(json);
    ajax.open("POST", 'resign.php', true); //asyncronous
    ajax.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
    ajax.send(json);//(sendToDatabase);
}