<?php

require_once SHAREDPATH . 'template.php';
$allIcons = glob("/public/images/koma/preview/*.png");

begin_html_page('', ['all_pages.css', 'icon_select.css']);

?>

<a id="backButton" href="/settings">≪</a>
<br><br><br>
<h1 style="font-size:6vw">駒をタップしてプレビューを拡大</h1>
<?php foreach ($allIcons as $icon) : ?>
    <a href="/settings/preview-koma-set?newKomaSet=<?php echo substr($icon, 20, (strlen($icon) - 24)) ?>">
        <img src="<?php echo $icon ?>" class="iconSelect" id="<?php echo $icon ?>"></a> <br>';
    </a>
<?php endforeach ?>
<?php end_html_page() ?>
