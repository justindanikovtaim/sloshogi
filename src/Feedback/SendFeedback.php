<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

$sourcePage = htmlspecialchars($_POST['src']);
$feedbackType = htmlspecialchars($_POST['fBType']);
$comment = htmlspecialchars($_POST['comment']);

$query = safe_sql_query("INSERT INTO feedback (fbtype, user, source, comment)
    VALUES (?, ?, ?, ?)", ['ssss', $feedbackType, getCurrentUser(), $sourcePage, $comment]);

begin_html_page("SLO Shogi Feedback");
?>

<h2>
    <a id="backButton" href="<?php echo $sourcePage ?>">≪</a>
    <br><br>
    <?php if ($query) : ?>
        フィードバックが配信されました！ありがとうございます。Feedback Sent! Thank you
    <?php endif; ?>
</h2>

<?php end_html_page(); ?>
