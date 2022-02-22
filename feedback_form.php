
<?php 
require 'connect.php';
  
$sourcePage = htmlspecialchars($_GET['src']).".php?id=".htmlspecialchars($_GET['id']);


?>
<!DOCTYPE html>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"></meta>
<head>
<link href="CSS/all_pages.css" rel="stylesheet">
<link href="CSS/form.css" rel ="stylesheet">
</head>
<body>
<a id = "backButton" href = "<?=$sourcePage?>">≪</a>


<form action="send_feedback.php" name = "feedbackData" method = "post">
<h5>フィードバックの種類・Feedback Type:</h5>
<input type = "radio" id = "bug" name = "fBType" value = "bug">
<label for="bug" class = "radioText">バグ・Bug</label><br>
<input type = "radio" id = "suggestion" name = "fBType" value = "suggestion">
<label for="suggestion" class = "radioText">サジェスチョン・Suggestion</label><br>
<input type = "radio" id ="other" name = "fBType" value = "other">
<label for ="other" class = "radioText">その他・Other</label><br><br>
<label for="comment" class = "radioText">詳細・Details</label><br>
<textarea id ="comment" name ="comment" required></textarea>
<input type ="hidden" name="src" value ="<?=$sourcePage?>">
<input type="submit" value="送信・Send">
</form>

</body>


