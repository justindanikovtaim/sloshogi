<?php
$allIcons = glob("images/icons/*.png"); //https://www.tutorialspoint.com/get-all-the-images-from-a-folder-in-php
?>

<!DOCTYPE HTML>
<head>

    <link href="CSS/all_pages.css" rel="stylesheet">
    <link href="CSS/icon_select.css" rel="stylesheet">

 </head>
 <body>
     <h1>アイコンを選択してください<br> Please choose your icon</h1>
     <?php
     
     foreach($allIcons as $icon){
         //isolate only the name of the icon without the _icon.php at the end
         echo '<a href = "set_icon.php?newIcon='.substr($icon, 12, (strlen($icon) - 21 )).'"> 
         <img src="'.$icon.'" class = "iconSelect" id = "'.$icon.'"></a> <br>';
     }
     ?>

 </body>