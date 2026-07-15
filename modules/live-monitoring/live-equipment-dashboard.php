<?php

/* =====================================================
   CMMS 2026
   Critical Care Equipment Live Dashboard
   Module: Live Monitoring
===================================================== */

require_once '../../config/auth.php';
require_once '../../config/db.php';


/* =====================================================
   1. FILTER VARIABLES
===================================================== */

$hospital_id   = intval($_GET['hospital_id'] ?? 0);
$department_id = intval($_GET['department_id'] ?? 0);
$status        = $_GET['status'] ?? '';


/* =====================================================
   2. LOAD FILTER DROPDOWNS
===================================================== */

/* Hospital List */

$hospitals = mysqli_query(
    $conn,
    "SELECT id, institute_name
     FROM institutes
     ORDER BY institute_name"
);

/* Department List */

$departments = mysqli_query(
    $conn,
    "SELECT id, department_name
     FROM departments
     ORDER BY department_name"
);


/* =====================================================
   3. DASHBOARD SUMMARY COUNTERS
===================================================== */

/* Total Equipment */

$total = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) total
         FROM live_monitoring_equipment"
    )
)['total'];


/* Online Equipment */

$online = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) total
         FROM live_monitoring_equipment
         WHERE live_status='ONLINE'"
    )
)['total'];


/* Offline Equipment */

$offline = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) total
         FROM live_monitoring_equipment
         WHERE live_status='OFFLINE'"
    )
)['total'];


/* Alarm Equipment */

$alarm = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) total
         FROM live_monitoring_equipment
         WHERE live_status='ALARM'"
    )
)['total'];


/* Maintenance Equipment */

$maintenance = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) total
         FROM live_monitoring_equipment
         WHERE live_status='MAINTENANCE'"
    )
)['total'];


/* =====================================================
   4. BUILD FILTER CONDITIONS
===================================================== */

$where = [];

/* Filter by Hospital */

if ($hospital_id > 0) {
    $where[] = "e.institute_id = $hospital_id";
}

/* Filter by Department */

if ($department_id > 0) {
    $where[] = "e.department_id = $department_id";
}

/* Filter by Status */

if ($status != '') {

    $status = mysqli_real_escape_string(
        $conn,
        $status
    );

    $where[] =
        "l.live_status='$status'";
}

/* Create WHERE Clause */

$where_sql = '';

if (count($where) > 0) {

    $where_sql =
        ' WHERE '.implode(
            ' AND ',
            $where
        );
}


/* =====================================================
   5. MAIN DASHBOARD QUERY
===================================================== */

$sql = "

SELECT

    l.*,

    e.equipment_name,
    e.model,

    i.institute_name,

    d.department_name,

    m.manufacturer_name

FROM live_monitoring_equipment l

LEFT JOIN equipment e
    ON l.equipment_id=e.id

LEFT JOIN institutes i
    ON e.institute_id=i.id

LEFT JOIN departments d
    ON e.department_id=d.id

LEFT JOIN manufacturers m
    ON e.manufacturer_id=m.id

$where_sql

ORDER BY

    i.institute_name,
    d.department_name,
    e.equipment_name

";

$result = mysqli_query($conn,$sql);

function getEquipmentIcon($equipment){

    $equipment = strtolower($equipment);

    if(strpos($equipment,'ventilator') !== false)
        return 'fas fa-lungs';

    if(strpos($equipment,'anesthesia') !== false)
        return 'fas fa-procedures';

    if(strpos($equipment,'monitor') !== false)
        return 'fas fa-heartbeat';

    if(strpos($equipment,'defibrillator') !== false)
        return 'fas fa-bolt';

    if(strpos($equipment,'infusion') !== false)
        return 'fas fa-syringe';

    if(strpos($equipment,'ecg') !== false)
        return 'fas fa-wave-square';

    return 'fas fa-medkit';
}


/* =====================================================
   6. LOAD PAGE TEMPLATE
===================================================== */

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>


<!-- =====================================================
     PAGE CONTENT
===================================================== -->

<div class="content-wrapper">


<!-- =====================================================
     PAGE HEADER
===================================================== -->

<section class="content-header">

<div class="container-fluid">

<div class="row">

<div class="col-sm-6">

<h1>
Critical Care Equipment Live Dashboard
</h1>

</div>

<div class="col-sm-6 text-right">

<span class="badge badge-primary">

Auto Refresh: 10 sec

</span>

</div>

</div>

</div>

</section>



<!-- =====================================================
     MAIN CONTENT
===================================================== -->

<section class="content">

<div class="container-fluid">



<!-- =====================================================
     ICU STATUS SUMMARY CARDS
===================================================== -->

<div class="row">

<!-- Total -->

<div class="col-lg-2">

<div class="small-box bg-info">

<div class="inner">
<h3><?= $total ?></h3>
<p>Total</p>
</div>

<div class="icon">
<i class="fas fa-procedures"></i>
</div>

</div>

</div>


<!-- Online -->

<div class="col-lg-2">

<div class="small-box bg-success">

<div class="inner">
<h3><?= $online ?></h3>
<p>Online</p>
</div>

<div class="icon">
<i class="fas fa-heartbeat"></i>
</div>

</div>

</div>


<!-- Offline -->

<div class="col-lg-2">

<div class="small-box bg-danger">

<div class="inner">
<h3><?= $offline ?></h3>
<p>Offline</p>
</div>

<div class="icon">
<i class="fas fa-power-off"></i>
</div>

</div>

</div>


<!-- Alarm -->

<div class="col-lg-2">

<div class="small-box bg-warning">

<div class="inner">
<h3><?= $alarm ?></h3>
<p>Alarm</p>
</div>

<div class="icon">
<i class="fas fa-exclamation-triangle"></i>
</div>

</div>

</div>


<!-- Maintenance -->

<div class="col-lg-2">

<div class="small-box bg-secondary">

<div class="inner">
<h3><?= $maintenance ?></h3>
<p>Maintenance</p>
</div>

<div class="icon">
<i class="fas fa-tools"></i>
</div>

</div>

</div>

</div>



<!-- =====================================================
     FILTER PANEL
===================================================== -->

<div class="card">

<div class="card-header">

<h3 class="card-title">

Filter Equipment

</h3>

</div>

<div class="card-body">

<form method="GET">

<div class="row">


<!-- Hospital Filter -->

<div class="col-md-4">

<label>Hospital</label>

<select
name="hospital_id"
class="form-control">

<option value="0">

All Hospitals

</option>

<?php while($h=mysqli_fetch_assoc($hospitals)){ ?>

<option
value="<?= $h['id']; ?>"
<?= ($hospital_id==$h['id'])?'selected':''; ?>>

<?= htmlspecialchars(
        $h['institute_name']
); ?>

</option>

<?php } ?>

</select>

</div>


<!-- Department Filter -->

<div class="col-md-4">

<label>Department</label>

<select
name="department_id"
class="form-control">

<option value="0">

All Departments

</option>

<?php while($d=mysqli_fetch_assoc($departments)){ ?>

<option
value="<?= $d['id']; ?>"
<?= ($department_id==$d['id'])?'selected':''; ?>>

<?= htmlspecialchars(
        $d['department_name']
); ?>

</option>

<?php } ?>

</select>

</div>


<!-- Status Filter -->

<div class="col-md-2">

<label>Status</label>

<select
name="status"
class="form-control">

<option value="">All</option>

<option value="ONLINE"
<?= ($status=='ONLINE')?'selected':''; ?>>
ONLINE
</option>

<option value="OFFLINE"
<?= ($status=='OFFLINE')?'selected':''; ?>>
OFFLINE
</option>

<option value="ALARM"
<?= ($status=='ALARM')?'selected':''; ?>>
ALARM
</option>

<option value="MAINTENANCE"
<?= ($status=='MAINTENANCE')?'selected':''; ?>>
MAINTENANCE
</option>

</select>

</div>


<!-- Filter Buttons -->

<div class="col-md-2">

<label>&nbsp;</label>

<div>

<button
type="submit"
class="btn btn-primary">

<i class="fas fa-filter"></i>
Filter

</button>

<a
href="live-equipment-dashboard.php"
class="btn btn-secondary">

Reset

</a>

</div>

</div>

</div>

</form>

</div>

</div>


<!-- =====================================================
     LIVE STATUS TABLE
===================================================== -->

<div class="card">

    <div class="card-header">

        <h3 class="card-title">

            Critical Care Equipment Status

        </h3>

    </div>

    <div class="card-body">

        <table
            id="liveTable"
            class="table table-bordered table-striped">

            <thead>

                <tr>

                    <th>Hospital</th>
                    <th>Location</th>
                    <th>Equipment</th>
                    <th>Manufacturer</th>
                    <th>Model</th>
                    <th>Comm</th>
                    <th>Status</th>
                    <th>Last Update</th>
                    <th>Remarks</th>
                    <th width="130">Action</th>

                </tr>

            </thead>

            <tbody>

            <?php while($row = mysqli_fetch_assoc($result)){ ?>

                <tr>

                    <td>
                        <?= htmlspecialchars($row['institute_name'] ?? '-'); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['department_name'] ?? '-'); ?>
                    </td>

                    <td>

                        <i class="<?= getEquipmentIcon($row['equipment_name']); ?>"></i>

                        <?= htmlspecialchars($row['equipment_name']); ?>

                    </td>

                    <td>
                        <?= htmlspecialchars($row['manufacturer_name'] ?? '-'); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['model'] ?? '-'); ?>
                    </td>

                    <td>

                    <?php

                    if(!$row['last_update']){

                        echo '<i class="fas fa-times-circle text-danger"></i>';

                    }else{

                        $diff =
                            time() -
                            strtotime($row['last_update']);

                        if($diff < 30){

                            echo '<i class="fas fa-circle text-success"></i>';

                        }elseif($diff < 120){

                            echo '<i class="fas fa-circle text-warning"></i>';

                        }else{

                            echo '<i class="fas fa-circle text-danger"></i>';

                        }
                    }

                    ?>

                    </td>

                    <td>

                    <?php

                    switch($row['live_status']){

                        case 'ONLINE':
                            echo '<span class="badge badge-success">ONLINE</span>';
                            break;

                        case 'OFFLINE':
                            echo '<span class="badge badge-danger">OFFLINE</span>';
                            break;

                        case 'ALARM':
                            echo '<span class="badge badge-warning">
                                    <i class="fas fa-bell"></i>
                                    ALARM
                                  </span>';
                            break;

                        case 'MAINTENANCE':
                            echo '<span class="badge badge-secondary">
                                    MAINTENANCE
                                  </span>';
                            break;

                        default:
                            echo '<span class="badge badge-info">
                                    STANDBY
                                  </span>';
                    }

                    ?>

                    </td>

                    <td>

                    <?php

                    if(!empty($row['last_update'])){

                        echo date(
                            'Y-m-d H:i:s',
                            strtotime($row['last_update'])
                        );

                    }else{

                        echo '-';

                    }

                    ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($row['remarks'] ?? '-'); ?>

                    </td>

                    <td>

                        <a
                            href="<?= $base_url ?>modules/live-monitoring/equipment-health-card.php?equipment_id=<?= $row['equipment_id'] ?>"
                            class="btn btn-info btn-sm">

                            <i class="fas fa-heartbeat"></i>
                            Health

                        </a>

                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</div>

</section>

</div>

<style>

.badge-warning{

    animation: blink 1s infinite;

}

@keyframes blink{

    0%{
        opacity:1;
    }

    50%{
        opacity:0.3;
    }

    100%{
        opacity:1;
    }

}

</style>


<?php include '../../includes/footer.php'; ?>


<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>

$(function(){

    $('#liveTable').DataTable({

        responsive:true,
        autoWidth:false,
        pageLength:25

    });

});


/* Auto Refresh */

setTimeout(function(){

    location.reload();

},10000);

</script>