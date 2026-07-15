<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$sql = "
SELECT *
FROM manufacturers
WHERE status='active'
ORDER BY manufacturer_name;
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

<h1>Manufacturers</h1>

</div>

<div class="col-sm-6 text-right">

<a href="add-manufacturer.php"
   class="btn btn-success">

<i class="fas fa-plus"></i>

Add Manufacturer

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

Manufacturer List

</h3>

</div>

<div class="card-body">

<table
id="manufacturerTable"
class="table table-bordered table-striped">

<thead>

<tr>

<th width="100">Code</th>
<th>Manufacturer</th>
<th>Country</th>
<th>Contact Person</th>
<th>Phone</th>
<th>Service Email</th>
<th width="100">Status</th>
<th width="170">Action</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td>

<?= htmlspecialchars($row['manufacturer_code']); ?>

</td>

<td>

<strong>

<?= htmlspecialchars($row['manufacturer_name']); ?>

</strong>

</td>

<td>

<?= htmlspecialchars($row['country']); ?>

</td>

<td>

<?= htmlspecialchars($row['contact_person']); ?>

</td>

<td>

<?= htmlspecialchars($row['phone']); ?>

</td>

<td>

<?= htmlspecialchars($row['service_email']); ?>

</td>

<td>

<?php

if($row['status']=='active'){

echo '<span class="badge badge-success">
      Active
      </span>';

}else{

echo '<span class="badge badge-danger">
      Inactive
      </span>';

}

?>

</td>

<td>

<div class="btn-group" role="group">

<a
href="edit-manufacturer.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm"
title="Edit">

<i class="fas fa-edit"></i>

</a>

<a
href="delete-manufacturer.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
title="Delete"

onclick="
return confirm(
'Deactivate this manufacturer?'
);">

<i class="fas fa-trash"></i>

</a>

</div>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</section>

</div>

<?php include '../../includes/footer.php'; ?>

<script>

$(function(){

$('#manufacturerTable').DataTable({

responsive:true,

autoWidth:false,

pageLength:25,

order:[[1,'asc']]

});

});

</script>