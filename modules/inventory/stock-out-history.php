<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$sql = "
SELECT

    so.*,

    sp.part_code,
    sp.part_name,

    u1.name AS issued_to_name,
    u2.name AS issued_by_name

FROM stock_out so

LEFT JOIN spare_parts sp
    ON so.part_id = sp.id

LEFT JOIN users u1
    ON so.issued_to = u1.id

LEFT JOIN users u2
    ON so.issued_by = u2.id

ORDER BY so.issued_date DESC
";

$result = mysqli_query($conn,$sql);

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>

<div class="content-wrapper">

<section class="content-header">

<div class="container-fluid">

<div class="row">

<div class="col-sm-6">

<h1>Stock Out History</h1>

</div>

<div class="col-sm-6 text-right">

<a href="stock-out.php"
class="btn btn-danger">

<i class="fas fa-minus-circle"></i>

Issue Spare Part

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

Issued Spare Parts History

</h3>

</div>

<div class="card-body">

<table
id="stockOutTable"
class="table table-bordered table-striped">

<thead>

<tr>

<th>ID</th>
<th>Date</th>
<th>Part Code</th>
<th>Spare Part</th>
<th>Barcode</th>
<th>Quantity</th>
<th>Work Order</th>
<th>Issued To</th>
<th>Issued By</th>
<th>Remarks</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td>

<?= $row['id']; ?>

</td>

<td>

<?= date(
    'Y-m-d H:i',
    strtotime(
        $row['issued_date']
    )
); ?>

</td>

<td>

<span class="badge badge-info">

<?= htmlspecialchars(
    $row['part_code']
); ?>

</span>

</td>

<td>

<?= htmlspecialchars(
    $row['part_name']
); ?>

</td>

<td>

<?= htmlspecialchars(
    $row['barcode']
); ?>

</td>

<td>

<span class="badge badge-danger">

<?= $row['quantity']; ?>

</span>

</td>

<td>

<?php

if($row['work_order_id']){

echo
'WO-'
.$row['work_order_id'];

}else{

echo '-';

}

?>

</td>

<td>

<?= htmlspecialchars(
    $row['issued_to_name']
    ?? '-'
); ?>

</td>

<td>

<?= htmlspecialchars(
    $row['issued_by_name']
    ?? '-'
); ?>

</td>

<td>

<?= htmlspecialchars(
    $row['remarks']
); ?>

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

$('#stockOutTable')
.DataTable({

responsive:true,

autoWidth:false,

pageLength:25,

order:[[1,'desc']]

});

});

</script>