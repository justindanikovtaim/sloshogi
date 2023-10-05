<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';

/**
 * Use HTTP REFERER to return to previous page instead of passing URL params,
 * if referer is not defined or unsupported then use javascript to go back in history.
 */
$sourcePage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'javascript:history.go(-1)';

begin_html_page("SLO Shogi Feedback", ['form.css']);
?>

<a id="backButton" href="<?php echo $sourcePage ?>">≪</a>

<form action="/send-feedback" name="feedbackData" method="post">
    <h5>フィードバックの種類・Feedback Type:</h5>
    <input type="radio" id="bug" name="fBType" value="bug">
    <label for="bug" class="radioText">バグ・Bug</label><br>
    <input type="radio" id="suggestion" name="fBType" value="suggestion">
    <label for="suggestion" class="radioText">サジェスチョン・Suggestion</label><br>
    <input type="radio" id="other" name="fBType" value="other">
    <label for="other" class="radioText">その他・Other</label><br><br>
    <label for="comment" class="radioText">詳細・Details</label><br>
    <textarea id="comment" name="comment" required></textarea>
    <input type="hidden" name="src" value="<?php echo $sourcePage ?>">
    <input type="submit" value="送信・Send">
</form>

<?php
end_html_page();
