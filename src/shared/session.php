<?php

/**
 * Manages user authentication and session.
 *
 * This file contains functions related to user authentication, including
 * checking if a user is logged in, retrieving the current user, and logging out
 * the user.
 *
 * @file        session.php
 * @category    PHP
 * @package     Authentication
 * @license     MIT License
 */

/**
 * Checks if a user is logged in.
 *
 * @return bool True if the user is logged in, false otherwise.
 */
function isUserLoggedIn()
{
    return isset($_COOKIE['current_user_cookie']);
}

/**
 * Retrieves the current logged-in user.
 *
 * @return string|null The username of the current logged-in user, or null if no user is logged in.
 */
function getCurrentUser()
{
    return $_COOKIE['current_user_cookie'];
}

/**
 * Logs out the current user.
 *
 * @param string $redirect The URL to redirect to after logging out (default: '/').
 * @return void
 */
function logoutUser(string $redirect = '/')
{
    unset($_COOKIE['current_user_cookie']);
    setcookie('current_user_cookie', null, time() - 1, '/'); // reset the cookie
    header('Location: ' . $redirect);
}
