<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$sql = "
SELECT

    lm.*,

    e.equipment_name,
    e.model,

    m.manufacturer_name,

    i.institute_name,

    d.department_name

FROM live_monitoring_equipment lm

LEFT JOIN equipment e
    ON lm.equipment_id = e.id

LEFT JOIN manufacturers m
    ON e.manufacturer_id = m.id

LEFT JOIN institutes i
    ON e.institute_id = i.id

LEFT JOIN departments d
    ON e.department_id = d.id

ORDER BY
    i.institute_name,
    d.department_name,
    e.equipment_name
";

$result = mysqli_query($conn,$sql);

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>

<div class="content-wrapper">

    <section class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1>Monitored Equipment</h1>
                </div>

                <div class="col-sm-6 text-right">

                    <a href="add-monitored-equipment.php"
                       class="btn btn-success">

                        <i class="fas fa-plus"></i>
                        Add Equipment

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
                        IoT Monitored Equipment List
                    </h3>

                </div>

                <div class="card-body">

                    <table
                        id="monitorTable"
                        class="table table-bordered table-striped">

                        <thead>

                        <tr>

                            <th>Hospital</th>
                            <th>Department</th>
                            <th>Equipment</th>
                            <th>Manufacturer</th>
                            <th>Model</th>
                            <th>IoT Device</th>
                            <th>Status</th>
                            <th>Last Update</th>
                            <th width="150">Action</th>

                        </tr>

                        </thead>

                        <tbody>

                        <?php while($row=mysqli_fetch_assoc($result)){ ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars(
                                    $row['institute_name'] ?? '-'
                                ); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $row['department_name'] ?? '-'
                                ); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $row['equipment_name'] ?? '-'
                                ); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $row['manufacturer_name'] ?? '-'
                                ); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $row['model'] ?? '-'
                                ); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $row['iot_device_id'] ?? '-'
                                ); ?>
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
                                        echo '<span class="badge badge-warning">ALARM</span>';
                                        break;

                                    case 'STANDBY':
                                        echo '<span class="badge badge-info">STANDBY</span>';
                                        break;

                                    case 'MAINTENANCE':
                                        echo '<span class="badge badge-secondary">MAINTENANCE</span>';
                                        break;

                                    default:
                                        echo '<span class="badge badge-dark">UNKNOWN</span>';

                                }

                                ?>

                            </td>

                            <td>

                                <?php

                                if($row['last_update']){

                                    echo date(
                                        'Y-m-d H:i',
                                        strtotime(
                                            $row['last_update']
                                        )
                                    );

                                }else{

                                    echo '-';

                                }

                                ?>

                            </td>

                            <td>

                                <div class="btn-group">

                                    <a
                                        href="live-equipment-dashboard.php?id=<?= $row['id']; ?>"
                                        class="btn btn-info btn-sm">

                                        <i class="fas fa-eye"></i>

                                    </a>

                                    <a
                                        href="edit-monitored-equipment.php?id=<?= $row['id']; ?>"
                                        class="btn btn-primary btn-sm">

                                        <i class="fas fa-edit"></i>

                                    </a>

                                    <a
                                        href="delete-monitored-equipment.php?id=<?= $row['id']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this monitored equipment?')">

                                        <i class="fas fa-trash"></i>

                                    </a>

                                </div>

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

<?php include '../../includes/footer.php'; ?>

<script>

$(function(){

    $('#monitorTable').DataTable({

        responsive:true,
        autoWidth:false,
        pageLength:25

    });

});

</script>