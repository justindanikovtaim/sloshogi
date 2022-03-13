<?php
//create_cat.php
include '../connect.php';
include 'header.php';
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    //the form hasn't been posted yet, display it
    echo "<form method='post' action=''>
        カテゴリー名: <input type='text' name='cat_name' />
        カテゴリー説明: <textarea name='cat_description' /></textarea>
        <input type='submit' value='カテゴリーを追加' />
     </form>";
}
else
{
    //the form has been posted, so save it
    $sql = "INSERT INTO forum_categories(cat_name, cat_description)
       VALUES('". mysqli_real_escape_string($link, $_POST['cat_name']).
              "','".mysqli_real_escape_string($link, $_POST['cat_description']). "')";
    $result = mysqli_query($link, $sql);
    if(!$result)
    {
        //something went wrong, display the error
        echo 'エラー' . mysqli_error($link);
    }
    else
    {
        echo '新カテゴリー追加成功.';
    }
}
include 'footer.php';
?>