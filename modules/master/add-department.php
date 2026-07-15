<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $institute_id   = (int)$_POST['institute_id'];
    $department_name = trim($_POST['department_name']);
    $location       = trim($_POST['location']);

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO departments
        (institute_id, department_name, location)
        VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iss",
        $institute_id,
        $department_name,
        $location
    );

    if (mysqli_stmt_execute($stmt)) {

        header("Location: departments.php");
        exit;
    }

    $error = "Failed to save department.";
}

$institutes = mysqli_query(
    $conn,
    "SELECT id, institute_name
     FROM institutes
     ORDER BY institute_name"
);

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>

<div class="content-wrapper">

    <section class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1>Add Department</h1>
                </div>

                <div class="col-sm-6">

                    <a href="departments.php"
                       class="btn btn-secondary float-sm-right">

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

                        New Department

                    </h3>

                </div>

                <form method="POST">

                    <div class="card-body">

                        <?php if(isset($error)): ?>

                            <div class="alert alert-danger">

                                <?= $error ?>

                            </div>

                        <?php endif; ?>

                        <div class="form-group">

                            <label>Institute</label>

                            <select name="institute_id"
                                    class="form-control"
                                    required>

                                <option value="">
                                    Select Institute
                                </option>

                                <?php while($inst = mysqli_fetch_assoc($institutes)): ?>

                                    <option value="<?= $inst['id']; ?>">

                                        <?= htmlspecialchars($inst['institute_name']); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                        <div class="form-group">

                            <label>Department Name</label>

                            <input type="text"
                                   name="department_name"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="form-group">

                            <label>Location</label>

                            <input type="text"
                                   name="location"
                                   class="form-control">

                        </div>

                    </div>

                    <div class="card-footer">

                        <button type="submit"
                                class="btn btn-success">

                            <i class="fas fa-save"></i>
                            Save Department

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </section>

</div>

<?php include '../../includes/footer.php'; ?>