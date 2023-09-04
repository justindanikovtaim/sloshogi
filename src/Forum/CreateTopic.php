<?php
//create_topic.php
include '../connect.php';
include 'header.php';

echo '<h2>トピックを作成</h2>';

    //the user is signed in
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {   
        //the form hasn't been posted yet, display it
        //retrieve the categories from the database for use in the dropdown
        $sql = "SELECT
                    cat_id,
                    cat_name,
                    cat_description
                FROM
                    forum_categories";
         
        $result = mysqli_query($link,$sql);
         
        if(!$result)
        {
            //the query failed, uh-oh :-(
            echo 'Error while selecting from database. Please try again later.';
        }
        else
        {
         
                echo '<form method="post" action="">
                    サブジェクト: <input type="text" name="topic_subject" required />
                    <br>カテゴリー:'; 
                 
                echo '<select name="topic_cat" id ="dropdown" >';
                    while($row = mysqli_fetch_assoc($result))
                    {
                        echo '<option value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>';
                    }
                echo '</select><br><br>'; 
                     
                echo 'メッセージ<br> <textarea name="post_content" required /></textarea>
                    <input type="submit" value="トピックを追加" />
                 </form>';
            
        }
    }
    else
    {
        // Turn autocommit off   https://www.w3schools.com/php/func_mysqli_rollback.asp
        mysqli_autocommit($link,FALSE);
            //the form has been posted, so save it
            //insert the topic into the topics table first, then we'll save the post into the posts table
            $sql = "INSERT INTO 
                        forum_topics(topic_subject,
                               topic_date,
                               topic_cat,
                               topic_by)
                   VALUES('" . mysqli_real_escape_string($link, $_POST['topic_subject']) . "',
                               NOW(),
                               " . mysqli_real_escape_string($link, $_POST['topic_cat']) . ",
                               TRIM('" . $_COOKIE['current_user_cookie'] . "') 
                               )";
                      //for some reason, whitespace is added before the username, so i had to put in the TRIM() function to get rid of it
            $result = mysqli_query($link, $sql);
            if(!$result)
            {
                //something went wrong, display the error
                echo 'An error occured while inserting your data. Please try again later.' . mysqli_error($link);
                mysqli_rollback($link);
            }
            else
            {
                //the first query worked, now start the second, posts query
                //retrieve the id of the freshly created topic for usage in the posts query
                $topicid = mysqli_insert_id($link);
                 
                $sql = "INSERT INTO
                            forum_posts(post_content,
                                  post_date,
                                  post_topic,
                                  post_by)
                        VALUES
                            ('" . mysqli_real_escape_string($link, $_POST['post_content']) . "',
                                  NOW(),
                                  " . $topicid . ",
                                '" . $_COOKIE['current_user_cookie'] . "'
                            )";
                $result = mysqli_query($link, $sql);
                 
                if(!$result)
                {
                    //something went wrong, display the error
                    echo 'An error occured while inserting your post. Please try again later.' . mysqli_error($link);
                    mysqli_rollback($link);
                }
                else
                {
                    mysqli_commit($link);
                     
                    //after a lot of work, the query succeeded!
                    echo '<a href="topic.php?id='. $topicid . '">あなたの新しいトピック</a>が追加されました';
                }
            }
        
    }

 
include 'footer.php';
?>