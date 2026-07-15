<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: inventory.php");
    exit;
}

/* Check Spare Part */

$part = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM spare_parts WHERE id='$id'"
    )
);

if (!$part) {
    die("Spare Part Not Found");
}

/* Check Usage */

$usage = mysqli_fetch_row(
    mysqli_query(
        $conn,
        "SELECT COUNT(*)
         FROM spare_part_usage
         WHERE part_id='$id'"
    )
)[0];

if ($usage > 0) {

    include '../../includes/header.php';
    include '../../includes/navbar.php';
    include '../../includes/sidebar.php';

    ?>

    <div class="content-wrapper">

    <section class="content-header">

    <div class="container-fluid">

    <h1>Delete Spare Part</h1>

    </div>

    </section>

    <section class="content">

    <div class="container-fluid">

    <div class="alert alert-danger">

    <h5>

    <i class="fas fa-exclamation-triangle"></i>

    Cannot Delete Spare Part

    </h5>

    <p>

    This spare part has already been used in maintenance activities.

    </p>

    <p>

    <strong>

    Part :

    <?= htmlspecialchars($part['part_name']); ?>

    </strong>

    </p>

    <a href="inventory.php"
       class="btn btn-secondary">

        Back to Inventory

    </a>

    </div>

    </div>

    </section>

    </div>

    <?php

    include '../../includes/footer.php';

    exit;

}

/* Delete Spare Part */

mysqli_query(
    $conn,
    " UPDATE spare_parts
SET status='inactive'
WHERE id='$id''"
);

header("Location: inventory.php");
exit;

?>