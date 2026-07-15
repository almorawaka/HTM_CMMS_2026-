<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$error = "";
$success = "";

/* Generate Manufacturer Code */

$result = mysqli_query(
    $conn,
    "SELECT MAX(id) AS max_id FROM manufacturers"
);

$row = mysqli_fetch_assoc($result);

$next_id = ($row['max_id'] ?? 0) + 1;

$manufacturer_code =
    "MFG" .
    str_pad($next_id, 6, "0", STR_PAD_LEFT);


/* Save */

if($_SERVER['REQUEST_METHOD']=="POST"){

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
    INSERT INTO manufacturers
    (
        manufacturer_code,
        manufacturer_name,
        country,
        website,
        contact_person,
        phone,
        email,
        service_email,
        remarks,
        status
    )
    VALUES
    (
        '$manufacturer_code',
        '$manufacturer_name',
        '$country',
        '$website',
        '$contact_person',
        '$phone',
        '$email',
        '$service_email',
        '$remarks',
        '$status'
    )
    ";

    if(mysqli_query($conn,$sql)){

        header(
            "Location: manufacturers.php"
        );
        exit;

    }else{

        $error =
            "Unable to save manufacturer.";

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

<h1>Add Manufacturer</h1>

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

<?php if($error!=""){ ?>

<div class="alert alert-danger">

<?= $error ?>

</div>

<?php } ?>

<div class="row">

<div class="col-md-3">

<div class="form-group">

<label>Manufacturer Code</label>

<input
type="text"
class="form-control"
value="<?= $manufacturer_code ?>"
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

<div class="row">

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

<div class="col-md-4">

<div class="form-group">

<label>Email</label>

<input
type="email"
name="email"
class="form-control">

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
class="form-control">

</div>

</div>

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

Save Manufacturer

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