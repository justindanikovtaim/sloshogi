<?php
$allIcons = glob("images/koma/".$_GET['newKomaSet']."/*.png"); //https://www.tutorialspoint.com/get-all-the-images-from-a-folder-in-php
?>

<!DOCTYPE HTML>
<head>

    <link href="CSS/all_pages.css" rel="stylesheet">
    <link href="CSS/icon_select.css" rel="stylesheet">

 </head>
 <body>
 <a id = "backButton" href = "change_koma_set.php">≪</a>
 <br><br>
 <a href = "set_koma_set.php?newKomaSet=<?=$_GET['newKomaSet']?>"><h1>この駒セットを使用</h1></a>
     <?php
     
     foreach($allIcons as $icon){
         //isolate only the name of the icon without the .php at the end
         echo ' 
         <img src="'.$icon.'" class = "iconSelect" id = "'.$icon.'"><br>';
     }
     ?>

 </body>