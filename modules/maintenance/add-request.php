<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $equipment_id = intval($_POST['equipment_id']);
    $requested_by = $_SESSION['user_id'];
    $problem_description = mysqli_real_escape_string(
        $conn,
        $_POST['problem_description']
    );
    $priority = $_POST['priority'];

    $sql = "
    INSERT INTO repair_requests
    (
        equipment_id,
        requested_by,
        problem_description,
        priority,
        status
    )
    VALUES
    (
        '$equipment_id',
        '$requested_by',
        '$problem_description',
        '$priority',
        'open'
    )
    ";

    mysqli_query($conn, $sql);

    header("Location: maintenance.php");
    exit;
}

$equipment = mysqli_query(
    $conn,
    "SELECT id,equipment_name,asset_number
     FROM equipment
     ORDER BY equipment_name"
);

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>

<div class="content-wrapper">

<section class="content-header">

<div class="container-fluid">

<h1>Create Repair Request</h1>

</div>

</section>

<section class="content">

<div class="container-fluid">

<div class="card">

<div class="card-body">

<form method="post">

<div class="form-group">

<label>Equipment</label>

<select name="equipment_id"
        class="form-control"
        required>

<option value="">Select Equipment</option>

<?php while($row=mysqli_fetch_assoc($equipment)): ?>

<option value="<?= $row['id']; ?>">

<?= $row['asset_number']; ?>

-

<?= $row['equipment_name']; ?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="form-group">

<label>Problem Description</label>

<textarea
name="problem_description"
class="form-control"
rows="5"
required></textarea>

</div>

<div class="form-group">

<label>Priority</label>

<select
name="priority"
class="form-control">

<option value="low">Low</option>
<option value="medium" selected>Medium</option>
<option value="high">High</option>
<option value="critical">Critical</option>

</select>

</div>

<button
type="submit"
class="btn btn-success">

<i class="fas fa-save"></i>

Save Request

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