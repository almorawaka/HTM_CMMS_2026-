<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: vendors.php");
    exit;
}

/* Get Vendor */

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM vendors
     WHERE id='$id'"
);

$vendor = mysqli_fetch_assoc($result);

if (!$vendor) {
    die("Vendor not found");
}

/* Update Vendor */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $vendor_name =
        mysqli_real_escape_string(
            $conn,
            $_POST['vendor_name']
        );

    $contact_person =
        mysqli_real_escape_string(
            $conn,
            $_POST['contact_person']
        );

    $phone =
        mysqli_real_escape_string(
            $conn,
            $_POST['phone']
        );

    $email =
        mysqli_real_escape_string(
            $conn,
            $_POST['email']
        );

    $address =
        mysqli_real_escape_string(
            $conn,
            $_POST['address']
        );

    $website =
        mysqli_real_escape_string(
            $conn,
            $_POST['website']
        );

    $vendor_type =
        mysqli_real_escape_string(
            $conn,
            $_POST['vendor_type']
        );

    $status =
        mysqli_real_escape_string(
            $conn,
            $_POST['status']
        );

    $remarks =
        mysqli_real_escape_string(
            $conn,
            $_POST['remarks']
        );

    $sql = "
    UPDATE vendors
    SET
        vendor_name='$vendor_name',
        contact_person='$contact_person',
        phone='$phone',
        email='$email',
        address='$address',
        website='$website',
        vendor_type='$vendor_type',
        status='$status',
        remarks='$remarks'
    WHERE id='$id'
    ";

    if (mysqli_query($conn, $sql)) {

        header("Location: vendors.php");
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

<h1>Edit Vendor</h1>

</div>

<div class="col-sm-6 text-right">

<a href="vendors.php"
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

Vendor Information

</h3>

</div>

<form method="POST">

<div class="card-body">

<div class="row">

<div class="col-md-3">

<div class="form-group">

<label>Vendor Code</label>

<input
type="text"
class="form-control"
value="<?= htmlspecialchars($vendor['vendor_code']); ?>"
readonly>

</div>

</div>

<div class="col-md-9">

<div class="form-group">

<label>Vendor Name</label>

<input
type="text"
name="vendor_name"
class="form-control"
value="<?= htmlspecialchars($vendor['vendor_name']); ?>"
required>

</div>

</div>

</div>

<div class="row">

<div class="col-md-4">

<div class="form-group">

<label>Vendor Type</label>

<select
name="vendor_type"
class="form-control">

<option value="supplier"
<?= ($vendor['vendor_type']=='supplier')?'selected':'';?>>
Supplier
</option>

<option value="manufacturer"
<?= ($vendor['vendor_type']=='manufacturer')?'selected':'';?>>
Manufacturer
</option>

<option value="distributor"
<?= ($vendor['vendor_type']=='distributor')?'selected':'';?>>
Distributor
</option>

<option value="service_provider"
<?= ($vendor['vendor_type']=='service_provider')?'selected':'';?>>
Service Provider
</option>

</select>

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Contact Person</label>

<input
type="text"
name="contact_person"
class="form-control"
value="<?= htmlspecialchars($vendor['contact_person']); ?>">

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Phone</label>

<input
type="text"
name="phone"
class="form-control"
value="<?= htmlspecialchars($vendor['phone']); ?>">

</div>

</div>

</div>

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($vendor['email']); ?>">

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Website</label>

<input
type="text"
name="website"
class="form-control"
value="<?= htmlspecialchars($vendor['website']); ?>">

</div>

</div>

</div>

<div class="form-group">

<label>Address</label>

<textarea
name="address"
class="form-control"
rows="3"><?= htmlspecialchars($vendor['address']); ?></textarea>

</div>

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Status</label>

<select
name="status"
class="form-control">

<option value="active"
<?= ($vendor['status']=='active')?'selected':'';?>>
Active
</option>

<option value="inactive"
<?= ($vendor['status']=='inactive')?'selected':'';?>>
Inactive
</option>

</select>

</div>

</div>

</div>

<div class="form-group">

<label>Remarks</label>

<textarea
name="remarks"
class="form-control"
rows="4"><?= htmlspecialchars($vendor['remarks']); ?></textarea>

</div>

</div>

<div class="card-footer">

<button
type="submit"
class="btn btn-primary">

<i class="fas fa-save"></i>

Update Vendor

</button>

<a
href="vendors.php"
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