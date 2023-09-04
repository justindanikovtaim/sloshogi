<?php

$json = file_get_contents('php://input');
$decoded = json_decode($json, true);
$gametofind = $decoded['id'];

require_once SHAREDPATH . 'database.php';

safe_sql_query("UPDATE gamerecord SET status = 3 WHERE id = ?", ['i', $gametofind]);

?>
