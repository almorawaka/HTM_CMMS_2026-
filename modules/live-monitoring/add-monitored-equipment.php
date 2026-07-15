<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$message = "";

/* Load Equipment */

$equipment = mysqli_query(
    $conn,
    "
    SELECT
        e.id,
        e.equipment_name,
        e.model,
        e.asset_number,
        i.institute_name,
        d.department_name
    FROM equipment e

    LEFT JOIN institutes i
        ON e.institute_id=i.id

    LEFT JOIN departments d
        ON e.department_id=d.id

    ORDER BY
        e.equipment_name,
        e.model
    "
);

/* Save */

if($_SERVER['REQUEST_METHOD']=='POST'){

    $equipment_id = intval($_POST['equipment_id']);

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

    $remarks =
        mysqli_real_escape_string(
            $conn,
            $_POST['remarks']
        );

    /* Check duplicate */

    $check = mysqli_query(
        $conn,
        "
        SELECT id
        FROM live_monitoring_equipment
        WHERE equipment_id='$equipment_id'
        "
    );

    if(mysqli_num_rows($check)>0){

        $message =
            "This equipment is already registered for live monitoring.";

    }
    else{

        $sql = "
        INSERT INTO live_monitoring_equipment
        (
            equipment_id,
            iot_device_id,
            mqtt_topic,
            ip_address,
            monitoring_enabled,
            criticality,
            remarks
        )
        VALUES
        (
            '$equipment_id',
            '$iot_device_id',
            '$mqtt_topic',
            '$ip_address',
            '$monitoring_enabled',
            '$criticality',
            '$remarks'
        )
        ";

        if(mysqli_query($conn,$sql)){

            header(
                "Location: monitored-equipment.php"
            );

            exit;
        }
        else{

            $message =
                "Unable to save monitored equipment.";
        }
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
<h1>Add Monitored Equipment</h1>
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

<?php if($message!=""){ ?>

<div class="alert alert-danger">

<?= $message; ?>

</div>

<?php } ?>

<div class="row">

<div class="col-md-12">

<div class="form-group">

<label>Select Equipment</label>

<select
name="equipment_id"
class="form-control"
required>

<option value="">
Select Equipment
</option>

<?php
while($row=mysqli_fetch_assoc($equipment)){
?>

<option
value="<?= $row['id']; ?>">

<?= htmlspecialchars(
        $row['equipment_name']
); ?>

(
<?= htmlspecialchars(
        $row['model']
); ?>

)

-
<?= htmlspecialchars(
        $row['institute_name']
); ?>

-
<?= htmlspecialchars(
        $row['department_name']
); ?>

</option>

<?php } ?>

</select>

</div>

</div>

</div>

<div class="row">

<div class="col-md-4">

<div class="form-group">

<label>IoT Device ID</label>

<input
type="text"
name="iot_device_id"
class="form-control"
placeholder="ESP32_ICU_001">

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>MQTT Topic</label>

<input
type="text"
name="mqtt_topic"
class="form-control"
placeholder="hospital/icu/ventilator1">

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>IP Address</label>

<input
type="text"
name="ip_address"
class="form-control"
placeholder="192.168.1.100">

</div>

</div>

</div>

<div class="row">

<div class="col-md-4">

<div class="form-group">

<label>Criticality</label>

<select
name="criticality"
class="form-control">

<option value="HIGH">
HIGH
</option>

<option value="MEDIUM">
MEDIUM
</option>

<option value="LOW">
LOW
</option>

</select>

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Monitoring</label>

<select
name="monitoring_enabled"
class="form-control">

<option value="1">
Enabled
</option>

<option value="0">
Disabled
</option>

</select>

</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Status</label>

<input
type="text"
class="form-control"
value="OFFLINE"
readonly>

</div>

</div>

</div>

<div class="row">

<div class="col-md-12">

<div class="form-group">

<label>Remarks</label>

<textarea
name="remarks"
class="form-control"
rows="3"></textarea>

</div>

</div>

</div>

</div>

<div class="card-footer">

<button
type="submit"
class="btn btn-success">

<i class="fas fa-save"></i>
Save

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