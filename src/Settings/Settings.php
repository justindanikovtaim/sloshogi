<?php
require SHAREDPATH . 'database.php';
require SHAREDPATH . 'session.php';
require SHAREDPATH . 'template.php';

$currentUser = getCurrentUser();

$getUserInfo = safe_sql_query("SELECT * FROM users WHERE username = ?", ['s', getCurrentUser()]);
$userInfoArray = mysqli_fetch_array($getUserInfo);

begin_html_page('', ['user_page.css']);

?>

<div id="all">
  <a id="backButton" onClick="saveSettings()" href="#">≪</a>
  <br><br>
  <div id="nameIconRating">
    <h1 id="userName"><?php echo $currentUser ?> </h1>
    <h2 id="rating">段級: <?php echo $userInfoArray['rating'] ?></h2>
    <h2 id="record"><?php echo $userInfoArray['record'] ?></h2>
    <form id="updateSettingsForm" method="post" action="/settings/update-settings">
      <input type="text" name="hitokoto" id="hitokotoInput" value="<?php echo $userInfoArray['hitokoto'] ?>">
    </form>
    <a href="/settings/update-icon" id="settings">更新　Change</a>
    <div id="iconBox">
      <a href="/settings/update-icon"><img src="/public/images/icons/<?php echo $_COOKIE['icon'] ?>_icon.png" id="userIcon"></a>
    </div>
  </div>


  <br><br>

  <div id="resetPassLink">
    <img src="/public/images/koma/preview/<?php echo $userInfoArray['komaSet'] ?>.png" style="width: 15vw;"><a href="/settings/change-koma-set" style="font-size: 6vw; padding:2vw;">使用する駒を選択</a>
    <br><br>
    <button onclick="askNotificationPermission()" id="allowPermish" class="gameURL">通知を許可する Allow Notifications</button>
    <br><br>
    <a href="#" onclick="showPassReset()" class="gameURL">パスワード変更 Change Password</a>
  </div>
  <br>
  <br>
  <form id="passForm" action='/reset-password' method='post'>
    <label for='oldPass' class="formLabel"><b>現在のパスワード Current Password</b></label>
    <input type='password' placeholder='Old Password' name='oldPass' required>
    <br>
    <label for='pass' class="formLabel"><b>新パスワード New Password</b></label>
    <input title='3~20文字｜3-20 characters' pattern='{3,20}' type='password' placeholder='New Password' name='pass' maxlength='20' required>
    <br>
    <label for='confirmPass' class="formLabel"><b>パスワード再入力/Re-enter Password</b></label>
    <input type='password' pattern='{3,20}' placeholder='Confirm Password' name='confirmPass' required>
    <br>
    <button type='submit' id="submitButton">更新/Update</button>
  </form>

  <h1><a href="/logout" class=logoutButton>ログアウトLog Out</a></h1>
</div>

<script>
  function showPassReset() {
    document.getElementById("passForm").style.visibility = "visible";
  }

  function saveSettings() {
    document.getElementById("updateSettingsForm").submit();
  }

  function askNotificationPermission() {
    // function to actually ask the permissions
    function handlePermission(permission) {
      // set the button to shown or hidden, depending on what the user answers
      if (Notification.permission === 'denied' || Notification.permission === 'default') {
        document.getElementById("allowPermish").style.display = 'block';
      } else {
        document.getElementById("allowPermish").style.display = 'none';
      }
    }

    // Let's check if the browser supports notifications
    if (!('Notification' in window)) {
      alert("This browser does not support notifications.");
    } else {
      if (checkNotificationPromise()) {
        Notification.requestPermission()
          .then((permission) => {
            handlePermission(permission);
          })
      } else {
        Notification.requestPermission(function(permission) {
          handlePermission(permission);
        });
      }
    }
  }

  function checkNotificationPromise() {
    try {
      Notification.requestPermission().then();
    } catch (e) {
      return false;
    }

    return true;
  }
</script>

<?php end_html_page() ?>
