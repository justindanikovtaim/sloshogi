<?php

require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

$enteredEmail = htmlspecialchars($_POST['email']);
$emailOkay = filter_var($enteredEmail, FILTER_VALIDATE_EMAIL); //make sure email is in correct format

begin_html_page("SLO Shogi Invite Friends");
?>

<a id="backButton" href="/friends">≪ <span style="font-size: 5vw">友達リストへ Back to Friends</span></a>
<br>
<br>
<h3>友達へ招待リンクを送ります Send and invite link to a friend</h3>

<?php
if (!$emailOkay) {
    echo "入力したアドレスに誤りがありました。もう一回入力してみてください There was an error in the email address you entered. PLease try again.";
} else {
    //send the email
    $message = "友達がSLO Shogiに招待してくれました！
    \n下記のリンクよりアカウント登録ができます。
    \nA friend has invited you to SLO Shogi!
    \nFollow the link below to create your account.
    \nwww.sloshogi.com/new-account
    \n
    \n
    \nSLO将棋で友達とゆっくりした対局を楽しみましょう！
    \nEnjoy a slow-paced game with your friend on SLO Shogi!";

    $message = wordwrap($message, 70);

    if (mail($enteredEmail, "友達がSLO将棋へ招待してくれました　A Friend Invited You to SLO Shogi", $message)) {

        echo "Invitation email has been sent! Ask your friend to check their email<br>
招待メールは送信されました！友達にメールをチェックしてもらいましょう";
    } else {
        echo "問題がありました。少し待ってからもう一回送ってみて下さい There was a problem sending the email. Please try again after waiting for a short bit";
    }
}
?>

<?php
end_html_page();
