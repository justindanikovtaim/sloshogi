<?php
require 'connect.php';
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
  }

$enteredPass1 = htmlspecialchars($_POST['pass']);
$enteredPass2 = htmlspecialchars($_POST['confirmPass']);
$OTP = htmlspecialchars($_POST['OTP']);


if($enteredPass1 != $enteredPass2){
    echo "パスワードが異なる　Passwords do not match <br>";
    echo "<a href = 'account_setup.php?OTP=".$OTP."'>戻る　Go Back</a>";
    }else{
        $result = mysqli_query($link, "SELECT * FROM newaccounts WHERE OTP =".$OTP);
        //get the user's email and username based on the OTP
        $getNewAccountInfo = mysqli_fetch_array($result);
        $newEmail = $getNewAccountInfo['email'];
        $newUsername = $getNewAccountInfo['username'];
        $enteredPass1 = password_hash($enteredPass1, PASSWORD_DEFAULT);

        $addUserQuery = "INSERT INTO users (username, email, pass) VALUES ('".$newUsername."', '".$newEmail."', '".$enteredPass1."')";
        if(mysqli_query($link, $addUserQuery)){

            setcookie('current_user_cookie', $newUsername, time() + (86400 * 30), "/");//set the cookie so they can visit the user page 
            echo "<h2>アカウントが確定されました</h2> <br>";
            echo "<a href = 'user_page.php'>ユーザーページへ移動</a>";
            
            //delete the record from the newaccounts table
            mysqli_query($link, "DELETE FROM newaccounts WHERE OTP =".$OTP);

            //see if the user has a saved highscore from tsumeShogi
            if(isset($_COOKIE['tsume_score'])){
                //add the user to the list of userswho finished the problem
                mysqli_query($link, "UPDATE tsumeshogi SET completed = CONCAT(completed, '".$newUsername.";') WHERE id = '".$_COOKIE['tsume_problem']."'");
                
                //update the scoreboad for that problem
                if($_COOKIE['tsume_score']>0){


                    $insertArray = [$newUsername.";", $_COOKIE['tsume_score'].";"];
                    $getStandings = mysqli_query($link, "SELECT scoreBoard FROM tsumeshogi WHERE id = '".$_COOKIE['tsume_problem']."'");
                    $getStandingsArray = mysqli_fetch_array($getStandings);
                    $standingsArray = explode(";", $getStandingsArray['scoreBoard']);
                    $updatedStandings="";
                    //$newArray = ["default","basic"];
                    if(sizeof($standingsArray)<2){//if there is noone in the standings yet
                        $updatedStandings = $insertArray[0] . $insertArray[1];
                    }else{
                        $inserted = false;
                        for($i = 0; $i < (sizeof($standingsArray) -1); $i+=2){
                            if($inserted == false && $_COOKIE['tsume_score'] < $standingsArray[$i+1]){
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
                    $query = "UPDATE tsumeshogi SET scoreBoard = '".$updatedStandings."' WHERE id = '".$_COOKIE['tsume_problem']."'";
                    mysqli_query($link, $query);
                }
            }
        }else{
            echo mysqli_error($link);
             echo "サーバーへの接続が失敗しました。後でもう一回試してください　Error connecting to the server. Please try again later<br>";
             echo "<a href = 'account_setup.php?OTP=".$OTP."'>戻る Back</a>";
        } 

    }       


    ?>

<!DOCTYPE HTML>
<head>
    <meta charset="utf-8">
    <title>Slo Shogi Create Finalize Account</title>
    
    <link href="CSS/all_pages.css" rel="stylesheet">

</head>

<body>
</body>