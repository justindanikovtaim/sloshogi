<?php
//create_cat.php
include '../connect.php';
include 'header.php';
 
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    //someone is calling the file directly, which we don't want
    echo 'This file cannot be called directly.';
}
else
{
    //check for sign in status

        //a real user posted a real reply
        $sql = "INSERT INTO 
                    forum_posts(post_content,
                          post_date,
                          post_topic,
                          post_by) 
                VALUES ('" . $_POST['reply-content'] . "',
                        NOW(),'
                        " . mysqli_real_escape_string($link, $_GET['id']) . "',TRIM('" . $_COOKIE['current_user_cookie'] . "'))";
                         
        $result = mysqli_query($link, $sql);
                         
        if(!$result)
        {
            echo 'Your reply has not been saved, please try again later. :'.mysqli_error($link);
        }
        else
        {
            echo 'あなたの返信が保存された。<a href="topic.php?id=' . htmlentities($_GET['id']) . '">見る</a>.';
        }
    
}
 
include 'footer.php';
?>