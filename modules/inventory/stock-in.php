
<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

/* Load Spare Parts */

$parts = mysqli_query(
    $conn,
    "SELECT id,
            part_code,
            part_name,
            barcode
     FROM spare_parts
     WHERE status='active'
     ORDER BY part_name"
);

/* Load Vendors */

$vendors = mysqli_query(
    $conn,
    "SELECT id,
            vendor_name
     FROM vendors
     WHERE status='active'
     ORDER BY vendor_name"
);

/* Save Stock In */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $part_id      = intval($_POST['part_id']);
    $vendor_id    = intval($_POST['vendor_id']);
    $quantity     = intval($_POST['quantity']);
    $unit_price   = floatval($_POST['unit_price']);
    $invoice_no   = mysqli_real_escape_string($conn,$_POST['invoice_no']);
    $remarks      = mysqli_real_escape_string($conn,$_POST['remarks']);
    $received_by  = $_SESSION['user_id'];

    /* Get Barcode */

    $barcode_sql =
        mysqli_query(
            $conn,
            "SELECT barcode
             FROM spare_parts
             WHERE id='$part_id'"
        );

    $barcode =
        mysqli_fetch_assoc($barcode_sql)['barcode'];

    /* Insert Transaction */

    $sql = "
    INSERT INTO stock_in
    (
        part_id,
        vendor_id,
        quantity,
        unit_price,
        invoice_no,
        barcode,
        remarks,
        received_by
    )
    VALUES
    (
        '$part_id',
        '$vendor_id',
        '$quantity',
        '$unit_price',
        '$invoice_no',
        '$barcode',
        '$remarks',
        '$received_by'
    )
    ";

    if (mysqli_query($conn,$sql)) {

        /* Update Stock */

        mysqli_query(
            $conn,
            "UPDATE spare_parts
             SET quantity = quantity + $quantity
             WHERE id='$part_id'"
        );

        header("Location: inventory.php");
        exit;
    }
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

<h1>Stock In</h1>

</div>

<div class="col-sm-6 text-right">

<a href="inventory.php"
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

Receive Spare Parts

</h3>

</div>

<form method="POST">

<div class="card-body">

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Spare Part</label>

<select
name="part_id"
class="form-control"
required>

<option value="">
Select Part
</option>

<?php while($p=mysqli_fetch_assoc($parts)){ ?>

<option value="<?= $p['id']; ?>">

<?= $p['part_code']; ?>
 -
<?= $p['part_name']; ?>

</option>

<?php } ?>

</select>

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Vendor</label>

<select
name="vendor_id"
class="form-control">

<option value="">
Select Vendor
</option>

<?php while($v=mysqli_fetch_assoc($vendors)){ ?>

<option value="<?= $v['id']; ?>">

<?= $v['vendor_name']; ?>

</option>

<?php } ?>

</select>

</div>

</div>

</div>

<div class="row">

<div class="col-md-4">

<div class="form-group">

<label>Quantity</label>

<input
type="number"
name="quantity"
class="form-control"
required>

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Unit Price</label>

<input
type="number"
step="0.01"
name="unit_price"
class="form-control">

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Invoice No</label>

<input
type="text"
name="invoice_no"
class="form-control">

</div>

</div>

</div>

<div class="form-group">

<label>Remarks</label>

<textarea
name="remarks"
class="form-control"
rows="3"></textarea>

</div>

</div>

<div class="card-footer">

<button
type="submit"
class="btn btn-success">

<i class="fas fa-save"></i>

Receive Stock

</button>

</div>

</form>

</div>

</div>

</section>

</div>

<?php include '../../includes/footer.php'; ?>