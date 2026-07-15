<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: maintenance.php");
    exit;
}

/* Get Work Order */

$sql = "
SELECT
    wo.*,
    rr.equipment_id
FROM work_orders wo
LEFT JOIN repair_requests rr
ON wo.repair_request_id = rr.id
WHERE wo.id = $id
";

$result = mysqli_query($conn, $sql);
$workorder = mysqli_fetch_assoc($result);

if (!$workorder) {
    die('Work Order Not Found');
}

$equipment_id = $workorder['equipment_id'];

/* Complete Work Order */

mysqli_query($conn,"
UPDATE work_orders
SET
    status='completed',
    completion_time = NOW()
WHERE id='$id'
");

/* Complete Repair Request */

mysqli_query($conn,"
UPDATE repair_requests
SET
    status='completed'
WHERE id='{$workorder['repair_request_id']}'
");

/* Set Equipment Active */

mysqli_query($conn,"
UPDATE equipment
SET
    status_id = 1
WHERE id='$equipment_id'
");

/* Equipment History */

$user_id = $_SESSION['user_id'] ?? 0;

mysqli_query($conn,"
INSERT INTO equipment_history
(
    equipment_id,
    action_type,
    action_details,
    performed_by
)
VALUES
(
    '$equipment_id',
    'Repair Completed',
    'Work Order #$id completed successfully',
    '$user_id'
)
");

header("Location: view-workorder.php?id=".$id);
exit;
?>