//display the prompt with the ammount of time that the other player thought about the move
let promptText;

//convert last move into normal notation
moveArray = gameHistory[0].split(",");
ml = moveArray.length;
//startSquare = convertSquare(moveArray[ml-3]);//set the square where the last piece was moved to
endSquare = numberToKanji(convertSquare(moveArray[ml - 2])); //set the square where the last piece was moved to
pieceKanji = pieceToKanji(moveArray[ml - 1]);
gameTurn = moveArray.length / 3;
let triangle;
if (String(moveArray[ml - 1]).substr(0, 1) === "B") {
    triangle = "▲";
} else {
    triangle = "△";
}

//promptText = gameTurn+" "+triangle+endSquare+pieceKanji+"("+startSquare+")";
promptText = gameTurn + " " + triangle + endSquare + pieceKanji;

function convertSquare(num) {
    //converts the 0 - 80 number into kif format
    num1 = Math.floor(num / 9) + 1;
    num2 = ((num % 9) + 1) * 10;
    return num1 + num2;
}
function numberToKanji(num) {
    //convert the hankaku number to zenkaku
    turnToZenkaku = String(num).substr(0, 1);
    let zenkaku;
    let kanji;
    switch (turnToZenkaku) {
        case "1":
            zenkaku = "１";
            break;
        case "2":
            zenkaku = "２";
            break;
        case "3":
            zenkaku = "３";
            break;
        case "4":
            zenkaku = "４";
            break;
        case "5":
            zenkaku = "５";
            break;
        case "6":
            zenkaku = "６";
            break;
        case "7":
            zenkaku = "７";
            break;
        case "8":
            zenkaku = "８";
            break;
        case "9":
            zenkaku = "９";
            break;
    }
    //convert the second number to kanji
    turnToKanji = String(num).substr(-1); //get the last digit from the number
    switch (turnToKanji) {
        case "1":
            kanji = "一";
            break;
        case "2":
            kanji = "二";
            break;
        case "3":
            kanji = "三";
            break;
        case "4":
            kanji = "四";
            break;
        case "5":
            kanji = "五";
            break;
        case "6":
            kanji = "六";
            break;
        case "7":
            kanji = "七";
            break;
        case "8":
            kanji = "八";
            break;
        case "9":
            kanji = "九";
            break;
    }

    return zenkaku + kanji; //return the new number/kanji pair
}

function pieceToKanji(piece) {
    switch (
        String(piece).substr(1) //need to cut off the black or which letter at the beginning
    ) {
        case "F":
            return "歩";
            break;
        case "HI":
            return "飛";
            break;
        case "KAKU":
            return "角";
            break;
        case "KO":
            return "香";
            break;
        case "KEI":
            return "桂";
            break;
        case "GIN":
            return "銀";
            break;
        case "KIN":
            return "金";
            break;
        case "NGIN":
            return "成銀";
            break;
        case "NGIN*":
            return "銀成";
            break;
        case "NKEI":
            return "成桂";
            break;
        case "NKEI*":
            return "桂成";
            break;
        case "NKO":
            return "成香";
            break;
        case "NKO*":
            return "香成";
            break;
        case "NF":
            return "と";
            break;
        case "NF*":
            return "歩成";
            break;
        case "NHI":
            return "龍";
            break;
        case "NHI*":
            return "飛成";
            break;
        case "NKAKU*":
            return "角成";
            break;
        case "NKAKU":
            return "馬";
            break;
        case "GYOKU":
            return "玉";
            break;
    }
}
//add the time taken
/*let timeMins = Math.floor(gameHistory[9]/60);
let timesecs = gameHistory[9]%60;
let timeDisplay = timeMins +":"+timesecs;
promptText += " 思考時間: "+timeDisplay+" | ";*/
promptText += "  "; //TEMPORARY

//add whose turn it is to play
if (gameHistory[7] != null) {
    //if a winner has been set
    promptText = gameHistory[7] + " が勝ちました";
} else if (turn % 2 === 0) {
    //update the prompt showing which player's turn it is
    //White's turn
    promptText += gameHistory[2] + " to play";
} else {
    //black's turn
    promptText += gameHistory[1] + " to play";
}
//write to innerhtml of playerPrompt
document.getElementById("playerPrompt").innerHTML = promptText;
