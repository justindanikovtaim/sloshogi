<?php
require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';

$enteredPass1 = htmlspecialchars($_POST['pass']);
$enteredPass2 = htmlspecialchars($_POST['confirmPass']);
$OTP = htmlspecialchars($_POST['OTP']);

begin_html_page("Slo Shogi Create Finalize Account");

if ($enteredPass1 != $enteredPass2) {
?>
    パスワードが異なる　Passwords do not match <br>
    <a href="/account-setup?OTP=<?php echo $OTP ?>">戻る　Go Back</a>
    <?php
} else {
    $result = safe_sql_query("SELECT * FROM newaccounts WHERE OTP = ?", ['i', $OTP]);
    //get the user's email and username based on the OTP
    $getNewAccountInfo = mysqli_fetch_array($result);
    $newEmail = $getNewAccountInfo['email'];
    $newUsername = $getNewAccountInfo['username'];
    $enteredPass1 = password_hash($enteredPass1, PASSWORD_DEFAULT);

    $addUserQuery = safe_sql_query("INSERT INTO users (username, email, pass) VALUES (?, ?, ?)", ['sss', $newUsername, $newEmail, $enteredPass1]);
    if ($addUserQuery) {
        setcookie('current_user_cookie', $newUsername, time() + (86400 * 30), "/"); //set the cookie so they can visit the user page

    ?>
        <h2>アカウントが確定されました</h2> <br>
        <a href='/user-page'>ユーザーページへ移動</a>
        <?php

        //delete the record from the newaccounts table
        safe_sql_query("DELETE FROM newaccounts WHERE OTP = ?", ['i', $OTP]);

        //see if the user has a saved highscore from tsumeShogi
        if (isset($_COOKIE['tsume_score'])) {
            //add the user to the list of userswho finished the problem
            safe_sql_query("UPDATE tsumeshogi SET completed = CONCAT(completed, ?) WHERE id = ?", ['si', $newUsername . ";", $_COOKIE['tsume_problem']]);

            //update the scoreboad for that problem
            if ($_COOKIE['tsume_score'] > 0) {
                $insertArray = [$newUsername . ";", $_COOKIE['tsume_score'] . ";"];
                $getStandings = safe_sql_query("SELECT scoreBoard FROM tsumeshogi WHERE id = ?", ['i', $_COOKIE['tsume_problem']]);
                $getStandingsArray = mysqli_fetch_array($getStandings);
                $standingsArray = explode(";", $getStandingsArray['scoreBoard']);
                $updatedStandings = "";
                //$newArray = ["default","basic"];
                if (sizeof($standingsArray) < 2) { //if there is noone in the standings yet
                    $updatedStandings = $insertArray[0] . $insertArray[1];
                } else {
                    $inserted = false;
                    for ($i = 0; $i < (sizeof($standingsArray) - 1); $i += 2) {
                        if ($inserted == false && $_COOKIE['tsume_score'] < $standingsArray[$i + 1]) {
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
                safe_sql_query("UPDATE tsumeshogi SET scoreBoard = ? WHERE id = ?", ['si', $updatedStandings, $_COOKIE['tsume_problem']]);
            }
        }
    } else {
        ?>
        サーバーへの接続が失敗しました。後でもう一回試してください　Error connecting to the server. Please try again later<br>
        <a href="/account-setup?OTP=<?php echo $OTP ?>">戻る　Go Back</a>
<?php
    }
}


end_html_page();
