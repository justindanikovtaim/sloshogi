<?php
//create_cat.php
require '../connect.php';
include 'header.php';
         
$sql = "SELECT
            cat_id,
            cat_name,
            cat_description
        FROM forum_categories";
 
$result = mysqli_query($link, $sql);
 
if(!$result)
{
    echo 'The categories could not be displayed, please try again later.'.mysqli_error($link);
}
else
{
    if(mysqli_num_rows($result) == 0)
    {
        echo 'No categories defined yet.';
    }
    else
    {
        //prepare the table
        echo '<table border="1">
              <tr>
                <th>カテゴリー</th>
                <th>最新トピック</th>
              </tr>'; 
             
        while($row = mysqli_fetch_assoc($result))
        {   
            $mostRecentQuery = mysqli_query($link,"SELECT * FROM forum_topics WHERE topic_cat = '".$row['cat_id']. "' ORDER BY topic_id DESC LIMIT 1");
            $mostRecent = mysqli_fetch_array($mostRecentQuery);
            $recentTopic = $mostRecent['topic_subject'];
            $recentDate = $mostRecent['topic_date'];
            $recentId = $mostRecent['topic_id'];
            echo '<tr>';
                echo '<td class="leftpart">';
                    echo '<h3><a href="category.php?id='.$row['cat_id'].'">' . $row['cat_name'] . '</a></h3>' . $row['cat_description'];
                echo '</td>';
                echo '<td class="rightpart">';
                            echo '<a href="topic.php?id='.$recentId.'">'.$recentTopic.'</a> at '.$recentDate;
                echo '</td>';
            echo '</tr>';
        }
    }
}
include 'footer.php';
?>