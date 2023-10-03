<?php
$newIcon = $_GET['newIcon'];

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'session.php';

setcookie('icon', $newIcon, time() + (86400 * 365), "/");
safe_sql_query("UPDATE users SET icon = ? WHERE username = ?", ['ss', $newIcon, getCurrentUser()]);
header("Location: /settings");
