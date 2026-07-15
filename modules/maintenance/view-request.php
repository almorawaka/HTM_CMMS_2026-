<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: maintenance.php");
    exit;
}

$sql = "
SELECT
    rr.*,
    e.asset_number,
    e.equipment_name,
    e.manufacturer,
    e.model,
    e.serial_number,
    u.full_name AS requested_user
FROM repair_requests rr

LEFT JOIN equipment e
ON rr.equipment_id = e.id

LEFT JOIN users u
ON rr.requested_by = u.id

WHERE rr.id = $id
";

$result = mysqli_query($conn, $sql);
$request = mysqli_fetch_assoc($result);

if (!$request) {
    die("Repair Request Not Found");
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
<h1>Repair Request Details</h1>
</div>

<div class="col-sm-6 text-right">

<a href="maintenance.php"
class="btn btn-secondary">

<i class="fas fa-arrow-left"></i>
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
Request #<?= $request['id']; ?>
</h3>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6">

<table class="table table-bordered">

<tr>
<th width="220">Equipment</th>
<td><?= htmlspecialchars($request['equipment_name']); ?></td>
</tr>

<tr>
<th>Asset Number</th>
<td><?= htmlspecialchars($request['asset_number']); ?></td>
</tr>

<tr>
<th>Manufacturer</th>
<td><?= htmlspecialchars($request['manufacturer']); ?></td>
</tr>

<tr>
<th>Model</th>
<td><?= htmlspecialchars($request['model']); ?></td>
</tr>

<tr>
<th>Serial Number</th>
<td><?= htmlspecialchars($request['serial_number']); ?></td>
</tr>

<tr>
<th>Requested By</th>
<td><?= htmlspecialchars($request['requested_user']); ?></td>
</tr>

<tr>
<th>Request Date</th>
<td><?= $request['request_date']; ?></td>
</tr>

</table>

</div>

<div class="col-md-6">

<table class="table table-bordered">

<tr>

<th width="220">Priority</th>

<td>

<?php

switch($request['priority']) {

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

switch($request['status']) {

case 'open':
echo '<span class="badge badge-danger">Open</span>';
break;

case 'assigned':
echo '<span class="badge badge-warning">Assigned</span>';
break;

case 'in_progress':
echo '<span class="badge badge-primary">In Progress</span>';
break;

case 'completed':
echo '<span class="badge badge-success">Completed</span>';
break;

default:
echo $request['status'];
}

?>

</td>

</tr>

</table>

</div>

</div>

<hr>

<h5>Problem Description</h5>

<div class="alert alert-light border">

<?= nl2br(htmlspecialchars($request['problem_description'])); ?>

</div>

<hr>

<div class="mt-3">

<a href="assign-workorder.php?id=<?= $request['id']; ?>"
class="btn btn-primary">

<i class="fas fa-user-cog"></i>

Assign Work Order

</a>

<a href="close-request.php?id=<?= $request['id']; ?>"
class="btn btn-success">

<i class="fas fa-check-circle"></i>

Close Request

</a>

</div>

</div>

</div>

</div>

</section>

</div>

<?php include '../../includes/footer.php'; ?>