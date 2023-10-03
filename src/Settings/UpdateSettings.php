<?php
require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'session.php';

$newHitokoto = $_POST['hitokoto'];

 safe_sql_query("UPDATE users SET hitokoto = ? WHERE username = ?", ['ss', $newHitokoto, getCurrentUser()]);

 header('location: /user-page');
?>
