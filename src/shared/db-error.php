<?php
require_once ABSPATH . 'template.php';

/**
 * Renders the HTML markup for the database error page.
 *
 * @package db-error
 */
begin_html_page('Database Error');
?>

<h1>データベースのエラーが発生しました。少し待ってからやり直すか <a href="feedback_form.php">バッグを報告してください</a>
  <br>Database error. Please try again later or <a href="feedback_form.php">report a bug</a>
</h1>
<h3><a href="user_page.php">ユーザーページへ　To User Page</a></h3>

<?php
end_html_page();
