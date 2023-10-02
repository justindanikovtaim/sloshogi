<?php

require_once SHAREDPATH . 'database.php';
require_once 'templates/header.php';

function displayForm()
{
?>
    <form method='post' action=''>
        カテゴリー名: <input type='text' name='cat_name' /><br>
        カテゴリー説明: <textarea name='cat_description'></textarea><br><br>
        <input style='font-size:5vw' type='submit' value='カテゴリーを追加' />
    </form>
<?php
}

function addCategory($link)
{
    $catName = mysqli_real_escape_string($link, $_POST['cat_name']);
    $catDescription = mysqli_real_escape_string($link, $_POST['cat_description']);

    $sql = "INSERT INTO forum_categories(cat_name, cat_description)
            VALUES('$catName', '$catDescription')";

    $result = safe_sql_query($sql);

    if (!$result) {
        echo 'エラー: ' . mysqli_error($link);
    } else {
        echo '新カテゴリー追加成功.';
    }
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // Display the form
    displayForm();
} else {
    // Handle form submission
    addCategory($link);
}

require_once 'templates/footer.php';
?>
