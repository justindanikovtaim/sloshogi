<?php
$kifu = fopen("piyocopy.kif", "r+");
$export = fopen("NewFile.kif", "w");

$toConvert = fread($kifu,filesize("piyocopy.kif"));
$newKifu = mb_convert_encoding($toConvert, "UTF-8", "SJIS");
fwrite($export, $newKifu);

fclose($kifu);
fclose($export);

?>