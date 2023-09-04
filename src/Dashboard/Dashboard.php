<?php
require_once 'database.php';
require_once 'template.php';
require_once 'session.php';

if (!isset($_POST['uName']) || !isset($_POST['pw'])) {
    echo "<h5> you've got to log in first</h5>";
    die();
}

if (isset($_POST['id'])) { //if a bug is being resolved, the id of the bug will be in the post global
    if (safe_sql_query("DELETE FROM feedback WHERE id = ?", ['i', $_POST['id']])) {
        echo "<h5>Bug/Feedback " . $_POST['id'] . " Resolved successfully</h5>";
    };
}
$uName = htmlspecialchars($_POST['uName']);
$pw = htmlspecialchars($_POST['pw']);

if ($uName != "Chris" && $uName != "kikentaro") {
    die();
} else if ($pw != "SSDashboard!") {
    die();
} else {
    $getBugs = safe_sql_query("SELECT * FROM feedback");
    $getUsers = safe_sql_query("SELECT * FROM users");
    $getGames = safe_sql_query("SELECT * FROM gamerecord");
}

begin_html_page("SLO Shogi Dashboard");
?>

<h1>Bug Reports/Feedback</h1>
<table border='1'>
    <tr>
        <th>Type</th>
        <th>User</th>
        <th>Source Page</th>
        <th>Comment</th>
        <th>Time Created</th>
        <th>RESOLVE</th>
        <?php while ($row = mysqli_fetch_array($getBugs)) : ?>
    <tr>
        <td><?php echo $row['fbtype'] ?></td>
        <td><?php echo $row['user'] ?></td>
        <td><?php echo $row['source'] ?></td>
        <td><?php echo $row['comment'] ?></td>
        <td><?php echo $row['timeCreated'] ?></td>
        <td>
            <form method='post'><input type='hidden' name='pw' value="<?php echo $pw ?>">
                <input type='hidden' name='uName' value='<?php echo $uName ?>'>
                <input type='hidden' name='id' value='<?php echo $row['id'] ?>'>
                <input type='submit' value=' X '>
            </form>
        </td>
    </tr>
<?php endwhile; ?>
</table>
<h1>Users</h1>
<table border='1'>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Record</th>
        <th>Email</th>
        <th>Friends</th>
        <th>Last Active</th>
        <?php while ($row = mysqli_fetch_array($getUsers)) : ?>
    <tr>
        <td><?php echo $row['id'] ?></td>
        <td><?php echo $row['username'] ?></td>
        <td><?php echo $row['record'] ?></td>
        <td><?php echo $row['email'] ?></td>
        <td><?php echo $row['friends'] ?></td>
        <td><?php echo $row['lastActive'] ?></td>
    </tr>
<?php endwhile; ?>
</table>
<h1>Games</h1>
<table border='1'>
    <tr>
        <th>ID</th>
        <th>Turn</th>
        <th>Black Player</th>
        <th>White Player</th>
        <th>Status</th>
        <th>Creator</th>
        <th>Date Started</th>
        <?php while ($row = mysqli_fetch_array($getGames)) : ?>
    <tr>
        <td><a href='/gameboard?id=<?php echo $row['id'] ?> '> <?php echo $row['id'] ?> </a></td>
        <td><?php echo $row['turn'] ?></td>
        <td><?php echo $row['blackplayer'] ?></td>
        <td><?php echo $row['whiteplayer'] ?></td>
        <td><?php echo $row['status'] ?></td>
        <td><?php echo $row['creator'] ?></td>
        <td><?php echo $row['dateStarted'] ?></td>
    </tr>
<?php endwhile; ?>
</table>

<?php
end_html_page();
