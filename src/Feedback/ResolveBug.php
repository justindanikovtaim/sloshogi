<?php
require_once SHAREDPATH . 'database.php';

if (safe_sql_query("DELETE FROM feedback WHERE id = ?", ['i', $_GET['id']])) {
    header('location: /dashboard');
}
