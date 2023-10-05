//next steps:
//add undo button the will remove the last move from the mainMoveSequence string

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
//(Wfu, WKo, Wkei, Wgin, Wkin, Wkaku, Whi, Bfu, BKo, Bkei, Bgin, Bkin, Bkaku, Bhi)
let mochiGomaAlreadySelected = false;
let isCheck = null; //keep track of if it is check or not
let checkingPieces = [];
let move = [];
let boardSquare = [];
let newlyPromoted = false;
let sC = 0; //square counter
let sendToDatabase; //an object used to pass JSON data of the move made to PHP
let mainMoveSequence;

setMessage("駒をタップして詰将棋の正解筋を入力してください");

let rowCounter = 0;
let columnCounter = 0;
for (i = 0; i < 9; i++) {
    for (x = 0; x < 9; x++) {
        boardSquare[sC] = document.createElement("img"); //create each of the 81 squares as an image in the document
        boardSquare[sC].src = "/public/images/koma/1/empty.png"; //temporarily set image source
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
    columnCounter = 0; // start back at the right side of the board
    spacer = 0; //reset the spacer for the first piece in the row
}
mochiGoma = [];
mochiGomaAmmount = [];
spacer = 60;

mochiGomaOrder = [
    "MWF",
    "MWKO",
    "MWKEI",
    "MWGIN",
    "MWKIN",
    "MWKAKU",
    "MWHI",
    "MBF",
    "MBKO",
    "MBKEI",
    "MBGIN",
    "MBKIN",
    "MBKAKU",
    "MBHI",
];

for (jupiter = 0; jupiter < 2; jupiter++) {
    // initialize the mochigoma on the board
    for (x = 0; x < 7; x++) {
        if (jupiter === 0) {
            //if it's the first time through, we are drawing the white mochigoma
            mochiGoma[x] = document.createElement("img"); //create a new img element for each mochigoma type
            mochiGoma[x].src =
                "/public/images/koma/1/" + mochiGomaOrder[x] + ".png";
            mochiGoma[x].setAttribute("id", mochiGomaOrder[x]);
            mochiGoma[x].setAttribute("onClick", "placePiece(this.id)");
            mochiGoma[x].style.width = "9vw";
            mochiGoma[x].style.position = "absolute";
            mochiGoma[x].style.right = spacer + "vw";
            mochiGoma[x].style.top = "0vw";
            document.getElementById("whiteMochigoma").appendChild(mochiGoma[x]);
            mochiGomaAmmount[x] = document.createElement("img");
            mochiGomaAmmount[x].src = "/public/images/mochiGomaNum2.png";
            mochiGomaAmmount[x].style.width = "3vw";
            mochiGomaAmmount[x].style.position = "absolute";
            mochiGomaAmmount[x].style.right = spacer + "vw"; //offset it from the piece
            mochiGomaAmmount[x].style.top = "0vw";
            document
                .getElementById("whiteMochigoma")
                .appendChild(mochiGomaAmmount[x]);
        } else {
            //otherwise it's the second time through, so we are drawing the black mochigoma
            mochiGoma[x + 7] = document.createElement("img"); //create a new img element for each mochigoma type
            mochiGoma[x + 7].src =
                "/public/images/koma/1/" + mochiGomaOrder[x + 7] + ".png";
            mochiGoma[x + 7].setAttribute("id", mochiGomaOrder[x + 7]);
            mochiGoma[x + 7].setAttribute("onClick", "placePiece(this.id)");
            mochiGoma[x + 7].style.width = "9vw";
            mochiGoma[x + 7].style.position = "absolute";
            mochiGoma[x + 7].style.right = spacer + "vw";
            mochiGoma[x + 7].style.top = "0vw";
            document
                .getElementById("blackMochigoma")
                .appendChild(mochiGoma[x + 7]);
            mochiGomaAmmount[x + 7] = document.createElement("img");
            mochiGomaAmmount[x + 7].src = "/public/images/mochiGomaNum2.png";
            mochiGomaAmmount[x + 7].style.width = "3vw";
            mochiGomaAmmount[x + 7].style.position = "absolute";
            mochiGomaAmmount[x + 7].style.right = spacer + "vw"; //offset it from the piece
            mochiGomaAmmount[x + 7].style.top = "0vw";
            document
                .getElementById("blackMochigoma")
                .appendChild(mochiGomaAmmount[x + 7]);
        }
        spacer -= 10;
    }
    spacer = 60;
}

let tempGameState = [];
realTurn = turn; //needed to make sure that the user can't play when looking at a previous game state
drawBoard();

//Starting formation
function drawBoard() {
    for (i = 0; i < 81; i++) {
        boardSquare[i].src = "/public/images/koma/1/" + gameState[i] + ".png"; //set each of the urls to match the image
    }
    //draw the correct mochigoma
    for (i = 0; i < 14; i++) {
        if (mochiGomaArray[i] > 0) {
            mochiGoma[i].style.visibility = "visible";
        } else {
            mochiGoma[i].style.visibility = "hidden";
        }
        //if there is more than one of that mochigoma type, draw the number as well
        if (mochiGomaArray[i] > 1) {
            mochiGomaAmmount[i].style.visibility = "visible";
            mochiGomaAmmount[i].src =
                "/public/images/mochiGomaNum" + mochiGomaArray[i] + ".png";
        } else {
            mochiGomaAmmount[i].style.visibility = "hidden";
        }
    }
}

function addToMochiGoma(gamePiece) {
    let gamePieceColor;
    if (gamePiece.charAt(1) === "N") {
        //if it's a promoted piece
        gamePiece = gamePiece.replace("N", ""); //remove the N
    }
    if (gamePiece.charAt(0) === "B") {
        gamePieceColor = 0;
    } else {
        gamePieceColor = 7; //if it's a white piece, start at the 7th array spot
    }
    switch (
        gamePiece.substr(1, gamePiece.length) // return the piece name minus thethe color
    ) {
        case "F":
            mochiGomaArray[0 + gamePieceColor] += 1; //add a fu to the fu place
            break;

        case "KO":
            mochiGomaArray[1 + gamePieceColor] += 1; //add a ko to the ko place
            break;

        case "KEI":
            mochiGomaArray[2 + gamePieceColor] += 1; //add a kei to the kei place
            break;

        case "GIN":
            mochiGomaArray[3 + gamePieceColor] += 1; //add a gin to the gin place
            break;

        case "KIN":
            mochiGomaArray[4 + gamePieceColor] += 1; //add a kin to the kin place
            break;

        case "KAKU":
            mochiGomaArray[5 + gamePieceColor] += 1; //add a kaku to the kaku place
            break;

        case "HI":
            mochiGomaArray[6 + gamePieceColor] += 1; //add a hi to the hi place
            break;
        default:
            console.log("piece name is incorrect");
            break;
    }
}

function pieceClick(id) {
    if (
        ((turn % 2 === 0 && gameState[id].charAt(0) != "W") ||
            (turn % 2 !== 0 && gameState[id].charAt(0) != "B")) &&
        justChecking === false &&
        boardSquare[id].style.background.substr(0, 7) != "rgb(230"
    ) {
        deselectAll();
        //do nothing
    } else {
        if (justChecking === false) {
            console.log(id);
        }
        if (boardSquare[id].style.background.substr(0, 7) === "rgb(230") {
            //need to sample just this part of the string, becasue different browsers write it differently
            //if the clicked square is highlighted as a possible move
            movePiece(id);
        } else if (
            !justChecking &&
            selectedPiece !== null &&
            (id === selectedPiece ||
                (id !== selectedPiece &&
                    boardSquare[id].style.background.substr(0, 7) != "rgb(230"))
        ) {
            //if the same piece is clicked again or another unrelated place is clicked
            deselectAll();
            selectedPiece = null;
        } else {
            //otherwise, highlight the possible moves

            if (justChecking === false) {
                selectedPiece = id; // define the selected piece
                boardSquare[id].style.filter = "brightness(1.5)"; //highlight the selected piece only if not checking for checkmate
            }

            highlightSquares(showMove(id, gameState[id]));
        }
    }
}

function eliminateIllegalMoves(color) {
    //only worry about eliminating the illegal moves if there is a gyoku of the player's color
    //eg. tsume problem with only the opponent's gyoku doesn't need it
    if (gameState.includes(color + "GYOKU")) {
        let moveFromHolder;
        let moveToHolder;
        for (c = 0; c < move.length; c++) {
            //go through each potential move
            moveFromHolder = gameState[selectedPiece];
            moveToHolder = gameState[move[c]];

            gameState[move[c]] = gameState[selectedPiece]; //test executing the move
            gameState[selectedPiece] = "empty";

            if (checkForCheck(color) === color) {
                //if the move would result in check
                gameState[move[c]] = moveToHolder; //reset move to how it was before
                move[c] = null;
            } else {
                gameState[move[c]] = moveToHolder; //reset the move to how it was before
            }

            gameState[selectedPiece] = moveFromHolder; //reset selectedpiece square to how it was before
            checkingPieces = [
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
            ];
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
}
function highlightSquares(highlightArray) {
    if (justChecking === false) {
        for (i = highlightArray.length - 1; i > -1; i--) {
            if (highlightArray[i] !== null) {
                boardSquare[highlightArray[i]].style.background =
                    "rgb(230, 197, 11)"; //highlight each possible square to move into
            }
        }
    }
}

function movePiece(id) {
    let isMochiGoma;

    gameState[82] = gameState[id]; //a temporary placeholder for the clicked place
    if (selectedPiece < 81) {
        //if it's other than the mochigoma
        //see if piece can promote
        let promoteZone = false;
        if (turn % 2 === 0) {
            if (id > 53 || selectedPiece > 53) {
                promoteZone = true;
            }
        } else {
            if (id < 27 || selectedPiece < 27) {
                promoteZone = true;
            }
        }

        if (gameState[selectedPiece].charAt(1) !== "N" && promoteZone) {
            //if the piece isn't already promoted (the second letter isn't N) and it is in or will move into the promotion zone
            promotePiece(id);
        }

        if (gameState[id].charAt(0) !== "e") {
            //if capturing a piece
            addToMochiGoma(gameState[id]);
        }
    }

    if (selectedPiece === 81) {
        //if it is a mochigoma
        let mochigomaPlace = mochiGomaOrder.indexOf(
            "M" + gameState[selectedPiece]
        ); //find the place where it is
        mochiGomaArray[mochigomaPlace]--; //remove a piece from the array
        isMochiGoma = gameState[81];
    }

    if (turn === 1) {
        //on the first turn, we don't want to start by sending a comma in the data
        mainMoveSequence =
            selectedPiece.toString() +
            "," +
            id.toString() +
            "," +
            gameState[selectedPiece];
    } else {
        mainMoveSequence +=
            "," +
            selectedPiece.toString() +
            "," +
            id.toString() +
            "," +
            gameState[selectedPiece];
    }

    gameState[id] = gameState[selectedPiece]; //move the piece to the new square
    gameState[selectedPiece] = "empty"; //make the space where the piece moved from empty
    turn++; //increment the turn

    drawBoard();
    deselectAll();
}

function yesNoPromote() {
    //add code to make a confirmation popup appear on the screen
}

function deselectAll() {
    for (i = 0; i < 81; i++) {
        //cycle through every square and remove the background highlight color
        boardSquare[i].style.background = "none";
        boardSquare[i].style.filter = "none";
    }
    for (i = 0; i < 14; i++) {
        mochiGoma[i].style.filter = "none";
    }
    move = [];
    isCheck = null;
    checkingPieces = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    selectedPiece = null;
    id = null;
}

function placePiece(piece) {
    let playerColor;
    if (turn % 2 === 0) {
        playerColor = "W";
    } else {
        playerColor = "B";
    }
    if (
        (mochiGoma[mochiGomaOrder.indexOf(piece)].style.filter ===
            "brightness(1.5)" &&
            justChecking === false) ||
        piece.charAt(1) != playerColor
    ) {
        //if the currently selected piece is clicked again
        deselectAll();
        mochiGomaAlreadySelected = false;
    } else if (mochiGomaAlreadySelected) {
        //if a mochigoma is already selected, this makes sure that multiple pieces can't be highlighted at the same time
        deselectAll();
        mochiGomaAlreadySelected = false;
    } else {
        selectedPiece = 81; //set selected piece to number outside of the board
        gameState[81] = piece.substr(1, piece.length); //put the piece in the 81st spot of the gameState array
        mochiGomaAlreadySelected = true;
        let startingPlace = 0;
        let endAfter;
        let MGColor = piece.charAt(1);

        if (justChecking === false) {
            let mochigomaPlace = mochiGomaOrder.indexOf(
                "M" + gameState[selectedPiece]
            ); //find the place where it is
            mochiGoma[mochigomaPlace].style.filter = "brightness(1.5)"; //highlight the selected piece
        }

        switch (
            piece.substr(2, piece.length) //fu have special rules about place ment
        ) {
            case "F":
                let possibleFuRows = [0, 0, 0, 0, 0, 0, 0, 0, 0];
                let fuStartingPlace;
                for (i = 0; i < 9; i++) {
                    let yesNoFu = false;
                    for (x = 0; x < 9; x++) {
                        if (gameState[allBoardRows[i][x]] === MGColor + "F") {
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
                let directionMultiplier;
                if (playerColor === "B") {
                    //black starts on the second row and goes all the way down
                    fuStartingPlace = 1;
                } else {
                    //white starts from the top row and will stop before the bottom row
                    fuStartingPlace = 0;
                }
                for (i = 0; i < 9; i++) {
                    //go through to find all the possible rows again
                    if (possibleFuRows[i] === 1) {
                        for (
                            x = fuStartingPlace;
                            x < fuStartingPlace + 8;
                            x++
                        ) {
                            if (gameState[allBoardRows[i][x]] === "empty") {
                                move.push(allBoardRows[i][x]); //add all the empty spaces to the move array
                            }
                        }
                    }
                }
                break;

            case "KO": //Ko can't be placed on last row
                if (playerColor === "B") {
                    startingPlace = 9;
                } else {
                    startingPlace = 0;
                }
                endAfter = 72; //count for 72 squares (all but the last row)
                for (i = startingPlace; i < startingPlace + endAfter; i++) {
                    //cycle through each square in the board that is possible for that color
                    if (gameState[i] === "empty") {
                        move.push(i); //add all empty squares to the list of possible moves
                    }
                }
                break;

            case "KEI": //kei can't be placed in the last 2 rows since they couldn't move
                if (playerColor === "B") {
                    startingPlace = 18;
                } else {
                    startingPlace = 0;
                }
                endAfter = 63;

                for (i = startingPlace; i < startingPlace + endAfter; i++) {
                    //cycle through each square in the board that is possible for that color
                    if (gameState[i] === "empty") {
                        move.push(i); //add all empty squares to the list of possible moves
                    }
                }
                break;

            default:
                for (i = 0; i < 81; i++) {
                    if (gameState[i] === "empty") {
                        move.push(i); //add all empty squares to the list of possible moves
                    }
                }
        }

        eliminateIllegalMoves(MGColor); //will remove all moves from move array that would result in check to own gyoku

        if (justChecking === false) {
            for (i = move.length - 1; i > -1; i--) {
                if (move[i] !== null) {
                    boardSquare[move[i]].style.background = "rgb(230, 197, 11)"; //highlight each possible square to move into
                }
            }
        }
    }
}
function removeMG(gamePiece) {
    let gamePieceColor;
    if (gamePiece.charAt(1) === "N") {
        //if it's a promoted piece
        gamePiece = gamePiece.replace("N", ""); //remove the N
    }
    if (gamePiece.charAt(0) === "B") {
        gamePieceColor = 0;
    } else {
        gamePieceColor = 7; //if it's a white piece, start at the 7th array spot
    }
    switch (
        gamePiece.substr(1, gamePiece.length) // return the piece name minus thethe color
    ) {
        case "F":
            mochiGomaArray[0 + gamePieceColor] -= 1; //add a fu to the fu place
            break;

        case "KO":
            mochiGomaArray[1 + gamePieceColor] -= 1; //add a ko to the ko place
            break;

        case "KEI":
            mochiGomaArray[2 + gamePieceColor] -= 1; //add a kei to the kei place
            break;

        case "GIN":
            mochiGomaArray[3 + gamePieceColor] -= 1; //add a gin to the gin place
            break;

        case "KIN":
            mochiGomaArray[4 + gamePieceColor] -= 1; //add a kin to the kin place
            break;

        case "KAKU":
            mochiGomaArray[5 + gamePieceColor] -= 1; //add a kaku to the kaku place
            break;

        case "HI":
            mochiGomaArray[6 + gamePieceColor] -= 1; //add a hi to the hi place
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
//   16   17     knights
function checkForCheck(gyokuColor) {
    let gyokuPosition = gameState.indexOf(gyokuColor + "GYOKU"); //get the location of the gyoku being checked
    let gyokuForward;
    let gyokuOnTopRow;
    let gyokuOnBottomRow;
    let gyokuOnRightColumn;
    let gyokuOnLeftColumn;

    if (!justChecking && turn % 2 === 1) {
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
        gameState[gyokuPosition + gyokuForward * 9].charAt(0) != "e" &&
        gameState[gyokuPosition + gyokuForward * 9].charAt(0) != gyokuColor
    ) {
        // if it's an enemy piece
        switch (
            gameState[gyokuPosition + gyokuForward * 9].substr(
                1,
                gameState[gyokuPosition + gyokuForward * 9].length
            ) //check the square right in front of the gyoku
        ) {
            case "mpty":
            case "KEI":
            case "KAKU":
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
        gameState[gyokuPosition + gyokuForward * 10].charAt(0) != "e" &&
        gameState[gyokuPosition + gyokuForward * 10].charAt(0) != gyokuColor
    ) {
        switch (
            gameState[gyokuPosition + gyokuForward * 10].substr(
                1,
                gameState[gyokuPosition + gyokuForward * 10].length
            )
        ) {
            case "F":
            case "KO":
            case "KEI":
            case "HI":
            case "mpty":
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
        gameState[gyokuPosition + gyokuForward * 1].charAt(0) != "e" &&
        gameState[gyokuPosition + gyokuForward * 1].charAt(0) != gyokuColor
    ) {
        switch (
            gameState[gyokuPosition + gyokuForward * 1].substr(
                1,
                gameState[gyokuPosition + gyokuForward * 1].length
            )
        ) {
            case "F":
            case "KO":
            case "GIN":
            case "KAKU":
            case "KEI":
            case "mpty":
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
        gameState[gyokuPosition + gyokuForward * -8].charAt(0) != "e" &&
        gameState[gyokuPosition + gyokuForward * -8].charAt(0) != gyokuColor
    ) {
        switch (
            gameState[gyokuPosition + gyokuForward * -8].substr(
                1,
                gameState[gyokuPosition + gyokuForward * -8].length
            )
        ) {
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
        gameState[gyokuPosition + gyokuForward * -9].charAt(0) != "e" &&
        gameState[gyokuPosition + gyokuForward * -9].charAt(0) != gyokuColor
    ) {
        // if it's an enemy piece
        switch (
            gameState[gyokuPosition + gyokuForward * -9].substr(
                1,
                gameState[gyokuPosition + gyokuForward * -9].length
            ) //check the square right in front of the gyoku
        ) {
            case "mpty":
            case "KEI":
            case "GIN":
            case "KO":
            case "FU":
            case "KAKU":
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
        gameState[gyokuPosition + gyokuForward * -10].charAt(0) != "e" &&
        gameState[gyokuPosition + gyokuForward * -10].charAt(0) != gyokuColor
    ) {
        switch (
            gameState[gyokuPosition + gyokuForward * -10].substr(
                1,
                gameState[gyokuPosition + gyokuForward * -10].length
            )
        ) {
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
        gameState[gyokuPosition + gyokuForward * -1].charAt(0) != "e" &&
        gameState[gyokuPosition + gyokuForward * -1].charAt(0) != gyokuColor
    ) {
        switch (
            gameState[gyokuPosition + gyokuForward * -1].substr(
                1,
                gameState[gyokuPosition + gyokuForward * -1].length
            )
        ) {
            case "F":
            case "KO":
            case "GIN":
            case "KAKU":
            case "KEI":
            case "mpty":
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
        gameState[gyokuPosition + gyokuForward * 8].charAt(0) != "e" &&
        gameState[gyokuPosition + gyokuForward * 8].charAt(0) != gyokuColor
    ) {
        switch (
            gameState[gyokuPosition + gyokuForward * 8].substr(
                1,
                gameState[gyokuPosition + gyokuForward * 8].length
            )
        ) {
            case "F":
            case "KO":
            case "KEI":
            case "HI":
            case "mpty":
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
                        gameState[checkingPosition].length
                    )
                ) {
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
                        gameState[checkingPosition].length
                    )
                ) {
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
        (gyokuColor === "B" && board1Row.includes(checkingPosition)) || //if it is black and on right edge
        (gyokuForward === "W" && board9Row.includes(checkingPosition))
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
                        gameState[checkingPosition].length
                    )
                ) {
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
                        gameState[checkingPosition].length
                    )
                ) {
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
                        gameState[checkingPosition].length
                    )
                ) {
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
                        gameState[checkingPosition].length
                    )
                ) {
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
        (gyokuColor === "W" && board1Row.includes(checkingPosition)) || //if it is black and on right edge
        (gyokuForward === "B" && board9Row.includes(checkingPosition))
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
                        gameState[checkingPosition].length
                    )
                ) {
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
                        gameState[checkingPosition].length
                    )
                ) {
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
    if (
        (gyokuColor === "B" && gyokuPosition < 27) ||
        (gyokuColor === "W" && gyokuPosition > 54)
    ) {
        checkingPieces[16] = 0; //no keima can check if gyoku is in the top 3 rows
    } else if (
        checkingPieces[16] !== 2 &&
        gameState[gyokuPosition + gyokuForward * 17].charAt(0) != gyokuColor &&
        gameState[gyokuPosition + gyokuForward * 17].substr(1, 3) === "KEI"
    ) {
        checkingPieces[16] = gyokuPosition + gyokuForward * 17;
        //added to the array of checking pieces
        isCheck = gyokuColor;
    } else {
        checkingPieces[16] = 0; // own piece is in the square, so no check
    }

    //check the right side keima spot
    if (
        (gyokuColor === "B" && gyokuPosition < 27) ||
        (gyokuColor === "W" && gyokuPosition > 54)
    ) {
        checkingPieces[17] = 0; //no keima can check if gyoku is in the top 3 rows
    } else if (
        checkingPieces[17] !== 2 &&
        gameState[gyokuPosition + gyokuForward * 19].charAt(0) != gyokuColor &&
        gameState[gyokuPosition + gyokuForward * 19].substr(1, 3) === "KEI"
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

let justChecking = false;
let isCheckMate = false;
function checkForMate(color) {
    let counterForMove = 0;
    justChecking = true; //this will affect all the functions called
    for (s = 0; s < 81; s++) {
        if (gameState[s].charAt(0) === color) {
            //if it's an own piece
            selectedPiece = s;
            pieceClick(s); //call the piececlick function to get the moves

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
        if (color === "B") {
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
function toSavePage() {
    document.getElementById("tsumeData").style.display = "block";
    document.getElementById("moveSequence").value = mainMoveSequence;

    //send form
    // document.getElementById('tsumeData').submit();
}
