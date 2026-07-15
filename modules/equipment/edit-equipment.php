<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: equipment.php");
    exit;
}

/* Load Equipment */

$sql = "
SELECT
    e.*,
    m.manufacturer_name
FROM equipment e
LEFT JOIN manufacturers m
    ON e.manufacturer_id = m.id
WHERE e.id = $id
";

$result = mysqli_query($conn, $sql);
$equipment = mysqli_fetch_assoc($result);

if (!$equipment) {
    die("Equipment not found.");
}

/* Update Equipment */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $asset_number      = mysqli_real_escape_string($conn, $_POST['asset_number']);
    $equipment_name    = mysqli_real_escape_string($conn, $_POST['equipment_name']);

    $category_id       = intval($_POST['category_id']);
    $institute_id      = intval($_POST['institute_id']);
    $department_id     = intval($_POST['department_id']);

    $manufacturer_id   =
        !empty($_POST['manufacturer_id'])
        ? intval($_POST['manufacturer_id'])
        : "NULL";

    $model             = mysqli_real_escape_string($conn, $_POST['model']);
    $serial_number     = mysqli_real_escape_string($conn, $_POST['serial_number']);

    $purchase_date =
        !empty($_POST['purchase_date'])
        ? $_POST['purchase_date']
        : NULL;

    $warranty_expiry =
        !empty($_POST['warranty_expiry'])
        ? $_POST['warranty_expiry']
        : NULL;

    $status_id         = intval($_POST['status_id']);

    $criticality_level =
        mysqli_real_escape_string(
            $conn,
            $_POST['criticality_level']
        );

    $barcode =
        mysqli_real_escape_string(
            $conn,
            $_POST['barcode']
        );

    $remarks =
        mysqli_real_escape_string(
            $conn,
            $_POST['remarks']
        );

    $update = "
    UPDATE equipment
    SET
        asset_number      = '$asset_number',
        equipment_name    = '$equipment_name',
        category_id       = $category_id,
        institute_id      = $institute_id,
        department_id     = $department_id,
        manufacturer_id   = $manufacturer_id,
        model             = '$model',
        serial_number     = '$serial_number',
        purchase_date     = ".($purchase_date ? "'$purchase_date'" : "NULL").",
        warranty_expiry   = ".($warranty_expiry ? "'$warranty_expiry'" : "NULL").",
        status_id         = $status_id,
        criticality_level = '$criticality_level',
        barcode           = '$barcode',
        remarks           = '$remarks'
    WHERE id = $id
    ";

    mysqli_query($conn, $update);

    header("Location: equipment.php");
    exit;
}

/* Dropdown Data */

$categories = mysqli_query(
    $conn,
    "SELECT * FROM equipment_categories
     ORDER BY category_name"
);

$institutes = mysqli_query(
    $conn,
    "SELECT * FROM institutes
     ORDER BY institute_name"
);

$departments = mysqli_query(
    $conn,
    "SELECT * FROM departments
     ORDER BY department_name"
);

$statuses = mysqli_query(
    $conn,
    "SELECT * FROM equipment_status
     ORDER BY status_name"
);

$manufacturers = mysqli_query(
    $conn,
    "SELECT id, manufacturer_name
     FROM manufacturers
     WHERE status='active'
     ORDER BY manufacturer_name"
);

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>

<div class="content-wrapper">

<section class="content-header">

<div class="container-fluid">

<div class="row mb-2">

<div class="col-sm-6">
<h1>Edit Equipment</h1>
</div>

<div class="col-sm-6 text-right">

<a href="equipment.php"
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
Equipment Information
</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-6">

<div class="form-group">
<label>Asset Number</label>
<input
type="text"
name="asset_number"
class="form-control"
value="<?= htmlspecialchars($equipment['asset_number']); ?>"
required>
</div>

<div class="form-group">
<label>Equipment Name</label>
<input
type="text"
name="equipment_name"
class="form-control"
value="<?= htmlspecialchars($equipment['equipment_name']); ?>"
required>
</div>

<div class="form-group">
<label>Category</label>

<select
name="category_id"
class="form-control">

<?php while($cat=mysqli_fetch_assoc($categories)){ ?>

<option
value="<?= $cat['id']; ?>"
<?= ($cat['id']==$equipment['category_id']) ? 'selected' : ''; ?>>

<?= htmlspecialchars($cat['category_name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="form-group">
<label>Institute</label>

<select
name="institute_id"
class="form-control">

<?php while($ins=mysqli_fetch_assoc($institutes)){ ?>

<option
value="<?= $ins['id']; ?>"
<?= ($ins['id']==$equipment['institute_id']) ? 'selected' : ''; ?>>

<?= htmlspecialchars($ins['institute_name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="form-group">
<label>Department</label>

<select
name="department_id"
class="form-control">

<?php while($dep=mysqli_fetch_assoc($departments)){ ?>

<option
value="<?= $dep['id']; ?>"
<?= ($dep['id']==$equipment['department_id']) ? 'selected' : ''; ?>>

<?= htmlspecialchars($dep['department_name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="form-group">
<label>Manufacturer</label>

<select
name="manufacturer_id"
class="form-control">

<option value="">
Select Manufacturer
</option>

<?php while($m=mysqli_fetch_assoc($manufacturers)){ ?>

<option
value="<?= $m['id']; ?>"
<?= ($m['id']==$equipment['manufacturer_id']) ? 'selected' : ''; ?>>

<?= htmlspecialchars($m['manufacturer_name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="form-group">
<label>Model</label>
<input
type="text"
name="model"
class="form-control"
value="<?= htmlspecialchars($equipment['model']); ?>">
</div>

</div>

<div class="col-md-6">

<div class="form-group">
<label>Serial Number</label>
<input
type="text"
name="serial_number"
class="form-control"
value="<?= htmlspecialchars($equipment['serial_number']); ?>">
</div>

<div class="form-group">
<label>Purchase Date</label>
<input
type="date"
name="purchase_date"
class="form-control"
value="<?= $equipment['purchase_date']; ?>">
</div>

<div class="form-group">
<label>Warranty Expiry</label>
<input
type="date"
name="warranty_expiry"
class="form-control"
value="<?= $equipment['warranty_expiry']; ?>">
</div>

<div class="form-group">
<label>Status</label>

<select
name="status_id"
class="form-control">

<?php while($st=mysqli_fetch_assoc($statuses)){ ?>

<option
value="<?= $st['id']; ?>"
<?= ($st['id']==$equipment['status_id']) ? 'selected' : ''; ?>>

<?= htmlspecialchars($st['status_name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="form-group">
<label>Criticality</label>

<select
name="criticality_level"
class="form-control">

<option value="low"
<?= ($equipment['criticality_level']=='low')?'selected':''; ?>>
Low
</option>

<option value="medium"
<?= ($equipment['criticality_level']=='medium')?'selected':''; ?>>
Medium
</option>

<option value="high"
<?= ($equipment['criticality_level']=='high')?'selected':''; ?>>
High
</option>

<option value="critical"
<?= ($equipment['criticality_level']=='critical')?'selected':''; ?>>
Critical
</option>

</select>

</div>

<div class="form-group">
<label>Barcode</label>
<input
type="text"
name="barcode"
class="form-control"
value="<?= htmlspecialchars($equipment['barcode']); ?>">
</div>

<div class="form-group">
<label>Remarks</label>

<textarea
name="remarks"
rows="4"
class="form-control"><?= htmlspecialchars($equipment['remarks']); ?></textarea>

</div>

</div>

</div>

<hr>

<button
type="submit"
class="btn btn-primary">

<i class="fas fa-save"></i>
Update Equipment

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