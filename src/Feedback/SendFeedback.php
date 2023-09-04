<?php

require 'connect.php';

    $sourcePage = htmlspecialchars($_POST['src']);
    $feedbackType = htmlspecialchars($_POST['fBType']);
    $comment = htmlspecialchars($_POST['comment']);

    $query = "INSERT INTO feedback (fbtype, user, source, comment) 
    values ('".$feedbackType."', '".$_COOKIE['current_user_cookie']."', '".$sourcePage."', '".$comment."')";
  
?> 
<!DOCTYPE html>
<head>
    <title>Slo Shogi Feedback</title>
    <link href="CSS/all_pages.css" rel="stylesheet">

</head>
<h2>
<a id = "backButton" href = "<?=$sourcePage?>">≪</a>
<br><br>
<?php
    if(mysqli_query($link, $query)){
        echo "フィードバックが配信されました！ありがとうございます。Feedback Sent! Thank you";
    } else{
        echo "ERROR: Not able to execute $newChallenge. " . mysqli_error($link);
    }

 ?>
 </h2>


 </html>