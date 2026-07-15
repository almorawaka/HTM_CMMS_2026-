<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$sql = "
SELECT
    e.id,
    e.asset_number,
    e.equipment_name,
    c.category_name,
    i.institute_name,
    d.department_name,
    s.status_name,
    e.criticality_level

FROM equipment e

LEFT JOIN equipment_categories c
    ON e.category_id = c.id

LEFT JOIN institutes i
    ON e.institute_id = i.id

LEFT JOIN departments d
    ON e.department_id = d.id

LEFT JOIN equipment_status s
    ON e.status_id = s.id

ORDER BY e.id DESC
";

$result = mysqli_query($conn, $sql);

?>

<div class="content-wrapper">

    <section class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1>Equipment Management</h1>
                </div>

                <div class="col-sm-6">

                    <a href="add-equipment.php"
                       class="btn btn-success float-sm-right">

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

                        Equipment List

                    </h3>

                </div>

                <div class="card-body">

                    <table id="equipmentTable"
                           class="table table-bordered table-striped">

                        <thead>

                        <tr>

                            <th>ID</th>
                            <th>Asset No</th>
                            <th>Equipment</th>
                            <th>Category</th>
                            <th>Institute</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Criticality</th>
                            <th width="160">Action</th>

                        </tr>

                        </thead>

                        <tbody>

                        <?php while($row = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <td><?= $row['id']; ?></td>

                                <td>
                                    <?= htmlspecialchars($row['asset_number']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['equipment_name']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['category_name']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['institute_name']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['department_name']); ?>
                                </td>

                                <td>

                                    <?php

                                    $status = $row['status_name'];

                                    if($status == 'Active'){
                                        echo '<span class="badge badge-success">Active</span>';
                                    }
                                    elseif($status == 'Under Repair'){
                                        echo '<span class="badge badge-warning">Under Repair</span>';
                                    }
                                    elseif($status == 'PM Due'){
                                        echo '<span class="badge badge-info">PM Due</span>';
                                    }
                                    elseif($status == 'Calibration Due'){
                                        echo '<span class="badge badge-primary">Calibration Due</span>';
                                    }
                                    elseif($status == 'Out of Service'){
                                        echo '<span class="badge badge-danger">Out of Service</span>';
                                    }
                                    else{
                                        echo '<span class="badge badge-secondary">'.$status.'</span>';
                                    }

                                    ?>

                                </td>

                                <td>

                                    <?php

                                    switch($row['criticality_level']) {

                                        case 'critical':
                                            echo '<span class="badge badge-danger">Critical</span>';
                                            break;

                                        case 'high':
                                            echo '<span class="badge badge-warning">High</span>';
                                            break;

                                        case 'medium':
                                            echo '<span class="badge badge-info">Medium</span>';
                                            break;

                                        default:
                                            echo '<span class="badge badge-success">Low</span>';
                                    }

                                    ?>

                                </td>

                               
<td class="text-center">

    <div class="btn-group" role="group">

        <a href="view-equipment.php?id=<?= $row['id']; ?>"
           class="btn btn-info btn-sm"
           title="View">
            <i class="fas fa-eye"></i>
        </a>

        <a href="edit-equipment.php?id=<?= $row['id']; ?>"
           class="btn btn-primary btn-sm"
           title="Edit">
            <i class="fas fa-edit"></i>
        </a>

        <a href="equipment-history.php?id=<?= $row['id']; ?>"
           class="btn btn-warning btn-sm"
           title="History">
            <i class="fas fa-history"></i>
        </a>

        <a href="delete-equipment.php?id=<?= $row['id']; ?>"
           class="btn btn-danger btn-sm"
           title="Delete"
           onclick="return confirm('Delete this equipment?')">
            <i class="fas fa-trash"></i>
        </a>

    </div>

</td>



                            </tr>

                        <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </section>

</div>

<link rel="stylesheet"
href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>

$(function(){

    $('#equipmentTable').DataTable({

        responsive: true,
        autoWidth: false,
        pageLength: 25

    });

});

</script>
<style>
.btn-group .btn{
    min-width:40px;
}

.table td{
    vertical-align:middle;
}
</style>

<?php include '../../includes/footer.php'; ?>