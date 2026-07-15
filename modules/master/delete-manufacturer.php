<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if(!$id){
    header("Location: manufacturers.php");
    exit;
}

mysqli_query(
    $conn,
    "UPDATE manufacturers
     SET status='inactive'
     WHERE id='$id'"
);

header("Location: manufacturers.php");
exit;

?>