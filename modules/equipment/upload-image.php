<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$equipment_id = intval($_GET['equipment_id'] ?? 0);

if (!$equipment_id) {
    die("Invalid Equipment ID");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {



    if (!empty($_FILES['images']['name'][0])) {

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {

            $file_name = time() . '_' .
                         rand(1000,9999) . '_' .
                         basename($_FILES['images']['name'][$key]);

            $target = "../../uploads/equipment/" . $file_name;

            if (move_uploaded_file($tmp_name, $target)) {

                mysqli_query(
                    $conn,
                    "INSERT INTO equipment_images
                    (equipment_id,image_path)
                    VALUES
                    ($equipment_id,'$file_name')"
                );

            }
        }
    }

    header(
        "Location: view-equipment.php?id=" .
        $equipment_id
    );

    exit;
}

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>

<div class="content-wrapper">

<section class="content-header">

<div class="container-fluid">

<h1>Upload Equipment Images</h1>

</div>

</section>

<section class="content">

<div class="container-fluid">

<div class="card">

<div class="card-header">

<h3 class="card-title">

Equipment Images

</h3>

</div>

<div class="card-body">

<form method="POST"
      enctype="multipart/form-data">

<div class="form-group">

<label>Select Images</label>

<input type="file"
       name="images[]"
       class="form-control"
       accept="image/*"
       multiple
       required>

<small class="text-muted">

You can upload multiple images.

</small>

</div>

<button type="submit"
        class="btn btn-success">

<i class="fas fa-upload"></i>

Upload Images

</button>

<a href="view-equipment.php?id=<?= $equipment_id ?>"
   class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

<!-- Existing Images -->

<div class="card">

<div class="card-header">

<h3 class="card-title">

Existing Images

</h3>

</div>

<div class="card-body">

<div class="row">

<?php

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM equipment_images
     WHERE equipment_id = $equipment_id"
);

while ($img = mysqli_fetch_assoc($result)) {

?>

<div class="col-md-3 mb-3">

<div class="card">

<img src="../../uploads/equipment/<?= $img['image_path']; ?>"
     class="card-img-top"
     style="height:200px;object-fit:cover;">

<div class="card-body text-center">

<a href="delete-image.php?id=<?= $img['id']; ?>&equipment_id=<?= $equipment_id; ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Delete image?')">

<i class="fas fa-trash"></i>

Delete

</a>

</div>

</div>

</div>

<?php

}

?>

</div>

</div>

</div>

</div>

</section>

</div>

<?php include '../../includes/footer.php'; ?>