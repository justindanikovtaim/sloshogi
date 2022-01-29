<?php
$allIcons = glob("images/koma/preview/*.png"); //https://www.tutorialspoint.com/get-all-the-images-from-a-folder-in-php
?>

<!DOCTYPE HTML>
<head>

    <link href="CSS/all_pages.css" rel="stylesheet">
    <link href="CSS/icon_select.css" rel="stylesheet">

 </head>
 <body>
 <a id = "backButton" href = "settings.php">≪</a>
 <br><br><br>
     <h1 style="font-size:6vw">駒をタップしてプレビューを拡大</h1>
     <?php
     
     foreach($allIcons as $icon){
         //isolate only the name of the icon without the .php at the end
         echo '<a href = "preview_koma_set.php?newKomaSet='.substr($icon, 20, (strlen($icon) - 24 )).'"> 
         <img src="'.$icon.'" class = "iconSelect" id = "'.$icon.'"></a> <br>';
     }
     ?>

 </body>