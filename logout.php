<?php

require_once 'config/db.php';

session_start();

/*
|--------------------------------------------------------------------------
| Log User Logout
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['user_id'])) {

    $user_id = $_SESSION['user_id'];
    $ip = $_SERVER['REMOTE_ADDR'];

    $sql = "
    INSERT INTO user_logs
    (
        user_id,
        activity,
        ip_address
    )
    VALUES
    (
        ?,
        'User Logout',
        ?
    )
    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "is",
        $user_id,
        $ip
    );

    mysqli_stmt_execute($stmt);
}

/*
|--------------------------------------------------------------------------
| Destroy Session
|--------------------------------------------------------------------------
*/

$_SESSION = array();

if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: login.php?logout=1");
exit();

?>