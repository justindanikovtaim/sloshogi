<?php

require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'utils.php';

begin_html_page('SLO Shogi Account Creation Attempt');

$enteredEmail = htmlspecialchars($_POST['address']);
$confirmEmail = htmlspecialchars($_POST['confirmAddress']);
$enteredUsername = htmlspecialchars($_POST['userData']);

$emailOkay = filter_var($enteredEmail, FILTER_VALIDATE_EMAIL);
$usernameOkay = isValidUsername($enteredUsername) && strlen($enteredUsername) >= 4 && strlen($enteredUsername) <= 20;

if (!$emailOkay || !$usernameOkay || $enteredEmail != $confirmEmail) {
    echo "入力に誤りがありました。もう一回入力してみてください。Invalid input. Please try again";
} else if (!isEmailAvailable($enteredEmail) || !isUsernameAvailable($enteredUsername)) {
    echo "ユーザー名またはメールアドレスは既に使用されています。The username or email is already in use";
} else {
    $OTP = random_int(100000, 999999);
    $newUser = safe_sql_query("INSERT INTO newaccounts (email, username, OTP) VALUES (?, ?, ?)", ['ssi', $enteredEmail, $enteredUsername, $OTP]);

    $message = "アカウントはまだ確定されていません！
    \n24時間以内に下記のリンクよりメールアドレスを承認してください。
    \nYour account is not confirmed yet!
    \nPlease click the link below to confirm your email within 24 hours
    \nwww.sloshogi.com/account-setup?OTP=" . $OTP . "
    \nSLO将棋に興味を持って頂きたい誠にありがとうございます。
    \nThank you for your interest in SLO Shogi!";

    $message = wordwrap($message, 70);

    mail($enteredEmail, "SLO将棋アカウント承認　Verify SLO Shogi Account", $message);

    if ($newUser) {
        echo "Account information submitted. Please check your email within 1 hour to validate your account <br>
    アカウント情報は発信されました。1時間以内にメールをチェックしてアカウント有効化して下さい";
    }
}
?>

<a href="/">トップに戻る・Return to Homepage</a>

<?php
end_html_page();
?>
