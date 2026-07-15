<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$request_id = intval($_GET['id'] ?? 0);

if(!$request_id){
    header("Location: maintenance.php");
    exit;
}

if($_SERVER['REQUEST_METHOD']=='POST'){

    $assigned_to = intval($_POST['assigned_to']);

    mysqli_query($conn,"
    INSERT INTO work_orders
    (
        repair_request_id,
        assigned_to,
        status
    )
    VALUES
    (
        '$request_id',
        '$assigned_to',
        'pending'
    )
    ");

    mysqli_query($conn,"
    UPDATE repair_requests
    SET status='assigned'
    WHERE id='$request_id'
    ");

    header("Location: maintenance.php");
    exit;
}

$request = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT rr.*,e.equipment_name,e.asset_number
FROM repair_requests rr
LEFT JOIN equipment e
ON rr.equipment_id=e.id
WHERE rr.id='$request_id'
")
);

$staff = mysqli_query($conn,"
SELECT id,full_name,designation
FROM users
WHERE role_id IN (3,4,5)
ORDER BY full_name
");

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>

<div class="content-wrapper">

<section class="content-header">
<div class="container-fluid">
<h1>Assign Work Order</h1>
</div>
</section>

<section class="content">

<div class="container-fluid">

<div class="card">

<div class="card-body">

<form method="post">

<div class="form-group">

<label>Equipment</label>

<input type="text"
class="form-control"
value="<?= $request['equipment_name']; ?>"
readonly>

</div>

<div class="form-group">

<label>Asset Number</label>

<input type="text"
class="form-control"
value="<?= $request['asset_number']; ?>"
readonly>

</div>

<div class="form-group">

<label>Assign To</label>

<select name="assigned_to"
class="form-control"
required>

<option value="">
Select Staff
</option>

<?php while($u=mysqli_fetch_assoc($staff)): ?>

<option value="<?= $u['id']; ?>">

<?= $u['full_name']; ?>

(<?= $u['designation']; ?>)

</option>

<?php endwhile; ?>

</select>

</div>

<div class="form-group">

<label>Problem Description</label>

<textarea
class="form-control"
rows="5"
readonly><?= $request['problem_description']; ?></textarea>

</div>

<button type="submit"
class="btn btn-success">

<i class="fas fa-save"></i>

Assign Work Order

</button>

<a href="maintenance.php"
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