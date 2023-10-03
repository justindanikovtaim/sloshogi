<?php
require_once SHAREDPATH . 'template.php';

$allIcons = glob("/public/images/koma/" . $_GET['newKomaSet'] . "/*.png");

begin_html_page('', ['icon_select.css']);
?>

<a id="backButton" href="/settings/change-koma-set">≪</a>
<br><br>
<a href="/settings/set-koma-set?newKomaSet=<?php echo $_GET['newKomaSet'] ?>">
    <h1>この駒セットを使用</h1>
</a>
<?php foreach ($allIcons as $icon) : ?>
    <img src="<?php echo $icon ?>" class="iconSelect" id="<?php echo $icon ?>"><br>
<?php endforeach ?>
<?php end_html_page() ?>
