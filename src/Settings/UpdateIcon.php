<?php

require_once SHAREDPATH . 'template.php';
$allIcons = glob("/public/images/icons/*.png");

begin_html_page('', ['icon_select.css']);
?>

<h1>アイコンを選択してください<br> Please choose your icon</h1>
<?php foreach ($allIcons as $icon) : ?>
    <a href="/settings/set-icon?newIcon='.substr($icon, 13, (strlen($icon) - 22 )).'">
        <img src="<?php echo $icon ?>" class="iconSelect" id="<?php echo $icon ?>"></a> <br>
<?php endforeach ?>

<?php end_html_page() ?>
