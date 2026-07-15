<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$result = mysqli_query(
    $conn,
    "SELECT *
FROM vendors
WHERE status='active'
ORDER BY vendor_name;"
);

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>

<div class="content-wrapper">

<section class="content-header">

<div class="container-fluid">

<div class="row">

<div class="col-sm-6">

<h1>Vendors</h1>

</div>

<div class="col-sm-6 text-right">

<a href="add-vendor.php"
   class="btn btn-success">

<i class="fas fa-plus"></i>

Add Vendor

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

Vendor List

</h3>

</div>

<div class="card-body">

<table
id="vendorTable"
class="table table-bordered table-striped">

<thead>

<tr>

<th>Code</th>
<th>Vendor Name</th>
<th>Type</th>
<th>Contact</th>
<th>Phone</th>
<th>Email</th>
<th>Status</th>
<th width="120">Action</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td>
<?= htmlspecialchars($row['vendor_code']); ?>
</td>

<td>
<strong>
<?= htmlspecialchars($row['vendor_name']); ?>
</strong>
</td>

<td>

<?php

$type=$row['vendor_type'];

if($type=='manufacturer')
    echo '<span class="badge badge-primary">Manufacturer</span>';

elseif($type=='distributor')
    echo '<span class="badge badge-info">Distributor</span>';

elseif($type=='service_provider')
    echo '<span class="badge badge-warning">Service</span>';

else
    echo '<span class="badge badge-secondary">Supplier</span>';

?>

</td>

<td>
<?= htmlspecialchars($row['contact_person']); ?>
</td>

<td>
<?= htmlspecialchars($row['phone']); ?>
</td>

<td>
<?= htmlspecialchars($row['email']); ?>
</td>

<td>

<?php

if($row['status']=='active')
    echo '<span class="badge badge-success">Active</span>';
else
    echo '<span class="badge badge-danger">Inactive</span>';

?>

</td>

<td>

<div class="btn-group">

<a href="edit-vendor.php?id=<?= $row['id']; ?>"
   class="btn btn-primary btn-sm">

<i class="fas fa-edit"></i>

</a>

<a href="delete-vendor.php?id=<?= $row['id']; ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Deactivate this vendor?')">

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

$('#vendorTable').DataTable({

responsive:true,
autoWidth:false,
pageLength:25

});

});

</script>