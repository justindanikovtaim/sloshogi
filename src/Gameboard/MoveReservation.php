<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

$gameID = $_GET['id'];
// 6/27 reducing the reservation buttons from 3 to 1
//$reservationSlot = "reservation".$_GET['resBox'];

$result = safe_sql_query('SELECT * FROM gamerecord WHERE id = ?', ['i', $gameID]);
if (isset($_GET['komaSet'])) {
    $komaSet = $_GET['komaSet'];
} else {
    $komaSet = 1;
}

begin_html_page('Slo Shogi - Reserve', ['reservation.css'], [], true);
?>
<div id=wholeBoard>
    <div id="whiteMochigoma"></div>
    <div id="board"></div>
    <div id="blackMochigoma"></div>
</div>
<h3 id="playerPrompt"></h3>
<a href="/gameboard?id=<?php echo $_GET['id'] ?>"> <img src="/public/images/return.png" id="toUserPage"> </a>
<img src="/public/images/submit.PNG" id="submitButton" onClick="sendMoveData()">

<?php
$temparray = array();
$row = mysqli_fetch_array($result);
array_push($temparray, $row["moves"], $row["blackplayer"], $row["whiteplayer"]);
?>

<script>
    var currentGameID = <?php echo $gameID; ?>;
    var reservationSlot = "<?php echo $reservationSlot ?>";
    var gameHistory = <?php echo json_encode($temparray); ?>;
    var phpColor = "<?php echo $_COOKIE['current_user_cookie']; ?>";
    var komaSet = <?php echo $komaSet ?>;
</script>

<script src="/public/js/move-reservation.js"></script>

<?php
end_html_page();
