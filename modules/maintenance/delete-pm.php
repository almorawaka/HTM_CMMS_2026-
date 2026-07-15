<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: pm-schedule.php");
    exit;
}

/* Get PM Record Before Delete */

$sql = "
SELECT
    p.*,
    e.equipment_name
FROM pm_schedule p

LEFT JOIN equipment e
ON p.equipment_id = e.id

WHERE p.id = '$id'
";

$result = mysqli_query($conn, $sql);
$pm = mysqli_fetch_assoc($result);

if (!$pm) {
    die('PM Schedule Not Found');
}

/* Save History Record */

$performed_by = $_SESSION['user_id'];

$history = "
INSERT INTO equipment_history
(
    equipment_id,
    action_type,
    action_details,
    performed_by
)
VALUES
(
    '{$pm['equipment_id']}',
    'PM Schedule Deleted',
    'PM Schedule deleted. Frequency: {$pm['frequency']}, Next PM Date: {$pm['next_pm_date']}',
    '$performed_by'
)
";

mysqli_query($conn, $history);

/* Delete PM Schedule */

$delete = "
DELETE FROM pm_schedule
WHERE id = '$id'
";

mysqli_query($conn, $delete);

header("Location: pm-schedule.php");
exit;

?>