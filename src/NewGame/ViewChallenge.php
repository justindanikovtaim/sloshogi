<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';

$gameID = $_GET['id'];

$result = safe_sql_query("SELECT * FROM gamerecord WHERE id = ?", ['s', $gameID]);
$row = mysqli_fetch_array($result);

begin_html_page('Slo Shogi', ['view_challenge.css']);

?>

<a id = "backButton" href = "/user-page">≪</a>
<br><br>
<h2 class = "centered">Challenge From <?php echo $row["creator"] ?>　からのチャレンジ</h2>
<h1>黒Black: <?php echo $row["blackplayer"] ?> </h1>

<img src = "/public/images/untouchedBoard.JPG" id = "boardPhoto">
<h1>白White: <?php echo $row["whiteplayer"] ?></h1>

<div id = "accept">
<h2><a href = "/accept-challenge?id=<?php echo $gameID ?>">承認 Accept</a></h2>
<h2><a href = "/decline-challenge?id=<?php echo $gameID ?>">拒否 Decline</a></h2>
</div>

<?php end_html_page() ?>
