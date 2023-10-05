<?php

require_once SHAREDPATH . 'database.php';

function processGameMove($decoded)
{
    $gametofind = $decoded['gameId'];
    $playerColor = $decoded['color'];

    if ($playerColor == 'B') {
        $opColor = 'W';
        $playerTime = "blackTimeOn";
        $opTime = "whiteTimeOn";
    } else {
        $opColor = 'B';
        $playerTime = "whiteTimeOn";
        $opTime = "blackTimeOn";
    }

    $turn = $decoded['turn'];
    $deleteRule = $decoded['delete'];
    $chatSeen = $decoded['chatSeen'];

    $lastTurn = getLastTurn($gametofind);

    if ($lastTurn != $turn) {
        $deleteCommand = generateDeleteCommand($deleteRule);
        $updateCommand = 'UPDATE gamerecord SET moves = CONCAT(moves, "' . $decoded['newmoves'] . '"),' . $deleteCommand . ' turn = "' . $turn . '", chatseen = "' . $chatSeen . '",
        lastMoveTime = ' . $playerTime . ' - moveTimestamp' . $playerColor . ',
        moveTimestamp' . $opColor . ' = ' . $opTime . ', moveTimestamp' . $playerColor . ' = ' . $playerTime . '  WHERE id = ?';

        safe_sql_query($updateCommand, []);
    } else {
        echo "<script>console.log('already played')</script>";
    }
}

function getLastTurn($gameId)
{
    $result = safe_sql_query("SELECT turn FROM gamerecord WHERE id = ?", ['s', $gameId]);
    $lastTurn = mysqli_fetch_array($result);
    return $lastTurn['turn'];
}

function generateDeleteCommand($deleteRule)
{
    $delete1 = 'reservation1 = "",';
    $chop1 = 'reservation1 = SUBSTR(reservation1, (1 + CHAR_LENGTH(SUBSTRING_INDEX(reservation1, ";", 3)))),';
    $delete2 = 'reservation2 = "",';
    $chop2 = 'reservation2 = SUBSTR(reservation2, (1 + CHAR_LENGTH(SUBSTRING_INDEX(reservation2, ";", 3)))),';
    $delete3 = 'reservation3 = "",';
    $chop3 = 'reservation3 = SUBSTR(reservation3, (1 + CHAR_LENGTH(SUBSTRING_INDEX(reservation3, ";", 3)))),';

    switch ($deleteRule) {
        case "1":
            return $chop1 . $delete2 . $delete3;
        case "2":
            return $delete1 . $chop2 . $delete3;
        case "3":
            return $delete1 . $delete2 . $chop3;
        case "12":
            return $chop1 . $chop2 . $delete3;
        case "13":
            return $chop1 . $delete2 . $chop3;
        case "123":
            return $chop1 . $chop2 . $chop3;
        case "23":
            return $delete1 . $chop2 . $chop3;
        case "skip":
            return $delete1 . $delete2 . $delete3;
        default:
            echo "There was an error with the switch command";
            break;
    }
}

$in = file_get_contents('php://input');
$decoded = json_decode($in, true);

processGameMove($decoded);
