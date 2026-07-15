<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$sql = "
SELECT
    d.id,
    d.department_name,
    d.location,
    i.institute_name
FROM departments d
LEFT JOIN institutes i
    ON d.institute_id = i.id
ORDER BY d.department_name ASC
";

$result = mysqli_query($conn, $sql);

?>

<div class="content-wrapper">

    <section class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1>Departments</h1>
                </div>

                <div class="col-sm-6">

                    <a href="add-department.php"
                       class="btn btn-success float-sm-right">

                        <i class="fas fa-plus"></i>
                        Add Department

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
                        Department List
                    </h3>

                </div>

                <div class="card-body">

                    <table id="departmentTable"
                           class="table table-bordered table-striped">

                        <thead>

                        <tr>

                            <th width="80">ID</th>
                            <th>Department</th>
                            <th>Institute</th>
                            <th>Location</th>
                            <th width="140">Action</th>

                        </tr>

                        </thead>

                        <tbody>

                        <?php while($row = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <td><?= $row['id']; ?></td>

                                <td>
                                    <?= htmlspecialchars($row['department_name']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['institute_name']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['location']); ?>
                                </td>

                                <td>

                                    <a href="edit-department.php?id=<?= $row['id']; ?>"
                                       class="btn btn-primary btn-sm"
                                       title="Edit">

                                        <i class="fas fa-edit"></i>

                                    </a>

                                    <a href="delete-department.php?id=<?= $row['id']; ?>"
                                       class="btn btn-danger btn-sm"
                                       title="Delete"
                                       onclick="return confirm('Delete this department?')">

                                        <i class="fas fa-trash"></i>

                                    </a>

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

    $('#departmentTable').DataTable({
        responsive: true,
        autoWidth: false
    });

});

</script>

<?php include '../../includes/footer.php'; ?>