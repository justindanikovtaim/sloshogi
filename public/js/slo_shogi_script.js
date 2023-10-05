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
let allBoardRows = [
    board1Row,
    board2Row,
    board3Row,
    board4Row,
    board5Row,
    board6Row,
    board7Row,
    board8Row,
    board9Row,
];
let boardTopEdge = [0, 1, 2, 3, 4, 5, 6, 7, 8];
let boardBottomEdge = [72, 73, 74, 75, 76, 77, 78, 79, 80];
let mochiGomaArray = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; //array for black mochi goma
//(Wfu, WKo, Wkei, Wgin, Wkin, Wkaku, Whi, Bfu, BKo, Bkei, Bgin, Bkin, Bkaku, Bhi)
let mochiGomaAlreadySelected = false;
let isCheck = null; //keep track of if it is check or not
let justChecking = false;
let isCheckMate = false;
let checkingPieces = [];
let move = [];
let boardSquare = [];
let newlyPromoted = false;
let sC = 0; //square counter
let sendToDatabase; //an object used to pass JSON data of the move made to PHP

//initialize gameboard
let playerColor;
let opponentColor;
if (gameHistory[1] === phpColor) {
    //blackplayer is stored in gameHistory[1]
    playerColor = 'B';
    opponentColor = 'W';
} else {
    playerColor = 'W';
    opponentColor = 'B';
}
let usersTurn; //defined after gamestate is loaded
let flipped;
if (playerColor === 'W') {
    flipped = true;
} else {
    flipped = false;
}
let movesHistory;
if (gameHistory[0] != '') {
    movesHistory = gameHistory[0].split(','); //break the moves into an array
}

let rowCounter = 0;
let columnCounter = 0;
for (i = 0; i < 9; i++) {
    for (x = 0; x < 9; x++) {
        boardSquare[sC] = document.createElement('img'); //create each of the 81 squares as an image in the document
        boardSquare[sC].src = '/public/images/koma/' + komaSet + '/empty.png'; //temporarily set image source
        boardSquare[sC].style.width = '10vw'; //scale to fit board
        boardSquare[sC].style.position = 'absolute';
        boardSquare[sC].style.right = columnCounter + 'vw'; //set the distance from the right side of the board
        boardSquare[sC].style.top = rowCounter + 'vw'; //set the distance from the top
        boardSquare[sC].setAttribute('id', sC);
        boardSquare[sC].setAttribute('onclick', 'pieceClick(Number(this.id))'); //run the piececlick funtion when clicked
        document.getElementById('board').appendChild(boardSquare[sC]); //add the image to the screen

        columnCounter += 10; //add space between the right side for the next piece
        sC++; //move to the next square
    }
    rowCounter += 10; //add space between the top for the next row
    columnCounter = 0; // start back at the right side of the board
    spacer = 0; //reset the spacer for the first piece in the row
}
mochiGoma = [];
mochiGomaAmmount = [];
spacer = 60;

mochiGomaOrder = [
    'MWF',
    'MWKO',
    'MWKEI',
    'MWGIN',
    'MWKIN',
    'MWKAKU',
    'MWHI',
    'MBF',
    'MBKO',
    'MBKEI',
    'MBGIN',
    'MBKIN',
    'MBKAKU',
    'MBHI',
];
if (playerColor === 'B') {
    for (jupiter = 0; jupiter < 2; jupiter++) {
        // initialize the mochigoma on the board
        for (x = 0; x < 7; x++) {
            if (jupiter === 0) {
                //if it's the first time through, we are drawing the white mochigoma
                mochiGoma[x] = document.createElement('img'); //create a new img element for each mochigoma type
                mochiGoma[x].src =
                    '/public/images/koma/' +
                    komaSet +
                    '/' +
                    mochiGomaOrder[x] +
                    '.png';
                mochiGoma[x].setAttribute('id', mochiGomaOrder[x]);
                mochiGoma[x].setAttribute('onClick', 'placePiece(this.id)');
                mochiGoma[x].style.width = '9vw';
                mochiGoma[x].style.position = 'absolute';
                mochiGoma[x].style.right = spacer + 'vw';
                mochiGoma[x].style.top = '0vw';
                document
                    .getElementById('whiteMochigoma')
                    .appendChild(mochiGoma[x]);
                mochiGomaAmmount[x] = document.createElement('img');
                mochiGomaAmmount[x].src = '/public/images/mochiGomaNum2.png';
                mochiGomaAmmount[x].style.width = '3vw';
                mochiGomaAmmount[x].style.position = 'absolute';
                mochiGomaAmmount[x].style.right = spacer + 'vw'; //offset it from the piece
                mochiGomaAmmount[x].style.top = '0vw';
                document
                    .getElementById('whiteMochigoma')
                    .appendChild(mochiGomaAmmount[x]);
            } else {
                //otherwise it's the second time through, so we are drawing the black mochigoma
                mochiGoma[x + 7] = document.createElement('img'); //create a new img element for each mochigoma type
                mochiGoma[x + 7].src =
                    '/public/images/koma/' +
                    komaSet +
                    '/' +
                    mochiGomaOrder[x + 7] +
                    '.png';
                mochiGoma[x + 7].setAttribute('id', mochiGomaOrder[x + 7]);
                mochiGoma[x + 7].setAttribute('onClick', 'placePiece(this.id)');
                mochiGoma[x + 7].style.width = '9vw';
                mochiGoma[x + 7].style.position = 'absolute';
                mochiGoma[x + 7].style.right = spacer + 'vw';
                mochiGoma[x + 7].style.top = '0vw';
                document
                    .getElementById('blackMochigoma')
                    .appendChild(mochiGoma[x + 7]);
                mochiGomaAmmount[x + 7] = document.createElement('img');
                mochiGomaAmmount[x + 7].src =
                    '/public/images/mochiGomaNum2.png';
                mochiGomaAmmount[x + 7].style.width = '3vw';
                mochiGomaAmmount[x + 7].style.position = 'absolute';
                mochiGomaAmmount[x + 7].style.right = spacer + 'vw'; //offset it from the piece
                mochiGomaAmmount[x + 7].style.top = '0vw';
                document
                    .getElementById('blackMochigoma')
                    .appendChild(mochiGomaAmmount[x + 7]);
            }
            spacer -= 10;
        }
        spacer = 60;
    }
} else {
    for (jupiter = 0; jupiter < 2; jupiter++) {
        // initialize the mochigoma on the board
        for (x = 0; x < 7; x++) {
            if (jupiter === 0) {
                //if it's the first time through, we are drawing the white mochigoma
                mochiGoma[x] = document.createElement('img'); //create a new img element for each mochigoma type
                mochiGoma[x].src =
                    '/public/images/koma/' +
                    komaSet +
                    '/' +
                    mochiGomaOrder[x + 7] +
                    '.png';
                mochiGoma[x].setAttribute('id', mochiGomaOrder[x]);
                mochiGoma[x].setAttribute('onClick', 'placePiece(this.id)');
                mochiGoma[x].style.width = '9vw';
                mochiGoma[x].style.position = 'absolute';
                mochiGoma[x].style.right = spacer + 'vw';
                mochiGoma[x].style.top = '0vw'; //draw them at the bottom since the white player is playing
                document
                    .getElementById('blackMochigoma')
                    .appendChild(mochiGoma[x]);
                mochiGomaAmmount[x] = document.createElement('img');
                mochiGomaAmmount[x].src = '/public/images/mochiGomaNum2.png';
                mochiGomaAmmount[x].style.width = '3vw';
                mochiGomaAmmount[x].style.position = 'absolute';
                mochiGomaAmmount[x].style.right = spacer + 'vw'; //offset it from the piece
                mochiGomaAmmount[x].style.top = '0vw';
                document
                    .getElementById('blackMochigoma')
                    .appendChild(mochiGomaAmmount[x]);
            } else {
                //otherwise it's the second time through, so we are drawing the black mochigoma
                mochiGoma[x + 7] = document.createElement('img'); //create a new img element for each mochigoma type
                mochiGoma[x + 7].src =
                    '/public/images/koma/' +
                    komaSet +
                    '/' +
                    mochiGomaOrder[x] +
                    '.png';
                mochiGoma[x + 7].setAttribute('id', mochiGomaOrder[x + 7]);
                mochiGoma[x + 7].setAttribute('onClick', 'placePiece(this.id)');
                mochiGoma[x + 7].style.width = '9vw';
                mochiGoma[x + 7].style.position = 'absolute';
                mochiGoma[x + 7].style.right = spacer + 'vw';
                mochiGoma[x + 7].style.top = '0vw'; //draw them at the top since the white player is playing
                document
                    .getElementById('whiteMochigoma')
                    .appendChild(mochiGoma[x + 7]);
                mochiGomaAmmount[x + 7] = document.createElement('img');
                mochiGomaAmmount[x + 7].src =
                    '/public/images/mochiGomaNum2.png';
                mochiGomaAmmount[x + 7].style.width = '3vw';
                mochiGomaAmmount[x + 7].style.position = 'absolute';
                mochiGomaAmmount[x + 7].style.right = spacer + 'vw'; //offset it from the piece
                mochiGomaAmmount[x + 7].style.top = '0vw';
                document
                    .getElementById('whiteMochigoma')
                    .appendChild(mochiGomaAmmount[x + 7]);
            }
            spacer -= 10;
        }
        spacer = 60;
    }
}
let gameState = [];

function resetGameState() {
    gameState = [
        'WKO',
        'WKEI',
        'WGIN',
        'WKIN',
        'WGYOKU',
        'WKIN',
        'WGIN',
        'WKEI',
        'WKO',
        'empty',
        'WKAKU',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'WHI',
        'empty',
        'WF',
        'WF',
        'WF',
        'WF',
        'WF',
        'WF',
        'WF',
        'WF',
        'WF',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'BF',
        'BF',
        'BF',
        'BF',
        'BF',
        'BF',
        'BF',
        'BF',
        'BF',
        'empty',
        'BHI',
        'empty',
        'empty',
        'empty',
        'empty',
        'empty',
        'BKAKU',
        'empty',
        'BKO',
        'BKEI',
        'BGIN',
        'BKIN',
        'BGYOKU',
        'BKIN',
        'BGIN',
        'BKEI',
        'BKO',
        'empty',
    ];
    // set each square in initial gamestate plus one extra at the end for mochigoma placements
    mochiGomaArray = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    turn = 1;
}

resetGameState();

let tempGameState = [];
loadGameState(1);
realTurn = turn; //needed to make sure that the user can't play when looking at a previous game state
drawBoard();
drawMochigoma();
document.getElementById('resButtons').style.visibility = 'hidden';
if (gameHistory[6] != '3') {
    if (!usersTurn || gameHistory[6] === '3' || gameHistory[6] === '4') {
        //if not the user's turn or the game has ended
        disableAll();
        document.getElementById('resButtons').style.visibility = 'visible';
        if (gameHistory[3].length > 1) {
            //if there is a reservation in the first reservation slot
            document.getElementById('resButton1').src =
                '/public/images/reservation/res_1_green.png';
            //make the button green instead of grey
        }
        if (gameHistory[4].length > 1) {
            //if there is a reservation in the second reservation slot
            document.getElementById('resButton2').src =
                '/public/images/reservation/res_2_green.png';
            //make the button green instead of grey
        }
        if (gameHistory[5].length > 1) {
            //if there is a reservation in the third reservation slot
            document.getElementById('resButton3').src =
                '/public/images/reservation/res_3_green.png';
            //make the button green instead of grey
        }
    }
    if (
        (gameHistory[6] === '4' && gameHistory[7] != gameHistory[8]) ||
        (gameHistory[6] === '5' && gameHistory[7] === gameHistory[8])
    ) {
        //if the game is status 4 (someone was checkmated) and the person who lost is viewing it
        //or if the game status is 5 (someone resigned) and the winner is viewing it
        showGameOver();
    } else {
        //otherwise, check for checkmate
        if (gameHistory[6] === '2' && checkForMate(opponentColor)) {
            endGame(playerColor);
        } else if (checkForMate(playerColor, 'checkForMateUserColor')) {
            //needs to check both players for checkmate
            endGame(opponentColor);
        } else {
            deselectAll();
            selectedPiece = null;
        }
    }
}

highlightLastMove();

function loadGameState(placeCalled) {
    //loads the current game state from the database (slo Shogi v.1)
    if (movesHistory != undefined) {
        for (g = 0; g < movesHistory.length; g += 3) {
            if (Number(movesHistory[g]) > 99) {
                //if the game ended in regination or checkmate (100 or 101)
                break;
            } else {
                if (movesHistory[g] === '81') {
                    //if the piece is a mochigoma
                    let mochigomaPlace = mochiGomaOrder.indexOf(
                        'M' + movesHistory[g + 2],
                    ); //find the place where it is
                    mochiGomaArray[mochigomaPlace]--; //remove a piece from the array

                    gameState[movesHistory[g + 1]] = movesHistory[g + 2]; //move the piece to the new square
                } else {
                    //otherwise, if it's a piece on the board
                    if (gameState[movesHistory[g + 1]].charAt(0) !== 'e') {
                        //if capturing a piece
                        addToMochiGoma(gameState[movesHistory[g + 1]]); //add it to the proper place in mochigoma array
                    }
                    //need to take off the * if there is one (if the piece was newly promoted)
                    if (
                        movesHistory[g + 2].charAt(
                            movesHistory[g + 2].length - 1,
                        ) === '*'
                    ) {
                        movesHistory[g + 2] = movesHistory[g + 2].substring(
                            0,
                            movesHistory[g + 2].length - 1,
                        ); //cut off the last * character
                    }
                    gameState[movesHistory[g + 1]] = movesHistory[g + 2]; //move the piece to the new square
                    gameState[movesHistory[g]] = 'empty'; //make the space where the piece moved from empty
                }
                turn++;
            }
        }
        if (
            movesHistory[movesHistory.length - 1] != 100 &&
            movesHistory[movesHistory.length - 1] != 101
        ) {
            //if the game hasn't ended in resgination (100) or checkmate(101)

            let redSquare1;
            let redSquare2;
            if (playerColor === 'W') {
                //flip the move indicator position if the white player is viewing
                redSquare1 = 80 - movesHistory[movesHistory.length - 3];
                redSquare2 = 80 - movesHistory[movesHistory.length - 2];
            } else {
                redSquare1 = movesHistory[movesHistory.length - 3];
                redSquare2 = movesHistory[movesHistory.length - 2];
            }

            boardSquare[redSquare2].style.background = 'red';
            if (movesHistory[movesHistory.length - 3] != 81) {
                boardSquare[redSquare1].style.background = 'red';
            }
        }
    }
    if (
        (turn % 2 != 0 && playerColor === 'B') ||
        (turn % 2 === 0 && playerColor === 'W')
    ) {
        //set whether or not it is the user's turn
        usersTurn = true;
    } else {
        usersTurn = false;
    }

    if (playerColor === 'W' && placeCalled === 1) {
        //If it is the white player, flip the borard around so they're not playing upside down
        let flipGamestate = [];
        for (f = 0; f < 81; f++) {
            flipGamestate[f] = gameState[80 - f];
        }
        gameState = flipGamestate; //put the flipped gamestate into gameState
    }

    viewTurn = turn - 1; // viewing the current game state
    document.getElementById('undo').style.visibility = 'hidden';

    if (playerColor === 'W') {
        //switch the order of the numbers on the board
        document.getElementById('topNumber1').innerHTML = '1';
        document.getElementById('topNumber2').innerHTML = '2';
        document.getElementById('topNumber3').innerHTML = '3';
        document.getElementById('topNumber4').innerHTML = '4';
        document.getElementById('topNumber5').innerHTML = '5';
        document.getElementById('topNumber6').innerHTML = '6';
        document.getElementById('topNumber7').innerHTML = '7';
        document.getElementById('topNumber8').innerHTML = '8';
        document.getElementById('topNumber9').innerHTML = '9';

        document.getElementById('kanji9').innerHTML = '九';
        document.getElementById('kanji8').innerHTML = '八';
        document.getElementById('kanji7').innerHTML = '七';
        document.getElementById('kanji6').innerHTML = '六';
        document.getElementById('kanji5').innerHTML = '五';
        document.getElementById('kanji4').innerHTML = '四';
        document.getElementById('kanji3').innerHTML = '三';
        document.getElementById('kanji2').innerHTML = '二';
        document.getElementById('kanji1').innerHTML = '一';
    }
}
function highlightLastMove() {
    let searchPoint;
    if (
        movesHistory[movesHistory.length - 1] === 100 ||
        movesHistory[movesHistory.length - 1] === 101
    ) {
        //if the game hasn't ended in resgination (100) or checkmate(101)
        searchPoint = movesHistory.length - 3; //chop off the numbers 100 or 101 that are placed into the array when someone resigns of loses
    } else {
        searchPoint = movesHistory.length;
    }
    let redSquare1;
    let redSquare2;
    if (playerColor === 'W') {
        //flip the move indicator position if the white player is viewing
        redSquare1 = 80 - movesHistory[searchPoint - 3];
        redSquare2 = 80 - movesHistory[searchPoint - 2];
    } else {
        redSquare1 = movesHistory[searchPoint - 3];
        redSquare2 = movesHistory[searchPoint - 2];
    }

    boardSquare[redSquare2].style.background = 'red';
    if (movesHistory[searchPoint - 3] != 81) {
        boardSquare[redSquare1].style.background = 'red';
    }
}
function sendMoveData(thingsToDelete) {
    let tempObject = JSON.parse(sendToDatabase);
    tempObject['delete'] = thingsToDelete; // add the rules about what to delete to the JSON object
    tempObject['color'] = playerColor;
    if (msgSent) {
        tempObject['chatSeen'] = chatSeenNum;
    } else {
        if (seenNotSent) {
            tempObject['chatSeen'] = 3; //everyone has seen
        } else {
            tempObject['chatSeen'] = chatNoChange; // leave it the way it was
        }
    }
    sendToDatabase = JSON.stringify(tempObject); // and convert it back to a string

    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function () {
        // If ajax.readyState is 4, then the connection was successful
        // If ajax.status (the HTTP return code) is 200, the request was successful
        if (ajax.readyState === 4 && ajax.status === 200) {
            // Use ajax.responseText to get the raw response from the server

            console.log(ajax.responseText);
            window.location.reload(); //once the data is sent, reload the page and check for checkmate
        } else {
            console.log('Error: ' + ajax.status); // An error occurred during the request.
        }
    };
    ajax.open('POST', 'send.php', true); //asyncronous
    ajax.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    ajax.send(sendToDatabase); //(sendToDatabase);
}

function resetGame() {
    let confirmreset = confirm('本当にリセットする？');
    if (confirmreset) {
        var ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function () {
            // If ajax.readyState is 4, then the connection was successful
            // If ajax.status (the HTTP return code) is 200, the request was successful
            if (ajax.readyState === 4 && ajax.status === 200) {
                // Use ajax.responseText to get the raw response from the server
                console.log(ajax.responseText);
            } else {
                console.log('Error: ' + ajax.status); // An error occurred during the request.
            }
        };
        let json = JSON.stringify({
            id: currentGameID,
        });
        console.log(json);
        ajax.open('POST', 'reset.php', true); //asyncronous
        ajax.setRequestHeader(
            'Content-Type',
            'application/json; charset=UTF-8',
        );
        ajax.send(json); //(sendToDatabase);
    }
    loadGameState(1);
}
function resign() {
    let confirmresign = confirm('本当に投了しますか？');
    if (confirmresign) {
        var ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function () {
            // If ajax.readyState is 4, then the connection was successful
            // If ajax.status (the HTTP return code) is 200, the request was successful
            if (ajax.readyState === 4 && ajax.status === 200) {
                // Use ajax.responseText to get the raw response from the server
                console.log(ajax.responseText);
                window.location.reload();
            } else {
                console.log('Error: ' + ajax.status); // An error occurred during the request.
            }
        };
        let winnerName;
        let loserName;
        //set the winner and loser by finding the usernames of the players from the gamehistory array
        if (playerColor === 'B') {
            winnerName = gameHistory[2];
            loserName = gameHistory[1];
        } else {
            winnerName = gameHistory[1];
            loserName = gameHistory[2];
        }

        let json = JSON.stringify({
            id: currentGameID,
            winner: winnerName,
            loser: loserName,
        });

        console.log(json);
        ajax.open('POST', 'resign.php', true); //asyncronous
        ajax.setRequestHeader(
            'Content-Type',
            'application/json; charset=UTF-8',
        );
        ajax.send(json); //(sendToDatabase);
    }
}
//Starting formation
function drawBoard() {
    for (i = 0; i < 81; i++) {
        if (playerColor === 'W') {
            if (gameState[i].charAt(0) === 'B') {
                //switch the B with a W for display purposes
                boardSquare[i].src =
                    '/public/images/koma/' +
                    komaSet +
                    '/W' +
                    gameState[i].substr(1, gameState[i].length) +
                    '.png';
            } else if (gameState[i].charAt(0) === 'W') {
                //switch the W with B for display purposes
                boardSquare[i].src =
                    '/public/images/koma/' +
                    komaSet +
                    '/B' +
                    gameState[i].substr(1, gameState[i].length) +
                    '.png';
            } else {
                boardSquare[i].src =
                    '/public/images/koma/' +
                    komaSet +
                    '/' +
                    gameState[i] +
                    '.png'; //empty square
            }
        } else {
            boardSquare[i].src =
                '/public/images/koma/' + komaSet + '/' + gameState[i] + '.png'; //set each of the urls to match the image
        }
    }
}

function addToMochiGoma(gamePiece) {
    let gamePieceColor;
    if (gamePiece.charAt(1) === 'N') {
        //if it's a promoted piece
        gamePiece = gamePiece.replace('N', ''); //remove the N
    }
    if (gamePiece.charAt(0) === 'B') {
        gamePieceColor = 0;
    } else {
        gamePieceColor = 7; //if it's a white piece, start at the 7th array spot
    }
    switch (
        gamePiece.substr(1, gamePiece.length) // return the piece name minus thethe color
    ) {
        case 'F':
            mochiGomaArray[0 + gamePieceColor] += 1; //add a fu to the fu place
            break;

        case 'KO':
            mochiGomaArray[1 + gamePieceColor] += 1; //add a ko to the ko place
            break;

        case 'KEI':
            mochiGomaArray[2 + gamePieceColor] += 1; //add a kei to the kei place
            break;

        case 'GIN':
            mochiGomaArray[3 + gamePieceColor] += 1; //add a gin to the gin place
            break;

        case 'KIN':
            mochiGomaArray[4 + gamePieceColor] += 1; //add a kin to the kin place
            break;

        case 'KAKU':
            mochiGomaArray[5 + gamePieceColor] += 1; //add a kaku to the kaku place
            break;

        case 'HI':
            mochiGomaArray[6 + gamePieceColor] += 1; //add a hi to the hi place
            break;
        default:
            console.log('piece name is incorrect');
            break;
    }
}

function drawMochigoma() {
    let blackOrWhite = 0;
    for (i = 0; i < 8; i += 7) {
        for (x = 0; x < 7; x++) {
            if (mochiGomaArray[x + i] > 1) {
                //if there is/are mochigoma of that type
                mochiGoma[x + i].style.visibility = 'visible';
                mochiGomaAmmount[x + i].style.visibility = 'visible';
                mochiGomaAmmount[x + i].src =
                    '/public/images/mochiGomaNum' +
                    mochiGomaArray[x + i] +
                    '.png'; //make it display the correct number
            } else if (mochiGomaArray[x + i] === 1) {
                mochiGoma[x + i].style.visibility = 'visible'; //show the piece
                mochiGomaAmmount[x + i].style.visibility = 'hidden'; //but no number
            } else {
                mochiGoma[x + i].style.visibility = 'hidden'; //otherwise hide it from view
                mochiGomaAmmount[x + i].style.visibility = 'hidden'; //and hide the number
            }
        }
        blackOrWhite = 1;
    }
}
function pieceClick(id, placeCalled) {
    //first, make sure that the piece cicked is your own
    if (!usersTurn && !justChecking) {
        deselectAll();
    } else if (
        ((turn % 2 === 0 && gameState[id].charAt(0) != 'W') ||
            (turn % 2 !== 0 && gameState[id].charAt(0) != 'B')) &&
        justChecking === false &&
        boardSquare[id].style.background.substr(0, 7) != 'rgb(230'
    ) {
        deselectAll();
        //do nothing
    } else {
        if (justChecking === false) {
            console.log(id);
        }
        if (boardSquare[id].style.background.substr(0, 7) === 'rgb(230') {
            //if the clicked square is highlighted as a possible move
            movePiece(selectedPiece, id);
        } else if (
            !justChecking &&
            selectedPiece !== null &&
            (id === selectedPiece ||
                (id !== selectedPiece &&
                    boardSquare[id].style.background.substr(0, 7) != 'rgb(230'))
        ) {
            //if the same piece is clicked again or another unrelated place is clicked
            deselectAll();
            selectedPiece = null;
        } else {
            //otherwise, highlight the possible moves

            if (justChecking === false) {
                selectedPiece = id; // define the selected piece
                boardSquare[id].style.filter = 'brightness(1.5)'; //highlight the selected piece only if not checking for checkmate
            }

            highlightSquares(showMove(id, gameState[id], placeCalled));
        }
    }
}
function showMove(square, komaType, placeCalled) {
    //this array represents the possible movements the pieces can do
    //the first 8 are the movements to the 8 directions, starting with forward and going clockwise)
    //1 means the piece can only move one space in that direction, 2 means it can move multiple spaces in the direction
    //the 9th spot ([8]) is kei's move right, and the 10th ([9]) is kei's move left
    //1 means black, 2 means white
    let moveDirections = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    let moveFormulas = [-9, -10, -1, 8, 9, 10, 1, -8, 19, 17]; //the position of the move relative to where the piece is
    //the knight's is adjusted by the negative or positive number in the moveDirections array
    let realKomaType = komaType;
    let turnColor = realKomaType.charAt(0);
    if (justChecking) {
        //if just checking, the piece should always be treated like a white koma, unless it is the checkformate function called for the user's own color
        if (placeCalled === 'checkForMateUserColor') {
            komaType = 'B' + komaType.substr(1, komaType.length);
        } else {
            komaType = 'W' + komaType.substr(1, komaType.length);
        }
        // turnColor = "W";
    } else {
        komaType = 'B' + komaType.substr(1, komaType.length);
        /*  if (turn % 2 === 0) {
            turnColor = "W";
        } else {
            turnColor = "B"
        }*/
    }

    switch (komaType) {
        case 'BF':
        case 'WF':
            moveDirections[0] = 1;
            break;
        case 'BKEI':
            moveDirections = [0, 0, 0, 0, 0, 0, 0, 0, -1, -1];
            break; //just added!
        case 'WKEI':
            moveDirections = [0, 0, 0, 0, 0, 0, 0, 0, 1, 1]; //negative will make it go backwards, positive forwards
            break;
        case 'BKO':
            moveDirections[0] = 2;
            break;
        case 'WKO':
            moveDirections[4] = 2;
            break;
        case 'BHI':
        case 'WHI':
            moveDirections = [2, 0, 2, 0, 2, 0, 2, 0, 0, 0];
            break;
        case 'BKAKU':
        case 'WKAKU':
            moveDirections = [0, 2, 0, 2, 0, 2, 0, 2, 0, 0];
            break;
        case 'BGIN':
            moveDirections = [1, 1, 0, 1, 0, 1, 0, 1, 0, 0];
            break;
        case 'WGIN':
            moveDirections = [0, 1, 0, 1, 1, 1, 0, 1, 0, 0];
            break;
        case 'BKIN':
        case 'BNGIN':
        case 'BNKEI':
        case 'BNKO':
        case 'BNF':
            moveDirections = [1, 1, 1, 0, 1, 0, 1, 1, 0, 0];
            break;
        case 'WKIN':
        case 'WNGIN':
        case 'WNKEI':
        case 'WNKO':
        case 'WNF':
            moveDirections = [1, 0, 1, 1, 1, 1, 1, 0, 0, 0];
            break;
        case 'BNHI':
        case 'WNHI':
            moveDirections = [2, 1, 2, 1, 2, 1, 2, 1, 0, 0];
            break;
        case 'BNKAKU':
        case 'WNKAKU':
            moveDirections = [1, 2, 1, 2, 1, 2, 1, 2, 0, 0];
            break;
        case 'BGYOKU':
        case 'WGYOKU':
            moveDirections = [1, 1, 1, 1, 1, 1, 1, 1, 0, 0];
            break;
    }

    //eliminate the illegal moves
    //check if the piece is on an edge
    let onRightEdge = false;
    let onLeftEdge = false;
    let onTopEdge = false;
    let onBottomEdge = false;

    if (board9Row.includes(square)) {
        //fill string with 1 for yes, 0 for no
        onLeftEdge = true;
        moveDirections[5] = 0; //the piece can't move down/left diagonally
        moveDirections[6] = 0; //the piece can't move to the left
        moveDirections[7] = 0; //the piece can't move up/left diagonally
    }
    if (boardTopEdge.includes(square)) {
        onTopEdge = true;
        moveDirections[7] = 0; //the piece can't move up/left diagonally
        moveDirections[0] = 0; //the piece can't move up
        moveDirections[1] = 0; //the piece can't move up/right diagonally
        //figure out how to test the kei position too
    }
    if (board1Row.includes(square)) {
        onRightEdge = true;
        moveDirections[1] = 0; //the piece can't move up/right diagonally
        moveDirections[2] = 0; //the piece can't move right
        moveDirections[3] = 0; //the piece can't move down/right diagonally
    }
    if (boardBottomEdge.includes(square)) {
        onBottomEdge = true;
        moveDirections[3] = 0; //the piece can't move down/right diagonally
        moveDirections[4] = 0; //the piece can't move down
        moveDirections[5] = 0; //the piece can't move down/left diagonally
    }

    //go through each (non-kei) direction that the piece could move and determine how far it can move in that direction
    let isBlocked;
    let moveSquare;
    for (i = 0; i < 8; i++) {
        //if it's possible to move in that direction
        if (moveDirections[i] > 0) {
            //check if the player's own piece is not in the square
            if (gameState[square + moveFormulas[i]].charAt(0) !== turnColor) {
                //if not, add the first square to the move array
                move.push(square + moveFormulas[i]);
                //check if the piece can move just one or multiple squares
                if (moveDirections[i] === 2) {
                    //if it can move multiple squares, continue checking the squares and adding any that are empty
                    //or have an opponent's piece until the edge of the board is reached
                    isBlocked = false;
                    moveSquare = square;
                    while (!isBlocked) {
                        //if the space is empty
                        if (
                            gameState[moveSquare + moveFormulas[i]] === 'empty'
                        ) {
                            move.push(moveSquare + moveFormulas[i]);
                            //if the space has an enemy piece
                        } else if (
                            gameState[moveSquare + moveFormulas[i]].charAt(
                                0,
                            ) !== turnColor
                        ) {
                            //add it to the move array
                            move.push(moveSquare + moveFormulas[i]);
                            isBlocked = true;
                        } else {
                            //if an own piece is in the square, isBlocked = true
                            isBlocked = true;
                        }
                        //start by checking if the square is on the edge or not

                        //figure out which direction to check [-9,-10,-1,+8,+9,+10,+1,-8,19,17]
                        switch (moveFormulas[i]) {
                            case -9: //up
                                if (
                                    boardTopEdge.includes(
                                        moveSquare + moveFormulas[i],
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;

                            case -10: //up/right
                                if (
                                    boardTopEdge.includes(
                                        moveSquare + moveFormulas[i],
                                    ) ||
                                    board1Row.includes(
                                        moveSquare + moveFormulas[i],
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;

                            case -1: //right
                                if (
                                    board1Row.includes(
                                        moveSquare + moveFormulas[i],
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;

                            case 8: //down/right
                                if (
                                    boardBottomEdge.includes(
                                        moveSquare + moveFormulas[i],
                                    ) ||
                                    board1Row.includes(
                                        moveSquare + moveFormulas[i],
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;

                            case 9: //down
                                if (
                                    boardBottomEdge.includes(
                                        moveSquare + moveFormulas[i],
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;

                            case 10: //down/left
                                if (
                                    boardBottomEdge.includes(
                                        moveSquare + moveFormulas[i],
                                    ) ||
                                    board9Row.includes(
                                        moveSquare + moveFormulas[i],
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;

                            case 1: //left
                                if (
                                    board9Row.includes(
                                        moveSquare + moveFormulas[i],
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;

                            case -8: //up/left
                                if (
                                    boardTopEdge.includes(
                                        moveSquare + moveFormulas[i],
                                    ) ||
                                    board9Row.includes(
                                        moveSquare + moveFormulas[i],
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;
                            default:
                                alert(
                                    "there's an error in the switch statement",
                                );
                                isBlocked = true;
                                break;
                        }
                        moveSquare += moveFormulas[i]; //move to the next square in that direction
                    }
                }
            }
        }
    }
    //if the piece is a kei
    if (moveDirections[8] != 0) {
        //if it's moving upwards (it's black)
        if (moveDirections[8] === -1) {
            if (onRightEdge) {
                moveDirections[8] = 0;
            }
            if (onLeftEdge) {
                moveDirections[9] = 0;
            }

            //otherwise it's moving down (it's white)
        } else {
            if (onRightEdge) {
                moveDirections[9] = 0;
            }
            if (onLeftEdge) {
                moveDirections[8] = 0;
            }
        }
        //eliminate the squares if an own piece is in the square
        if (
            gameState[square + moveFormulas[8] * moveDirections[8]].charAt(
                0,
            ) === turnColor
        ) {
            moveDirections[8] = 0;
        }
        if (
            gameState[square + moveFormulas[9] * moveDirections[9]].charAt(
                0,
            ) === turnColor
        ) {
            moveDirections[9] = 0;
        }
        for (i = 8; i < 10; i++) {
            if (moveDirections[i] != 0) {
                move.push(square + moveFormulas[i] * moveDirections[i]);
            }
        }
    }
    //eliminate moves that would put the gyoku in check
    eliminateIllegalMoves(realKomaType.charAt(0), turnColor); //the second color is the fake color(what the function is treating it as)

    //return the array of squares that can be moved to;
    return move;
}
function highlightSquares(highlightArray) {
    if (justChecking === false) {
        for (i = highlightArray.length - 1; i > -1; i--) {
            if (highlightArray[i] !== null) {
                boardSquare[highlightArray[i]].style.background =
                    'rgb(230, 197, 11)'; //highlight each possible square to move into
            }
        }
    }
}

function movePiece(from, id) {
    gameState[82] = gameState[id]; //a temporary placeholder for the clicked place
    if (from < 81) {
        //if it's other than the mochigoma
        //see if piece can promote
        if (
            gameState[from].charAt(1) !== 'N' && //if the piece is not promoted yet
            gameState[from].substr(1, 3) !== 'KIN' && //and it isn't a kin or gyoku
            gameState[from].substr(1, 5) !== 'GYOKU' &&
            (id < 27 || // or if it is an odd turn and the piece will move into the third row or less
                from < 27)
        ) {
            //or the piece is already within the first 3 rows

            promotePiece(from, id);
        } else {
            movePiecePt2(from, id);
        }
    } else {
        movePiecePt2(from, id);
    }
}
function movePiecePt2(from, id) {
    let isMochiGoma;
    let moveFromSend;
    let moveToSend;

    if (gameState[id].charAt(0) !== 'e') {
        //if capturing a piece
        addToMochiGoma(gameState[id]);
    }
    if (from === 81) {
        //if it is a mochigoma
        let mochigomaPlace = mochiGomaOrder.indexOf('M' + gameState[from]); //find the place where it is
        mochiGomaArray[mochigomaPlace]--; //remove a piece from the array
        isMochiGoma = gameState[81];
    }

    let tempMoveForGameHistory;
    if (turn === 1) {
        //on the first turn, we don't want to start by sending a comma in the data
        sendToDatabase = JSON.stringify({
            newmoves:
                from.toString() + ',' + id.toString() + ',' + gameState[from],
            gameId: currentGameID,
            turn: turn,
        }); //make the move into JSON object

        //this is for the forward and back buttons
        tempMoveForGameHistory =
            from.toString() + ',' + id.toString() + ',' + gameState[from];
    } else {
        //otherwise, check if it is white and flip the move if it is

        if (turn % 2 === 0) {
            if (from === 81) {
                moveFromSend = 81; //mochi goma will be 81 no matter what
            } else {
                moveFromSend = 80 - from;
            }
            moveToSend = 80 - id;
        } else {
            moveFromSend = from;
            moveToSend = id;
        }
        //set the name of the piece to send
        let gamePieceName;
        if (newlyPromoted) {
            gamePieceName = gameState[from] + '*'; //add an asterisk to the piecename in the gamerecord if it is newly promoted
        } else {
            gamePieceName = gameState[from];
        }
        newlyPromoted = false; //reset to false (probably not important since the page will be reloaded anyway, but just in case that changes later...)
        //also, start by sending a comma to separate the move from the last one stored
        sendToDatabase = JSON.stringify({
            newmoves:
                ',' +
                moveFromSend.toString() +
                ',' +
                moveToSend.toString() +
                ',' +
                gamePieceName,
            gameId: currentGameID,
            turn: turn,
        }); //make the move into JSON object

        //for Forward and Back buttons
        tempMoveForGameHistory =
            ',' +
            moveFromSend.toString() +
            ',' +
            moveToSend.toString() +
            ',' +
            gameState[from];
    }

    gameState[id] = gameState[from]; //move the piece to the new square
    gameState[from] = 'empty'; //make the space where the piece moved from empty

    drawBoard();

    drawMochigoma();

    disableAll();
    //only the piece that was moved can be clicked, and it will trigger the confirmMove function and pass it the needed variables
    boardSquare[id].setAttribute(
        'onclick',
        'confirmMove(' +
            moveFromSend +
            ',' +
            moveToSend +
            ", '" +
            tempMoveForGameHistory +
            "'," +
            id +
            ')',
    );
    document.getElementById('undo').style.visibility = 'visible';
    document.getElementById('playerPrompt').innerHTML =
        '再度クリックで承認｜Click again to confirm';
}
function confirmMove(
    moveFromSend,
    moveToSend,
    tempMoveForGameHistory,
    currentPlace,
) {
    turn++; //increase the turn counter

    gameHistory[0] += tempMoveForGameHistory; //for forward and back buttons
    movesHistory = gameHistory[0].split(','); //break the moves into an array

    if (turn > 1) {
        handleReservations(moveFromSend, moveToSend, gameState[currentPlace]);
    } else {
        sendMoveData('skip');
    }
}

function handleReservations(movedFrom, movedTo, movedPiece) {
    let reservationArrays = [];

    reservationArrays[0] = gameHistory[3].split(';'); //get the reserved moves and put each sequence into its own space in an array
    reservationArrays[1] = gameHistory[4].split(';');
    reservationArrays[2] = gameHistory[5].split(';');
    console.log(reservationArrays[0]); //REMOVE
    console.log(reservationArrays[1]); //REMOVE
    console.log(reservationArrays[2]); //REMOVE

    let triggered = false; //keeps track of whether or not a reserved move has been found yet or not
    let resToDelete = '';
    for (i = 0; i < 3; i++) {
        //go through each of the reservation arrays
        if (reservationArrays[i].length > 1) {
            //if the reserved spot isn't empty

            if (!triggered) {
                //if another reservation hasn't been triggered yet

                let reservedSequence = reservationArrays[i][1].split(',');
                if (
                    reservedSequence[0] === movedFrom &&
                    reservedSequence[1] === movedTo &&
                    reservedSequence[2] === movedPiece
                ) {
                    //if the reservation perfectly macthes the move made
                    alert('予約手があります');
                    //add the user's move and the reserved move to the sendToDatabase string
                    sendToDatabase = JSON.stringify({
                        newmoves:
                            ',' +
                            movedFrom +
                            ',' +
                            movedTo +
                            ',' +
                            movedPiece +
                            ',' +
                            reservationArrays[i][2],
                        gameId: currentGameID,
                        turn: turn,
                    });
                    triggered = true;
                    moveTriggered = reservationArrays[i][2];
                    resToDelete += String(i + 1); //add the reservation array the the list of reservation arrays to chop
                }
            } else {
                if (reservationArrays[i][2] === moveTriggered) {
                    //if the first part of another reserved sequence is the same as one that was already trigged
                    resToDelete += String(i + 1); //add the reservation array the the list of reservation arrays to chop
                }
            }
        }
    }

    if (triggered) {
        //if a reservation was triggered, sendMoveData Will only chop off the first move of the array from any that matched
        sendMoveData(resToDelete);
    } else {
        //otherwise, all will be cleared
        sendMoveData('skip');
    }
}

function disableAll() {
    for (i = 0; i < 81; i++) {
        boardSquare[i].setAttribute('onClick', null);
    }
    for (x = 0; x < 14; x++) {
        mochiGoma[x].setAttribute('onClick', null);
    }
    if (gameHistory[6] === '3') {
        //if the game is finished
        document.getElementById('resignButton').style.visibility = 'hidden';
    }
}

function promotePiece(from, id) {
    document.getElementById('pNoP').style.left =
        '' + boardSquare[id].offsetLeft + 'px';
    document.getElementById('pNoP').style.top =
        '' + boardSquare[id].offsetTop + 'px';

    switch (gameState[from].substr(1, gameState[from].length)) {
        case 'KO':
        case 'KEI':
        case 'F':
            if (id < 9) {
                doPromote(from, id);
            } else {
                document.getElementById('pNoP').style.visibility = 'visible';
                document
                    .getElementById('promote')
                    .setAttribute(
                        'src',
                        '/public/images/koma/' +
                            komaSet +
                            '/BN' +
                            gameState[from].substr(1, gameState[from].length) +
                            '.png',
                    );
                document
                    .getElementById('dontPromote')
                    .setAttribute(
                        'src',
                        '/public/images/koma/' +
                            komaSet +
                            '/B' +
                            gameState[from].substr(1, gameState[from].length) +
                            '.png',
                    );
                document
                    .getElementById('promote')
                    .setAttribute(
                        'onclick',
                        'doPromote(' + from + ',' + id + ')',
                    );
                document
                    .getElementById('dontPromote')
                    .setAttribute(
                        'onclick',
                        'dontPromote(' + from + ',' + id + ')',
                    );

                disableAll();
            }
            break;
        default:
            document.getElementById('pNoP').style.visibility = 'visible';
            document
                .getElementById('promote')
                .setAttribute(
                    'src',
                    '/public/images/koma/' +
                        komaSet +
                        '/BN' +
                        gameState[from].substr(1, gameState[from].length) +
                        '.png',
                );
            document
                .getElementById('dontPromote')
                .setAttribute(
                    'src',
                    '/public/images/koma/' +
                        komaSet +
                        '/B' +
                        gameState[from].substr(1, gameState[from].length) +
                        '.png',
                );
            document
                .getElementById('promote')
                .setAttribute('onclick', 'doPromote(' + from + ',' + id + ')');
            document
                .getElementById('dontPromote')
                .setAttribute(
                    'onclick',
                    'dontPromote(' + from + ',' + id + ')',
                );
            disableAll();
            break;
    }

    return;
}
function doPromote(from, id) {
    gameState[from] =
        gameState[from].substr(0, 1) + 'N' + gameState[from].substr(1, 4); // add an N for nari after the first character
    newlyPromoted = true;
    document.getElementById('pNoP').style.visibility = 'hidden';
    movePiecePt2(from, id);
}
function dontPromote(from, id) {
    document.getElementById('pNoP').style.visibility = 'hidden';
    movePiecePt2(from, id);
}

function deselectAll() {
    for (i = 0; i < 81; i++) {
        //cycle through every square and remove the background highlight color
        boardSquare[i].style.background = 'none';
        boardSquare[i].style.filter = 'none';
    }
    for (i = 0; i < 14; i++) {
        mochiGoma[i].style.filter = 'none';
    }
    move = [];
    isCheck = null;
    checkingPieces = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
}

function placePiece(piece) {
    if (
        (mochiGoma[mochiGomaOrder.indexOf(piece)].style.filter ===
            'brightness(1.5)' &&
            justChecking === false) ||
        piece.charAt(1) != playerColor
    ) {
        //if the currently selected piece is clicked again
        deselectAll();
        mochiGomaAlreadySelected = false;
        selectedPiece = null;
    } else if (mochiGomaAlreadySelected) {
        //if a mochigoma is already selected, this makes sure that multiple pieces can't be highlighted at the same time
        deselectAll();
        mochiGomaAlreadySelected = false;
        selectedPiece = null;
    } else {
        selectedPiece = 81; //set selected piece to number outside of the board
        gameState[81] = piece.substr(1, piece.length); //put the piece in the 81st spot of the gameState array
        mochiGomaAlreadySelected = true;
        let startingPlace = 0;
        let endAfter;
        let MGColor = piece.charAt(1);

        if (justChecking === false) {
            let mochigomaPlace = mochiGomaOrder.indexOf(
                'M' + gameState[selectedPiece],
            ); //find the place where it is
            mochiGoma[mochigomaPlace].style.filter = 'brightness(1.5)'; //highlight the selected piece
        }

        switch (
            piece.substr(2, piece.length) //fu have special rules about place ment
        ) {
            case 'F':
                let possibleFuRows = [0, 0, 0, 0, 0, 0, 0, 0, 0];
                let fuStartingPlace;
                for (i = 0; i < 9; i++) {
                    let yesNoFu = false;
                    for (x = 0; x < 9; x++) {
                        if (gameState[allBoardRows[i][x]] === MGColor + 'F') {
                            //if there's already a fu in that space
                            yesNoFu = true; //set the variable to true so that the row won't be included
                        }
                    }
                    if (yesNoFu === false) {
                        //if there is no other fu in the row
                        possibleFuRows[i] = 1;
                    } else {
                        possibleFuRows[i] = 0; //otherwise there is a fu already, so it is set to 0
                    }
                }
                fuStartingPlace = 1;

                for (i = 0; i < 9; i++) {
                    //go through to find all the possible rows again
                    if (possibleFuRows[i] === 1) {
                        for (
                            x = fuStartingPlace;
                            x < fuStartingPlace + 8;
                            x++
                        ) {
                            if (gameState[allBoardRows[i][x]] === 'empty') {
                                move.push(allBoardRows[i][x]); //add all the empty spaces to the move array
                            }
                        }
                    }
                }
                break;

            case 'KO': //Ko can't be placed on last row
                endAfter = 72; //count for 72 squares (all but the last row)
                startingPlace = 9;

                for (i = startingPlace; i < startingPlace + endAfter; i++) {
                    //cycle through each square in the board that is possible for that color
                    if (gameState[i] === 'empty') {
                        move.push(i); //add all empty squares to the list of possible moves
                    }
                }
                break;
            case 'KEI': //kei can't be placed in the last 2 rows since they couldn't move
                startingPlace = 18;
                endAfter = 63;

                for (i = startingPlace; i < startingPlace + endAfter; i++) {
                    //cycle through each square in the board that is possible for that color
                    if (gameState[i] === 'empty') {
                        move.push(i); //add all empty squares to the list of possible moves
                    }
                }
                break;

            default:
                for (i = 0; i < 81; i++) {
                    if (gameState[i] === 'empty') {
                        move.push(i); //add all empty squares to the list of possible moves
                    }
                }
        }

        eliminateIllegalMoves(MGColor, MGColor); //will remove all moves from move array that would result in check to own gyoku

        if (justChecking === false) {
            for (i = move.length - 1; i > -1; i--) {
                if (move[i] !== null) {
                    boardSquare[move[i]].style.background = 'rgb(230, 197, 11)'; //highlight each possible square to move into
                }
            }
        }
    }
}
function removeMG(gamePiece) {
    let gamePieceColor;
    if (gamePiece.charAt(1) === 'N') {
        //if it's a promoted piece
        gamePiece = gamePiece.replace('N', ''); //remove the N
    }
    if (gamePiece.charAt(0) === 'B') {
        gamePieceColor = 0;
    } else {
        gamePieceColor = 7; //if it's a white piece, start at the 7th array spot
    }
    switch (
        gamePiece.substr(1, gamePiece.length) // return the piece name minus thethe color
    ) {
        case 'F':
            mochiGomaArray[0 + gamePieceColor] -= 1; //add a fu to the fu place
            break;

        case 'KO':
            mochiGomaArray[1 + gamePieceColor] -= 1; //add a ko to the ko place
            break;

        case 'KEI':
            mochiGomaArray[2 + gamePieceColor] -= 1; //add a kei to the kei place
            break;

        case 'GIN':
            mochiGomaArray[3 + gamePieceColor] -= 1; //add a gin to the gin place
            break;

        case 'KIN':
            mochiGomaArray[4 + gamePieceColor] -= 1; //add a kin to the kin place
            break;

        case 'KAKU':
            mochiGomaArray[5 + gamePieceColor] -= 1; //add a kaku to the kaku place
            break;

        case 'HI':
            mochiGomaArray[6 + gamePieceColor] -= 1; //add a hi to the hi place
            break;
        default:
            console.log('piece name is incorrect');
            break;
    }
}

// 15   8    9
//   7  0  1
// 14 6 玉 2 10    check each potential checking square / angle in this order and return an array of all checking pieces' squares
//    5 4  3
// 13   12   11
//   16   17     knights
function checkForCheck(gyokuColor) {
    let gyokuPosition = gameState.indexOf(gyokuColor + 'GYOKU'); //get the location of the gyoku being checked
    let gyokuForward;
    let gyokuOnTopRow;
    let gyokuOnBottomRow;
    let gyokuOnRightColumn;
    let gyokuOnLeftColumn;

    if ((gyokuColor === 'B' && !flipped) || (gyokuColor === 'W' && flipped)) {
        gyokuForward = -1; //black ou moves negatively to go forward
        //this will check to see if the gyoku is on any of the edges of the board and set the corresponding spot
        //in the checkingPieces array to 2, which will prevent it from being checked in the next part

        if (boardTopEdge.includes(gyokuPosition)) {
            //if on top row, there can't be any pieces above it
            checkingPieces[0] = 2;
            checkingPieces[1] = 2;
            checkingPieces[7] = 2;
            gyokuOnTopRow = true;
        }
        if (boardBottomEdge.includes(gyokuPosition)) {
            // if on bottom row, there can't be any pieces below it
            checkingPieces[5] = 2;
            checkingPieces[4] = 2;
            checkingPieces[3] = 2;
            gyokuOnBottomRow = true;
        }
        if (board1Row.includes(gyokuPosition)) {
            //no pieces to right side
            checkingPieces[1] = 2;
            checkingPieces[2] = 2;
            checkingPieces[3] = 2;
            checkingPieces[17] = 2;
            gyokuOnRightColumn = true;
        }
        if (board9Row.includes(gyokuPosition)) {
            //none to the left
            checkingPieces[5] = 2;
            checkingPieces[6] = 2;
            checkingPieces[7] = 2;
            checkingPieces[16] = 2;
            gyokuOnLeftColumn = true;
        }
    } else {
        gyokuForward = 1; //white ou moves positively to go forward

        if (boardTopEdge.includes(gyokuPosition)) {
            //if on bottom row, there can't be any pieces below it
            checkingPieces[5] = 2;
            checkingPieces[4] = 2;
            checkingPieces[3] = 2;
            gyokuOnBottomRow = true;
        }
        if (boardBottomEdge.includes(gyokuPosition)) {
            //if on top row, there can't be any pieces above it
            checkingPieces[0] = 2;
            checkingPieces[1] = 2;
            checkingPieces[7] = 2;
            gyokuOnTopRow = true;
        }
        if (board1Row.includes(gyokuPosition)) {
            //none to the left
            checkingPieces[5] = 2;
            checkingPieces[6] = 2;
            checkingPieces[7] = 2;
            checkingPieces[16] = 2;
            gyokuOnLeftColumn = true;
        }
        if (board9Row.includes(gyokuPosition)) {
            //none to the right
            checkingPieces[1] = 2;
            checkingPieces[2] = 2;
            checkingPieces[3] = 2;
            checkingPieces[17] = 2;
            gyokuOnRightColumn = true;
        }
    }

    //check the square in front of the gyoku
    if (
        checkingPieces[0] !== 2 &&
        gameState[gyokuPosition + gyokuForward * 9].charAt(0) != 'e' &&
        gameState[gyokuPosition + gyokuForward * 9].charAt(0) != gyokuColor
    ) {
        // if it's an enemy piece
        switch (
            gameState[gyokuPosition + gyokuForward * 9].substr(
                1,
                gameState[gyokuPosition + gyokuForward * 9].length,
            ) //check the square right in front of the gyoku
        ) {
            case 'mpty':
            case 'KEI':
            case 'KAKU':
                checkingPieces[0] = 0; //none of these pieces can check the gyoku fron the front
                break;
            default:
                checkingPieces[0] = gyokuPosition + gyokuForward * 9; //the square contains a checking piece
                //so it's square is added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[0] = 0; //own piece is there, so it's not check
    }

    //check the forward right diagonal square

    if (
        checkingPieces[1] !== 2 &&
        gameState[gyokuPosition + gyokuForward * 10].charAt(0) != 'e' &&
        gameState[gyokuPosition + gyokuForward * 10].charAt(0) != gyokuColor
    ) {
        switch (
            gameState[gyokuPosition + gyokuForward * 10].substr(
                1,
                gameState[gyokuPosition + gyokuForward * 10].length,
            )
        ) {
            case 'F':
            case 'KO':
            case 'KEI':
            case 'HI':
            case 'mpty':
                checkingPieces[1] = 0; //none of these pieces can check the gyoku fron the side
                break;
            default:
                checkingPieces[1] = gyokuPosition + gyokuForward * 10;
                //added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[1] = 0; // own piece is in the square, so no check
    }

    //check the square to the Gyoku's right
    if (
        checkingPieces[2] !== 2 &&
        gameState[gyokuPosition + gyokuForward * 1].charAt(0) != 'e' &&
        gameState[gyokuPosition + gyokuForward * 1].charAt(0) != gyokuColor
    ) {
        switch (
            gameState[gyokuPosition + gyokuForward * 1].substr(
                1,
                gameState[gyokuPosition + gyokuForward * 1].length,
            )
        ) {
            case 'F':
            case 'KO':
            case 'GIN':
            case 'KAKU':
            case 'KEI':
            case 'mpty':
                checkingPieces[2] = 0; //none of these pieces can check the gyoku fron the side
                break;
            default:
                checkingPieces[2] = gyokuPosition + gyokuForward * 1;
                //added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[2] = 0; // own piece is in the square, so no check
    }

    //check the back right diagonal square

    if (
        checkingPieces[3] !== 2 &&
        gameState[gyokuPosition + gyokuForward * -8].charAt(0) != 'e' &&
        gameState[gyokuPosition + gyokuForward * -8].charAt(0) != gyokuColor
    ) {
        switch (
            gameState[gyokuPosition + gyokuForward * -8].substr(
                1,
                gameState[gyokuPosition + gyokuForward * -8].length,
            )
        ) {
            case 'F':
            case 'KO':
            case 'KIN':
            case 'KEI':
            case 'HI':
            case 'NF':
            case 'NGIN':
            case 'NKEI':
            case 'NKO':
            case 'mpty':
                checkingPieces[3] = 0; //none of these pieces can check the gyoku fron the back diagonal
                break;
            default:
                checkingPieces[3] = gyokuPosition + gyokuForward * -8;
                //added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[3] = 0; // own piece is in the square, so no check
    }

    //check the square behind the gyoku
    if (
        checkingPieces[4] !== 2 &&
        gameState[gyokuPosition + gyokuForward * -9].charAt(0) != 'e' &&
        gameState[gyokuPosition + gyokuForward * -9].charAt(0) != gyokuColor
    ) {
        // if it's an enemy piece
        switch (
            gameState[gyokuPosition + gyokuForward * -9].substr(
                1,
                gameState[gyokuPosition + gyokuForward * -9].length,
            ) //check the square right in front of the gyoku
        ) {
            case 'mpty':
            case 'KEI':
            case 'GIN':
            case 'KO':
            case 'FU':
            case 'KAKU':
                checkingPieces[4] = 0; //none of these pieces can check the gyoku fron the front
                break;
            default:
                checkingPieces[4] = gyokuPosition + gyokuForward * -9; //the square contains a checking piece
                //so it's square is added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[4] = 0; //own piece is there, so it's not check
    }
    //check the back left diagonal square

    if (
        checkingPieces[5] !== 2 &&
        gameState[gyokuPosition + gyokuForward * -10].charAt(0) != 'e' &&
        gameState[gyokuPosition + gyokuForward * -10].charAt(0) != gyokuColor
    ) {
        switch (
            gameState[gyokuPosition + gyokuForward * -10].substr(
                1,
                gameState[gyokuPosition + gyokuForward * -10].length,
            )
        ) {
            case 'F':
            case 'KO':
            case 'KIN':
            case 'KEI':
            case 'HI':
            case 'NF':
            case 'NGIN':
            case 'NKEI':
            case 'NKO':
            case 'mpty':
                checkingPieces[5] = 0; //none of these pieces can check the gyoku fron the back diagonal
                break;
            default:
                checkingPieces[5] = gyokuPosition + gyokuForward * -10;
                //added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[5] = 0; // own piece is in the square, so no check
    }

    //check the square to the Gyoku's left
    if (
        checkingPieces[6] !== 2 &&
        gameState[gyokuPosition + gyokuForward * -1].charAt(0) != 'e' &&
        gameState[gyokuPosition + gyokuForward * -1].charAt(0) != gyokuColor
    ) {
        switch (
            gameState[gyokuPosition + gyokuForward * -1].substr(
                1,
                gameState[gyokuPosition + gyokuForward * -1].length,
            )
        ) {
            case 'F':
            case 'KO':
            case 'GIN':
            case 'KAKU':
            case 'KEI':
            case 'mpty':
                checkingPieces[6] = 0; //none of these pieces can check the gyoku fron the side
                break;
            default:
                checkingPieces[6] = gyokuPosition + gyokuForward * -1;
                //added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[6] = 0; // own piece is in the square, so no check
    }

    //check the forward left diagonal square

    if (
        checkingPieces[7] !== 2 &&
        gameState[gyokuPosition + gyokuForward * 8].charAt(0) != 'e' &&
        gameState[gyokuPosition + gyokuForward * 8].charAt(0) != gyokuColor
    ) {
        switch (
            gameState[gyokuPosition + gyokuForward * 8].substr(
                1,
                gameState[gyokuPosition + gyokuForward * 8].length,
            )
        ) {
            case 'F':
            case 'KO':
            case 'KEI':
            case 'HI':
            case 'mpty':
                checkingPieces[7] = 0; //none of these pieces can check the gyoku fron the side
                break;
            default:
                checkingPieces[7] = gyokuPosition + gyokuForward * 8;
                //added to the array of checking pieces
                isCheck = gyokuColor;
                break;
        }
    } else {
        checkingPieces[7] = 0; // own piece is in the square, so no check
    }

    // check the forward row
    let checkingPosition = gyokuPosition + gyokuForward * 9;
    let pieceBlocking = false;
    while (pieceBlocking === false) {
        //check the forward row
        if (checkingPosition < 81 && checkingPosition > -1) {
            //if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) {
                //if it is an enemy piece or empty
                switch (
                    gameState[checkingPosition].substr(
                        1,
                        gameState[checkingPosition].length,
                    )
                ) {
                    case 'KO':
                    case 'HI':
                    case 'NHI':
                        checkingPieces[8] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case 'mpty':
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[8] = 0; //none of the other pieces can check the gyoku from a distance
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[8] = 0; // own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[8] = 0; //no checking pieces in the row since it went all the way to the edge
        }
        checkingPosition += 9 * gyokuForward; //increment the counter
    }
    pieceBlocking = false; //reset piece blocking tracker

    //check the forward right diagonal
    if (gyokuOnRightColumn) {
        pieceBlocking = true;
        checkingPieces[9] = 0;
    } else {
        pieceBlocking = false;
    }
    checkingPosition = gyokuPosition + gyokuForward * 10;
    while (pieceBlocking === false) {
        //check the forward row
        if (checkingPosition < 81 && checkingPosition > -1) {
            //if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) {
                //if it is an enemy piece or empty
                switch (
                    gameState[checkingPosition].substr(
                        1,
                        gameState[checkingPosition].length,
                    )
                ) {
                    case 'KAKU':
                    case 'NKAKU':
                        checkingPieces[9] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case 'mpty':
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[9] = 0; //none of the other pieces can check the gyoku from a distance
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[9] = 0; // own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[9] = 0; //no checking pieces in the row since it went all the way to the edge
        }
        if (
            board1Row.includes(checkingPosition) ||
            board9Row.includes(checkingPosition)
        ) {
            //if the last square checked was on the edge of the board
            pieceBlocking = true;
        } else {
            checkingPosition += 10 * gyokuForward; //increment the counter
        }
    }
    pieceBlocking = false; //reset piece blocking tracker

    //check the right row
    checkingPosition = gyokuPosition + gyokuForward * 1;
    if (
        (gyokuColor === 'B' && board1Row.includes(checkingPosition)) || //if it is black and on right edge
        (gyokuForward === 'W' && board9Row.includes(checkingPosition))
    ) {
        //or white and on left edge
        pieceBlocking = true; //skip next section (it can't move anywhere, anyway)
        checkingPieces[10] = 0;
    } else {
        pieceBlocking = false;
    }

    while (pieceBlocking === false) {
        if (checkingPosition < 81 && checkingPosition > -1) {
            //if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) {
                //if it is an enemy piece or empty
                switch (
                    gameState[checkingPosition].substr(
                        1,
                        gameState[checkingPosition].length,
                    )
                ) {
                    case 'HI':
                    case 'NHI':
                        checkingPieces[10] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case 'mpty':
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[10] = 0; //none of the other pieces can check the gyoku from a distance
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[10] = 0; // own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[10] = 0; //no checking pieces in the row since it went all the way to the edge
        }

        if (
            board1Row.includes(checkingPosition) ||
            board9Row.includes(checkingPosition)
        ) {
            //if reached the edge of the board
            pieceBlocking = true;
            if (checkingPieces[10] === undefined) {
                checkingPieces[10] = 0; //no checking pieces in the row since it went all the way to the edge
            }
        }
        checkingPosition += 1 * gyokuForward; //increment the counter
    }
    pieceBlocking = false; //reset piece blocking tracker

    //check the back right diagonal
    if (gyokuOnRightColumn) {
        pieceBlocking = true;
        checkingPieces[11] = 0;
    } else {
        pieceBlocking = false;
    }

    checkingPosition = gyokuPosition + gyokuForward * -8;
    while (pieceBlocking === false) {
        //check the forward row
        if (checkingPosition < 81 && checkingPosition > -1) {
            //if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) {
                //if it is an enemy piece or empty
                switch (
                    gameState[checkingPosition].substr(
                        1,
                        gameState[checkingPosition].length,
                    )
                ) {
                    case 'KAKU':
                    case 'NKAKU':
                        checkingPieces[11] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case 'mpty':
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[11] = 0; //none of the other pieces can check the gyoku from a distance
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[11] = 0; // own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[11] = 0; //no checking pieces in the row since it went all the way to the edge
        }
        if (
            board1Row.includes(checkingPosition) ||
            board9Row.includes(checkingPosition)
        ) {
            //if the last square checked was on the edge of the board
            pieceBlocking = true;
        } else {
            checkingPosition += -8 * gyokuForward; //increment the counter
        }
    }
    pieceBlocking = false; //reset piece blocking tracker

    // check the back row
    checkingPosition = gyokuPosition + gyokuForward * -9;
    while (pieceBlocking === false) {
        if (checkingPosition < 81 && checkingPosition > -1) {
            //if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) {
                //if it is an enemy piece or empty
                switch (
                    gameState[checkingPosition].substr(
                        1,
                        gameState[checkingPosition].length,
                    )
                ) {
                    case 'KO':
                    case 'HI':
                    case 'NHI':
                        checkingPieces[12] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case 'mpty':
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[12] = 0; //none of the other pieces can check the gyoku from a distance
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[12] = 0; // own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[12] = 0; //no checking pieces in the row since it went all the way to the edge
        }
        checkingPosition += -9 * gyokuForward; //increment the counter
    }
    pieceBlocking = false; //reset piece blocking tracker

    //check the back left diagonal
    if (gyokuOnLeftColumn) {
        pieceBlocking = true;
        checkingPieces[13] = 0;
    } else {
        pieceBlocking = false;
    }
    checkingPosition = gyokuPosition + gyokuForward * -10;
    while (pieceBlocking === false) {
        //check the forward row
        if (checkingPosition < 81 && checkingPosition > -1) {
            //if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) {
                //if it is an enemy piece or empty
                switch (
                    gameState[checkingPosition].substr(
                        1,
                        gameState[checkingPosition].length,
                    )
                ) {
                    case 'KAKU':
                    case 'NKAKU':
                        checkingPieces[13] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case 'mpty':
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[13] = 0; //none of the other pieces can check the gyoku from a distance
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[13] = 0; // own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[13] = 0; //no checking pieces in the row since it went all the way to the edge
        }
        if (
            board1Row.includes(checkingPosition) ||
            board9Row.includes(checkingPosition)
        ) {
            //if the last square checked was on the edge of the board
            pieceBlocking = true;
        } else {
            checkingPosition += -10 * gyokuForward; //increment the counter
        }
    }
    pieceBlocking = false; //reset piece blocking tracker

    //check the left row
    checkingPosition = gyokuPosition + gyokuForward * -1;
    if (
        (gyokuColor === 'W' && board1Row.includes(checkingPosition)) || //if it is black and on right edge
        (gyokuForward === 'B' && board9Row.includes(checkingPosition))
    ) {
        //or white and on left edge
        pieceBlocking = true; //skip next section (it can't move anywhere, anyway)
        checkingPieces[10] = 0;
    } else {
        pieceBlocking = false;
    }

    while (pieceBlocking === false) {
        if (checkingPosition < 81 && checkingPosition > -1) {
            //if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) {
                //if it is an enemy piece or empty
                switch (
                    gameState[checkingPosition].substr(
                        1,
                        gameState[checkingPosition].length,
                    )
                ) {
                    case 'HI':
                    case 'NHI':
                        checkingPieces[14] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case 'mpty':
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[14] = 0; //none of the other pieces can check the gyoku from a distance
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[14] = 0; // own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[14] = 0; //no checking pieces in the row since it went all the way to the edge
        }

        if (
            board1Row.includes(checkingPosition) ||
            board9Row.includes(checkingPosition)
        ) {
            //if reached the edge of the board
            pieceBlocking = true;
            if (checkingPieces[14] === undefined) {
                //it will be defined if another piece is in the square
                checkingPieces[14] = 0; //no checking pieces in the row since it went all the way to the edge
            }
        }
        checkingPosition += -1 * gyokuForward; //increment the counter
    }
    pieceBlocking = false; //reset piece blocking tracker

    //check the forward left diagonal
    if (gyokuOnLeftColumn) {
        pieceBlocking = true;
        checkingPieces[15] = 0;
    } else {
        pieceBlocking = false;
    }
    checkingPosition = gyokuPosition + gyokuForward * 8;
    while (pieceBlocking === false) {
        //check the forward row
        if (checkingPosition < 81 && checkingPosition > -1) {
            //if square is on the board

            if (gameState[checkingPosition].charAt(0) != gyokuColor) {
                //if it is an enemy piece or empty
                switch (
                    gameState[checkingPosition].substr(
                        1,
                        gameState[checkingPosition].length,
                    )
                ) {
                    case 'KAKU':
                    case 'NKAKU':
                        checkingPieces[15] = checkingPosition;
                        //added to the array of checking pieces
                        isCheck = gyokuColor;
                        pieceBlocking = true;
                        break;
                    case 'mpty':
                        pieceBlocking = false;
                        break;
                    default:
                        checkingPieces[15] = 0; //none of the other pieces can check the gyoku from a distance
                        pieceBlocking = true;
                        break;
                }
            } else {
                checkingPieces[15] = 0; // own piece is in the square, so no check
                pieceBlocking = true;
            }
        } else {
            pieceBlocking = true; //it's off the board
            checkingPieces[15] = 0; //no checking pieces in the row since it went all the way to the edge
        }
        if (
            board1Row.includes(checkingPosition) ||
            board9Row.includes(checkingPosition)
        ) {
            //if the last square checked was on the edge of the board
            pieceBlocking = true;
        } else {
            checkingPosition += 8 * gyokuForward; //increment the counter
        }
    }
    //check the left side keima spot
    //needs to be fixed to account for checking a flipped board
    if (
        (((gyokuColor === 'B' && !flipped) ||
            (gyokuColor === 'W' && flipped)) &&
            gyokuPosition < 27) ||
        (((gyokuColor === 'W' && !flipped) ||
            (gyokuColor === 'B' && flipped)) &&
            gyokuPosition > 54)
    ) {
        checkingPieces[16] = 0; //no keima can check if gyoku is in the top 3 rows
    } else if (
        checkingPieces[16] !== 2 &&
        gameState[gyokuPosition + gyokuForward * 17].charAt(0) != gyokuColor &&
        gameState[gyokuPosition + gyokuForward * 17].substr(1, 3) === 'KEI'
    ) {
        checkingPieces[16] = gyokuPosition + gyokuForward * 17;
        //added to the array of checking pieces
        isCheck = gyokuColor;
    } else {
        checkingPieces[16] = 0; // own piece is in the square, so no check
    }

    //check the right side keima spot
    if (
        (((gyokuColor === 'B' && !flipped) ||
            (gyokuColor === 'W' && flipped)) &&
            gyokuPosition < 27) ||
        (((gyokuColor === 'W' && !flipped) ||
            (gyokuColor === 'B' && flipped)) &&
            gyokuPosition > 54)
    ) {
        checkingPieces[17] = 0; //no keima can check if gyoku is in the top 3 rows
    } else if (
        checkingPieces[17] !== 2 &&
        gameState[gyokuPosition + gyokuForward * 19].charAt(0) != gyokuColor &&
        gameState[gyokuPosition + gyokuForward * 19].substr(1, 3) === 'KEI'
    ) {
        checkingPieces[17] = gyokuPosition + gyokuForward * 19;
        //added to the array of checking pieces
        isCheck = gyokuColor;
    } else {
        checkingPieces[17] = 0; // own piece is in the square, so no check
    }

    for (i = 0; i < checkingPieces.length; i++) {
        if (checkingPieces[i] > 0) {
            isCheck = gyokuColor;
        }
    }
    return isCheck;
}

function eliminateIllegalMoves(color, fakeColor) {
    let moveFromHolder;
    let moveToHolder;
    for (c = 0; c < move.length; c++) {
        //go through each potential move
        moveFromHolder = gameState[selectedPiece];
        moveToHolder = gameState[move[c]];

        gameState[move[c]] = gameState[selectedPiece]; //test executing the move
        gameState[selectedPiece] = 'empty';

        if (checkForCheck(fakeColor) === fakeColor) {
            //if the move would result in check
            gameState[move[c]] = moveToHolder; //reset move to how it was before
            move[c] = null;
        } else {
            gameState[move[c]] = moveToHolder; //reset the move to how it was before
        }

        gameState[selectedPiece] = moveFromHolder; //reset selectedpiece square to how it was before
        checkingPieces = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        isCheck = null; //needs to be reset, otherwise if previous spots check returned true, it will still return true
    }

    //if there are any null values in the array, they need to be removed:
    let tempMoveArray = [];
    for (i = 0; i < move.length; i++) {
        if (move[i] != null) {
            tempMoveArray.push(move[i]);
        }
    }
    move = tempMoveArray;
    console.log(move);
}

function checkForMate(color, placeCalled) {
    let counterForMove = 0;
    justChecking = true; //this will affect all the functions called
    for (s = 0; s < 81; s++) {
        if (gameState[s].charAt(0) === color) {
            //if it's an own piece
            selectedPiece = s;
            pieceClick(s, placeCalled); //call the piececlick function to get the moves

            for (b = 0; b < move.length; b++) {
                counterForMove += move[b];
            }
            if (counterForMove > 0) {
                //if there are possible moves
                isCheckMate = false;
                break; //can break the loop
            } else {
                isCheckMate = true;
            }
        }
    }
    if (isCheckMate === true) {
        //if none of the pieces on the board can be moved
        let startCountingMG;
        if (color === 'B') {
            startCountingMG = 7; //black pieces in the mochigoma array start at the 7th spot
        } else {
            startCountingMG = 0; //at the beginning for the white pieces
        }
        for (v = startCountingMG; v < startCountingMG + 7; v++) {
            if (mochiGomaArray[v] > 0) {
                //if there is actually a mochigoma in that spot
                placePiece(mochiGomaOrder[v]);

                for (b = 0; b < move.length; b++) {
                    counterForMove += move[b];
                }
                if (counterForMove > 0) {
                    //if there are possible moves
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

function disableSubmit() {
    document.getElementById('submitmovebutton').style.visibility = 'hidden';
}

function stepForward() {
    //if it's not the first move and it's not displaying the current turn
    if (realTurn > 1 && viewTurn < turn) {
        viewTurn++;
        movesHistory = gameHistory[0].split(',');
        movesHistory.splice(3 * viewTurn, movesHistory.length - 3 * viewTurn);
        resetGameState();
        deselectAll();
        loadGameState(1);
        drawBoard();
        drawMochigoma();
    }
}
function skipForward() {
    viewTurn = realTurn - 1;
    movesHistory = gameHistory[0].split(',');
    resetGameState();
    deselectAll();
    loadGameState(1);
    drawBoard();
    drawMochigoma();
}

function stepBack() {
    //if it's not the first move and it's not displaying the first move
    if (turn > 1 && viewTurn > 1) {
        viewTurn--; //go back one turn
        movesHistory = gameHistory[0].split(',');
        movesHistory.splice(3 * viewTurn, movesHistory.length - 3 * viewTurn);
        resetGameState();
        deselectAll();
        loadGameState(1);
        drawBoard();
        drawMochigoma();
    }
}
function skipBack() {
    viewTurn = 0;
    movesHistory = undefined;
    resetGameState();
    deselectAll();
    loadGameState(1);
    drawBoard();
    drawMochigoma();
}

function endGame(winner) {
    if (winner === playerColor) {
        alert('勝ちました。おめでとう！');
    }
    let ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function () {
        // If ajax.readyState is 4, then the connection was successful
        // If ajax.status (the HTTP return code) is 200, the request was successful
        if (ajax.readyState === 4 && ajax.status === 200) {
            // Use ajax.responseText to get the raw response from the server
            console.log(ajax.responseText);
            window.location.reload();
        } else {
            console.log('Error: ' + ajax.status); // An error occurred during the request.
        }
    };
    let winnerName;
    let loserName;
    //set the winner and loser by finding the usernames of the players from the gamehistory array
    if (winner === 'W') {
        winnerName = gameHistory[2];
        loserName = gameHistory[1];
    } else {
        winnerName = gameHistory[1];
        loserName = gameHistory[2];
    }

    let json = JSON.stringify({
        id: currentGameID,
        winner: winnerName,
        loser: loserName,
    });

    console.log(json);
    ajax.open('POST', 'gameOver.php', true); //asyncronous
    ajax.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    ajax.send(json); //(sendToDatabase);
}
function showGameOver() {
    if (gameHistory[7] === gameHistory[8]) {
        //if the person who won is looking at the page
        alert('相手が校了しました　| Your opponent has resigned');
    } else {
        alert(
            '対局が終了しました。　' +
                gameHistory[7] +
                ' が勝ちました | Game over. ' +
                gameHistory[7] +
                ' has won.',
        );
    }
    let ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function () {
        // If ajax.readyState is 4, then the connection was successful
        // If ajax.status (the HTTP return code) is 200, the request was successful
        if (ajax.readyState === 4 && ajax.status === 200) {
            // Use ajax.responseText to get the raw response from the server
            console.log(ajax.responseText);
        } else {
            console.log('Error: ' + ajax.status); // An error occurred during the request.
        }
    };
    let json = JSON.stringify({
        id: currentGameID,
    });

    console.log(json);
    ajax.open('POST', 'status_to_3.php', true); //asyncronous
    ajax.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    ajax.send(json); //(sendToDatabase);
}
function sendForTsume() {
    if (flipped) {
        //need  to switch the koma to the opposite color if the white player is calling the function
        for (x = 0; x < 81; x++) {
            if (gameState[x] != 'empty') {
                if (gameState[x].charAt(0) === 'B') {
                    gameState[x] =
                        'W' + gameState[x].substr(1, gameState[x].length); //switch the B and W
                } else {
                    gameState[x] =
                        'B' + gameState[x].substr(1, gameState[x].length); //switch the W and B
                }
            }
        }
        let flippedMochiGomaArray = [];
        for (x = 7; x < 14; x++) {
            flippedMochiGomaArray.push(mochiGomaArray[x]);
        }
        for (x = 0; x < 7; x++) {
            flippedMochiGomaArray.push(mochiGomaArray[x]);
        }
        mochiGomaArray = flippedMochiGomaArray;
    }
    document.getElementById('boardConfig').value = gameState.toString();
    document.getElementById('mochigomaConfig').value =
        mochiGomaArray.toString();
    document.getElementById('tsumeInfo').submit();
}
