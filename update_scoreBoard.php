<?php
require 'connect.php';
$in = file_get_contents('php://input');
$decoded = json_decode($in, true);
$problemTofind = $decoded['problemId'];
$user = $_COOKIE['current_user_cookie'];
$solveTime = $decoded['scoreBoard'];

$insertArray = [$user.";", $solveTime.";"];

$getStandings = mysqli_query($link, "SELECT scoreBoard FROM tsumeshogi WHERE id = '".$problemTofind."'");
$getStandingsArray = mysqli_fetch_array($getStandings);
$standingsArray = explode(";", $getStandingsArray['scoreBoard']);

$updatedStandings="";

if(sizeof($standingsArray)<2){//if there is noone in the standings yet
    $updatedStandings = $insertArray[0] . $insertArray[1];

}else{
    for($i=0;$i<sizeof($standingsArray);$i+=2){
        //if the new time is better than the time being checked
        if($solveTime < $standingsArray[$i+1]){
            //insert it into the array before the time being checked
            array_splice($getStandingsArray, $i, $insertArray);
            break;
        }
    }
    //turn the array back into a string
    for($i=0;$i<sizeof($standingsArray);$i++){
        $updatedStandings .=$standingsArray[$i];
    }
}


$query = "UPDATE tsumeShogi SET scoreBoard = '".$updatedStandings."' WHERE id = '".$problemTofind."'";
mysqli_query($link, $query);

?>