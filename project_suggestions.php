<?php

require_once 'config/db.php';

$message = "";
$message_type = "success";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.php");
    exit();
}

/* ==========================================
   Get Form Data
========================================== */

$name         = trim($_POST['name'] ?? '');
$email        = trim($_POST['email'] ?? '');
$organization = trim($_POST['organization'] ?? '');
$country      = trim($_POST['country'] ?? '');
$suggestion   = trim($_POST['suggestion'] ?? '');

/* ==========================================
   Validation
========================================== */

if ($suggestion == "") {

    $message = "Suggestion cannot be empty.";
    $message_type = "danger";

} else {

    $sql = "
    INSERT INTO project_suggestions
    (
        name,
        email,
        organization,
        country,
        suggestion,
        ip_address,
        submitted_at
    )
    VALUES
    (
        ?, ?, ?, ?, ?, ?, NOW()
    )
    ";

    $stmt = mysqli_prepare($conn, $sql);

    $ip = $_SERVER['REMOTE_ADDR'];

    mysqli_stmt_bind_param(
        $stmt,
        "ssssss",
        $name,
        $email,
        $organization,
        $country,
        $suggestion,
        $ip
    );

    if (mysqli_stmt_execute($stmt)) {

        $message =
        "Thank you! Your suggestion has been submitted successfully.";

        $message_type = "success";

    } else {

        $message =
        "Unable to submit your suggestion.";

        $message_type = "danger";
    }

    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Suggestion Submitted</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<style>

body{

background:#eef5fb;

}

.card{

margin-top:80px;

border-radius:15px;

}

</style>

</head>

<body>

<div class="container">

<div class="row justify-content-center">

<div class="col-md-7">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>

<i class="fas fa-lightbulb"></i>

Project Suggestions

</h3>

</div>

<div class="card-body text-center">

<div class="alert alert-<?php echo $message_type; ?>">

<?php echo htmlspecialchars($message); ?>

</div>

<p>

Your feedback helps improve the
<strong>Intelligent Medical Equipment CMMS</strong>.

</p>

<p>

We sincerely appreciate your contribution
towards developing an open-source platform
for Biomedical Engineers worldwide.

</p>

<hr>

<a
href="login.php"
class="btn btn-primary">

<i class="fas fa-arrow-left"></i>

Back to Login

</a>

<a
href="https://github.com/almorawaka/HTM_CMMS_2026-"
target="_blank"
class="btn btn-dark">

<i class="fab fa-github"></i>

GitHub Repository

</a>

</div>

</div>

</div>

</div>

</div>

</body>

</html>