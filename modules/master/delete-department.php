<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = (int)$_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM departments WHERE id = $id"
);

header("Location: departments.php");
exit;