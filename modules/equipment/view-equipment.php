<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: equipment.php");
    exit;
}

$sql = "
SELECT
    e.*,
    c.category_name,
    i.institute_name,
    d.department_name,
    s.status_name
FROM equipment e

LEFT JOIN equipment_categories c
ON e.category_id = c.id

LEFT JOIN institutes i
ON e.institute_id = i.id

LEFT JOIN departments d
ON e.department_id = d.id

LEFT JOIN equipment_status s
ON e.status_id = s.id

WHERE e.id = $id
";

$result = mysqli_query($conn, $sql);
$equipment = mysqli_fetch_assoc($result);

if (!$equipment) {
    die('Equipment not found');
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
<h1>Equipment Details</h1>
</div>

<div class="col-sm-6 text-right">

<a href="edit-equipment.php?id=<?= $id ?>"
   class="btn btn-primary">

<i class="fas fa-edit"></i>
Edit

</a>

<a href="equipment.php"
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

<?= htmlspecialchars($equipment['equipment_name']); ?>

</h3>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-8">

<table class="table table-bordered">

<tr>
<th width="250">Asset Number</th>
<td><?= htmlspecialchars($equipment['asset_number']); ?></td>
</tr>

<tr>
<th>Equipment Name</th>
<td><?= htmlspecialchars($equipment['equipment_name']); ?></td>
</tr>

<tr>
<th>Category</th>
<td><?= htmlspecialchars($equipment['category_name']); ?></td>
</tr>

<tr>
<th>Institute</th>
<td><?= htmlspecialchars($equipment['institute_name']); ?></td>
</tr>

<tr>
<th>Department</th>
<td><?= htmlspecialchars($equipment['department_name']); ?></td>
</tr>

<tr>
<th>Manufacturer</th>
<td><?= htmlspecialchars($equipment['manufacturer']); ?></td>
</tr>

<tr>
<th>Model</th>
<td><?= htmlspecialchars($equipment['model']); ?></td>
</tr>

<tr>
<th>Serial Number</th>
<td><?= htmlspecialchars($equipment['serial_number']); ?></td>
</tr>

<tr>
<th>Purchase Date</th>
<td><?= $equipment['purchase_date']; ?></td>
</tr>

<tr>
<th>Warranty Expiry</th>
<td><?= $equipment['warranty_expiry']; ?></td>
</tr>

<tr>
<th>Status</th>

<td>

<?php

$status = strtolower($equipment['status_name']);

if ($status == 'active') {

    echo '<span class="badge badge-success">Active</span>';

} elseif ($status == 'under repair') {

    echo '<span class="badge badge-warning">Under Repair</span>';

} elseif ($status == 'out of service') {

    echo '<span class="badge badge-danger">Out of Service</span>';

} else {

    echo '<span class="badge badge-info">'
         . htmlspecialchars($equipment['status_name'])
         . '</span>';
}

?>

</td>

</tr>

<tr>
<th>Criticality</th>

<td>

<?php

$criticality = strtolower($equipment['criticality_level']);

switch ($criticality) {

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

</tr>

<tr>
<th>Barcode</th>
<td><?= htmlspecialchars($equipment['barcode']); ?></td>
</tr>

<tr>
<th>QR Code</th>
<td><?= htmlspecialchars($equipment['qr_code']); ?></td>
</tr>

<tr>
<th>Remarks</th>
<td><?= nl2br(htmlspecialchars($equipment['remarks'])); ?></td>
</tr>

<tr>
<th>Created Date</th>
<td><?= $equipment['created_at']; ?></td>
</tr>

</table>

</div>

<div class="col-md-4">

<div class="card">

<div class="card-header">
<h3 class="card-title">
Equipment Images
</h3>
</div>

<a href="upload-image.php?equipment_id=<?= $id ?>"
   class="btn btn-success btn-sm float-right">

<i class="fas fa-camera"></i>

Upload Images

</a>

<div class="card-body text-center">

<?php

$image_sql = "
SELECT *
FROM equipment_images
WHERE equipment_id = $id
";

$image_result = mysqli_query($conn, $image_sql);

if (mysqli_num_rows($image_result) > 0) {

    while ($img = mysqli_fetch_assoc($image_result)) {

        echo '
        <img src="../../uploads/equipment/'
        . $img['image_path'] .
        '"
        class="img-fluid mb-2 border"
        style="max-height:200px;"><br>';
    }

} else {

    echo '<p class="text-muted">
            No images uploaded.
          </p>';
}

?>

</div>

</div>

</div>

</div>

</div>

</div>

<!-- Equipment History -->

<div class="card">

<div class="card-header">

<h3 class="card-title">

Equipment History

</h3>

</div>

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>Date</th>
<th>Action</th>
<th>Details</th>
<th>Performed By</th>

</tr>

</thead>

<tbody>

<?php

$history_sql = "
SELECT *
FROM equipment_history
WHERE equipment_id = $id
ORDER BY created_at DESC
";

$history_result = mysqli_query($conn, $history_sql);

if (mysqli_num_rows($history_result) > 0) {

while ($history = mysqli_fetch_assoc($history_result)) {

?>

<tr>

<td><?= $history['created_at']; ?></td>

<td><?= htmlspecialchars($history['action_type']); ?></td>

<td><?= htmlspecialchars($history['action_details']); ?></td>

<td><?= htmlspecialchars($history['performed_by']); ?></td>

</tr>

<?php

}

} else {

?>

<tr>

<td colspan="4" class="text-center">

No history available

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