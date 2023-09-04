<?php
require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';

// Initialize variables
$enteredEmail = htmlspecialchars($_POST['email']);
$emailOkay = false;
$usernameToAddressTo = '';

// Validate email format and retrieve the username
if (filter_var($enteredEmail, FILTER_VALIDATE_EMAIL)) {
    $getEmails = safe_sql_query("SELECT username FROM users WHERE email = ?", ['s', $enteredEmail]);

    if ($getEmails->num_rows > 0) {
        $emailsArray = mysqli_fetch_array($getEmails);

        if (isset($emailsArray['username'])) {
            $emailOkay = true;
            $usernameToAddressTo = $emailsArray['username'];
        }
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
    // Generate a temporary password
    $tempPass = random_int(100000, 999999); // Generate a 6-digit random int

    // Send the email
    $message = "SLO Shogi　パスワードのリセット
    \n" . $usernameToAddressTo . "さん、
    \n仮パスワードはこれです：
    \n" . $tempPass . "
    \n
    \nSlo Shogi Password Reset
    \n" . $usernameToAddressTo . ",
    \nYour temporary password is:
    \n" . $tempPass . "
    \n
    \nログインしましたら、ユーザーページよりすぐ更新してください。
    \nAfter logging in, please update your password via the user page";

    $message = wordwrap($message, 70);

    if (mail($enteredEmail, "SLO Shogiパスワードのリセット　Slo Shogi Password Reset", $message)) {
        echo "<br><br><h3>A temporary password has been sent to your email address<br>
仮パスワードがメールアドレスに送信されました</h3>";

        // Update the user's password with the temporary password
        safe_sql_query("UPDATE users SET pass = ? WHERE username = ?", ['ss', password_hash($tempPass, PASSWORD_DEFAULT), $usernameToAddressTo]);
    } else {
        echo "問題がありました。少し待ってからもう一回送ってみて下さい There was a problem sending the email. Please try again after waiting for a short bit";
    }
}

end_html_page();
?>
