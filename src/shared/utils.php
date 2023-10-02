<?php

/**
 * This file has utilities used accross the entire application.
 * It is good to keep your shared utils on a single file or directory to have
 * a single source where you can make changes without having to edit all of your
 * files.
 */

function isValidEmail(string $email)
{
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function getEmailByUsername($enteredEmail)
{
  $getEmails = safe_sql_query("SELECT username FROM users WHERE email = ?", ['s', $enteredEmail]);

  if ($getEmails->num_rows > 0) {
    $emailsArray = mysqli_fetch_array($getEmails);
    if (isset($emailsArray['username'])) {
      return $emailsArray['username'];
    }
  }
  return null;
}

function generateRandomPassword()
{
  return random_int(100000, 999999);
}

function sendPasswordResetEmail($email, $username, $tempPass)
{
  $subject = "SLO Shogiパスワードのリセット　Slo Shogi Password Reset";
  $message = "SLO Shogi　パスワードのリセット\n" .
    $username . "さん、\n仮パスワードはこれです：\n" . $tempPass . "\n\n" .
    "Slo Shogi Password Reset\n" . $username . ",\nYour temporary password is:\n" . $tempPass . "\n\n" .
    "ログインしましたら、ユーザーページよりすぐ更新してください。\nAfter logging in, please update your password via the user page";

  $message = wordwrap($message, 70);

  return mail($email, $subject, $message);
}

function isValidUsername($username)
{
  return preg_match("/^[A-Za-z0-9\-_\.]{4,20}$/", $username);
}

function isEmailAvailable($email)
{
  $result = safe_sql_query("SELECT * FROM users WHERE email = ?", ['s', $email]);
  return mysqli_num_rows($result) == 0;
}

function isUsernameAvailable($username)
{
  $result = safe_sql_query("SELECT * FROM users WHERE username = ?", ['s', $username]);
  return mysqli_num_rows($result) == 0;
}
