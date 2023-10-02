<?php
require_once SHAREDPATH . 'database.php';
require_once SHAREDPATH . 'template.php';
require_once SHAREDPATH . 'session.php';

function authenticateDashboard($username, $password)
{
    return ($username === "Chris" || $username === "kikentaro") && $password === "SSDashboard!";
}

if (!isset($_POST['uName']) || !isset($_POST['pw'])) {
    echo "<h5>You need to log in first.</h5>";
    die();
}

$uName = htmlspecialchars($_POST['uName']);
$pw = htmlspecialchars($_POST['pw']);

if (!authenticateDashboard($uName, $pw)) {
    die();
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    if (safe_sql_query("DELETE FROM feedback WHERE id = ?", ['i', $id])) {
        echo "<h5>Bug/Feedback $id resolved successfully</h5>";
    }
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
    </tr>
    <?php
    $getBugs = safe_sql_query("SELECT * FROM feedback");
    while ($row = mysqli_fetch_array($getBugs)) : ?>
        <tr>
            <td><?php echo $row['fbtype'] ?></td>
            <td><?php echo $row['user'] ?></td>
            <td><?php echo $row['source'] ?></td>
            <td><?php echo $row['comment'] ?></td>
            <td><?php echo $row['timeCreated'] ?></td>
            <td>
                <form method='post'>
                    <input type='hidden' name='pw' value="<?php echo $pw ?>">
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
    </tr>
    <?php
    $getUsers = safe_sql_query("SELECT * FROM users");
    while ($row = mysqli_fetch_array($getUsers)) : ?>
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
    </tr>
    <?php
    $getGames = safe_sql_query("SELECT * FROM gamerecord");
    while ($row = mysqli_fetch_array($getGames)) : ?>
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
?>
