<?php

require 'connect.php';
if(isset($_POST['unOrEmail'])){
    $getResult =  mysqli_query($link, 'SELECT * FROM users WHERE username = "'.$_POST['unOrEmail'].'"');
    
    if($getResult->num_rows == 0){//if no matches in username
        $getResult =  mysqli_query($link, 'SELECT * FROM users WHERE email =  "'.$_POST['unOrEmail'].'"');
    } 

    if($getResult->num_rows == 0){//if still no match
        $resultId = 0;
    }else{
        $resultArray = mysqli_fetch_array($getResult);
        $resultId = $resultArray['id'];
        $result = $resultArray['username'];
        $resultRating = $resultArray['rating'];
    }
}

?>

<!DOCTYPE html>
<head>
<link href="CSS/all_pages.css" rel="stylesheet">
</head>
<body>
<a id = "backButton" href = "friends.php">≪</a><br><br>
<h3>友達ユーザー名またはメールアドレス<br>Friend's username or email </h3>
<form action="add_friends.php" method="post">
<input type="text" name="unOrEmail"><br>
<input type ="submit"value="検索 Search">
</form>
<br>
<div id="result">
    <h1>検索結果　Result</h1>
    <h5>
    <?php
    if(isset($resultId)){
        if($resultId == 0){
            echo "検索結果なし No Results";
        }else{
            echo "<a href='view_friend.php?friendName=".$result."'>".$result." ".$resultRating."</a>";
        }
    }
    ?>
    </h5>
</div>
</body>
