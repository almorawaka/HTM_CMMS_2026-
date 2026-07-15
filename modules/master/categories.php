<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$result = mysqli_query(
    $conn,
    "SELECT * FROM equipment_categories ORDER BY category_name"
);

?>

<div class="content-wrapper">

<section class="content-header">

<div class="container-fluid">

<div class="row mb-2">

<div class="col-sm-6">
<h1>Equipment Categories</h1>
</div>

<div class="col-sm-6">

<a href="add-category.php"
   class="btn btn-success float-sm-right">

<i class="fas fa-plus"></i>
Add Category

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

Category List

</h3>

</div>

<div class="card-body">

<table class="table table-bordered table-striped">

<thead>

<tr>
<th width="80">ID</th>
<th>Category Name</th>
<th width="150">Action</th>
</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)): ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['category_name']); ?></td>

<td>

<a href="edit-category.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm">

<i class="fas fa-edit"></i>

</a>

<a href="delete-category.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete category?')">

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

<?php include '../../includes/footer.php'; ?>