<?php

/**
 * Utilities for the application.
 *
 * This file contains various utility functions used across the entire application.
 * These functions include email validation, password generation, database queries, and more.
 *
 * @file        utils.php
 * @category    PHP
 * @package     Application
 * @license     MIT License
 */

/**
 * Validates an email address.
 *
 * @param string $email The email address to validate.
 * @return bool True if the email address is valid, false otherwise.
 */
function isValidEmail(string $email)
{
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Retrieves username by email from the database.
 *
 * @param string $enteredEmail The email address to search for.
 * @return string|null The username associated with the email, or null if not found.
 */
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

/**
 * Generates a random password consisting of 6 digits.
 *
 * @return int The generated random password.
 */
function generateRandomPassword()
{
  return random_int(100000, 999999);
}

/**
 * Sends a password reset email to the user.
 *
 * @param string $email The recipient's email address.
 * @param string $username The recipient's username.
 * @param string $tempPass The temporary password to be sent.
 * @return bool True if the email is sent successfully, false otherwise.
 */
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

/**
 * Validates a username against a regular expression.
 *
 * @param string $username The username to validate.
 * @return bool True if the username is valid, false otherwise.
 */
function isValidUsername($username)
{
  return preg_match("/^[A-Za-z0-9\-_\.]{4,20}$/", $username);
}

/**
 * Checks if an email address is available in the database.
 *
 * @param string $email The email address to check.
 * @return bool True if the email address is available, false otherwise.
 */
function isEmailAvailable($email)
{
  $result = safe_sql_query("SELECT * FROM users WHERE email = ?", ['s', $email]);
  return mysqli_num_rows($result) == 0;
}

/**
 * Checks if a username is available in the database.
 *
 * @param string $username The username to check.
 * @return bool True if the username is available, false otherwise.
 */
function isUsernameAvailable($username)
{
  $result = safe_sql_query("SELECT * FROM users WHERE username = ?", ['s', $username]);
  return mysqli_num_rows($result) == 0;
}
