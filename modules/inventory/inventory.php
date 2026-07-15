<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$sql = "
SELECT

    sp.*,

    m.manufacturer_name,

    c.category_name,

    v.vendor_name

FROM spare_parts sp

LEFT JOIN manufacturers m
    ON sp.manufacturer_id = m.id

LEFT JOIN equipment_categories c
    ON sp.category_id = c.id

LEFT JOIN vendors v
    ON sp.vendor_id = v.id

ORDER BY sp.part_name;
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
<h1>Inventory</h1>
</div>

<div class="col-sm-6 text-right">

<a href="add-part.php"
class="btn btn-success">

<i class="fas fa-plus"></i>

Add Spare Part

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

Spare Parts Inventory

</h3>

</div>

<div class="card-body">

<table id="inventoryTable"
class="table table-bordered table-striped">

<thead>

<tr>

<th>Code</th>
<th>Part Name</th>
<th>Manufacturer</th>
<th>Equipment Category</th>
<th>Part Type</th>
<th>Quantity</th>
<th>Reorder Level</th>
<th>Unit Price</th>
<th>Store Location</th>
<th>Status</th>
<th width="170">Action</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<!-- <tr>

<td><?= htmlspecialchars($row['part_code']); ?></td>

<td><?= htmlspecialchars($row['part_name']); ?></td>
<td><?= htmlspecialchars($row['manufacturer_name']?? '-'); ?></td>

 
<td> <?= htmlspecialchars($row['category_name']); ?> </td>

<td> <?= htmlspecialchars($row['part_type']); ?> </td>

<td class="text-center">

<?= $row['quantity']; ?>

</td>

<td class="text-center">

<?= $row['reorder_level']; ?>

</td>

<td class="text-right">

Rs.
<?= number_format($row['unit_price'],2); ?>

</td>

<td>

<?= htmlspecialchars($row['location']); ?>

</td>

<td>

<?php

if($row['quantity']==0){

echo '<span class="badge badge-danger">Out of Stock</span>';

}

elseif($row['quantity']<=$row['reorder_level']){

echo '<span class="badge badge-warning">Low Stock</span>';

}

else{

echo '<span class="badge badge-success">Normal</span>';

}

?>

</td>

<td>

<div class="btn-group" role="group">

<a href="view-part.php?id=<?= $row['id']; ?>"
class="btn btn-info btn-sm"
title="View">

<i class="fas fa-eye"></i>

</a>

<a href="edit-part.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm"
title="Edit">

<i class="fas fa-edit"></i>

</a>

<a href="delete-part.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
title="Delete"

onclick="return confirm('Delete this spare part?');">

<i class="fas fa-trash"></i>

</a>

</div>

</td>

</tr> -->









<tr>

<td><?= htmlspecialchars($row['part_code']); ?></td>

<td><?= htmlspecialchars($row['part_name']); ?></td>

<td>
<?= htmlspecialchars(
        $row['manufacturer_name']
        ?? '-'
); ?>
</td>

<td>
<?= htmlspecialchars(
        $row['category_name']
        ?? '-'
); ?>
</td>

<td>
<?= htmlspecialchars(
        $row['part_type']
        ?? '-'
); ?>
</td>

<td>
<?= $row['quantity']; ?>
</td>

<td>
<?= $row['reorder_level']; ?>
</td>

<td>
Rs. <?= number_format($row['unit_price'],2); ?>
</td>

<td>
<?= htmlspecialchars(
        $row['location']
        ?? '-'
); ?>
</td>

<td>

<?php
if($row['quantity'] <= $row['reorder_level']){
    echo '<span class="badge badge-danger">Low Stock</span>';
}else{
    echo '<span class="badge badge-success">Normal</span>';
}
?>

</td>

<td>
  <div class="btn-group" role="group">

<a href="view-part.php?id=<?= $row['id']; ?>"
class="btn btn-info btn-sm"
title="View">

<i class="fas fa-eye"></i>

</a>

<a href="edit-part.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm"
title="Edit">

<i class="fas fa-edit"></i>

</a>

<a href="delete-part.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
title="Delete"

onclick="return confirm('Delete this spare part?');">

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

$('#inventoryTable').DataTable({

responsive:true,

autoWidth:false,

pageLength:25,

order:[[1,'asc']]

});

});

</script>