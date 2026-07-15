<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if(!$id){
    header("Location: monitored-equipment.php");
    exit;
}

/* Get monitored equipment */

$sql = "
SELECT
    lm.*,
    e.equipment_name,
    e.model,
    e.asset_number
FROM live_monitoring_equipment lm
LEFT JOIN equipment e
    ON lm.equipment_id = e.id
WHERE lm.id='$id'
";

$result = mysqli_query($conn,$sql);
$data = mysqli_fetch_assoc($result);

if(!$data){
    die("Monitored Equipment Not Found");
}

/* Update */

if($_SERVER['REQUEST_METHOD']=='POST'){

    $iot_device_id =
        mysqli_real_escape_string(
            $conn,
            $_POST['iot_device_id']
        );

    $mqtt_topic =
        mysqli_real_escape_string(
            $conn,
            $_POST['mqtt_topic']
        );

    $ip_address =
        mysqli_real_escape_string(
            $conn,
            $_POST['ip_address']
        );

    $criticality =
        mysqli_real_escape_string(
            $conn,
            $_POST['criticality']
        );

    $monitoring_enabled =
        intval($_POST['monitoring_enabled']);

    $live_status =
        mysqli_real_escape_string(
            $conn,
            $_POST['live_status']
        );

    $remarks =
        mysqli_real_escape_string(
            $conn,
            $_POST['remarks']
        );

    $sql = "
    UPDATE live_monitoring_equipment
    SET

        iot_device_id='$iot_device_id',
        mqtt_topic='$mqtt_topic',
        ip_address='$ip_address',
        monitoring_enabled='$monitoring_enabled',
        criticality='$criticality',
        live_status='$live_status',
        remarks='$remarks'

    WHERE id='$id'
    ";

    mysqli_query($conn,$sql);

    header(
        "Location: monitored-equipment.php"
    );

    exit;
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
<h1>Edit Monitored Equipment</h1>
</div>

<div class="col-sm-6 text-right">

<a href="monitored-equipment.php"
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

IoT Monitoring Configuration

</h3>

</div>

<form method="POST">

<div class="card-body">

<!-- Equipment -->

<div class="row">

<div class="col-md-12">

<div class="form-group">

<label>Equipment</label>

<input
type="text"
class="form-control"
readonly

value="<?=
htmlspecialchars(
    $data['equipment_name']
);
?>
 -
<?=
htmlspecialchars(
    $data['model']
);
?>
 -
<?=
htmlspecialchars(
    $data['asset_number']
);
?>">

</div>

</div>

</div>

<!-- IoT -->

<div class="row">

<div class="col-md-4">

<div class="form-group">

<label>IoT Device ID</label>

<input
type="text"
name="iot_device_id"
class="form-control"

value="<?=
htmlspecialchars(
    $data['iot_device_id']
);
?>">

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>MQTT Topic</label>

<input
type="text"
name="mqtt_topic"
class="form-control"

value="<?=
htmlspecialchars(
    $data['mqtt_topic']
);
?>">

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>IP Address</label>

<input
type="text"
name="ip_address"
class="form-control"

value="<?=
htmlspecialchars(
    $data['ip_address']
);
?>">

</div>

</div>

</div>

<!-- Configuration -->

<div class="row">

<div class="col-md-4">

<div class="form-group">

<label>Criticality</label>

<select
name="criticality"
class="form-control">

<?php

$levels=[
'HIGH',
'MEDIUM',
'LOW'
];

foreach($levels as $level){

?>

<option
value="<?= $level; ?>"
<?= ($data['criticality']==$level)
    ? 'selected'
    : ''; ?>>

<?= $level; ?>

</option>

<?php } ?>

</select>

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Monitoring</label>

<select
name="monitoring_enabled"
class="form-control">

<option
value="1"
<?= ($data['monitoring_enabled']==1)
    ? 'selected'
    : ''; ?>>

Enabled

</option>

<option
value="0"
<?= ($data['monitoring_enabled']==0)
    ? 'selected'
    : ''; ?>>

Disabled

</option>

</select>

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Live Status</label>

<select
name="live_status"
class="form-control">

<?php

$status_list=[
'ONLINE',
'OFFLINE',
'ALARM',
'STANDBY',
'MAINTENANCE'
];

foreach($status_list as $status){

?>

<option
value="<?= $status; ?>"
<?= ($data['live_status']==$status)
    ? 'selected'
    : ''; ?>>

<?= $status; ?>

</option>

<?php } ?>

</select>

</div>

</div>

</div>

<!-- Remarks -->

<div class="row">

<div class="col-md-12">

<div class="form-group">

<label>Remarks</label>

<textarea
name="remarks"
rows="4"
class="form-control"><?=
htmlspecialchars(
    $data['remarks']
);
?></textarea>

</div>

</div>

</div>

</div>

<div class="card-footer">

<button
type="submit"
class="btn btn-primary">

<i class="fas fa-save"></i>

Update

</button>

<a
href="monitored-equipment.php"
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