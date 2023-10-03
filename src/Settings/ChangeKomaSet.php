<?php

require_once SHAREDPATH . 'template.php';
$allIcons = glob("/public/images/koma/preview/*.png");

begin_html_page('', ['all_pages.css', 'icon_select.css']);

?>

<a id="backButton" href="/settings">≪</a>
<br><br><br>
<h1 style="font-size:6vw">駒をタップしてプレビューを拡大</h1>
<?php foreach ($allIcons as $icon) : ?>
    <a href="/settings/preview-koma-set?newKomaSet='.substr($icon, 20, (strlen($icon) - 24 )).'">
        <img src="'.$icon.'" class="iconSelect" id="'.$icon.'"></a> <br>';
<?php endforeach ?>
<?php end_html_page() ?>
