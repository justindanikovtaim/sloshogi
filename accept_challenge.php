<?php

require 'connect.php';
if(mysqli_query($link,"UPDATE gamerecord SET status = 2 WHERE id = '".$_GET['id']."'")){
    header('Location: gameboard.php?id='.$_GET['id']);
}else{
    echo "There was an error. Please try again";
}
    


?> 
<!DOCTYPE html>
<head>
    <title>Slo Shogi Challenge</title>
    <link href="CSS/all_pages.css" rel="stylesheet">
</head>
<body>
<a id = "backButton" href = "view_challenge.php?id=<?=$_GET['id']?>">≪</a>

</body>
 </html>