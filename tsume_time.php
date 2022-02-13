<?php
require 'public_connect.php';
$in = file_get_contents('php://input');
$decoded = json_decode($in, true);
$id = $decoded['id'];
$seconds = $decoded['seconds']*1;//* 1makes sure that it's a number

setcookie($id.'timeLimit', $seconds, time() + (86400 * 365), "/"); // 86400 = 1 day

?>