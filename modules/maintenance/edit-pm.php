<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: pm-schedule.php");
    exit;
}

/* Load PM Record */

$sql = "
SELECT *
FROM pm_schedule
WHERE id = '$id'
";

$result = mysqli_query($conn,$sql);
$pm = mysqli_fetch_assoc($result);

if(!$pm){
    die('PM Schedule Not Found');
}

/* Update Record */

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $equipment_id = intval($_POST['equipment_id']);
    $frequency = mysqli_real_escape_string($conn,$_POST['frequency']);
    $assigned_to = intval($_POST['assigned_to']);
    $last_pm_date = $_POST['last_pm_date'];
    $next_pm_date = $_POST['next_pm_date'];
    $estimated_duration = intval($_POST['estimated_duration']);
    $priority = mysqli_real_escape_string($conn,$_POST['priority']);
    $status = mysqli_real_escape_string($conn,$_POST['status']);
    $remarks = mysqli_real_escape_string($conn,$_POST['remarks']);

    $update = "
    UPDATE pm_schedule
    SET
        equipment_id='$equipment_id',
        frequency='$frequency',
        assigned_to='$assigned_to',
        last_pm_date='$last_pm_date',
        next_pm_date='$next_pm_date',
        estimated_duration='$estimated_duration',
        priority='$priority',
        status='$status',
        remarks='$remarks'
    WHERE id='$id'
    ";

    mysqli_query($conn,$update);

    header("Location: view-pm.php?id=".$id);
    exit;
}

/* Equipment */

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
<h1>Edit PM Schedule</h1>
</div>

<div class="col-sm-6 text-right">

<a href="view-pm.php?id=<?= $id ?>"
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
Edit Preventive Maintenance Schedule
</h3>
</div>

<form method="post">

<div class="card-body">

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Equipment</label>

<select name="equipment_id"
class="form-control"
required>

<?php while($eq=mysqli_fetch_assoc($equipment)): ?>

<option value="<?= $eq['id']; ?>"
<?= ($pm['equipment_id']==$eq['id']) ? 'selected' : ''; ?>>

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

<label>Frequency</label>

<select name="frequency"
class="form-control">

<option value="weekly"
<?= ($pm['frequency']=='weekly')?'selected':''; ?>>
Weekly
</option>

<option value="monthly"
<?= ($pm['frequency']=='monthly')?'selected':''; ?>>
Monthly
</option>

<option value="quarterly"
<?= ($pm['frequency']=='quarterly')?'selected':''; ?>>
Quarterly
</option>

<option value="semi_annual"
<?= ($pm['frequency']=='semi_annual')?'selected':''; ?>>
Semi Annual
</option>

<option value="annual"
<?= ($pm['frequency']=='annual')?'selected':''; ?>>
Annual
</option>

</select>

</div>

</div>

</div>

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Assigned To</label>

<select name="assigned_to"
class="form-control">

<option value="">Select User</option>

<?php while($user=mysqli_fetch_assoc($users)): ?>

<option value="<?= $user['id']; ?>"
<?= ($pm['assigned_to']==$user['id']) ? 'selected' : ''; ?>>

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
class="form-control"
value="<?= $pm['last_pm_date']; ?>">

</div>

</div>

<div class="col-md-3">

<div class="form-group">

<label>Next PM Date</label>

<input type="date"
name="next_pm_date"
class="form-control"
value="<?= $pm['next_pm_date']; ?>"
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
value="<?= $pm['estimated_duration']; ?>">

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Priority</label>

<select name="priority"
class="form-control">

<option value="low"
<?= ($pm['priority']=='low')?'selected':''; ?>>
Low
</option>

<option value="medium"
<?= ($pm['priority']=='medium')?'selected':''; ?>>
Medium
</option>

<option value="high"
<?= ($pm['priority']=='high')?'selected':''; ?>>
High
</option>

<option value="critical"
<?= ($pm['priority']=='critical')?'selected':''; ?>>
Critical
</option>

</select>

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Status</label>

<select name="status"
class="form-control">

<option value="scheduled"
<?= ($pm['status']=='scheduled')?'selected':''; ?>>
Scheduled
</option>

<option value="in_progress"
<?= ($pm['status']=='in_progress')?'selected':''; ?>>
In Progress
</option>

<option value="completed"
<?= ($pm['status']=='completed')?'selected':''; ?>>
Completed
</option>

<option value="overdue"
<?= ($pm['status']=='overdue')?'selected':''; ?>>
Overdue
</option>

</select>

</div>

</div>

</div>

<div class="form-group">

<label>Remarks</label>

<textarea name="remarks"
rows="5"
class="form-control"><?= htmlspecialchars($pm['remarks']); ?></textarea>

</div>

</div>

<div class="card-footer">

<button type="submit"
class="btn btn-primary">

<i class="fas fa-save"></i>
Update PM Schedule

</button>

<a href="view-pm.php?id=<?= $id ?>"
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