<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';

$id = $_GET['id'];

if (safe_sql_query("DELETE FROM gamerecord WHERE id = ?", ['s', $id])) {
    header('Location: /user-page');
} else {
    echo "There was an error. Please try again";
}

begin_html_page('Slo Shogi Challenge');

?>
<a id="backButton" href="/view-challenge?id=<?php echo $id ?>">≪</a>

<?php end_html_page(); ?>
