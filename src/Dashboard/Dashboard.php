<?php
    require 'connect.php';

if(!isset($_POST['uName']) || !isset($_POST['pw'])){
    echo "<h5> you've got to log in first</h5>";
    die();
}

if(isset($_POST['id'])){//if a bug is being resolved, the id of the bug will be in the post global
    if(mysqli_query($link, "DELETE FROM feedback WHERE id ='".$_POST['id']."'")){
        echo"<h5>Bug/Feedback ".$_POST['id']." Resolved successfully</h5>";
    };
}
$uName = htmlspecialchars($_POST['uName']);
$pw = htmlspecialchars($_POST['pw']);

if($uName != "Chris" && $uName != "kikentaro"){
    die();
}else if($pw != "SSDashboard!"){
    die();
}else{

    $getBugs = mysqli_query($link, "SELECT * FROM feedback");
    $getUsers = mysqli_query($link, "SELECT * FROM users");
    $getGames = mysqli_query($link,"SELECT * FROM gamerecord");


}

?>

<!DOCTYPE HTML>
<head>
</head>
<body>
    <h1>Bug Reports/Feedback</h1>
    <?php
    echo "<table border='1'><tr><th>Type</th><th>User</th><th>Source Page</th><th>Comment</th><th>Time Created</th><th>RESOLVE</th>";
    while($row = mysqli_fetch_array($getBugs)){
        echo "<tr>";
        echo "<td>".$row['fbtype']."</td>";
        echo "<td>".$row['user']."</td>";
        echo "<td>".$row['source']."</td>";
        echo "<td>".$row['comment']."</td>";
        echo "<td>".$row['timeCreated']."</td>";
        echo "<td><form method = 'post'><input type='hidden' name='pw' value ='".$pw."'><input type='hidden' name='uName' value='".$uName."'><input type='hidden' name='id' value='".$row['id']."'><input type='submit' value=' X '></td>";
        echo "</tr>";
    }
    echo "</table>";
    ?>
    <h1>Users</h1>
    <?php
     echo "<table border='1'><tr><th>ID</th><th>Username</th><th>Record</th><th>Email</th><th>Friends</th><th>Last Active</th>";
     while($row = mysqli_fetch_array($getUsers)){
         echo "<tr>";
         echo "<td>".$row['id']."</td>";
         echo "<td>".$row['username']."</td>";
         echo "<td>".$row['record']."</td>";
         echo "<td>".$row['email']."</td>";
         echo "<td>".$row['friends']."</td>";
         echo "<td>".$row['lastactive']."</td>";
         echo "</tr>";
     }
     echo "</table>";
    ?>
    <h1>Games</h1>
    <?php
     echo "<table border='1'><tr><th>ID</th><th>Turn</th><th>Black Player</th><th>White Player</th><th>Status</th><th>Creator</th><th>Date Started</th>";
     while($row = mysqli_fetch_array($getGames)){
         echo "<tr>";
         echo "<td><a href ='/gameboard?id=".$row['id']."'>".$row['id']."</a></td>";
         echo "<td>".$row['turn']."</td>";
         echo "<td>".$row['blackplayer']."</td>";
         echo "<td>".$row['whiteplayer']."</td>";
         echo "<td>".$row['status']."</td>";
         echo "<td>".$row['creator']."</td>";
         echo "<td>".$row['dateStarted']."</td>";
         echo "</tr>";
     }
     echo "</table>";
    ?>
</body>
