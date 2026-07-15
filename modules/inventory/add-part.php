<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$message = "";

/* Manufacturers */
$manufacturers = mysqli_query(
    $conn,
    "SELECT id, manufacturer_name
     FROM manufacturers
     WHERE status='active'
     ORDER BY manufacturer_name"
);

/* Vendors */
$vendors = mysqli_query(
    $conn,
    "SELECT id, vendor_name
     FROM vendors
     WHERE status='active'
     ORDER BY vendor_name"
);

/* Equipment Categories */
$categories = mysqli_query(
    $conn,
    "SELECT id, category_name
     FROM equipment_categories
     ORDER BY category_name"
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $part_code       = mysqli_real_escape_string($conn,$_POST['part_code']);
    $barcode         = mysqli_real_escape_string($conn,$_POST['barcode']);
    $qr_code         = mysqli_real_escape_string($conn,$_POST['qr_code']);
    $barcode_type    = mysqli_real_escape_string($conn,$_POST['barcode_type']);

    $part_name       = mysqli_real_escape_string($conn,$_POST['part_name']);

    $manufacturer_id = intval($_POST['manufacturer_id']);

    $category_id     = intval($_POST['category_id']);

    $vendor_id =
        !empty($_POST['vendor_id'])
        ? intval($_POST['vendor_id'])
        : "NULL";

    $part_type       =
        mysqli_real_escape_string(
            $conn,
            $_POST['part_type']
        );

    $quantity        = intval($_POST['quantity']);

    $reorder_level   =
        intval($_POST['reorder_level']);

    $unit_price      =
        floatval($_POST['unit_price']);

    $location        =
        mysqli_real_escape_string(
            $conn,
            $_POST['location']
        );

    $sql = "
    INSERT INTO spare_parts
    (
        part_code,
        barcode,
        qr_code,
        barcode_type,
        part_name,
        manufacturer_id,
        category_id,
        part_type,
        vendor_id,
        quantity,
        reorder_level,
        unit_price,
        location
    )
    VALUES
    (
        '$part_code',
        '$barcode',
        '$qr_code',
        '$barcode_type',
        '$part_name',
        '$manufacturer_id',
        '$category_id',
        '$part_type',
        $vendor_id,
        '$quantity',
        '$reorder_level',
        '$unit_price',
        '$location'
    )
    ";

    if(mysqli_query($conn,$sql)){

        header("Location: inventory.php");
        exit;

    }else{

        $message = mysqli_error($conn);

    }
}

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';
?>

<div class="content-wrapper">

<section class="content-header">

<div class="container-fluid">

<div class="row mb-2">

<div class="col-sm-6">
<h1>Add Spare Part</h1>
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
Spare Part Information
</h3>

</div>

<form method="POST">

<div class="card-body">

<?php if($message!=""){ ?>

<div class="alert alert-danger">

<?= $message ?>

</div>

<?php } ?>

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Part Code</label>

<input
type="text"
name="part_code"
class="form-control"
required>

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Part Name</label>

<input
type="text"
name="part_name"
class="form-control"
required>

</div>

</div>

</div>

<div class="row">

<div class="col-md-4">

<div class="form-group">

<label>Barcode</label>

<input
type="text"
name="barcode"
class="form-control">

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>QR Code</label>

<input
type="text"
name="qr_code"
class="form-control">

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Barcode Type</label>

<select
name="barcode_type"
class="form-control">

<option selected>CODE128</option>
<option>CODE39</option>
<option>EAN13</option>
<option>QR</option>

</select>

</div>

</div>

</div>

<div class="row">

<div class="col-md-4">

<div class="form-group">

<label>Manufacturer</label>

<select
name="manufacturer_id"
class="form-control"
required>

<option value="">
Select Manufacturer
</option>

<?php
while($m=mysqli_fetch_assoc($manufacturers)){
?>

<option
value="<?= $m['id']; ?>">

<?= htmlspecialchars(
        $m['manufacturer_name']
); ?>

</option>

<?php } ?>

</select>

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Equipment Category</label>

<select
name="category_id"
class="form-control"
required>

<option value="">
Select Category
</option>

<?php
while($c=mysqli_fetch_assoc($categories)){
?>

<option
value="<?= $c['id']; ?>">

<?= htmlspecialchars(
        $c['category_name']
); ?>

</option>

<?php } ?>

</select>

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Vendor</label>

<select
name="vendor_id"
class="form-control">

<option value="">
Select Vendor
</option>

<?php
while($v=mysqli_fetch_assoc($vendors)){
?>

<option
value="<?= $v['id']; ?>">

<?= htmlspecialchars(
        $v['vendor_name']
); ?>

</option>

<?php } ?>

</select>

</div>

</div>

</div>

<div class="row">

<div class="col-md-4">

<div class="form-group">

<label>Part Type</label>

<select
name="part_type"
class="form-control">

<option>Consumable</option>
<option selected>Spare Part</option>
<option>Accessory</option>
<option>Battery</option>
<option>Sensor</option>
<option>PCB</option>
<option>Mechanical</option>
<option>Cable</option>

</select>

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Quantity</label>

<input
type="number"
name="quantity"
class="form-control"
value="0"
required>

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Reorder Level</label>

<input
type="number"
name="reorder_level"
class="form-control"
value="5"
required>

</div>

</div>

</div>

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Unit Price</label>

<input
type="number"
step="0.01"
name="unit_price"
class="form-control"
value="0.00">

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Store Location</label>

<input
type="text"
name="location"
class="form-control">

</div>

</div>

</div>

</div>

<div class="card-footer">

<button
type="submit"
class="btn btn-success">

<i class="fas fa-save"></i>

Save Spare Part

</button>

<a
href="inventory.php"
class="btn btn-secondary">

Cancel

</a>

</div>

</form>

</div>

</div>

</section>

</div>

<?php include '../../includes/footer.php'; ?>