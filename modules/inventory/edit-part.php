<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if(!$id){
    header("Location: inventory.php");
    exit;
}

/* Get Spare Part */

$sql = "
SELECT *
FROM spare_parts
WHERE id='$id'
";

$result = mysqli_query($conn,$sql);
$part = mysqli_fetch_assoc($result);

if(!$part){
    die("Spare Part Not Found");
}

/* Master Tables */

$manufacturers = mysqli_query(
    $conn,
    "SELECT id,manufacturer_name
     FROM manufacturers
     WHERE status='active'
     ORDER BY manufacturer_name"
);

$vendors = mysqli_query(
    $conn,
    "SELECT id,vendor_name
     FROM vendors
     WHERE status='active'
     ORDER BY vendor_name"
);

$categories = mysqli_query(
    $conn,
    "SELECT id,category_name
     FROM equipment_categories
     ORDER BY category_name"
);

/* Update */

if($_SERVER['REQUEST_METHOD']=="POST"){

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

    $part_type       = mysqli_real_escape_string($conn,$_POST['part_type']);

    $reorder_level   = intval($_POST['reorder_level']);

    $unit_price      = floatval($_POST['unit_price']);

    $location        = mysqli_real_escape_string($conn,$_POST['location']);

    $update = "
    UPDATE spare_parts
    SET
        part_code='$part_code',
        barcode='$barcode',
        qr_code='$qr_code',
        barcode_type='$barcode_type',
        part_name='$part_name',
        manufacturer_id='$manufacturer_id',
        category_id='$category_id',
        vendor_id=$vendor_id,
        part_type='$part_type',
        reorder_level='$reorder_level',
        unit_price='$unit_price',
        location='$location'
    WHERE id='$id'
    ";

    mysqli_query($conn,$update);

    header("Location:view-part.php?id=".$id);
    exit;
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
<h1>Edit Spare Part</h1>
</div>

<div class="col-sm-6 text-right">

<a href="view-part.php?id=<?= $id; ?>"
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

        <!-- Row 1 -->

        <div class="row">

            <div class="col-md-6">

                <div class="form-group">
                    <label>Part Code</label>
                    <input
                        type="text"
                        name="part_code"
                        class="form-control"
                        value="<?= htmlspecialchars($part['part_code']); ?>"
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
                        value="<?= htmlspecialchars($part['part_name']); ?>"
                        required>
                </div>

            </div>

        </div>

        <!-- Row 2 -->

        <div class="row">

            <div class="col-md-4">

                <div class="form-group">
                    <label>Barcode</label>
                    <input
                        type="text"
                        name="barcode"
                        class="form-control"
                        value="<?= htmlspecialchars($part['barcode'] ?? ''); ?>">
                </div>

            </div>

            <div class="col-md-4">

                <div class="form-group">
                    <label>QR Code</label>
                    <input
                        type="text"
                        name="qr_code"
                        class="form-control"
                        value="<?= htmlspecialchars($part['qr_code'] ?? ''); ?>">
                </div>

            </div>

            <div class="col-md-4">

                <div class="form-group">
                    <label>Barcode Type</label>

                    <select
                        name="barcode_type"
                        class="form-control">

                        <?php
                        $barcode_types=[
                            'CODE128',
                            'CODE39',
                            'EAN13',
                            'QR'
                        ];

                        foreach($barcode_types as $b){
                        ?>

                        <option
                            value="<?= $b; ?>"
                            <?= (($part['barcode_type'] ?? 'CODE128')==$b)
                                ? 'selected'
                                : ''; ?>>

                            <?= $b; ?>

                        </option>

                        <?php } ?>

                    </select>

                </div>

            </div>

        </div>

        <!-- Row 3 -->

        <div class="row">

            <div class="col-md-4">

                <div class="form-group">
                    <label>Manufacturer</label>

                    <select
                        name="manufacturer_id"
                        class="form-control"
                        required>

                        <?php
                        while($m=mysqli_fetch_assoc($manufacturers)){
                        ?>

                        <option
                            value="<?= $m['id']; ?>"
                            <?= ($part['manufacturer_id']==$m['id'])
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars($m['manufacturer_name']); ?>

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

                        <?php
                        while($c=mysqli_fetch_assoc($categories)){
                        ?>

                        <option
                            value="<?= $c['id']; ?>"
                            <?= ($part['category_id']==$c['id'])
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars($c['category_name']); ?>

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
                            value="<?= $v['id']; ?>"
                            <?= ($part['vendor_id']==$v['id'])
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars($v['vendor_name']); ?>

                        </option>

                        <?php } ?>

                    </select>

                </div>

            </div>

        </div>

        <!-- Row 4 -->

        <div class="row">

            <div class="col-md-3">

                <div class="form-group">

                    <label>Part Type</label>

                    <select
                        name="part_type"
                        class="form-control">

                        <?php

                        $types=[
                            'Consumable',
                            'Spare Part',
                            'Accessory',
                            'Battery',
                            'Sensor',
                            'PCB',
                            'Mechanical',
                            'Cable'
                        ];

                        foreach($types as $type){

                        ?>

                        <option
                            value="<?= $type; ?>"
                            <?= ($part['part_type']==$type)
                                ? 'selected'
                                : ''; ?>>

                            <?= $type; ?>

                        </option>

                        <?php } ?>

                    </select>

                </div>

            </div>

            <div class="col-md-3">

                <div class="form-group">

                    <label>Current Quantity</label>

                    <input
                        type="number"
                        class="form-control"
                        value="<?= $part['quantity']; ?>"
                        readonly>

                    <small class="text-muted">
                        Quantity updated using Stock In / Stock Out
                    </small>

                </div>

            </div>

            <div class="col-md-3">

                <div class="form-group">

                    <label>Reorder Level</label>

                    <input
                        type="number"
                        name="reorder_level"
                        class="form-control"
                        value="<?= $part['reorder_level']; ?>">

                </div>

            </div>

            <div class="col-md-3">

                <div class="form-group">

                    <label>Unit Price</label>

                    <input
                        type="number"
                        step="0.01"
                        name="unit_price"
                        class="form-control"
                        value="<?= $part['unit_price']; ?>">

                </div>

            </div>

        </div>

        <!-- Row 5 -->

        <div class="row">

            <div class="col-md-12">

                <div class="form-group">

                    <label>Store Location</label>

                    <input
                        type="text"
                        name="location"
                        class="form-control"
                        value="<?= htmlspecialchars($part['location']); ?>">

                </div>

            </div>

        </div>

    </div>

<button
type="submit"
class="btn btn-primary">

<i class="fas fa-save"></i>

Update Spare Part

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