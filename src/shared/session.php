<?php

function isUserLoggedIn()
{
    return isset($_COOKIE['current_user_cookie']);
}

function getCurrentUser()
{
    return $_COOKIE['current_user_cookie'];
}

function logoutUser(string $redirect = '/')
{
    unset($_COOKIE['current_user_cookie']);
    setcookie('current_user_cookie', null, time() - 1, '/'); // reset the cookie
    header('Location: ' . $redirect);
}
