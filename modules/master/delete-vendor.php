<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if (!$id) {

    header("Location: vendors.php");
    exit;
}

/* Check Vendor */

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM vendors
     WHERE id='$id'"
);

$vendor = mysqli_fetch_assoc($result);

if (!$vendor) {

    die("Vendor not found");
}

/* Soft Delete */

$sql = "
UPDATE vendors
SET status='inactive'
WHERE id='$id'
";

if (mysqli_query($conn, $sql)) {

    header("Location: vendors.php");
    exit;
} else {

    die("Unable to deactivate vendor.");
}

?>