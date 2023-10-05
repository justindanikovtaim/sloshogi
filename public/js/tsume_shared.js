function setMessage(msgText) {
    document.getElementById("playerPrompt").innerHTML = msgText;
}
function showMove(square, komaType, checkingOnly) {
    //this array represents the possible movements the pieces can do
    //the first 8 are the movements to the 8 directions, starting with forward and going clockwise)
    //1 means the piece can only move one space in that direction, 2 means it can move multiple spaces in the direction
    //the 9th spot ([8]) is kei's move right, and the 10th ([9]) is kei's move left
    //1 means black, 2 means white
    move = [];
    let moveDirections = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    let moveFormulas = [-9, -10, -1, 8, 9, 10, 1, -8, 19, 17]; //the position of the move relative to where the piece is
    //the knight's is adjusted by the negative or positive number in the moveDirections array
    let turnColor;
    if (typeof checkingOnly !== "undefined") {
        turnColor = checkingOnly;
    } else {
        if (turn % 2 === 0) {
            turnColor = "W";
        } else {
            turnColor = "B";
        }
    }

    switch (komaType) {
        case "BF":
            moveDirections[0] = 1;
            break;
        case "WF":
            moveDirections[4] = 1;
            break;
        case "BKEI":
            moveDirections = [0, 0, 0, 0, 0, 0, 0, 0, -1, -1]; //negative will make it go backwards
            break;
        case "WKEI":
            moveDirections = [0, 0, 0, 0, 0, 0, 0, 0, 1, 1]; //positive will make it go forward
            break;
        case "BKO":
            moveDirections[0] = 2;
            break;
        case "WKO":
            moveDirections[4] = 2;
            break;
        case "BHI":
        case "WHI":
            moveDirections = [2, 0, 2, 0, 2, 0, 2, 0, 0, 0];
            break;
        case "BKAKU":
        case "WKAKU":
            moveDirections = [0, 2, 0, 2, 0, 2, 0, 2, 0, 0];
            break;
        case "BGIN":
            moveDirections = [1, 1, 0, 1, 0, 1, 0, 1, 0, 0];
            break;
        case "WGIN":
            moveDirections = [0, 1, 0, 1, 1, 1, 0, 1, 0, 0];
            break;
        case "BKIN":
        case "BNGIN":
        case "BNKEI":
        case "BNKO":
        case "BNF":
            moveDirections = [1, 1, 1, 0, 1, 0, 1, 1, 0, 0];
            break;
        case "WKIN":
        case "WNGIN":
        case "WNKEI":
        case "WNKO":
        case "WNF":
            moveDirections = [1, 0, 1, 1, 1, 1, 1, 0, 0, 0];
            break;
        case "BNHI":
        case "WNHI":
            moveDirections = [2, 1, 2, 1, 2, 1, 2, 1, 0, 0];
            break;
        case "BNKAKU":
        case "WNKAKU":
            moveDirections = [1, 2, 1, 2, 1, 2, 1, 2, 0, 0];
            break;
        case "BGYOKU":
        case "WGYOKU":
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
                //check if the piece can move just one or multiple squares
                if (moveDirections[i] === 2) {
                    //if it can move multiple squares, continue checking the squares and adding any that are empty
                    //or have an opponent's piece until the edge of the board is reached
                    isBlocked = false;
                    moveSquare = square;
                    while (!isBlocked) {
                        //if the space is empty
                        if (
                            gameState[moveSquare + moveFormulas[i]] === "empty"
                        ) {
                            move.push(moveSquare + moveFormulas[i]);
                            //if the space has an enemy piece
                        } else if (
                            gameState[moveSquare + moveFormulas[i]].charAt(
                                0
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
                                        moveSquare + moveFormulas[i]
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;

                            case -10: //up/right
                                if (
                                    boardTopEdge.includes(
                                        moveSquare + moveFormulas[i]
                                    ) ||
                                    board1Row.includes(
                                        moveSquare + moveFormulas[i]
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;

                            case -1: //right
                                if (
                                    board1Row.includes(
                                        moveSquare + moveFormulas[i]
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;

                            case 8: //down/right
                                if (
                                    boardBottomEdge.includes(
                                        moveSquare + moveFormulas[i]
                                    ) ||
                                    board1Row.includes(
                                        moveSquare + moveFormulas[i]
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;

                            case 9: //down
                                if (
                                    boardBottomEdge.includes(
                                        moveSquare + moveFormulas[i]
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;

                            case 10: //down/left
                                if (
                                    boardBottomEdge.includes(
                                        moveSquare + moveFormulas[i]
                                    ) ||
                                    board9Row.includes(
                                        moveSquare + moveFormulas[i]
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;

                            case 1: //left
                                if (
                                    board9Row.includes(
                                        moveSquare + moveFormulas[i]
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;

                            case -8: //up/left
                                if (
                                    boardTopEdge.includes(
                                        moveSquare + moveFormulas[i]
                                    ) ||
                                    board9Row.includes(
                                        moveSquare + moveFormulas[i]
                                    )
                                ) {
                                    isBlocked = true;
                                }
                                break;
                            default:
                                alert(
                                    "there's an error in the switch statement"
                                );
                                isBlocked = true;
                                break;
                        }
                        moveSquare += moveFormulas[i]; //move to the next square in that direction
                    }
                } else {
                    //if it can only move one square at a time, add the first square to the move array
                    move.push(square + moveFormulas[i]);
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
        for (i = 8; i < 10; i++) {
            if (moveDirections[i] != 0) {
                //if the space is empty
                if (
                    gameState[square + moveFormulas[i] * moveDirections[i]] ===
                    "empty"
                ) {
                    move.push(square + moveFormulas[i] * moveDirections[i]);
                    //if the space has an enemy piece
                } else if (
                    gameState[
                        square + moveFormulas[i] * moveDirections[i]
                    ].charAt(0) !== turnColor
                ) {
                    //add it to the move array
                    move.push(square + moveFormulas[i] * moveDirections[i]);
                }
            }
        }
    }
    //eliminate moves that would put the gyoku in check
    justChecking = true;
    eliminateIllegalMoves(turnColor, square);
    justChecking = false;

    //return the array of squares that can be moved to;
    return move;
}
function promotePiece(id) {
    if (
        gameState[selectedPiece].charAt(1) !== "N" && //if the piece is not promoted yet
        gameState[selectedPiece].substr(1, 3) !== "KIN" &&
        gameState[selectedPiece].substr(1, 5) !== "GYOKU"
    ) {
        //and not a kin or Gyoku
        //if it's a kei and in the top two rows, or a kyosha or fu in the top row, automatically promote
        let yesNo;
        switch (gameState[selectedPiece]) {
            case "BKEI":
                if (id < 18) {
                    yesNo = true;
                } else {
                    yesNo = confirm("Promote?");
                }
                break;
            case "BKO":
            case "BF":
                if (id < 9) {
                    yesNo = true;
                } else {
                    yesNo = confirm("Promote?");
                }
                break;
            case "WKEI":
                if (id > 62) {
                    yesNo = true;
                } else {
                    yesNo = confirm("Promote?");
                }
                break;
            case "WKO":
            case "WF":
                if (id > 71) {
                    yesNo = true;
                } else {
                    yesNo = confirm("Promote?");
                }
                break;
            default:
                yesNo = confirm("Promote?");
                break;
        }
        if (yesNo) {
            gameState[selectedPiece] =
                gameState[selectedPiece].substr(0, 1) +
                "N" +
                gameState[selectedPiece].substr(1, 4); // add an N for nari after the first character
            newlyPromoted = true;
        }
    }
    return;
}
