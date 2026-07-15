<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = (int)$_GET['id'];

/*
Prevent deleting institute if departments exist
*/

$check = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM departments
     WHERE institute_id = $id"
);

$row = mysqli_fetch_assoc($check);

if ($row['total'] > 0) {

    echo "
    <script>
    alert('Cannot delete institute. Departments exist.');
    window.location='institutes.php';
    </script>
    ";

    exit;
}

mysqli_query(
    $conn,
    "DELETE FROM institutes
     WHERE id = $id"
);

header("Location: institutes.php");
exit;