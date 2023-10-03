<?php
require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

$userVar = getCurrentUser();
$getFriends =  safe_sql_query('SELECT friends FROM users WHERE username = ?', ['s', $userVar]);
$friendIds = explode(',', mysqli_fetch_array($getFriends)['friends']); //should separate the friend list by commas
$numOfFriends = count($friendIds);
//$findFriends = [];
for ($x = 0; $x < $numOfFriends; $x++) {
    $getData = safe_sql_query("SELECT username FROM users WHERE id = ?", ['s', $findFriends[$x]]);
    $findFriends[$x] = mysqli_fetch_row($getData)[0];
}

begin_html_page('');

?>

    <a id="backButton" href="/new-game">≪</a>
    <br>
    <h1>友達と対局</h1>
    <div class="inputBox">
        <datalist id="drawFriends"></datalist>
        <form action="/new-game/new-challenge" name="challengeData" method="post">
            <label for="opponentField">対戦相手</label><br>
            <input list="drawFriends" name="opponent" id="opponentField" require>
    </div>

    <br>
    <div class="inputBox">
        <h3>先手</h3>
        <input type="radio" id="userColorBlack" name="userColor" value="blackplayer">
        <label for="userColorBlack">自分</label><br>
        <input type="radio" id="userColorWhite" name="userColor" value="whiteplayer">
        <label for="userColorWhite">相手</label><br>
    </div>

    <br>
    <div class="inputBox">
        <h3>公開設定</h3>
        <input type="radio" id="private" name="publicPrivate" value=1>
        <label for="private">非公開</label><br>
        <input type="radio" id="notPrivate" name="publicPrivate" value=0>
        <label for="notPrivate">公開</label><br>
    </div>

    <input type="submit" value="チャレンジ送信">

    </form>

</body>
<script>
    /*function fillInName(opponent){
    document.getElementById("opponentField").value = opponent;
    document.getElementById("showOpponent").innerHTML = opponent;
}*/

    let friendsArrayLength = Number(<?php echo $numOfFriends; ?>);
    let friendLinks = [];
    let counter = 0;

    //make a php foreach loop to create each name in the friendLinks array as a p element in the DOM
    <?php foreach ($findFriends as $i) { ?>
        //https://developer.mozilla.org/en-US/docs/Web/HTML/Element/datalist
        friendLinks[counter] = "<option value = '<?= $i ?>'>";
        //friendLinks[counter].setAttribute("onClick", "fillInName('<?= $i ?>')");
        //friendLinks[counter].setAttribute("href", "#");
        document.getElementById("drawFriends").innerHTML += friendLinks[counter];
        //document.getElementById("drawFriends").innerHTML += "<br>";
        counter++
    <?php  }   ?>
</script>

<?php end_html_page() ?>
