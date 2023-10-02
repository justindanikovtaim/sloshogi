<?php

require_once 'database.php';
require_once 'session.php';

// function authenticateUser($enteredPassword, $currentUser)
// {
//   $verifyPWQuery = safe_sql_query("SELECT pass FROM users WHERE BINARY username = ?", ['s', $currentUser]);
//   $verifyPW = mysqli_fetch_array($verifyPWQuery, MYSQLI_NUM);

//   if (!password_verify($enteredPassword, $verifyPW[0]) && $enteredPassword != $verifyPW[0]) {
//     return false;
//   }

//   $getUserIcon = safe_sql_query("SELECT icon FROM users WHERE username = ?", ['s', $currentUser]);
//   $icon = mysqli_fetch_array($getUserIcon);

//   setcookie('current_user_cookie', $currentUser, time() + (86400 * 365), "/"); // 86400 = 1 day
//   setcookie('icon', $icon['icon'], time() + (86400 * 365), "/");

//   return true;
// }

// $enteredPW = htmlspecialchars($_POST['pw']);
// $currentUser = htmlspecialchars($_POST['userData']);

// if (authenticateUser($enteredPW, $currentUser)) {
//   header('Location: user_page.php');
// } else {
//   header('Location: /login_error.html');
//   exit();
// }
