<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $institute_name = trim($_POST['institute_name']);
    $institute_code = trim($_POST['institute_code']);
    $address = trim($_POST['address']);
    $telephone = trim($_POST['telephone']);

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO institutes
        (institute_name, institute_code, address, telephone)
        VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $institute_name,
        $institute_code,
        $address,
        $telephone
    );

    mysqli_stmt_execute($stmt);

    header("Location: institutes.php");
    exit;
}

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>

<div class="content-wrapper">

<section class="content-header">
<div class="container-fluid">
<h1>Add Institute</h1>
</div>
</section>

<section class="content">

<div class="container-fluid">

<div class="card">

<div class="card-body">

<form method="POST">

<div class="form-group">
<label>Institute Name</label>
<input type="text"
       name="institute_name"
       class="form-control"
       required>
</div>

<div class="form-group">
<label>Institute Code</label>
<input type="text"
       name="institute_code"
       class="form-control">
</div>

<div class="form-group">
<label>Address</label>
<input type="text"
       name="address"
       class="form-control">
</div>

<div class="form-group">
<label>Telephone</label>
<input type="text"
       name="telephone"
       class="form-control">
</div>

<button type="submit"
        class="btn btn-success">

<i class="fas fa-save"></i>
Save Institute

</button>

<a href="institutes.php"
   class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>

</section>

</div>

<?php include '../../includes/footer.php'; ?>