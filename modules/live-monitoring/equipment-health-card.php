<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config/auth.php';
require_once '../../config/db.php';
?>

<?php
require_once '../../config/auth.php';
require_once '../../config/db.php';

$equipment_id = intval($_GET['equipment_id'] ?? 0);

if ($equipment_id <= 0) {
    die("Invalid Equipment ID");
}

/* ==========================================
   EQUIPMENT INFORMATION
========================================== */

$sql = "
SELECT
e.*,
i.institute_name,
d.department_name,
m.manufacturer_name

FROM equipment e

LEFT JOIN institutes i
ON e.institute_id=i.id

LEFT JOIN departments d
ON e.department_id=d.id

LEFT JOIN manufacturers m
ON e.manufacturer_id=m.id

WHERE e.id=$equipment_id
";

$equipment = mysqli_fetch_assoc(mysqli_query($conn,$sql));


/* ==========================================
   HEALTH SCORE
========================================== */

$health = mysqli_fetch_assoc(

mysqli_query(
$conn,
"
SELECT *
FROM equipment_health_score
WHERE equipment_id=$equipment_id
ORDER BY calculated_at DESC
LIMIT 1
"
)

);


/* ==========================================
   SENSOR DATA
========================================== */

$sensor = mysqli_fetch_assoc(

mysqli_query(
$conn,
"
SELECT *
FROM equipment_sensor_data
WHERE equipment_id=$equipment_id
ORDER BY recorded_at DESC
LIMIT 1
"
)

);


// Retrieve live status
$live =
mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "
        SELECT live_status
        FROM live_monitoring_equipment
        WHERE equipment_id=$equipment_id
        LIMIT 1
        "
    )
);



/* ==========================================
   UTILIZATION
========================================== */

$util = mysqli_fetch_assoc(

mysqli_query(
$conn,
"
SELECT *
FROM equipment_utilization
WHERE equipment_id=$equipment_id
ORDER BY calculated_at DESC
LIMIT 1
"
)

);


/* ==========================================
   PREDICTIVE DATA
========================================== */

$predict = mysqli_fetch_assoc(

mysqli_query(
$conn,
"
SELECT *
FROM equipment_predictive_data
WHERE equipment_id=$equipment_id
ORDER BY calculated_at DESC
LIMIT 1
"
)

);


//Retrieve runtime data

$runtime_query = mysqli_query(
    $conn,
    "
    SELECT
        DATE(start_time) as run_date,
        SUM(runtime_minutes)/60 as runtime_hours
    FROM equipment_runtime_history
    WHERE equipment_id=$equipment_id
    AND start_time >= DATE_SUB(NOW(),INTERVAL 7 DAY)
    GROUP BY DATE(start_time)
    ORDER BY run_date
    "
);

$runtime_labels = [];
$runtime_values = [];

while($r=mysqli_fetch_assoc($runtime_query))
{
    $runtime_labels[] =
        date(
            'd M',
            strtotime($r['run_date'])
        );

    $runtime_values[] =
        round(
            $r['runtime_hours'],
            2
        );
}

/* ==========================================
   DOWNTIME HISTORY
========================================== */

$downtime_query = mysqli_query(

    $conn,

    "
    SELECT
        DATE(downtime_start) AS down_date,
        SUM(downtime_minutes)/60 AS downtime_hours

    FROM equipment_downtime_history

    WHERE equipment_id=$equipment_id

    AND downtime_start >=
        DATE_SUB(NOW(),INTERVAL 7 DAY)

    GROUP BY DATE(downtime_start)

    ORDER BY down_date
    "
);

$downtime_labels = [];
$downtime_values = [];

while($d = mysqli_fetch_assoc($downtime_query))
{
    $downtime_labels[] =
        date(
            'd M',
            strtotime(
                $d['down_date']
            )
        );

    $downtime_values[] =
        round(
            $d['downtime_hours'],
            2
        );
}


/* ==========================================
   BREAKDOWN HISTORY
========================================== */

$breakdowns = mysqli_query(
    $conn,
    "
    SELECT
        b.*,
        r.reason_code,
        r.reason_description
    FROM equipment_breakdowns b
    LEFT JOIN equipment_reason_codes r
        ON b.reason_code_id = r.id
    WHERE b.equipment_id='$equipment_id'
    ORDER BY b.breakdown_datetime DESC
    LIMIT 20
    "
);

/* ==========================================
   HEALTH SCORE HISTORY
========================================== */

$health_query = mysqli_query(

    $conn,

    "
    SELECT

        DATE(calculated_at)
            AS score_date,

        health_score

    FROM equipment_health_score

    WHERE
        equipment_id=$equipment_id

    ORDER BY
        calculated_at

    LIMIT 30
    "
);

$health_labels = [];
$health_values = [];

while($h=mysqli_fetch_assoc($health_query))
{
    $health_labels[] =
        date(
            'd M',
            strtotime(
                $h['score_date']
            )
        );

    $health_values[] =
        round(
            $h['health_score'],
            1
        );
}


/* ==========================================
   POWER HISTORY
========================================== */

$power_query = mysqli_query(

    $conn,

    "
    SELECT

        DATE(recorded_at)
            AS power_date,

        AVG(power_watt)
            AS avg_power

    FROM equipment_sensor_data

    WHERE
        equipment_id=$equipment_id

    AND recorded_at >=
        DATE_SUB(
            NOW(),
            INTERVAL 7 DAY
        )

    GROUP BY
        DATE(recorded_at)

    ORDER BY
        power_date
    "
);

$power_labels = [];
$power_values = [];

while($p=mysqli_fetch_assoc($power_query))
{
    $power_labels[] =
        date(
            'd M',
            strtotime(
                $p['power_date']
            )
        );

    $power_values[] =
        round(
            $p['avg_power'],
            1
        );
}

/* ==========================================
   MTBF / MTTR CALCULATIONS
========================================== */

/* Total runtime */

$runtime_data =
mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "
        SELECT

            SUM(runtime_minutes)
                AS total_runtime

        FROM
            equipment_runtime_history

        WHERE
            equipment_id=$equipment_id
        "
    )
);

$total_runtime_hours =
round(
    ($runtime_data['total_runtime'] ?? 0)
    /60,
    2
);


/* Breakdown count */

$breakdown_data =
mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "
        SELECT

            COUNT(*) total

        FROM
            equipment_breakdowns

        WHERE
            equipment_id=$equipment_id
        "
    )
);

$breakdown_count =
$breakdown_data['total'] ?? 0;


/* Total downtime */

$downtime_data =
mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "
        SELECT

            SUM(downtime_minutes)
                AS total

        FROM
            equipment_downtime_history

        WHERE
            equipment_id=$equipment_id
        "
    )
);

$total_downtime =
round(
    ($downtime_data['total'] ?? 0)
    /60,
    2
);


/* MTBF */

if($breakdown_count>0)
{
    $mtbf =
        round(
            $total_runtime_hours
            /
            $breakdown_count,
            2
        );
}
else
{
    $mtbf =
        $total_runtime_hours;
}


/* MTTR */

if($breakdown_count>0)
{
    $mttr =
        round(
            $total_downtime
            /
            $breakdown_count,
            2
        );
}
else
{
    $mttr = 0;
}


/* Availability */

if(
    ($mtbf+$mttr)>0
)
{
    $availability =
        round(
            (
                $mtbf
                /
                ($mtbf+$mttr)
            )*100,
            2
        );
}
else
{
    $availability=0;
}

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';
?>

<div class="content-wrapper">

<section class="content">

<div class="container-fluid">

<!-- moved to hedder.php -->
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"> -->


<!-- ======================================
     EQUIPMENT INFORMATION
======================================= -->

<div class="card card-primary">

<div class="card-header">
<h3 class="card-title">
Equipment Information
</h3>
</div>

<div class="card-body">

<div class="row">

<div class="col-md-3 text-center">
<?php

$image_url = '';

$sql_img = "
    SELECT image_path
    FROM equipment_images
    WHERE equipment_id = $equipment_id
    ORDER BY id ASC
    LIMIT 1
";

$res_img = mysqli_query($conn, $sql_img);

if($row_img = mysqli_fetch_assoc($res_img))
{
    $image_url =
        $base_url .
        rawurlencode(
            str_replace(
                '\\',
                '/',
                $row_img['image_path']
            )
        );

    // restore forward slashes
    $image_url =
        str_replace(
            '%2F',
            '/',
            $image_url
        );
}
?>

<td width="220">

<?php if($image_url != '') { ?>

    <img
        src="<?= $image_url ?>"
        alt="Equipment Image"
        class="img-thumbnail"
        style="
            width:300px;
            max-height:360px;
            object-fit:contain;
        ">

    <br><br>


<?php } else { ?>

    <div class="text-danger">
        No image found
    </div>

<?php } ?>

</td>

</div>

<div class="col-md-9">

<table class="table table-bordered">

<tr>
<th width="25%">Equipment</th>
<td><?= $equipment['equipment_name'] ?? '' ?></td>
</tr>

<tr>
<th>Manufacturer</th>
<td><?= $equipment['manufacturer_name'] ?? '' ?></td>
</tr>

<tr>
<th>Model</th>
<td><?= $equipment['model'] ?? '' ?></td>
</tr>

<tr>
<th>Serial Number</th>
<td><?= $equipment['serial_number'] ?? '' ?></td>
</tr>

<tr>
<th>Hospital</th>
<td><?= $equipment['institute_name'] ?? '' ?></td>
</tr>

<tr>
<th>Department</th>
<td><?= $equipment['department_name'] ?? '' ?></td>
</tr>

<tr>
<th>Current Status</th>
<td>

<?php
if(($live['live_status'] ?? '')=='ONLINE'){
    echo '<span class="badge badge-success">ONLINE</span>';
}else{
    echo '<span class="badge badge-danger">OFFLINE</span>';
}
?>

</td>
</tr>

</table>

</div>

</div>

</div>

</div>


<!-- ======================================
     HEALTH SCORE
======================================= -->

<div class="row">

<div class="col-md-3">
<div class="small-box bg-success">
<div class="inner">
<h3><?= round($health['health_score'] ?? 0) ?>%</h3>
<p>Health Score</p>
</div>
</div>
</div>

<div class="col-md-3">
<div class="small-box bg-info">
<div class="inner">
<h3><?= round($health['reliability_score'] ?? 0) ?>%</h3>
<p>Reliability</p>
</div>
</div>
</div>

<div class="col-md-3">
<div class="small-box bg-warning">
<div class="inner">
<h3><?= round($health['utilization_score'] ?? 0) ?>%</h3>
<p>Utilization</p>
</div>
</div>
</div>

<div class="col-md-3">
<div class="small-box bg-danger">
<div class="inner">
<h3><?= round($health['risk_score'] ?? 0) ?>%</h3>
<p>Risk Score</p>
</div>
</div>
</div>

</div>




<!-- ======================================
     SENSOR DATA
======================================= -->

<div class="card">

<div class="card-header">
<h3 class="card-title">
Live Sensor Data
</h3>
</div>

<div class="card-body">

<div class="row">

<div class="col-md-2">
<b>Voltage</b><br>
<?= $sensor['voltage'] ?? 0 ?> V
</div>

<div class="col-md-2">
<b>Current</b><br>
<?= $sensor['current_amp'] ?? 0 ?> A
</div>

<div class="col-md-2">
<b>Power</b><br>
<?= $sensor['power_watt'] ?? 0 ?> W
</div>

<div class="col-md-2">
<b>Frequency</b><br>
<?= $sensor['frequency'] ?? 0 ?> Hz
</div>

<div class="col-md-2">
<b>Temperature</b><br>
<?= $sensor['ambient_temperature'] ?? 0 ?> °C
</div>

<div class="col-md-2">
<b>WiFi</b><br>
<?= $sensor['wifi_rssi'] ?? 0 ?> dBm
</div>

</div>

</div>

</div>


<!-- ======================================
     UTILIZATION
======================================= -->

<div class="card">

<div class="card-header">
<h3 class="card-title">
Equipment Utilization
</h3>
</div>

<div class="card-body">

<div class="row">

<div class="col-md-2">
Runtime<br>
<b><?= $util['runtime_hours'] ?? 0 ?> h</b>
</div>

<div class="col-md-2">
Idle<br>
<b><?= $util['idle_hours'] ?? 0 ?> h</b>
</div>

<div class="col-md-2">
Standby<br>
<b><?= $util['standby_hours'] ?? 0 ?> h</b>
</div>

<div class="col-md-2">
Downtime<br>
<b><?= $util['downtime_hours'] ?? 0 ?> h</b>
</div>

<div class="col-md-2">
Availability<br>
<b><?= $util['availability_percent'] ?? 0 ?>%</b>
</div>

<div class="col-md-2">
Utilization<br>
<b><?= $util['utilization_percent'] ?? 0 ?>%</b>
</div>

</div>

</div>

</div>


<!-- ======================================
     PREDICTIVE MAINTENANCE
======================================= -->

<div class="card card-warning">

<div class="card-header">

<h3 class="card-title">
Predictive Maintenance
</h3>

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th width="30%">
Predicted Failure Date
</th>
<td>
<?= $predict['predicted_failure_date'] ?? '-' ?>
</td>
</tr>

<tr>
<th>
Remaining Useful Life
</th>
<td>
<?= $predict['remaining_useful_life_days'] ?? '-' ?>
days
</td>
</tr>

<tr>
<th>
Failure Probability
</th>
<td>
<?= $predict['failure_probability'] ?? 0 ?>%
</td>
</tr>

<tr>
<th>
Predicted Component
</th>
<td>
<?= $predict['predicted_component'] ?? '-' ?>
</td>
</tr>

<tr>
<th>
Anomaly Score
</th>
<td>
<?= $predict['anomaly_score'] ?? 0 ?>
</td>
</tr>

<tr>
<th>
Maintenance Priority
</th>
<td>
<?= $predict['maintenance_priority'] ?? '-' ?>
</td>
</tr>

<tr>
<th>
Recommendation
</th>
<td>
<?= $predict['recommendation'] ?? '-' ?>
</td>
</tr>

</table>

</div>

</div>



<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-line"></i>
            Runtime History (Last 7 Days)
        </h3>
    </div>

    <div class="card-body">

        <canvas id="runtimeChart"
                style="height:300px">
        </canvas>

    </div>

</div>

<div class="card card-danger">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-exclamation-triangle"></i>

            Downtime History
            (Last 7 Days)

        </h3>

    </div>

    <div class="card-body">

        <canvas
            id="downtimeChart"
            style="height:300px">
        </canvas>

    </div>

</div>


<!-- Health Score Card -->

<div class="card card-success">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-heartbeat"></i>

            Equipment Health Score Trend

        </h3>

    </div>

    <div class="card-body">

        <canvas
            id="healthChart"
            style="height:300px">
        </canvas>

    </div>

</div>






<div class="card card-danger">

<div class="card-header">

<h3 class="card-title">

<i class="fas fa-tools"></i>

Breakdown History

</h3>

</div>

<div class="card-body">

<table
id="breakdownTable"
class="table table-bordered table-striped">

<thead>

<tr>

<th>Date</th>
<th>Category</th>
<th>Severity</th>
<th>Fault</th>
<th>Root Cause</th>
<th>Downtime</th>
<th>Status</th>
<th>Cost</th>

</tr>

</thead>

<tbody>

<?php
while($b =
    mysqli_fetch_assoc(
        $breakdowns
    ))
{
?>
<tr>

<td>

<?= date(
        'Y-m-d',
        strtotime(
            $b['breakdown_datetime']
        )
); ?>

</td>

<td>

<?= htmlspecialchars(
        $b['fault_category'] ?? ''
); ?>

</td>

<td>

<?php

switch($b['severity'] ?? '')
{
    case 'CRITICAL':
        echo '
        <span class="
            badge badge-danger">
        CRITICAL
        </span>';
        break;

    case 'HIGH':
        echo '
        <span class="
            badge badge-warning">
        HIGH
        </span>';
        break;

    case 'MEDIUM':
        echo '
        <span class="
            badge badge-info">
        MEDIUM
        </span>';
        break;

    default:
        echo '
        <span class="
            badge badge-success">
        LOW
        </span>';
}

?>

</td>

<td>

<?= htmlspecialchars(
        $b['fault_description']
        ?? '-'
); ?>

</td>

<td>

<?= htmlspecialchars(
        $b['root_cause']
        ?? '-'
); ?>

</td>

<td>

<?= number_format(
    ($b['downtime_minutes'] ?? 0)/60,
    1
); ?>



</td>

<td>

<?php
if(($b['repaired'] ?? 0 ))
{
?>
<span class="badge badge-success">
REPAIRED
</span>
<?php
}
else
{
?>
<span class="badge badge-danger">
OPEN
</span>
<?php
}
?>

</td>

<td>

Rs.
<?= number_format(
        $b['total_cost'] ?? 0  ,
        2
); ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>



<!-- KPI dashboard -->
<div class="card card-info">

<div class="card-header">

<h3 class="card-title">

<i class="fas fa-chart-pie"></i>

Reliability KPIs

</h3>

</div>

<div class="card-body">

<div class="row">

<!-- MTBF -->
<div class="col-md-3">

<div class="small-box bg-success">

<div class="inner">

<h3>

<?= $mtbf ?>

</h3>

<p>

MTBF
(Hours)

</p>

</div>

<div class="icon">

<i class="fas fa-clock"></i>

</div>

</div>

</div>

<!-- MTTR -->

<div class="col-md-3">

<div class="small-box bg-warning">

<div class="inner">

<h3>

<?= $mttr ?>

</h3>

<p>

MTTR
(Hours)

</p>

</div>

<div class="icon">

<i class="fas fa-tools"></i>

</div>

</div>

</div>

<!-- Availability -->
<div class="col-md-3">

<div class="small-box bg-primary">

<div class="inner">

<h3>

<?= $availability ?>%

</h3>

<p>

Availability

</p>

</div>



<div class="icon">

<i class="fas fa-check-circle"></i>

</div>

</div>

</div>

<!-- Breakdown -->
<div class="col-md-3">

<div class="small-box bg-danger">

<div class="inner">

<h3>

<?= $breakdown_count ?>

</h3>

<p>

Breakdowns

</p>

</div>

<div class="icon">

<i class="fas fa-exclamation-triangle"></i>

</div>

</div>

</div>

</div>

</div>

</div>

</div>


<!-- Power Consumption Card -->

<div class="card card-warning">

<div class="card-header">

<h3 class="card-title">

<i class="fas fa-bolt"></i>

Power Consumption Trend

</h3>

</div>

<div class="card-body">

<canvas
    id="powerChart"
    style="height:300px">
</canvas>

</div>

</div>



<!-- </section>

</div> -->









<!-- <div class="content-wrapper"> -->

<!-- <section class="content-header"> -->



<!-- Power Consumption Chart -->
<script>
const powerCtx =
document.getElementById('powerChart');

if(powerCtx)
{
    new Chart(powerCtx,{
        type:'line',
        data:{
            labels: <?= json_encode($power_labels) ?>,
            datasets:[{
                label:'Power (W)',
                data: <?= json_encode($power_values) ?>,
                borderWidth:3,
                fill:false,
                tension:0.4
            }]
        },
        options:{
            responsive:true,
            scales:{
                y:{
                    beginAtZero:true
                }
            }
        }
    });
}

</script>

<!-- Enable DataTables -->
<script>

$(function(){

    $('#breakdownTable')
    .DataTable({

        responsive:true,

        autoWidth:false,

        pageLength:5,

        ordering:true

    });

});

</script>

<!-- Runtime Chart -->
<script>

const runtimeCtx =
    document.getElementById('runtimeChart');

if(runtimeCtx)
{
    new Chart(runtimeCtx,{

        type:'line',

        data:{
            labels: <?= json_encode($runtime_labels); ?>,

            datasets:[{
                label:'Runtime Hours',
                data: <?= json_encode($runtime_values); ?>,
                borderWidth:3,
                fill:true,
                tension:0.3
            }]
        },

        options:{
            responsive:true,
            scales:{
                y:{
                    beginAtZero:true
                }
            }
        }
    });
}

</script>

<!-- Downtime Chart -->

<script>
const downtimeCtx =
    document.getElementById('downtimeChart');

if(downtimeCtx)
{
    new Chart(downtimeCtx,{
        type:'bar',
        data:{
            labels: <?= json_encode($downtime_labels); ?>,
            datasets:[{
                label:'Downtime Hours',
                data: <?= json_encode($downtime_values); ?>,
                borderWidth:1
            }]
        },
        options:{
            responsive:true,
            scales:{
                y:{
                    beginAtZero:true
                }
            }
        }
    });
}
</script>


<!-- Health Score Chart -->

<script>

const healthCtx =
    document.getElementById('healthChart');

if(healthCtx)
{
    new Chart(
        healthCtx,
        {
            type:'line',

            data:{
                labels:
                    <?= json_encode($health_labels); ?>,

                datasets:[{
                    label:'Health Score',

                    data:
                        <?= json_encode($health_values); ?>,

                    borderWidth:3,
                    fill:false,
                    tension:0.3
                }]
            },

            options:{
                responsive:true,

                plugins:{
                    legend:{
                        display:true
                    }
                },

                scales:{
                    y:{
                        min:0,
                        max:100,

                        title:{
                            display:true,
                            text:'Health Score'
                        }
                    }
                }
            }
        }
    );
}

</script>

</div>

</section>

</div>

<?php
include '../../includes/footer.php';
?>

