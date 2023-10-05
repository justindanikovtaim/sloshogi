<?php
require_once SHAREDPATH . 'database.php';

$json = file_get_contents('php://input'); //https://www.geeksforgeeks.org/how-to-receive-json-post-with-php/
$gametofind = json_decode($json, true); //adding the 'true' here is critical, but not shown in the page above
print_r($gametofind);

$updatecommand = "UPDATE gamerecord SET moves = ? WHERE id = ?";
safe_sql_query($updatecommand, ['i', $gametofind['id']]);
