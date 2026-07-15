<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: manufacturers.php");
    exit;
}

/* Get Manufacturer */

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM manufacturers
     WHERE id='$id'"
);

$manufacturer = mysqli_fetch_assoc($result);

if (!$manufacturer) {
    die("Manufacturer not found");
}

/* Update Manufacturer */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $manufacturer_name =
        mysqli_real_escape_string(
            $conn,
            $_POST['manufacturer_name']
        );

    $country =
        mysqli_real_escape_string(
            $conn,
            $_POST['country']
        );

    $website =
        mysqli_real_escape_string(
            $conn,
            $_POST['website']
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

    $service_email =
        mysqli_real_escape_string(
            $conn,
            $_POST['service_email']
        );

    $remarks =
        mysqli_real_escape_string(
            $conn,
            $_POST['remarks']
        );

    $status =
        mysqli_real_escape_string(
            $conn,
            $_POST['status']
        );

    $sql = "
    UPDATE manufacturers
    SET
        manufacturer_name='$manufacturer_name',
        country='$country',
        website='$website',
        contact_person='$contact_person',
        phone='$phone',
        email='$email',
        service_email='$service_email',
        remarks='$remarks',
        status='$status'
    WHERE id='$id'
    ";

    if (mysqli_query($conn, $sql)) {

        header("Location: manufacturers.php");
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

<h1>Edit Manufacturer</h1>

</div>

<div class="col-sm-6 text-right">

<a href="manufacturers.php"
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

Manufacturer Information

</h3>

</div>

<form method="POST">

<div class="card-body">

<div class="row">

<div class="col-md-3">

<div class="form-group">

<label>Manufacturer Code</label>

<input
type="text"
class="form-control"
value="<?= htmlspecialchars($manufacturer['manufacturer_code']); ?>"
readonly>

</div>

</div>

<div class="col-md-9">

<div class="form-group">

<label>Manufacturer Name</label>

<input
type="text"
name="manufacturer_name"
class="form-control"
value="<?= htmlspecialchars($manufacturer['manufacturer_name']); ?>"
required>

</div>

</div>

</div>

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Country</label>

<input
type="text"
name="country"
class="form-control"
value="<?= htmlspecialchars($manufacturer['country']); ?>">

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Website</label>

<input
type="text"
name="website"
class="form-control"
value="<?= htmlspecialchars($manufacturer['website']); ?>">

</div>

</div>

</div>

<div class="row">

<div class="col-md-4">

<div class="form-group">

<label>Contact Person</label>

<input
type="text"
name="contact_person"
class="form-control"
value="<?= htmlspecialchars($manufacturer['contact_person']); ?>">

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Phone</label>

<input
type="text"
name="phone"
class="form-control"
value="<?= htmlspecialchars($manufacturer['phone']); ?>">

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($manufacturer['email']); ?>">

</div>

</div>

</div>

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Service Email</label>

<input
type="email"
name="service_email"
class="form-control"
value="<?= htmlspecialchars($manufacturer['service_email']); ?>">

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Status</label>

<select
name="status"
class="form-control">

<option value="active"
<?= ($manufacturer['status']=='active')?'selected':''; ?>>

Active

</option>

<option value="inactive"
<?= ($manufacturer['status']=='inactive')?'selected':''; ?>>

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
rows="4"><?= htmlspecialchars($manufacturer['remarks']); ?></textarea>

</div>

</div>

<div class="card-footer">

<button
type="submit"
class="btn btn-primary">

<i class="fas fa-save"></i>

Update Manufacturer

</button>

<a
href="manufacturers.php"
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