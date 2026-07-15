<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$sql = "
SELECT
    rr.*,
    e.asset_number,
    e.equipment_name
FROM repair_requests rr

LEFT JOIN equipment e
ON rr.equipment_id = e.id

ORDER BY rr.id DESC
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
<h1>Repair Requests</h1>
</div>

<div class="col-sm-6">

<a href="add-request.php"
   class="btn btn-success float-sm-right">

<i class="fas fa-plus"></i>

Create Request

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

Repair Request List

</h3>

</div>

<div class="card-body">

<table
id="requestTable"
class="table table-bordered table-striped">

<thead>

<tr>

<th>ID</th>
<th>Equipment</th>
<th>Asset No</th>
<th>Problem</th>
<th>Priority</th>
<th>Status</th>
<th>Requested Date</th>
<th width="160">Action</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)) : ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['equipment_name']); ?></td>

<td><?= htmlspecialchars($row['asset_number']); ?></td>

<td><?= htmlspecialchars($row['problem_description']); ?></td>

<td>

<?php

$priority = strtolower($row['priority']);

if($priority == 'critical'){
    echo '<span class="badge badge-danger">Critical</span>';
}
elseif($priority == 'high'){
    echo '<span class="badge badge-warning">High</span>';
}
elseif($priority == 'medium'){
    echo '<span class="badge badge-info">Medium</span>';
}
else{
    echo '<span class="badge badge-success">Low</span>';
}

?>

</td>

<td>

<?php

$status = strtolower($row['status']);

if($status == 'open'){
    echo '<span class="badge badge-danger">Open</span>';
}
elseif($status == 'assigned'){
    echo '<span class="badge badge-warning">Assigned</span>';
}
elseif($status == 'in_progress'){
    echo '<span class="badge badge-primary">In Progress</span>';
}
elseif($status == 'completed'){
    echo '<span class="badge badge-success">Completed</span>';
}
else{
    echo '<span class="badge badge-secondary">'
         .$row['status'].
         '</span>';
}

?>

</td>

<td><?= $row['request_date']; ?></td>

<td>

<a href="view-request.php?id=<?= $row['id']; ?>"
class="btn btn-info btn-sm"
title="View">

<i class="fas fa-eye"></i>

</a>

<a href="assign-workorder.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm"
title="Assign">

<i class="fas fa-user-cog"></i>

</a>

<a href="close-request.php?id=<?= $row['id']; ?>"
class="btn btn-success btn-sm"
title="Close">

<i class="fas fa-check"></i>

</a>

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

$(function(){

$('#requestTable').DataTable({
responsive:true,
autoWidth:false
});

});

</script>

<?php include '../../includes/footer.php'; ?>