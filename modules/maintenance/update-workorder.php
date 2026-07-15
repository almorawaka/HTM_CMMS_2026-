<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: workorders.php");
    exit;
}

/* Save Update */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $assigned_to      = intval($_POST['assigned_to']);
    $diagnosis        = mysqli_real_escape_string($conn, $_POST['diagnosis']);
    $corrective_action = mysqli_real_escape_string($conn, $_POST['corrective_action']);
    $status           = mysqli_real_escape_string($conn, $_POST['status']);

    if ($status == 'in_progress') {

        mysqli_query($conn,"
        UPDATE work_orders
        SET
            assigned_to='$assigned_to',
            diagnosis='$diagnosis',
            corrective_action='$corrective_action',
            status='$status',
            start_time = IFNULL(start_time,NOW())
        WHERE id='$id'
        ");

    } else {

        mysqli_query($conn,"
        UPDATE work_orders
        SET
            assigned_to='$assigned_to',
            diagnosis='$diagnosis',
            corrective_action='$corrective_action',
            status='$status'
        WHERE id='$id'
        ");
    }

    header("Location: view-workorder.php?id=".$id);
    exit;
}

/* Load Work Order */

$sql = "
SELECT *
FROM work_orders
WHERE id = $id
";

$result = mysqli_query($conn,$sql);
$workorder = mysqli_fetch_assoc($result);

if (!$workorder) {
    die('Work Order Not Found');
}

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
<h1>Update Work Order</h1>
</div>

<div class="col-sm-6 text-right">

<a href="view-workorder.php?id=<?= $id ?>"
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

Work Order #<?= $workorder['id']; ?>

</h3>

</div>

<form method="post">

<div class="card-body">

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Assigned To</label>

<select name="assigned_to"
class="form-control">

<option value="">Select User</option>

<?php while($u=mysqli_fetch_assoc($users)): ?>

<option value="<?= $u['id']; ?>"
<?= ($workorder['assigned_to']==$u['id'])?'selected':''; ?>>

<?= htmlspecialchars($u['full_name']); ?>

</option>

<?php endwhile; ?>

</select>

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Status</label>

<select name="status"
class="form-control">

<option value="pending"
<?= $workorder['status']=='pending'?'selected':''; ?>>
Pending
</option>

<option value="in_progress"
<?= $workorder['status']=='in_progress'?'selected':''; ?>>
In Progress
</option>

<option value="completed"
<?= $workorder['status']=='completed'?'selected':''; ?>>
Completed
</option>

</select>

</div>

</div>

</div>

<div class="form-group">

<label>Diagnosis</label>

<textarea name="diagnosis"
rows="5"
class="form-control"><?= htmlspecialchars($workorder['corrective_action']); ?></textarea>

</div>

<div class="form-group">

<label>Corrective Action</label>

<textarea name="corrective_action"
rows="6"
class="form-control"><?= htmlspecialchars($workorder['corrective_action']); ?></textarea>

</div>

</div>

<div class="card-footer">

<button type="submit"
class="btn btn-primary">

<i class="fas fa-save"></i>

Save Update

</button>

<a href="view-workorder.php?id=<?= $id ?>"
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