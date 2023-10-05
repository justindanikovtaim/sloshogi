//create a new tsumeshogi problem

let boardSquare = [];
let sC = 0; //square counter
let rowCounter = 0;
let columnCounter = 0;
let mochiGomaArray = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; //array for black mochi goma
let mochiGoma = [];
let selectionGoma = [];
let mochiGomaAmmount = [];
let spacer = 0;
let mochiGomaOrder = ["MWF", "MWKO", "MWKEI", "MWGIN", "MWKIN", "MWKAKU", "MWHI",
"MBF", "MBKO", "MBKEI", "MBGIN", "MBKIN", "MBKAKU", "MBHI"];
let selectionGomaOrder = ["WGYOKU", "WF", "WKO", "WKEI", "WGIN", "WKIN", "WKAKU", "WHI",
"empty", "WNF", "WNKO","WNKEI", "WNGIN", "empty", "WNKAKU", "WNHI",
"empty", "BNF", "BNKO","BNKEI", "BNGIN", "empty", "BNKAKU", "BNHI",
"BGYOKU", "BF", "BKO", "BKEI", "BGIN", "BKIN", "BKAKU", "BHI","empty"];
let selectedSquare;

setMessage("将棋盤にタップして駒を選択してください");

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
spacer = 60;
for (jupiter = 0; jupiter < 2; jupiter++) { // initialize the mochigoma on the board
    for (x = 0; x < 7; x++) {
        if (jupiter === 0) { //if it's the first time through, we are drawing the white mochigoma
            mochiGoma[x] = document.createElement("img");//create a new img element for each mochigoma type
            mochiGoma[x].src = "/public/images/koma/1/" + mochiGomaOrder[x] + ".png";
            mochiGoma[x].setAttribute("id", mochiGomaOrder[x]);
            mochiGoma[x].setAttribute("onClick", "mochiGomaIncrement(this.id)");
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
            mochiGomaAmmount[x].setAttribute("id", mochiGomaOrder[x]+"Counter");
            document.getElementById("whiteMochigoma").appendChild(mochiGomaAmmount[x]);
        } else {//otherwise it's the second time through, so we are drawing the black mochigoma
            mochiGoma[x + 7] = document.createElement("img");//create a new img element for each mochigoma type
            mochiGoma[x + 7].src = "/public/images/koma/1/" + mochiGomaOrder[x + 7] + ".png";
            mochiGoma[x + 7].setAttribute("id", mochiGomaOrder[x + 7]);
            mochiGoma[x + 7].setAttribute("onClick", "mochiGomaIncrement(this.id)");
            mochiGoma[x + 7].style.width = "9vw";
            mochiGoma[x + 7].style.position = "absolute";
            mochiGoma[x + 7].style.right = spacer + "vw";
            mochiGoma[x + 7].style.top = "0vw";
            mochiGoma[x + 7].style.opacity = "0.5";
            document.getElementById("blackMochigoma").appendChild(mochiGoma[x + 7]);
            mochiGomaAmmount[x + 7] = document.createElement("img");
            mochiGomaAmmount[x + 7].src = "/public/images/mochiGomaNum2.png";
            mochiGomaAmmount[x + 7].style.width = "3vw";
            mochiGomaAmmount[x + 7].style.position = "absolute";
            mochiGomaAmmount[x + 7].style.right = spacer + "vw"; //offset it from the piece
            mochiGomaAmmount[x + 7].style.top = "0vw";
            mochiGomaAmmount[x + 7].style.visibility = "hidden";
            mochiGomaAmmount[x + 7].setAttribute("id", mochiGomaOrder[x + 7]+"Counter");
            document.getElementById("blackMochigoma").appendChild(mochiGomaAmmount[x + 7]);
        }
        spacer -= 10;
    }
    spacer = 60;
}

//create the pieces that go inside the popup selection box
spacer = 70.5;
for (jupiter = 0; jupiter < 4; jupiter++) {
    for (x = 0; x < 8; x++) {
        if (jupiter === 0) { //if it's the first time through, we are drawing the white pieces
            selectionGoma[x] = document.createElement("img");//create a new img element for each mochigoma type
            selectionGoma[x].src = "/public/images/koma/1/" + selectionGomaOrder[x] + ".png";
            selectionGoma[x].setAttribute("id", selectionGomaOrder[x]);
            selectionGoma[x].setAttribute("onClick", "addPiece(this.id)");
            selectionGoma[x].setAttribute("class", "selectionGoma");
            selectionGoma[x].style.width = "9vw";
            selectionGoma[x].style.position = "absolute";
            selectionGoma[x].style.right = spacer + "vw";
            selectionGoma[x].style.top = ".5vw";
            document.getElementById("choosePieceId").appendChild(selectionGoma[x]);

        }else if(jupiter == 1){//drawing the white narigoma
            selectionGoma[x] = document.createElement("img");//create a new img element for each mochigoma type
            selectionGoma[x].src = "/public/images/koma/1/" + selectionGomaOrder[x+8] + ".png";
            selectionGoma[x].setAttribute("id", selectionGomaOrder[x+8]);
            selectionGoma[x].setAttribute("onClick", "addPiece(this.id)");
            selectionGoma[x].setAttribute("class", "selectionGoma");
            selectionGoma[x].style.width = "9vw";
            selectionGoma[x].style.position = "absolute";
            selectionGoma[x].style.right = spacer + "vw";
            selectionGoma[x].style.top = "10.5vw";
            document.getElementById("choosePieceId").appendChild(selectionGoma[x]);

        }else if(jupiter == 2){//drawing the black narigoma
            selectionGoma[x] = document.createElement("img");//create a new img element for each mochigoma type
            selectionGoma[x].src = "/public/images/koma/1/" + selectionGomaOrder[x+16] + ".png";
            selectionGoma[x].setAttribute("id", selectionGomaOrder[x+16]);
            selectionGoma[x].setAttribute("onClick", "addPiece(this.id)");
            selectionGoma[x].setAttribute("class", "selectionGoma");
            selectionGoma[x].style.width = "9vw";
            selectionGoma[x].style.position = "absolute";
            selectionGoma[x].style.right = spacer + "vw";
            selectionGoma[x].style.top = "21vw";
            document.getElementById("choosePieceId").appendChild(selectionGoma[x]);

        }else {//otherwise it's the last time through, so we are drawing the black pieces
            selectionGoma[x + 9] = document.createElement("img");//create a new img element for each mochigoma type
            selectionGoma[x + 9].src = "/public/images/koma/1/" + selectionGomaOrder[x + 24] + ".png";
            selectionGoma[x + 9].setAttribute("id",  selectionGomaOrder[x + 24]);
            selectionGoma[x + 9].setAttribute("onClick", "addPiece(this.id)");
            selectionGoma[x + 9].setAttribute("class", "selectionGoma");
            selectionGoma[x + 9].style.width = "9vw";
            selectionGoma[x + 9].style.position = "absolute";
            selectionGoma[x + 9].style.right = spacer + "vw";
            selectionGoma[x + 9].style.top = "31.5vw";
            document.getElementById("choosePieceId").appendChild(selectionGoma[x + 9]);
        }
        spacer -= 10;
    }
    spacer = 70.5;
}

drawBoard();

function pieceClick(id){
    //bring up a pop-up menu showing possible pieces to place
    if(id<44){
        document.getElementById("choosePieceId").classList.toggle("pieceSelectionShowLow");
    }else if(id>44){
        document.getElementById("choosePieceId").classList.toggle("pieceSelectionShowHigh");
    }
    selectedSquare = id;
}
function mochiGomaIncrement(id){
    //increment the mochigoma count
    let komaToIncrement = document.getElementById(id);
    let ammount = mochiGomaConfiguration[mochiGomaOrder.indexOf(id)];

   if(ammount < 4){
       ammount ++;
   }else{
       ammount = 0;
   }

   if(ammount>0){
       //make the koma fully visible
       komaToIncrement.style.opacity = "1";
   }else{
       //otherwise make it see-thru
       komaToIncrement.style.opacity = "0.5";
   }
   mochiGomaConfiguration[mochiGomaOrder.indexOf(id)] = ammount;
   if(ammount>1){
    document.getElementById(id+"Counter").style.visibility = "visible";
   document.getElementById(id+"Counter").src = "/public/images/mochiGomaNum"+ammount+".png";
   } else {
    document.getElementById(id+"Counter").style.visibility = "hidden";
   }
}

function addPiece(id){
    //place the piece into the gameboard
    boardConfiguration[selectedSquare] = id;
    boardSquare[selectedSquare].src = "/public/images/koma/1/"+id+".png";
    if(selectedSquare<44){
        document.getElementById("choosePieceId").classList.toggle("pieceSelectionShowLow");
    }else{
        document.getElementById("choosePieceId").classList.toggle("pieceSelectionShowHigh");
    }
}

function drawBoard(){
    //draw pieces on the board
    for(x=0; x < boardConfiguration.length; x++){
        boardSquare[x].src = "/public/images/koma/1/"+boardConfiguration[x]+".png";
    }

    //draw mochigoma
    for (i = 0; i < 8; i += 7) {
        for (x = 0; x < 7; x++) {
            if (mochiGomaConfiguration[x + i] > 0) {//if there is/are mochigoma of that type
                mochiGoma[x + i].style.opacity = "1";
                if(mochiGomaConfiguration[x + i] > 1){
                    mochiGomaAmmount[x + i].style.visibility = "visible";
                    mochiGomaAmmount[x + i].src = ("/public/images/mochiGomaNum" + mochiGomaConfiguration[x + i] + ".png"); //make it display the correct number

                }
                } else if (mochiGomaConfiguration[x + i] == 1) {
                mochiGoma[x + i].style.visibility = "visible"; //show the piece
                mochiGomaAmmount[x + i].style.visibility = "hidden";//but no number
            } else {
                mochiGoma[x + i].style.opacity = "0.5"; //otherwise hide it from view
                mochiGomaAmmount[x + i].style.visibility = "hidden";//and hide the number
            }
        }
    }

}
function toCreatePage(){

    document.getElementById("boardConfig").value = boardConfiguration.toString();
    document.getElementById("mochigomaConfig").value = mochiGomaConfiguration.toString();

    //send form
    document.getElementById('configData').submit();


}
function reset(){
    document.getElementById("resetTsume").submit();
}
