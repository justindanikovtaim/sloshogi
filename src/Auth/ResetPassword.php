<?php
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'session.php';

function updatePassword($oldPassword, $newPassword)
{
    $hashQuery = safe_sql_query("SELECT pass FROM users WHERE username = ?", ['s', getCurrentUser()]);
    $passHash = mysqli_fetch_array($hashQuery);

    if (!password_verify($oldPassword, $passHash['pass']) && $oldPassword != $passHash['pass']) {
        return false; // Incorrect old password
    }

    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $passwordQuery = safe_sql_query("UPDATE users SET pass = ? WHERE username = ?", ['ss', $newPasswordHash, getCurrentUser()]);

    return $passwordQuery;
}

begin_html_page("Slo Shogi Update Password");

$oldPass = htmlspecialchars($_POST['oldPass']);
$enteredPass1 = htmlspecialchars($_POST['pass']);
$enteredPass2 = htmlspecialchars($_POST['confirmPass']);

if ($enteredPass1 != $enteredPass2) {
?>
    パスワードが異なる　Passwords do not match <br>
    <a href="/settings">戻る　Go Back</a>
    <?php
} else {
    $passwordUpdated = updatePassword($oldPass, $enteredPass1);

    if ($passwordUpdated) {
    ?>
        <h2>パスワードが更新されました Your password has been updated <br>
            <a href="/user-page">ユーザーページへ移動　Go to your user page</a>
        </h2>
    <?php
    } else {
    ?>
        現在のパスワードが違います current password incorrect <br>
        <a href="/settings">戻る　Go Back</a>
<?php
    }
}
?>
