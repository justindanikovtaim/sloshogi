<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';

$result = safe_sql_query("SELECT id, problemName, createdBy, published FROM tsumeshogi WHERE createdBy = ?", ['s', getCurrentUser()]); //get all the current from moves

begin_html_page('Slo Tsumeshogi');
?>

<a href="/slotsumeshogi" id="backButton">≪</a><br>
<h1>SLO詰将棋問題集</h1>
<?php
while ($row = mysqli_fetch_array($result)) : ?>
    <a class='problemList' href='/slotsumeshogi/tsume?id=" . $row[' id'] . "'>#" . $row['id'] . " " . $row['problemName'] . " by " . $row['createdBy'] . "</a>
    <a class = 'problemList' href = '/slotsumeshogi/edit-tsume?id=" . $row['id'] . "'><button>編集</button></a>
    <?php if ($row['published'] == '0') : ?>
        <a class = 'problemList' href = '/slotsumeshogi/publish-tsume?id=<?php echo $row['id'] ?>&pUp=1'><button>公開する</button></a><br>
    <?php else : ?>
        <a class = 'problemList' href = '/slotsumeshogi/publish-tsume?id=<?php echo $row['id'] ?>&pUp=0'><button>非公開にする</button></a><br>
    <?php endif; ?>
<?php endwhile; ?>
<?php end_html_page(); ?>
