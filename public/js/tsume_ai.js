//SLO Shogi tsumeshogi AI

//Analyze all the possible moves and see which will create the most posibliites for movement afterwards

//analyze self for best move
function analyzeSelf() {
    move = [];
    allMovesArray = [];

    //go through each square on the board and try out every move for each white pieces
    for (x = 0; x < 81; x++) {
        if (gameState[x].charAt(0) === "W") {
            let pieceType = gameState[x];
            let movedFrom = x;
            //get all possible(legal) moves for the piece and add them to an array
            let theMoves = showMove(movedFrom, pieceType, "W");
            let movesArray = [];
            for (i = 0; i < theMoves.length; i++) {
                movesArray[i] = Array(3);
                movesArray[i][0] = movedFrom;
                movesArray[i][1] = theMoves[i];
                movesArray[i][2] = pieceType;
            }
            allMovesArray = allMovesArray.concat(movesArray);
        }
    }
    //WANT TO ADD: only run the check on the mochigoma if the gyoku is separated from the checking piece (ie. an aigoma could actually block the check)
    //go through each mochigoma and try out the moves
    for (v = 0; v < 7; v++) {
        if (mochiGomaArray[v] > 0) {
            //if there is actually a mochigoma in that spot
            pieceType = mochiGomaOrder[v].substr(1, mochiGomaOrder.length); //set the piece type (minus the M for mochigoma)
            justChecking = true;
            move = [];
            placePiece(mochiGomaOrder[v]); //try the move
            let movesArray = [];
            for (i = 0; i < move.length; i++) {
                movesArray[i] = Array(3);
                movesArray[i][0] = 81;
                movesArray[i][1] = move[i];
                movesArray[i][2] = pieceType;
            }
            allMovesArray = allMovesArray.concat(movesArray);
        }
    }

    justChecking = false;
    return allMovesArray;
}

function decideBestMove() {
    let pieceHolder;
    let bestMove = [];
    let firstMovesArray = [];
    //first, check computer's potential moves and return a nested array
    firstMovesArray = analyzeSelf();
    if (firstMovesArray.length === 1) {
        bestMove = firstMovesArray[0]; //if there's only one possible move, make that move
    } else if (firstMovesArray.length === 0) {
        bestMove = [0];
    } else {
        console.log("first Moves Array: " + firstMovesArray);
        let moveAnalyzeArray = [];
        //then, count the number of possible responses from the player that would check the computer for each move

        for (xray = 0; xray < firstMovesArray.length; xray++) {
            pieceHolder = gameState[firstMovesArray[xray][1]]; //remeber what was in the square where the piece moved into

            //try out the move
            gameState[firstMovesArray[xray][1]] = firstMovesArray[xray][2];
            gameState[firstMovesArray[xray][0]] = "empty";
            drawBoard(); //remove
            //set the move analyze array corresponding to the move to the number of possible checking moves that the player can make in response
            moveAnalyzeArray[xray] = countCheckingMoves();

            //revert the board back to the way it was before
            gameState[firstMovesArray[xray][0]] =
                gameState[firstMovesArray[xray][1]];
            gameState[firstMovesArray[xray][1]] = pieceHolder;
            drawBoard(); //remove
            //if the move would make it so the gyoku can't be checked, break the loop and use that move
            if (moveAnalyzeArray[xray] === 0) {
                bestMove = firstMovesArray[xray];
                setMessage("間違えました。Try Again");
                break;
            }
        }
        let fewestMoves = moveAnalyzeArray[0]; //set the default best move to the first possible move
        //if any would result in the player not being able to check the computer, play that move
        for (xyz = 0; xyz < moveAnalyzeArray.length; xyz++) {
            if (moveAnalyzeArray[xyz] < 1) {
                //this is the move to
                bestMove = firstMovesArray[xyz];
                break;
            } else if (moveAnalyzeArray[xyz] < fewestMoves) {
                //if the move has fewer possiblites than the one currently set
                fewestMoves = moveAnalyzeArray[xyz]; //set it to the new best move found so far
            }
        }
        if (bestMove.length < 3) {
            //if the best move hasn't been set yet (meaning there are no moves that would result in no checkable responses)
            bestMove = firstMovesArray[moveAnalyzeArray.indexOf(fewestMoves)];
        }
    }

    return bestMove;
}

function countCheckingMoves() {
    drawBoard(); //remove
    turn++; //increment the turn counter temporarily so that the showMove will function properly
    let numberOfCheckingMoves = 0;
    //go through all squares on the board
    for (n = 0; n < 80; n++) {
        //if it's a black piece, get all its possible moves
        if (gameState[n].charAt(0) === "B") {
            let tempCounterArray = showMove(n, gameState[n], "B");
            let length = tempCounterArray.length;

            let promotable;
            //check if it's actually a promotable piece
            switch (gameState[n]) {
                case "BF":
                case "BKEI":
                case "BKO":
                case "BHI":
                case "BKAKU":
                case "BGIN":
                    promotable = true;
                    break;
                default:
                    promotable = false;
                    break;
            }
            //if the piece hasn't promoted yet
            if (gameState[n].charAt(1) != "N" && promotable) {
                //if the piece is starting in the promotion zone
                if (n < 27) {
                    for (g = 0; g < length; g++) {
                        //add the move with the nari piece instead
                        tempCounterArray.push(tempCounterArray[g]);
                    }
                } else {
                    //otherwise, check each move to see if it goes into the promotion zone
                    for (g = 0; g < length; g++) {
                        if (tempCounterArray[g] < 27) {
                            tempCounterArray.push(tempCounterArray[g]);
                        }
                    }
                }
                console.log(tempCounterArray); //remove
            }

            //for each move that checks the white Gyoku, increment the counter
            for (z = 0; z < length; z++) {
                let tryTheMove = tryMove(
                    gameState[n],
                    n,
                    tempCounterArray[z],
                    gameState[n]
                );
                if (tryTheMove === "check") {
                    numberOfCheckingMoves++;
                } else if (tryTheMove === "mate") {
                    numberOfCheckingMoves += 1000; //add 1,000 for a checkmate so that we know not to move there
                }
            }
            //all spots in the array that were added on beyond the initial length are for the promoted version of the move
            for (z = length; z < tempCounterArray.length; z++) {
                let tryTheMove = tryMove(
                    "BN" + gameState[n].substr(1),
                    n,
                    tempCounterArray[z],
                    gameState[n]
                );
                if (tryTheMove === "check") {
                    numberOfCheckingMoves++;
                } else if (tryTheMove === "mate") {
                    numberOfCheckingMoves += 1000; //add 1,000 for a checkmate so that we know not to move there
                }
            }
        }
    }
    for (bboy = 8; bboy < 14; bboy++) {
        //go through mochigoma
        if (mochiGomaArray[bboy] > 0) {
            //if there is actually a mochigoma in that spot
            turn++;
            placePiece(mochiGomaOrder[bboy]);
            turn--;
            let newMoves = [];
            ///need to narrow down possible places to play (just the squares surrounding the gyoku, for instance)
            let whiteGyokuPos = gameState.indexOf("WGYOKU");
            //this is an array of squares surrounding the gyoku
            let surroundingSquares = [
                whiteGyokuPos - 1,
                whiteGyokuPos + 1,
                whiteGyokuPos - 10,
                whiteGyokuPos - 8,
                whiteGyokuPos - 9,
                whiteGyokuPos + 8,
                whiteGyokuPos + 9,
                whiteGyokuPos + 10,
            ];
            for (cm = 0; cm < move.length; cm++) {
                //eliminate any moves that don't fall within the squares surrounding the gyoku
                if (surroundingSquares.includes(move[cm])) {
                    newMoves.push(move[cm]); //add the move to the new array
                }
            }
            //for each move that checks the white Gyoku, increment the counter
            for (y = 0; y < newMoves.length; y++) {
                let tryTheMove = tryMove(
                    mochiGomaOrder[bboy].substr(1),
                    81,
                    newMoves[y],
                    mochiGomaOrder[bboy].substr(1)
                );
                if (tryTheMove === "check") {
                    numberOfCheckingMoves++;
                } else if (tryTheMove === "mate") {
                    numberOfCheckingMoves += 1000; //add 1,000 for a checkmate so that we know not to move there
                }
            }
        }
    }
    turn--; //revert the turn counter
    return numberOfCheckingMoves;
}

function tryMove(pieceType, from, to, realPiece) {
    let tempMoveRemember = gameState[to];
    let returnVal;

    //try out the move
    gameState[to] = pieceType;
    gameState[from] = "empty";

    //REMOVE
    drawBoard();
    console.log("trying move with TryMove() ");
    //if it causes check, check for mate and return "mate" or just "check"; otherwise, return false
    if (checkForCheck("W")) {
        if (checkForMate("W")) {
            returnVal = "mate";
        } else {
            returnVal = "check";
        }
    } else {
        returnVal = "ok";
    }

    //revert to the way the board was before trying it out
    gameState[to] = tempMoveRemember;
    gameState[from] = realPiece;
    drawBoard(); //remove
    return returnVal;
}
