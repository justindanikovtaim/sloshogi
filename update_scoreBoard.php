<?php
require 'connect.php';
$in = file_get_contents('php://input');
$decoded = json_decode($in, true);
$problemTofind = $decoded['problemId'];
$user = $_COOKIE['current_user_cookie'];
$solveTime = $decoded['scoreBoard'];
$resetTime = $decoded['startingTime'];

//update the completed list 
//turn the current completed list string into an array
$getCompletedList = mysqli_query($link, "SELECT completed FROM tsumeshogi WHERE id = '".$problemTofind."'");
$getCompletedArray = mysqli_fetch_array($getCompletedList);
$completedArray = explode(";", $getCompletedArray['completed']);
//check if the user's name is already in it
if(!in_array($user, $completedArray)){
//if not, add it to the end of string in the database
echo "user not in winner array!";
mysqli_query($link, "UPDATE tsumeshogi SET completed = CONCAT(completed, '".$user.";') WHERE id = '".$problemTofind."'");

//next, test to see if updating the scoreBoard
if($solveTime>0){


    $insertArray = [$user.";", $solveTime.";"];
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
    }
}
//reset the cookie
setcookie($problemTofind.'timeLimit', $resetTime, time() + (86400 * 365), "/");

?>