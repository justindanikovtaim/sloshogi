<?php
require 'connect.php';
$in = file_get_contents('php://input');
$decoded = json_decode($in, true);
$problemTofind = $decoded['problemId'];
$user = $_COOKIE['current_user_cookie'];
$solveTime = $decoded['scoreBoard'];
$insertArray = [$user.";", $solveTime.";"];
echo "insert arry:";echo print_r($insertArray);
$getStandings = mysqli_query($link, "SELECT scoreBoard FROM tsumeshogi WHERE id = '".$problemTofind."'");
$getStandingsArray = mysqli_fetch_array($getStandings);
$standingsArray = explode(";", $getStandingsArray['scoreBoard']);
echo 'solve time:'.$solveTime.'standings array';
echo print_r($standingsArray);
echo "size of standings arry:".sizeof($standingsArray);
$updatedStandings="";
//$newArray = ["default","basic"];
if(sizeof($standingsArray)<2){//if there is noone in the standings yet
    $updatedStandings = $insertArray[0] . $insertArray[1];
}else{
    $inserted = false;
    for($i = 0; $i < (sizeof($standingsArray) -1); $i+=2){
        if($inserted == false && $solveTime < $standingsArray[$i+1]){
            $updatedStandings.= $insertArray[0] . $insertArray[1];
            $inserted = true;
            $updatedStandings.= $standingsArray[$i].";".$standingsArray[$i+1].";";  
        }else{
        $updatedStandings.= $standingsArray[$i].";".$standingsArray[$i+1].";";  
        }
    }
    if($inserted == false){
        //if it is the lowest score
        $updatedStandings.= $insertArray[0] . $insertArray[1];
    }
}
$query = "UPDATE tsumeshogi SET scoreBoard = '".$updatedStandings."' WHERE id = '".$problemTofind."'";
echo $query;
mysqli_query($link, $query);

?>