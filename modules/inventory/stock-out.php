<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

/* Spare Parts */

$parts = mysqli_query(
    $conn,
    "SELECT *
     FROM spare_parts
     WHERE status='active'
     AND quantity > 0
     ORDER BY part_name"
);

/* Work Orders */

$workorders = mysqli_query(
    $conn,
    "SELECT id
     FROM work_orders
     ORDER BY id DESC"
);

/* Users */

$users = mysqli_query(
    $conn,
    "SELECT id,name
     FROM users
     ORDER BY name"
);

/* Save */

if($_SERVER['REQUEST_METHOD']=='POST'){

    $part_id      = intval($_POST['part_id']);
    $work_order   = intval($_POST['work_order_id']);
    $issued_to    = intval($_POST['issued_to']);
    $quantity     = intval($_POST['quantity']);
    $remarks      =
        mysqli_real_escape_string(
            $conn,
            $_POST['remarks']
        );

    $issued_by =
        $_SESSION['user_id'];

    /* Check stock */

    $stock =
        mysqli_fetch_assoc(
            mysqli_query(
                $conn,
                "SELECT quantity,
                        barcode
                 FROM spare_parts
                 WHERE id='$part_id'"
            )
        );

    if($quantity > $stock['quantity']){

        die(
            "Not enough stock available."
        );
    }

    /* Insert transaction */

    $sql = "
    INSERT INTO stock_out
    (
        part_id,
        work_order_id,
        quantity,
        barcode,
        issued_to,
        remarks,
        issued_by
    )
    VALUES
    (
        '$part_id',
        '$work_order',
        '$quantity',
        '{$stock['barcode']}',
        '$issued_to',
        '$remarks',
        '$issued_by'
    )
    ";

    if(mysqli_query($conn,$sql)){

        /* Deduct inventory */

        mysqli_query(
            $conn,
            "UPDATE spare_parts
             SET quantity=
                 quantity-$quantity
             WHERE id='$part_id'"
        );

        header(
            "Location: stock-history.php"
        );

        exit;
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

<h1>Stock Out</h1>

</div>

<div class="col-sm-6 text-right">

<a href="inventory.php"
   class="btn btn-secondary">

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

Issue Spare Parts

</h3>

</div>

<form method="POST">

<div class="card-body">

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Spare Part</label>

<select
name="part_id"
class="form-control"
required>

<option value="">
Select Part
</option>

<?php
while($p=mysqli_fetch_assoc($parts)){
?>

<option
value="<?= $p['id']; ?>">

<?= $p['part_code']; ?>
 -
<?= $p['part_name']; ?>
 (
<?= $p['quantity']; ?>
)

</option>

<?php } ?>

</select>

</div>

</div>

<div class="col-md-3">

<div class="form-group">

<label>Quantity</label>

<input
type="number"
name="quantity"
class="form-control"
required>

</div>

</div>

<div class="col-md-3">

<div class="form-group">

<label>Work Order</label>

<select
name="work_order_id"
class="form-control">

<option value="">
None
</option>

<?php
while($w=mysqli_fetch_assoc($workorders)){
?>

<option
value="<?= $w['id']; ?>">

WO-<?= $w['id']; ?>

</option>

<?php } ?>

</select>

</div>

</div>

</div>

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Issued To</label>

<select
name="issued_to"
class="form-control">

<option value="">
Select User
</option>

<?php
while($u=mysqli_fetch_assoc($users)){
?>

<option
value="<?= $u['id']; ?>">

<?= $u['name']; ?>

</option>

<?php } ?>

</select>

</div>

</div>

</div>

<div class="form-group">

<label>Remarks</label>

<textarea
name="remarks"
rows="3"
class="form-control"></textarea>

</div>

</div>

<div class="card-footer">

<button
type="submit"
class="btn btn-danger">

<i class="fas fa-minus-circle"></i>

Issue Part

</button>

</div>

</form>

</div>

</div>

</section>

</div>

<?php
include '../../includes/footer.php';
?>