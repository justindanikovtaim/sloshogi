<?php

require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'database.php';

$OTP = isset($_GET['OTP']) && htmlspecialchars($_GET['OTP']);

//delete old records from the newaccounts table
safe_sql_query("DELETE FROM newaccounts WHERE timeCreated < (NOW() - INTERVAL 1440 MINUTE)");

begin_html_page('Slo Shogi Create New Account');

//search for user's OTP and get their account name and email in the process
$result = safe_sql_query("SELECT * FROM newaccounts WHERE OTP = ?", ['s', $OTP]);

//If everything is okay, set password, rating, photo etc.
if ($result) {
?>
    <form action='/finalize-account' method='post'>
        <label for='pass'><b>パスワード Password</b></label>
        <input title='3~20文字｜3-20 characters' pattern='{3,20}' type='password' placeholder='Enter Password' name='pass' maxlength='20' required>
        <br>
        <label for='confirmPass'><b>パスワード再入力/Re-enter Password</b></label>
        <input type='password' pattern='{3,20}' placeholder='confirm password' name='confirmPass' required>
        <br>
        <input type='hidden' name='OTP' value="<?php echo $OTP ?>">
        <button type='submit'>アカウントを確定/Finalize Account</button>
    </form>
<?php
} else {
?>
    時間切れでアカウントが削除されました｜Your account has been deleted due to timeout
    作成をもう一回行ってください｜Please try making a new account again
<?php
}

?>

<a href="/">トップに戻る・Return to Homepage</a>

<?php
end_html_page();
