<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

/* Save PM Schedule */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $equipment_id = intval($_POST['equipment_id']);
    $frequency = mysqli_real_escape_string($conn,$_POST['frequency']);
    $assigned_to = intval($_POST['assigned_to']);
    $last_pm_date = $_POST['last_pm_date'] ?: NULL;
    $next_pm_date = $_POST['next_pm_date'];
    $estimated_duration = intval($_POST['estimated_duration']);
    $priority = mysqli_real_escape_string($conn,$_POST['priority']);
    $remarks = mysqli_real_escape_string($conn,$_POST['remarks']);

    $created_by = $_SESSION['user_id'];

    $sql = "
    INSERT INTO pm_schedule
    (
        equipment_id,
        frequency,
        assigned_to,
        last_pm_date,
        next_pm_date,
        estimated_duration,
        priority,
        remarks,
        created_by
    )
    VALUES
    (
        '$equipment_id',
        '$frequency',
        '$assigned_to',
        ".($last_pm_date ? "'$last_pm_date'" : "NULL").",
        '$next_pm_date',
        '$estimated_duration',
        '$priority',
        '$remarks',
        '$created_by'
    )
    ";

    mysqli_query($conn,$sql);

    header("Location: pm-schedule.php");
    exit;
}

/* Equipment List */

$equipment = mysqli_query($conn,"
SELECT id,asset_number,equipment_name
FROM equipment
ORDER BY equipment_name
");

/* Users */

$users = mysqli_query($conn,"
SELECT id,full_name
FROM users
WHERE status='active'
ORDER BY full_name
");

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>

<div class="content-wrapper">

<section class="content-header">

<div class="container-fluid">

<div class="row">

<div class="col-sm-6">
<h1>Create PM Schedule</h1>
</div>

<div class="col-sm-6 text-right">

<a href="pm-schedule.php"
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
Preventive Maintenance Schedule
</h3>
</div>

<form method="post">

<div class="card-body">

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Equipment *</label>

<select name="equipment_id"
class="form-control"
required>

<option value="">
Select Equipment
</option>

<?php while($eq=mysqli_fetch_assoc($equipment)): ?>

<option value="<?= $eq['id']; ?>">

<?= htmlspecialchars($eq['asset_number']); ?>
 -
<?= htmlspecialchars($eq['equipment_name']); ?>

</option>

<?php endwhile; ?>

</select>

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Frequency *</label>

<select name="frequency"
class="form-control"
required>

<option value="weekly">Weekly</option>
<option value="monthly">Monthly</option>
<option value="quarterly">Quarterly</option>
<option value="semi_annual">Semi Annual</option>
<option value="annual">Annual</option>

</select>

</div>

</div>

</div>

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Assigned Engineer</label>

<select name="assigned_to"
class="form-control">

<option value="">
Select User
</option>

<?php while($user=mysqli_fetch_assoc($users)): ?>

<option value="<?= $user['id']; ?>">

<?= htmlspecialchars($user['full_name']); ?>

</option>

<?php endwhile; ?>

</select>

</div>

</div>

<div class="col-md-3">

<div class="form-group">

<label>Last PM Date</label>

<input type="date"
name="last_pm_date"
class="form-control">

</div>

</div>

<div class="col-md-3">

<div class="form-group">

<label>Next PM Date *</label>

<input type="date"
name="next_pm_date"
class="form-control"
required>

</div>

</div>

</div>

<div class="row">

<div class="col-md-4">

<div class="form-group">

<label>Estimated Duration (Minutes)</label>

<input type="number"
name="estimated_duration"
class="form-control"
value="60">

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Priority</label>

<select name="priority"
class="form-control">

<option value="low">Low</option>
<option value="medium" selected>Medium</option>
<option value="high">High</option>
<option value="critical">Critical</option>

</select>

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Status</label>

<input type="text"
class="form-control"
value="Scheduled"
readonly>

</div>

</div>

</div>

<div class="form-group">

<label>Remarks</label>

<textarea name="remarks"
rows="4"
class="form-control"></textarea>

</div>

</div>

<div class="card-footer">

<button type="submit"
class="btn btn-success">

<i class="fas fa-save"></i>

Save PM Schedule

</button>

<a href="pm-schedule.php"
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