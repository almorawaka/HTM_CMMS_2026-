<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$sql = "
SELECT
    u.id,
    u.username,
    u.full_name,
    u.email,
    u.mobile,
    u.designation,
    u.status,
    r.role_name
FROM users u
LEFT JOIN roles r
ON u.role_id = r.id
ORDER BY u.id DESC
";

$result = mysqli_query($conn, $sql);

?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1>User Management</h1>
                </div>

                <div class="col-sm-6">
                    <a href="add-user.php"
                       class="btn btn-success float-sm-right">
                        <i class="fas fa-user-plus"></i>
                        Add User
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
                        System Users
                    </h3>
                </div>

                <div class="card-body">

                    <table id="usersTable"
                           class="table table-bordered table-striped">

                        <thead>

                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Designation</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>

                        </thead>

                        <tbody>

                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>

                            <tr>

                                <td><?= $row['id']; ?></td>

                                <td><?= htmlspecialchars($row['username']); ?></td>

                                <td><?= htmlspecialchars($row['full_name']); ?></td>

                                <td><?= htmlspecialchars($row['email']); ?></td>

                                <td><?= htmlspecialchars($row['mobile']); ?></td>

                                <td><?= htmlspecialchars($row['designation']); ?></td>

                                <td><?= htmlspecialchars($row['role_name']); ?></td>

                                <td>

                                    <?php if ($row['status'] == 'active') : ?>

                                        <span class="badge badge-success">
                                            Active
                                        </span>

                                    <?php else : ?>

                                        <span class="badge badge-danger">
                                            Inactive
                                        </span>

                                    <?php endif; ?>

                                </td>
                                    <td>

                                    <div class="btn-group" role="group">

                                        <a href="edit-user.php?id=<?= $row['id']; ?>"
                                        class="btn btn-primary btn-sm"
                                        title="Edit User">

                                            <i class="fas fa-edit"></i>

                                        </a>

                                        <?php if($row['status'] == 'active'): ?>

                                            <a href="delete-user.php?id=<?= $row['id']; ?>"
                                            class="btn btn-danger btn-sm"
                                            title="Deactivate User"
                                            onclick="return confirm('Deactivate this user?')">

                                                <i class="fas fa-user-slash"></i>

                                            </a>

                                        <?php else: ?>

                                            <a href="activate-user.php?id=<?= $row['id']; ?>"
                                            class="btn btn-success btn-sm"
                                            title="Activate User">

                                                <i class="fas fa-user-check"></i>

                                            </a>

                                        <?php endif; ?>

                                        <a href="reset-password.php?id=<?= $row['id']; ?>"
                                        class="btn btn-warning btn-sm"
                                        title="Reset Password">

                                            <i class="fas fa-key"></i>

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
$(function () {
    $('#usersTable').DataTable({
        responsive: true,
        autoWidth: false
    });
});
</script>

<?php include '../../includes/footer.php'; ?>