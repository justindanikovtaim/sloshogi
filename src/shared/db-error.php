<?php
require_once SHAREDPATH . 'template.php';

/**
 * Renders the HTML markup for the database error page.
 *
 * @package db-error
 */

// Begin HTML page with title 'Database Error'
begin_html_page('Database Error');
?>

<h1>データベースのエラーが発生しました。少し待ってからやり直すか <a href="feedback_form.php">バッグを報告してください</a>
  <br>Database error. Please try again later or <a href="feedback_form.php">report a bug</a>
</h1>
<h3><a href="/user-page">ユーザーページへ　To User Page</a></h3>

<?php
// End HTML page
end_html_page();
