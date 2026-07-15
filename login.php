<?php

require_once 'config/db.php';

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "
    SELECT
        u.*,
        r.role_name
    FROM users u
    INNER JOIN roles r
        ON u.role_id = r.id
    WHERE u.username = ?
    LIMIT 1
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {

        if ($user['status'] !== 'active') {

            $message = "Account is inactive.";

        } elseif (password_verify($password, $user['password'])) {

            // Create Session

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role_name'];
            $_SESSION['email'] = $user['email'];

            $_SESSION['LAST_ACTIVITY'] = time();

            // Update Last Login

            $updateSql = "
            UPDATE users
            SET last_login = NOW(),
                failed_login_attempts = 0
            WHERE id = ?
            ";

            $updateStmt = mysqli_prepare($conn, $updateSql);
            mysqli_stmt_bind_param(
                $updateStmt,
                "i",
                $user['id']
            );
            mysqli_stmt_execute($updateStmt);

            // Log Login

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
                'User Login',
                ?
            )
            ";

            $ip = $_SERVER['REMOTE_ADDR'];

            $logStmt = mysqli_prepare($conn, $logSql);
            mysqli_stmt_bind_param(
                $logStmt,
                "is",
                $user['id'],
                $ip
            );
            mysqli_stmt_execute($logStmt);

            header("Location: dashboard.php");
            exit();

        } else {

            $message = "Invalid username or password.";

            $attemptSql = "
            UPDATE users
            SET failed_login_attempts =
                failed_login_attempts + 1
            WHERE id = ?
            ";

            $attemptStmt = mysqli_prepare(
                $conn,
                $attemptSql
            );

            mysqli_stmt_bind_param(
                $attemptStmt,
                "i",
                $user['id']
            );

            mysqli_stmt_execute($attemptStmt);
        }

    } else {

        $message = "Invalid username or password.";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<title>CMMS Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container">

    <div class="row justify-content-center mt-5">

        <div class="col-md-4">

            <div class="card shadow">

                <div class="card-header text-center">

                    <h3>CMMS Login</h3>

                </div>

                <div class="card-body">

                    <?php if(!empty($message)): ?>

                        <div class="alert alert-danger">

                            <?php echo $message; ?>

                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">

                                Username

                            </label>

                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Password

                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Login

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>
// add logout and timeout messages
<?php if(isset($_GET['logout'])): ?>
    <div class="alert alert-success">
        You have been logged out successfully.
    </div>
<?php endif; ?>

<?php if(isset($_GET['timeout'])): ?>
    <div class="alert alert-warning">
        Session expired. Please login again.
    </div>
<?php endif; ?>

</body>
</html>