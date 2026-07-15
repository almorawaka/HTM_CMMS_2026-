<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

/* Generate Vendor Code */

$result = mysqli_query(
    $conn,
    "SELECT MAX(id) AS max_id
     FROM vendors"
);

$row = mysqli_fetch_assoc($result);

$next_id = ($row['max_id'] ?? 0) + 1;

$vendor_code =
    "VEN" .
    str_pad($next_id, 6, "0", STR_PAD_LEFT);


/* Save Vendor */

if($_SERVER['REQUEST_METHOD']=='POST'){

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
    INSERT INTO vendors
    (
        vendor_code,
        vendor_name,
        contact_person,
        phone,
        email,
        address,
        website,
        vendor_type,
        status,
        remarks
    )
    VALUES
    (
        '$vendor_code',
        '$vendor_name',
        '$contact_person',
        '$phone',
        '$email',
        '$address',
        '$website',
        '$vendor_type',
        '$status',
        '$remarks'
    )
    ";

    if(mysqli_query($conn,$sql)){

        header(
            "Location: vendors.php"
        );

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

<h1>Add Vendor</h1>

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
value="<?= $vendor_code ?>"
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

<option value="supplier">
Supplier
</option>

<option value="manufacturer">
Manufacturer
</option>

<option value="distributor">
Distributor
</option>

<option value="service_provider">
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
class="form-control">

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Phone</label>

<input
type="text"
name="phone"
class="form-control">

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
class="form-control">

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Website</label>

<input
type="text"
name="website"
class="form-control">

</div>

</div>

</div>

<div class="form-group">

<label>Address</label>

<textarea
name="address"
class="form-control"
rows="3"></textarea>

</div>

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Status</label>

<select
name="status"
class="form-control">

<option value="active">

Active

</option>

<option value="inactive">

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
rows="4"></textarea>

</div>

</div>

<div class="card-footer">

<button
type="submit"
class="btn btn-success">

<i class="fas fa-save"></i>

Save Vendor

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