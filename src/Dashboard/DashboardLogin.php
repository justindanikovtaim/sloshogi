<?php
require_once SHAREDPATH . 'template.php';

begin_html_page('Authorized personnel only');
?>

<h1>Login Credentials</h1>
<form method="post" action="/dashboard">
  <input id="uName" name="uName" type="text">
  <input id="pw" name="pw" type="password">
  <input type="submit">
</form>

<?php
end_html_page();
