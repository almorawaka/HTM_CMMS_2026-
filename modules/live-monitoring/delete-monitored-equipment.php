<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if(!$id){
    header("Location: monitored-equipment.php");
    exit;
}

/* Verify record exists */

$check = mysqli_query(
    $conn,
    "
    SELECT id
    FROM live_monitoring_equipment
    WHERE id='$id'
    "
);

if(mysqli_num_rows($check)==0){

    header("Location: monitored-equipment.php");
    exit;
}

/* Delete monitored equipment */

$sql = "
UPDATE live_monitoring_equipment
SET status='inactive'
WHERE id='$id'
";

if(mysqli_query($conn,$sql)){

    header("Location: monitored-equipment.php");
    exit;

}
else{

    die(
        "Unable to delete monitored equipment.<br><br>"
        . mysqli_error($conn)
    );
}
?>