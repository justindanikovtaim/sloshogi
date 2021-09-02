<?php

$link = mysqli_connect('localhost', 'christopherd', 'A*3BYyM5o#Qcs', 'sloshogi');//*******UPDATE**********/
if(mysqli_query($link,"DELETE FROM gamerecord WHERE id = '".$_GET['id']."'")){
    header('Location: user_page.php');
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