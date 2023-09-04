<?php
//create_cat.php
include '../connect.php';
include 'header.php';

//first select the category based on $_GET['cat_id']
$sql = "SELECT
            cat_id,
            cat_name,
            cat_description
        FROM
            forum_categories
        WHERE
            cat_id = '" . mysqli_real_escape_string($link, $_GET['id'])."'";
 
$result = mysqli_query($link, $sql);
 
if(!$result)
{
    echo '1The category could not be displayed, please try again later.' . mysqli_error($link);
}
else
{
    if(mysqli_num_rows($result) == 0)
    {
        echo 'このカテゴリーは存在しない.';
    }
    else
    {
        //display category data
        while($row = mysqli_fetch_assoc($result))
        {
            echo '<h2>′' . $row['cat_name'] . '′ のトピックス</h2>';
        }
     
        //do a query for the topics
        $sql = "SELECT  
                    topic_id,
                    topic_subject,
                    topic_date,
                    topic_cat
                FROM
                    forum_topics
                WHERE
                    topic_cat = " . mysqli_real_escape_string($link, $_GET['id']);
         
        $result = mysqli_query($link, $sql);
         
        if(!$result)
        {
            echo '2The topics could not be displayed, please try again later.';
        }
        else
        {
            if(mysqli_num_rows($result) == 0)
            {
                echo 'このカテゴリーにはトピックスはまだ存在しない';
            }
            else
            {
                //prepare the table
                echo '<table border="1">
                      <tr>
                        <th>トピック</th>
                        <th>作成日時</th>
                      </tr>'; 
                     
                while($row = mysqli_fetch_assoc($result))
                {               
                    echo '<tr>';
                        echo '<td class="leftpart">';
                            echo '<h3><a href="topic.php?id=' . $row['topic_id'] . '">' . $row['topic_subject'] . '</a><h3>';
                        echo '</td>';
                        echo '<td class="rightpart">';
                            echo date('d-m-Y', strtotime($row['topic_date']));
                        echo '</td>';
                    echo '</tr>';
                }
            }
        }
    }
}
 
include 'footer.php';
?>