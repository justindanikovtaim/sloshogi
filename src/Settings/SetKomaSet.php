<?php
$newKomaSet = $_GET['newKomaSet'];

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'session.php';

safe_sql_query("UPDATE users SET komaSet = ? WHERE username = ?", ['ss', $newKomaSet, getCurrentUser()]);
header("Location: /settings");
