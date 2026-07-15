<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$sql = "
SELECT
    si.*,
    sp.part_code,
    sp.part_name,
    v.vendor_name,
    u.name AS received_by_name
FROM stock_in si

LEFT JOIN spare_parts sp
    ON si.part_id = sp.id

LEFT JOIN vendors v
    ON si.vendor_id = v.id

LEFT JOIN users u
    ON si.received_by = u.id

ORDER BY si.received_date DESC
";

$result = mysqli_query($conn, $sql);

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>

<div class="content-wrapper">

<section class="content-header">

<div class="container-fluid">

<div class="row">

<div class="col-sm-6">

<h1>Stock In History</h1>

</div>

<div class="col-sm-6 text-right">

<a href="stock-in.php"
   class="btn btn-success">

<i class="fas fa-plus"></i>

New Stock In

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

Inventory Receiving History

</h3>

</div>

<div class="card-body">

<table
id="stockHistoryTable"
class="table table-bordered table-striped">

<thead>

<tr>

<th>ID</th>
<th>Date</th>
<th>Part Code</th>
<th>Spare Part</th>
<th>Barcode</th>
<th>Vendor</th>
<th>Quantity</th>
<th>Unit Price</th>
<th>Total</th>
<th>Invoice</th>
<th>Received By</th>

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
        strtotime($row['received_date'])
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

<?= htmlspecialchars(
        $row['vendor_name']
        ?? '-'
    ); ?>

</td>

<td>

<span class="badge badge-success">

<?= $row['quantity']; ?>

</span>

</td>

<td>

<?= number_format(
        $row['unit_price'],
        2
    ); ?>

</td>

<td>

<strong>

<?= number_format(
        $row['quantity']
        *
        $row['unit_price'],
        2
    ); ?>

</strong>

</td>

<td>

<?= htmlspecialchars(
        $row['invoice_no']
    ); ?>

</td>

<td>

<?= htmlspecialchars(
        $row['received_by_name']
        ?? '-'
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

    $('#stockHistoryTable')
    .DataTable({

        responsive:true,

        autoWidth:false,

        pageLength:25,

        order:[[1,'desc']]

    });

});

</script>