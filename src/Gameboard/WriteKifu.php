<?php
require '../connect.php';
$getGameData = mysqli_query($link, "SELECT * FROM gamerecord WHERE id = '".$_GET['id']."'");
$gameDataArray = mysqli_fetch_array($getGameData);

$movesArray = explode(",", $gameDataArray['moves']);

$filename = $gameDataArray['blackplayer']."_vs_".$gameDataArray['whiteplayer']."_".date("Y")."-".date("M")."-".date("d").".kif";
$kifu = fopen($filename, "w");

$turnCounter = 1;
$lastMove = 0;
$baseKifu = 
"開始日時：".$gameDataArray['dateStarted']."
終了日時：".date('Y')."/".date('m')."/".date('d')."
場所：SLO Shogi
手合割：平手
先手：".$gameDataArray['blackplayer']." 
後手：".$gameDataArray['whiteplayer']."
手数----指手---------消費時間--";

function convertSquare($num){
    //converts the 0 - 80 number into kif format
    $num1 = intdiv($num, 9) + 1;
    $num2 = (($num % 9) + 1) * 10;
    return $num1 + $num2;
}
function numberToKanji($num){
    //convert the hankaku number to zenkaku
    $turnToZenkaku = substr($num,0,1);
switch ($turnToZenkaku){
    case 1:
        $zenkaku = "１";
        break;
    case 2:
        $zenkaku = "２";
        break;
    case 3:
        $zenkaku = "３";
        break;
    case 4:
        $zenkaku = "４";
        break;
    case 5:
        $zenkaku = "５";
        break;
    case 6:
        $zenkaku = "６";
        break;
    case 7:
        $zenkaku = "７";
        break;
    case 8:
        $zenkaku = "８";
        break;
    case 9:
        $zenkaku = "９";
        break;
}
//convert the second number to kanji
$turnToKanji = substr($num, -1);//get the last digit from the number
switch ($turnToKanji){
    case 1:
        $kanji = "一";
        break;
    case 2:
        $kanji = "二";
        break;
    case 3:
        $kanji = "三";
        break;
    case 4:
        $kanji = "四";
        break;
    case 5:
        $kanji = "五";
        break;
    case 6:
        $kanji = "六";
        break;
    case 7:
        $kanji = "七";
        break;
    case 8:
        $kanji = "八";
        break;
    case 9:
        $kanji = "九";
        break;
}

return $zenkaku.$kanji; //return the new number/kanji pair
}
function pieceToKanji($piece){
    switch (substr($piece, 1)){ //need to cut off the black or which letter at the beginning
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

//go through each move and convert it to .kif move 
for($i = 0; $i < sizeof($movesArray); $i += 3){
    $baseKifu .= "\r\n".$turnCounter." ";//add the turn

    //if the player resigned
    if($movesArray[$i] == 100){
        $baseKifu .= "投了 ( 00:00/00:00:00)";
    }else if($movesArray[$i] == 101){//if checkmate
        $baseKifu .= "詰み ( 00:00/00:00:00)";
    }else{

    if($lastMove == $movesArray[$i+1]){//if it is a 同 capture
        $baseKifu .= "同　";
    }else{
        //turn the first move into number/kanji pair
        $baseKifu .= numberToKanji(convertSquare($movesArray[$i+1]));
    }
    //set the $lastMove variable for next time
    $lastMove = $movesArray[$i+1];

    //convert the piece into kif kanji format and add to string
    $baseKifu .= pieceToKanji($movesArray[$i+2]);

    //if it's a mochigoma
    if($movesArray[$i] == 81){
        $baseKifu .= "打 ( 00:00/00:00:00)";
    }else{
    //convert the move from piece to kif format and add time to the end
    $baseKifu .= "(".convertSquare($movesArray[$i]).")( 00:00/00:00:00)";
    }

    $turnCounter ++;
}
}
//convert to Shift JIS encoding https://stackoverflow.com/questions/34078845/convert-from-utf-8-to-shift-jis
$baseKifu = mb_convert_encoding($baseKifu, "SJIS", "UTF-8");
fwrite($kifu, $baseKifu);

fclose($kifu);

//download the file
//https://linuxhint.com/download_file_php/


//Define header information
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: 0");
header('Content-Disposition: attachment; filename="'.basename($filename).'"');
header('Content-Length: ' . filesize($filename));
header('Pragma: public');

//Clear system output buffer
flush();

//Read the size of the file
readfile($filename);

unlink($filename);//delete the file
die();


echo $baseKifu;

?>