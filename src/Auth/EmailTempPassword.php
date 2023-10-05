<?php
require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'utils.php';

$enteredEmail = htmlspecialchars($_POST['email']);
$emailOkay = false;
$usernameToAddressTo = '';

if (isValidEmail($enteredEmail)) {
    $usernameToAddressTo = getEmailByUsername($enteredEmail);

    if ($usernameToAddressTo !== null) {
        $emailOkay = true;
    }
}

begin_html_page('Slo Shogi Forgot Password');
?>

<a id="backButton" href="/login">≪ <span style="font-size: 5vw">ログインへ Back to Login</span></a>
<br>
<br>

<?php

if (!$emailOkay) {
    echo "<br><br>入力したアドレスに誤りがありました。もう一回入力してみてください There was an error in the email address you entered. Please try again.";
} else {
    $tempPass = generateRandomPassword();

    if (sendPasswordResetEmail($enteredEmail, $usernameToAddressTo, $tempPass)) {
        echo "<br><br><h3>A temporary password has been sent to your email address<br>
仮パスワードがメールアドレスに送信されました</h3>";

        $hashedPassword = password_hash($tempPass, PASSWORD_DEFAULT);
        safe_sql_query("UPDATE users SET pass = ? WHERE username = ?", ['ss', $hashedPassword, $usernameToAddressTo]);
    } else {
        echo "問題がありました。少し待ってからもう一回送ってみて下さい There was a problem sending the email. Please try again after waiting for a short bit";
    }
}

end_html_page();
?>
