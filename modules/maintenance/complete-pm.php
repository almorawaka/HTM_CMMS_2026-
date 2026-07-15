<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: pm-schedule.php");
    exit;
}

/* Get Current PM Schedule */

$sql = "
SELECT *
FROM pm_schedule
WHERE id = '$id'
";

$result = mysqli_query($conn, $sql);
$pm = mysqli_fetch_assoc($result);

if (!$pm) {
    die('PM Schedule Not Found');
}

$today = date('Y-m-d');

/* Calculate Next PM Date */

$currentNextDate = $pm['next_pm_date'];

switch ($pm['frequency']) {

    case 'weekly':
        $newNextDate = date(
            'Y-m-d',
            strtotime($currentNextDate . ' +7 days')
        );
        break;

    case 'monthly':
        $newNextDate = date(
            'Y-m-d',
            strtotime($currentNextDate . ' +1 month')
        );
        break;

    case 'quarterly':
        $newNextDate = date(
            'Y-m-d',
            strtotime($currentNextDate . ' +3 months')
        );
        break;

    case 'semi_annual':
        $newNextDate = date(
            'Y-m-d',
            strtotime($currentNextDate . ' +6 months')
        );
        break;

    case 'annual':
        $newNextDate = date(
            'Y-m-d',
            strtotime($currentNextDate . ' +1 year')
        );
        break;

    default:
        $newNextDate = $currentNextDate;
}

/* Mark Current Schedule Completed */

$update = "
UPDATE pm_schedule
SET
    status = 'completed',
    last_pm_date = '$today'
WHERE id = '$id'
";

mysqli_query($conn, $update);

/* Create New PM Cycle */

$equipment_id = $pm['equipment_id'];
$frequency = $pm['frequency'];
$assigned_to = $pm['assigned_to'];
$estimated_duration = $pm['estimated_duration'];
$priority = $pm['priority'];
$remarks = $pm['remarks'];
$created_by = $_SESSION['user_id'];

$insert = "
INSERT INTO pm_schedule
(
    equipment_id,
    frequency,
    assigned_to,
    last_pm_date,
    next_pm_date,
    estimated_duration,
    priority,
    status,
    remarks,
    created_by
)
VALUES
(
    '$equipment_id',
    '$frequency',
    '$assigned_to',
    '$today',
    '$newNextDate',
    '$estimated_duration',
    '$priority',
    'scheduled',
    '$remarks',
    '$created_by'
)
";

mysqli_query($conn, $insert);

/* Create Equipment History Record */

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
    '$equipment_id',
    'PM Completed',
    'Preventive maintenance completed. Next PM scheduled on $newNextDate',
    '" . $_SESSION['user_id'] . "'
)
";

mysqli_query($conn, $history);

header("Location: pm-schedule.php");
exit;

?>