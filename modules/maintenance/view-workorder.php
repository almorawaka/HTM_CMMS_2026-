<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: workorders.php");
    exit;
}

$sql = "
SELECT
    wo.*,

    rr.problem_description,
    rr.priority,
    rr.status AS request_status,

    e.asset_number,
    e.equipment_name,
    e.manufacturer,
    e.model,
    e.serial_number,

    u.full_name AS assigned_user

FROM work_orders wo

LEFT JOIN repair_requests rr
ON wo.repair_request_id = rr.id

LEFT JOIN equipment e
ON rr.equipment_id = e.id

LEFT JOIN users u
ON wo.assigned_to = u.id

WHERE wo.id = $id
";

$result = mysqli_query($conn, $sql);
$workorder = mysqli_fetch_assoc($result);

if (!$workorder) {
    die("Work Order Not Found");
}

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>

<div class="content-wrapper">

<section class="content-header">

<div class="container-fluid">

<div class="row">

<div class="col-sm-6">
<h1>Work Order Details</h1>
</div>

<div class="col-sm-6 text-right">

<a href="workorders.php"
class="btn btn-secondary">

<i class="fas fa-arrow-left"></i>
Back

</a>

<a href="update-workorder.php?id=<?= $id ?>"
class="btn btn-primary">

<i class="fas fa-edit"></i>
Update

</a>

</div>

</div>

</div>

</section>

<section class="content">

<div class="container-fluid">

<div class="row">

<div class="col-md-8">

<div class="card">

<div class="card-header">
<h3 class="card-title">
Work Order #<?= $workorder['id']; ?>
</h3>
</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th width="250">Equipment</th>
<td><?= htmlspecialchars($workorder['equipment_name']); ?></td>
</tr>

<tr>
<th>Asset Number</th>
<td><?= htmlspecialchars($workorder['asset_number']); ?></td>
</tr>

<tr>
<th>Manufacturer</th>
<td><?= htmlspecialchars($workorder['manufacturer']); ?></td>
</tr>

<tr>
<th>Model</th>
<td><?= htmlspecialchars($workorder['model']); ?></td>
</tr>

<tr>
<th>Serial Number</th>
<td><?= htmlspecialchars($workorder['serial_number']); ?></td>
</tr>

<tr>
<th>Assigned To</th>
<td><?= htmlspecialchars($workorder['assigned_user']); ?></td>
</tr>

<tr>
<th>Priority</th>
<td>

<?php

switch($workorder['priority']) {

case 'critical':
echo '<span class="badge badge-danger">Critical</span>';
break;

case 'high':
echo '<span class="badge badge-warning">High</span>';
break;

case 'medium':
echo '<span class="badge badge-info">Medium</span>';
break;

default:
echo '<span class="badge badge-success">Low</span>';
}

?>

</td>
</tr>

<tr>
<th>Work Order Status</th>
<td>

<?php

switch($workorder['status']) {

case 'pending':
echo '<span class="badge badge-warning">Pending</span>';
break;

case 'in_progress':
echo '<span class="badge badge-primary">In Progress</span>';
break;

case 'completed':
echo '<span class="badge badge-success">Completed</span>';
break;

}

?>

</td>
</tr>

<tr>
<th>Start Time</th>
<td><?= $workorder['start_time']; ?></td>
</tr>

<tr>
<th>Completion Time</th>
<td><?= $workorder['completion_time']; ?></td>
</tr>

</table>

</div>

</div>

<div class="card">

<div class="card-header">
<h3 class="card-title">
Fault Description
</h3>
</div>

<div class="card-body">

<?= nl2br(htmlspecialchars($workorder['problem_description'])); ?>

</div>

</div>

<div class="card">

<div class="card-header">
<h3 class="card-title">
Diagnosis
</h3>
</div>

<div class="card-body">

<?=
!empty($workorder['diagnosis'])
? nl2br(htmlspecialchars($workorder['diagnosis'] ?? ''))
: '<span class="text-muted">Not entered yet</span>';
?>

</div>

</div>

<div class="card">

<div class="card-header">
<h3 class="card-title">
Corrective Action
</h3>
</div>

<div class="card-body">

<?=
!empty($workorder['corrective_action'])
? nl2br(htmlspecialchars($workorder['corrective_action'] ?? ''))
: '<span class="text-muted">Not entered yet</span>';
?>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card">

<div class="card-header">
<h3 class="card-title">
Quick Actions
</h3>
</div>

<div class="card-body">

<a href="update-workorder.php?id=<?= $id ?>"
class="btn btn-primary btn-block mb-2">

<i class="fas fa-tools"></i>

Update Work Order

</a>

<!-- <?php if($workorder['status'] != 'completed'): ?>

<a href="complete-workorder.php?id=<?= $id ?>"
class="btn btn-success btn-block">

<i class="fas fa-check-circle"></i>

Complete Work Order

</a>

<?php endif; ?> -->

<?php if($workorder['status'] != 'completed'): ?>

<a href="complete-workorder.php?id=<?= $workorder['id']; ?>"
   class="btn btn-success btn-block"
   onclick="return confirm('Complete this work order?');">

    <i class="fas fa-check-circle"></i>
    Complete Work Order

</a>

<?php endif; ?>



</div>

</div>

</div>

</div>

</div>

</section>

</div>

<?php include '../../includes/footer.php'; ?>