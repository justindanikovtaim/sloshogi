<?php
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'session.php';

$hashQuery = safe_sql_query("SELECT pass FROM users WHERE username = ?", ['s', getCurrentUser()]);
$passHash = mysqli_fetch_array($hashQuery);

$oldPass = htmlspecialchars($_POST['oldPass']);
$enteredPass1 = htmlspecialchars($_POST['pass']);
$enteredPass2 = htmlspecialchars($_POST['confirmPass']);

begin_html_page("Slo Shogi Create Finalize Account");

if ($enteredPass1 != $enteredPass2) {
?>
    パスワードが異なる　Passwords do not match <br>
    <a href="/settings">戻る　Go Back</a>
<?php
} else if (!password_verify($oldPass, $passHash['pass']) && $oldPass != $passHash['pass']) { //remove second part of statement once we've updated our passwords
?>
    現在のパスワードが違います current password incorrect <br>
    <a href="/settings">戻る　Go Back</a>
    <?php
} else {
    $enteredPass1 = password_hash($enteredPass1, PASSWORD_DEFAULT);

    $passwordQuery = safe_sql_query("UPDATE users SET pass = ? WHERE username = ?", ['ss', $enteredPass1, getCurrentUser()]);
    if ($passwordQuery) {
    ?>
        <h2>パスワードが更新されました Your password has been updated <br>
            <a href="/user-page">ユーザーページへ移動　Go to your user page</a>
        </h2>
    <?php
    } else {
    ?>
        サーバーへの接続が失敗しました。後でもう一回試してください　Error connecting to the server. Please try again later<br>
        <a href="/settings">戻る Back</a>
<?php
    }
}
