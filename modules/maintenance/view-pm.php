<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: pm-schedule.php");
    exit;
}

$sql = "
SELECT
    p.*,
    e.asset_number,
    e.equipment_name,
    e.manufacturer,
    e.model,
    u.full_name AS assigned_user,
    cu.full_name AS created_user

FROM pm_schedule p

LEFT JOIN equipment e
ON p.equipment_id = e.id

LEFT JOIN users u
ON p.assigned_to = u.id

LEFT JOIN users cu
ON p.created_by = cu.id

WHERE p.id = '$id'
";

$result = mysqli_query($conn,$sql);
$pm = mysqli_fetch_assoc($result);

if(!$pm){
    die('PM Schedule Not Found');
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
<h1>PM Schedule Details</h1>
</div>

<div class="col-sm-6 text-right">

<a href="edit-pm.php?id=<?= $id ?>"
class="btn btn-primary">

<i class="fas fa-edit"></i>
Edit

</a>

<a href="pm-schedule.php"
class="btn btn-secondary">

Back

</a>

</div>

</div>

</div>

</section>

<section class="content">

<div class="container-fluid">

<div class="card">

<div class="card-header">

<h3 class="card-title">

<?= htmlspecialchars($pm['equipment_name']); ?>

</h3>

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th width="250">Asset Number</th>
<td><?= htmlspecialchars($pm['asset_number']); ?></td>
</tr>

<tr>
<th>Equipment Name</th>
<td><?= htmlspecialchars($pm['equipment_name']); ?></td>
</tr>

<tr>
<th>Manufacturer</th>
<td><?= htmlspecialchars($pm['manufacturer']); ?></td>
</tr>

<tr>
<th>Model</th>
<td><?= htmlspecialchars($pm['model']); ?></td>
</tr>

<tr>
<th>Frequency</th>
<td><?= ucfirst(str_replace('_',' ',$pm['frequency'])); ?></td>
</tr>

<tr>
<th>Assigned To</th>
<td><?= htmlspecialchars($pm['assigned_user']); ?></td>
</tr>

<tr>
<th>Last PM Date</th>
<td><?= $pm['last_pm_date']; ?></td>
</tr>

<tr>
<th>Next PM Date</th>
<td><?= $pm['next_pm_date']; ?></td>
</tr>

<tr>
<th>Estimated Duration</th>
<td><?= $pm['estimated_duration']; ?> Minutes</td>
</tr>

<tr>
<th>Priority</th>

<td>

<?php

switch($pm['priority']){

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
<th>Status</th>

<td>

<?php

switch($pm['status']){

case 'scheduled':
echo '<span class="badge badge-primary">Scheduled</span>';
break;

case 'in_progress':
echo '<span class="badge badge-warning">In Progress</span>';
break;

case 'completed':
echo '<span class="badge badge-success">Completed</span>';
break;

case 'overdue':
echo '<span class="badge badge-danger">Overdue</span>';
break;

}

?>

</td>

</tr>

<tr>
<th>Remarks</th>
<td><?= nl2br(htmlspecialchars($pm['remarks'])); ?></td>
</tr>

<tr>
<th>Created By</th>
<td><?= htmlspecialchars($pm['created_user']); ?></td>
</tr>

<tr>
<th>Created At</th>
<td><?= $pm['created_at']; ?></td>
</tr>

<tr>
<th>Updated At</th>
<td><?= $pm['updated_at']; ?></td>
</tr>

</table>

</div>

<div class="card-footer">

<?php if($pm['status'] != 'completed'): ?>

<a href="complete-pm.php?id=<?= $id ?>"
class="btn btn-success"
onclick="return confirm('Mark PM Completed?')">

<i class="fas fa-check"></i>

Complete PM

</a>

<?php endif; ?>

<a href="edit-pm.php?id=<?= $id ?>"
class="btn btn-primary">

<i class="fas fa-edit"></i>

Edit Schedule

</a>

</div>

</div>

</div>

</section>

</div>

<?php include '../../includes/footer.php'; ?>