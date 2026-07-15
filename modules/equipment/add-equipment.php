<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

/* MASTER TABLES */

$manufacturers = mysqli_query(
    $conn,
    "SELECT id, manufacturer_name
     FROM manufacturers
     WHERE status='active'
     ORDER BY manufacturer_name"
);

$categories = mysqli_query(
    $conn,
    "SELECT *
     FROM equipment_categories
     ORDER BY category_name"
);

$institutes = mysqli_query(
    $conn,
    "SELECT *
     FROM institutes
     ORDER BY institute_name"
);

$departments = mysqli_query(
    $conn,
    "SELECT *
     FROM departments
     ORDER BY department_name"
);

$statuses = mysqli_query(
    $conn,
    "SELECT *
     FROM equipment_status
     ORDER BY status_name"
);

/* SAVE EQUIPMENT */

if($_SERVER['REQUEST_METHOD']=='POST'){

    $asset_number      = mysqli_real_escape_string($conn,$_POST['asset_number']);
    $equipment_name    = mysqli_real_escape_string($conn,$_POST['equipment_name']);

    $category_id       = intval($_POST['category_id']);
    $institute_id      = intval($_POST['institute_id']);
    $department_id     = intval($_POST['department_id']);

    $manufacturer_id   =
        !empty($_POST['manufacturer_id'])
        ? intval($_POST['manufacturer_id'])
        : "NULL";

    $model             = mysqli_real_escape_string($conn,$_POST['model']);
    $serial_number     = mysqli_real_escape_string($conn,$_POST['serial_number']);

    $purchase_date     =
        !empty($_POST['purchase_date'])
        ? $_POST['purchase_date']
        : NULL;

    $warranty_expiry   =
        !empty($_POST['warranty_expiry'])
        ? $_POST['warranty_expiry']
        : NULL;

    $status_id         = intval($_POST['status_id']);

    $criticality_level =
        mysqli_real_escape_string(
            $conn,
            $_POST['criticality_level']
        );

    $barcode           =
        mysqli_real_escape_string(
            $conn,
            $_POST['barcode']
        );

    $remarks           =
        mysqli_real_escape_string(
            $conn,
            $_POST['remarks']
        );

    $qr_code = $asset_number;

    $sql = "
    INSERT INTO equipment
    (
        asset_number,
        equipment_name,
        category_id,
        institute_id,
        department_id,
        manufacturer_id,
        model,
        serial_number,
        purchase_date,
        warranty_expiry,
        remarks,
        qr_code,
        status_id,
        criticality_level,
        barcode
    )
    VALUES
    (
        '$asset_number',
        '$equipment_name',
        '$category_id',
        '$institute_id',
        '$department_id',
        $manufacturer_id,
        '$model',
        '$serial_number',
        ".($purchase_date?"'$purchase_date'":"NULL").",
        ".($warranty_expiry?"'$warranty_expiry'":"NULL").",
        '$remarks',
        '$qr_code',
        '$status_id',
        '$criticality_level',
        '$barcode'
    )
    ";

    mysqli_query($conn,$sql);

    header("Location: equipment.php");
    exit;
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
<h1>Add Equipment</h1>
</div>

<div class="col-sm-6">

<a href="equipment.php"
class="btn btn-secondary float-sm-right">

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
New Equipment Registration
</h3>

</div>

<div class="card-body">

<form method="POST">

<!-- ROW 1 -->

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Asset Number *</label>

<input
type="text"
name="asset_number"
class="form-control"
required>

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Equipment Name *</label>

<input
type="text"
name="equipment_name"
class="form-control"
required>

</div>

</div>

</div>

<!-- ROW 2 -->

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Category *</label>

<select
name="category_id"
class="form-control"
required>

<option value="">
Select Category
</option>

<?php while($cat=mysqli_fetch_assoc($categories)){ ?>

<option value="<?= $cat['id']; ?>">

<?= htmlspecialchars($cat['category_name']); ?>

</option>

<?php } ?>

</select>

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Institute *</label>

<select
name="institute_id"
class="form-control"
required>

<option value="">
Select Institute
</option>

<?php while($ins=mysqli_fetch_assoc($institutes)){ ?>

<option value="<?= $ins['id']; ?>">

<?= htmlspecialchars($ins['institute_name']); ?>

</option>

<?php } ?>

</select>

</div>

</div>

</div>

<!-- ROW 3 -->

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Department *</label>

<select
name="department_id"
class="form-control"
required>

<option value="">
Select Department
</option>

<?php while($dep=mysqli_fetch_assoc($departments)){ ?>

<option value="<?= $dep['id']; ?>">

<?= htmlspecialchars($dep['department_name']); ?>

</option>

<?php } ?>

</select>

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Status *</label>

<select
name="status_id"
class="form-control"
required>

<?php while($st=mysqli_fetch_assoc($statuses)){ ?>

<option value="<?= $st['id']; ?>">

<?= htmlspecialchars($st['status_name']); ?>

</option>

<?php } ?>

</select>

</div>

</div>

</div>

<!-- ROW 4 -->

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Manufacturer</label>

<select
name="manufacturer_id"
class="form-control">

<option value="">
Select Manufacturer
</option>

<?php while($m=mysqli_fetch_assoc($manufacturers)){ ?>

<option value="<?= $m['id']; ?>">

<?= htmlspecialchars($m['manufacturer_name']); ?>

</option>

<?php } ?>

</select>

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Model</label>

<input
type="text"
name="model"
class="form-control">

</div>

</div>

</div>

<!-- ROW 5 -->

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Serial Number</label>

<input
type="text"
name="serial_number"
class="form-control">

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Criticality</label>

<select
name="criticality_level"
class="form-control">

<option value="low">Low</option>
<option value="medium" selected>Medium</option>
<option value="high">High</option>
<option value="critical">Critical</option>

</select>

</div>

</div>

</div>

<!-- ROW 6 -->

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Purchase Date</label>

<input
type="date"
name="purchase_date"
class="form-control">

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Warranty Expiry</label>

<input
type="date"
name="warranty_expiry"
class="form-control">

</div>

</div>

</div>

<!-- ROW 7 -->

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Barcode</label>

<input
type="text"
name="barcode"
class="form-control">

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>QR Code</label>

<input
type="text"
class="form-control"
value="Auto generated from Asset Number"
disabled>

</div>

</div>

</div>

<div class="form-group">

<label>Remarks</label>

<textarea
name="remarks"
rows="4"
class="form-control"></textarea>

</div>

<hr>

<button
type="submit"
class="btn btn-success">

<i class="fas fa-save"></i>
Save Equipment

</button>

<a
href="equipment.php"
class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

</section>

</div>

<?php include '../../includes/footer.php'; ?>