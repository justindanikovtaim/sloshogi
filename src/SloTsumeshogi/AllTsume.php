<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
$result = safe_sql_query("SELECT id, problemName, createdBy, completed FROM tsumeshogi WHERE published = ?", ['i', 1]);

begin_html_page('Slo Tsumeshogi List');
?>

<a href="/slo_tsumeshogi" id="backButton">≪</a><br>
<h1>SLO詰将棋問題集</h1>

<?php

while ($row = mysqli_fetch_array($result)) :
    //list all of the tsume shogi problems
    $tempVar = explode(";", $row['completed']);
    //make the url color different
    if (in_array($_COOKIE['current_user_cookie'], $tempVar)) : ?>
        <a class='problemList' style='color: grey' href="/tsume?id=<?php echo $row['id'] ?>">
            <?php echo $row['problemName'] ?> by <?php echo $row['createdBy'] ?>
        </a>
        <br>
    <?php else : ?>
        <a class='problemList' style='color: black' href="/tsume?id=<?php echo $row[' id'] ?>">
            <?php echo $row['problemName'] ?> by <?php echo $row['createdBy'] ?>
        </a>
        <br>
    <?php endif; ?>
<?php endwhile; end_html_page(); ?>
