<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

/*
|--------------------------------------------------------------------------
| Super Admin Only
|--------------------------------------------------------------------------
*/

if ($_SESSION['role'] != 'super_admin') {

    die('Access Denied');

}

if (!isset($_GET['id'])) {

    header('Location: users.php');
    exit();

}

$user_id = intval($_GET['id']);

/*
|--------------------------------------------------------------------------
| Prevent Deleting Yourself
|--------------------------------------------------------------------------
*/

if ($user_id == $_SESSION['user_id']) {

    die('You cannot delete your own account.');

}

/*
|--------------------------------------------------------------------------
| Check User Exists
|--------------------------------------------------------------------------
*/

$sql = "
SELECT id, username
FROM users
WHERE id = ?
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

if (!$user) {

    die('User not found.');

}

/*
|--------------------------------------------------------------------------
| Soft Delete
|--------------------------------------------------------------------------
*/

$updateSql = "
UPDATE users
SET status='inactive'
WHERE id=?
";

$updateStmt = mysqli_prepare(
    $conn,
    $updateSql
);

mysqli_stmt_bind_param(
    $updateStmt,
    "i",
    $user_id
);

if (mysqli_stmt_execute($updateStmt)) {

    /*
    |--------------------------------------------------------------------------
    | Log Activity
    |--------------------------------------------------------------------------
    */

    $logSql = "
    INSERT INTO user_logs
    (
        user_id,
        activity,
        ip_address
    )
    VALUES
    (
        ?,
        ?,
        ?
    )
    ";

    $activity =
    'Deactivated User: ' .
    $user['username'];

    $ip =
    $_SERVER['REMOTE_ADDR'];

    $logStmt =
    mysqli_prepare(
        $conn,
        $logSql
    );

    mysqli_stmt_bind_param(
        $logStmt,
        "iss",
        $_SESSION['user_id'],
        $activity,
        $ip
    );

    mysqli_stmt_execute(
        $logStmt
    );

}

header('Location: users.php');
exit();

?>