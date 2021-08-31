<?php 
unset($_COOKIE['current_user_cookie']);
setcookie('current_user_cookie', null, time() - 1, '/'); // reset the cookie
            header('Location: index.php');
            ?>