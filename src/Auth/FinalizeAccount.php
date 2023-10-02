<?php
require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';

function updateUserPasswordAndCreateSession($newUsername, $newEmail, $enteredPass)
{
    $enteredPass = password_hash($enteredPass, PASSWORD_DEFAULT);
    $addUserQuery = safe_sql_query("INSERT INTO users (username, email, pass) VALUES (?, ?, ?)", ['sss', $newUsername, $newEmail, $enteredPass]);

    if ($addUserQuery) {
        setcookie('current_user_cookie', $newUsername, time() + (86400 * 30), "/"); // Set the cookie so they can visit the user page
        return true;
    } else {
        return false;
    }
}

function updateTsumeShogiScores($username, $tsume_score, $tsume_problem)
{
    //add the user to the list of userswho finished the problem
    safe_sql_query("UPDATE tsumeshogi SET completed = CONCAT(completed, ?) WHERE id = ?", ['si', $username . ";", $tsume_problem]);

    //update the scoreboad for that problem
    if ($tsume_score > 0) {
        $insertArray = [$username . ";", $tsume_score . ";"];
        $getStandings = safe_sql_query("SELECT scoreBoard FROM tsumeshogi WHERE id = ?", ['i', $tsume_problem]);
        $getStandingsArray = mysqli_fetch_array($getStandings);
        $standingsArray = explode(";", $getStandingsArray['scoreBoard']);
        $updatedStandings = "";
        //$newArray = ["default","basic"];
        if (sizeof($standingsArray) < 2) { //if there is noone in the standings yet
            $updatedStandings = $insertArray[0] . $insertArray[1];
        } else {
            $inserted = false;
            for ($i = 0; $i < (sizeof($standingsArray) - 1); $i += 2) {
                if ($inserted == false && $tsume_score < $standingsArray[$i + 1]) {
                    $updatedStandings .= $insertArray[0] . $insertArray[1];
                    $inserted = true;
                    $updatedStandings .= $standingsArray[$i] . ";" . $standingsArray[$i + 1] . ";";
                } else {
                    $updatedStandings .= $standingsArray[$i] . ";" . $standingsArray[$i + 1] . ";";
                }
            }
            if ($inserted == false) {
                //if it is the lowest score
                $updatedStandings .= $insertArray[0] . $insertArray[1];
            }
        }
        safe_sql_query("UPDATE tsumeshogi SET scoreBoard = ? WHERE id = ?", ['si', $updatedStandings, $tsume_problem]);
    }
}

begin_html_page("Slo Shogi Create Finalize Account");

$enteredPass1 = htmlspecialchars($_POST['pass']);
$enteredPass2 = htmlspecialchars($_POST['confirmPass']);
$OTP = htmlspecialchars($_POST['OTP']);

$result = safe_sql_query("SELECT * FROM newaccounts WHERE OTP = ?", ['i', $OTP]);

if ($enteredPass1 != $enteredPass2 || !$result) {
?>
    パスワードが異なる　Passwords do not match <br>
    <a href="/account-setup?OTP=<?php echo $OTP ?>">戻る　Go Back</a>
    <?php
} else {
    $newAccountInfo = mysqli_fetch_array($result);
    $newEmail = $newAccountInfo['email'];
    $newUsername = $newAccountInfo['username'];

    if (updateUserPasswordAndCreateSession($newUsername, $newEmail, $enteredPass1)) {
    ?>
        <h2>アカウントが確定されました</h2> <br>
        <a href='/user-page'>ユーザーページへ移動</a>
        <?php

        //delete the record from the newaccounts table
        safe_sql_query("DELETE FROM newaccounts WHERE OTP = ?", ['i', $OTP]);

        //see if the user has a saved highscore from tsumeShogi
        if (isset($_COOKIE['tsume_score'])) {
            updateTsumeShogiScores($newUsername, $_COOKIE['tsume_score'], $_COOKIE['tsume_problem']);
        }
    } else {
        ?>
        サーバーへの接続が失敗しました。後でもう一回試してください　Error connecting to the server. Please try again later<br>
        <a href="/account-setup?OTP=<?php echo $OTP ?>">戻る　Go Back</a>
<?php
    }
}

end_html_page();
?>
