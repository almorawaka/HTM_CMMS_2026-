<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$sql = "
SELECT
    p.*,
    e.asset_number,
    e.equipment_name,
    u.full_name AS assigned_user

FROM pm_schedule p

LEFT JOIN equipment e
ON p.equipment_id = e.id

LEFT JOIN users u
ON p.assigned_to = u.id

ORDER BY p.next_pm_date ASC
";

$result = mysqli_query($conn, $sql);

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>

<div class="content-wrapper">

<section class="content-header">

<div class="container-fluid">

<div class="row mb-2">

<div class="col-sm-6">
<h1>Preventive Maintenance Schedule</h1>
</div>

<div class="col-sm-6">

<a href="add-pm.php"
class="btn btn-success float-sm-right">

<i class="fas fa-plus"></i>
Create PM Schedule

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
PM Schedule List
</h3>
</div>

<div class="card-body">

<table id="pmTable"
class="table table-bordered table-striped">

<thead>

<tr>

<th>ID</th>
<th>Asset No</th>
<th>Equipment</th>
<th>Frequency</th>
<th>Assigned To</th>
<th>Next PM Date</th>
<th>Priority</th>
<th>Status</th>
<th width="170">Action</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['asset_number']); ?></td>

<td><?= htmlspecialchars($row['equipment_name']); ?></td>

<td>

<span class="badge badge-info">

<?= ucfirst(str_replace('_',' ',$row['frequency'])); ?>

</span>

</td>

<td>

<?= htmlspecialchars($row['assigned_user'] ?? 'Not Assigned'); ?>

</td>

<td>

<?= $row['next_pm_date']; ?>

</td>

<td>

<?php

switch($row['priority']){

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

<td>

<?php

switch($row['status']){

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

default:
echo $row['status'];

}

?>

</td>

<td>

<div class="btn-group">

<a href="view-pm.php?id=<?= $row['id']; ?>"
class="btn btn-info btn-sm"
title="View">

<i class="fas fa-eye"></i>

</a>

<a href="edit-pm.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm"
title="Edit">

<i class="fas fa-edit"></i>

</a>

<?php if($row['status']!='completed'): ?>

<a href="complete-pm.php?id=<?= $row['id']; ?>"
class="btn btn-success btn-sm"
title="Complete"
onclick="return confirm('Mark PM Completed?')">

<i class="fas fa-check"></i>

</a>

<?php endif; ?>

<a href="delete-pm.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
title="Delete"
onclick="return confirm('Delete PM Schedule?')">

<i class="fas fa-trash"></i>

</a>

</div>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</div>

</div>

</section>

</div>

<script>

$(function () {

$('#pmTable').DataTable({

responsive:true,
autoWidth:false

});

});

</script>

<?php include '../../includes/footer.php'; ?>