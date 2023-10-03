<?php
require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'session.php';

$boardConfig = $_POST['boardConfig'];
$problemName = $_POST['problemName'];
$mochigomaConfig = $_POST['mochigomaConfig'];
$moveSequence = $_POST['moveSequence'];
$timeLimit = $_POST['timeLimit'];
$createdBy = getCurrentUser();
if (isset($_GET['reSave'])) {
    $temp = $_GET['reSave'];
    //need to just update the problem. WHERE finds both the id (stored in $_GET['reSave']) as well as the user's nusername, in case they changed the URL to a different problem
    $query = "UPDATE tsumeshogi SET problemName = '$problemName', boardSetup = '$boardConfig', mochigomaSetup = '$mochigomaConfig', mainSequence = '$moveSequence', timeLimit = '$timeLimit' WHERE id = '$temp' AND createdBy = '$createdBy'";
} else {
    //this nonsense is needed in case a prolem is deleted. It resets the auto-increment to whatever the next number above the highest current id is
    $lastRow = safe_sql_query("SELECT MAX(id) FROM tsumeshogi");
    $result = mysqli_fetch_row($lastRow);
    $number = $result[0];
    safe_sql_query("ALTER TABLE tsumeshogi AUTO_INCREMENT = ?", ['i', $number + 1]);


    $query = "INSERT INTO tsumeshogi (problemName, boardSetup, mochigomaSetup, mainSequence, createdBy, timeLimit) VALUES ('$problemName', '$boardConfig', '$mochigomaConfig', '$moveSequence', '$createdBy', '$timeLimit')";
}

begin_html_page('Save Slo tsumeshogi');
?>
<?php if (safe_sql_query($query)) : ?>
    <h1>詰将棋問題は保存されました</h1><br>
    <a href='/slotsumeshogi'>詰将棋ページへ移動　Go to tsumeshogi page</a>
<?php else : ?>
    サーバーへの接続が失敗しました。後でもう一回試してください　Error connecting to the server. Please try again later<br>
    <a href='/slotsumeshogi'>詰将棋ページへ移動　Go to tsumeshogi page</a>
<?php endif ?>
<?php end_html_page() ?>
