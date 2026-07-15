<?php

require_once 'config/auth.php';
require_once 'config/db.php';

/* Dashboard Counts */

$equipment_count = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM equipment")
)[0];

$repair_count = mysqli_fetch_row(
    mysqli_query(
        $conn,
        "SELECT COUNT(*)
         FROM repair_requests
         WHERE status IN ('open','assigned','in_progress')"
    )
)[0];

$pm_due_count = mysqli_fetch_row(
    mysqli_query(
        $conn,
        "SELECT COUNT(*)
         FROM pm_schedule
         WHERE next_pm_date <= CURDATE()"
    )
)[0];

$spare_parts_low = mysqli_fetch_row(
    mysqli_query(
        $conn,
        "SELECT COUNT(*)
         FROM spare_parts
         WHERE quantity <= reorder_level"
    )
)[0];

include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';

?>

<div class="content-wrapper">

<section class="content-header">

<div class="container-fluid">

<h1>Dashboard</h1>

</div>

</section>

<section class="content">

<div class="container-fluid">

<div class="row">

<div class="col-lg-3 col-6">

<div class="small-box bg-info">

<div class="inner">

<h3><?= $equipment_count ?></h3>

<p>Equipment</p>

</div>

<div class="icon">

<i class="fas fa-hospital-alt"></i>

</div>

</div>

</div>

<div class="col-lg-3 col-6">

<div class="small-box bg-warning">

<div class="inner">

<h3><?= $repair_count ?></h3>

<p>Open Repairs</p>

</div>

<div class="icon">

<i class="fas fa-tools"></i>

</div>

</div>

</div>

<div class="col-lg-3 col-6">

<div class="small-box bg-success">

<div class="inner">

<h3><?= $pm_due_count ?></h3>

<p>PM Due</p>

</div>

<div class="icon">

<i class="fas fa-calendar-check"></i>

</div>

</div>

</div>

<div class="col-lg-3 col-6">

<div class="small-box bg-danger">

<div class="inner">

<h3><?= $spare_parts_low ?></h3>

<p>Low Stock Parts</p>

</div>

<div class="icon">

<i class="fas fa-box-open"></i>

</div>

</div>

</div>

</div>

<div class="card">

<div class="card-header">

<h3 class="card-title">

Welcome

</h3>

</div>

<div class="card-body">

<h4>

Welcome, <?= $_SESSION['full_name']; ?>

</h4>

<p>

Role:
<b><?= $_SESSION['role']; ?></b>

</p>

<p>

Date:
<?= date('Y-m-d H:i:s'); ?>

</p>

</div>

</div>

</div>

</section>

</div>

<?php include 'includes/footer.php'; ?>