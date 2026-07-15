<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$sql = "
SELECT
    wo.*,
    e.equipment_name,
    e.asset_number,
    u.full_name

FROM work_orders wo

LEFT JOIN repair_requests rr
ON wo.repair_request_id = rr.id

LEFT JOIN equipment e
ON rr.equipment_id = e.id

LEFT JOIN users u
ON wo.assigned_to = u.id

ORDER BY wo.id DESC
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
<h1>Work Orders</h1>
</div>

</div>

</div>

</section>

<section class="content">

<div class="container-fluid">

<div class="card">

<div class="card-header">

<h3 class="card-title">
Work Order List
</h3>

</div>

<div class="card-body">

<table
id="workorderTable"
class="table table-bordered table-striped">

<thead>

<tr>

<th>ID</th>
<th>Equipment</th>
<th>Asset No</th>
<th>Assigned To</th>
<th>Status</th>
<th>Start Time</th>
<th>Completion Time</th>
<th width="160">Action</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['equipment_name']); ?></td>

<td><?= htmlspecialchars($row['asset_number']); ?></td>

<td><?= htmlspecialchars($row['full_name']); ?></td>

<td>

<?php

if($row['status']=='pending'){
    echo '<span class="badge badge-warning">Pending</span>';
}
elseif($row['status']=='in_progress'){
    echo '<span class="badge badge-primary">In Progress</span>';
}
elseif($row['status']=='completed'){
    echo '<span class="badge badge-success">Completed</span>';
}

?>

</td>

<td><?= $row['start_time']; ?></td>

<td><?= $row['completion_time']; ?></td>

<td>

<div class="btn-group">

<a href="view-workorder.php?id=<?= $row['id']; ?>"
class="btn btn-info btn-sm"
title="View">

<i class="fas fa-eye"></i>

</a>

<a href="update-workorder.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm"
title="Update">

<i class="fas fa-edit"></i>

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

<link rel="stylesheet"
href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>
$(function () {
    $('#workorderTable').DataTable({
        responsive:true,
        autoWidth:false
    });
});
</script>

<?php include '../../includes/footer.php'; ?>