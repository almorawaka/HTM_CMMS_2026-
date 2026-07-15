```php
<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$message = '';

if (!isset($_GET['id']))
{
    header("Location: users.php");
    exit;
}

$id = intval($_GET['id']);

$sql = "
SELECT *
FROM users
WHERE id = $id
LIMIT 1
";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0)
{
    header("Location: users.php");
    exit;
}

$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $username    = trim($_POST['username']);
    $full_name   = trim($_POST['full_name']);
    $email       = trim($_POST['email']);
    $mobile      = trim($_POST['mobile']);
    $institute   = trim($_POST['institute']);
    $designation = trim($_POST['designation']);
    $role_id     = intval($_POST['role_id']);
    $status      = trim($_POST['status']);

    $updateSql = "
    UPDATE users
    SET
        username    = '$username',
        full_name   = '$full_name',
        email       = '$email',
        mobile      = '$mobile',
        institute   = '$institute',
        designation = '$designation',
        role_id     = '$role_id',
        status      = '$status'
    WHERE id = $id
    ";

    if (mysqli_query($conn, $updateSql))
    {
        header("Location: users.php");
        exit;
    }
    else
    {
        $message = "Failed to update user.";
    }
}

$roles = mysqli_query(
    $conn,
    "SELECT id, role_name
     FROM roles
     ORDER BY role_name"
);

?>

<div class="content-wrapper">

<section class="content-header">

<div class="container-fluid">

<div class="row mb-2">

<div class="col-sm-6">
<h1>Edit User</h1>
</div>

<div class="col-sm-6 text-right">

<a href="users.php"
class="btn btn-secondary">

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

Update User Details

</h3>

</div>

<div class="card-body">

<?php if($message != ''): ?>

<div class="alert alert-danger">

<?= $message ?>

</div>

<?php endif; ?>

<form method="POST">

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Username</label>

<input type="text"
name="username"
class="form-control"
value="<?= htmlspecialchars($user['username']); ?>"
required>

</div>

<div class="form-group">

<label>Full Name</label>

<input type="text"
name="full_name"
class="form-control"
value="<?= htmlspecialchars($user['full_name']); ?>"
required>

</div>

<div class="form-group">

<label>Email</label>

<input type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($user['email']); ?>">

</div>

<div class="form-group">

<label>Mobile</label>

<input type="text"
name="mobile"
class="form-control"
value="<?= htmlspecialchars($user['mobile']); ?>">

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Institute</label>

<input type="text"
name="institute"
class="form-control"
value="<?= htmlspecialchars($user['institute']); ?>">

</div>

<div class="form-group">

<label>Designation</label>

<input type="text"
name="designation"
class="form-control"
value="<?= htmlspecialchars($user['designation']); ?>">

</div>

<div class="form-group">

<label>Role</label>

<select name="role_id"
class="form-control"
required>

<?php while($role = mysqli_fetch_assoc($roles)): ?>

<option
value="<?= $role['id']; ?>"
<?= ($user['role_id'] == $role['id']) ? 'selected' : ''; ?>>

<?= htmlspecialchars($role['role_name']); ?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="form-group">

<label>Status</label>

<select name="status"
class="form-control">

<option value="active"
<?= ($user['status'] == 'active') ? 'selected' : ''; ?>>

Active

</option>

<option value="inactive"
<?= ($user['status'] == 'inactive') ? 'selected' : ''; ?>>

Inactive

</option>

</select>

</div>

</div>

</div>

<hr>

<button type="submit"
class="btn btn-primary">

<i class="fas fa-save"></i>
Update User

</button>

<a href="users.php"
class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

</section>

</div>

<?php include '../../includes/footer.php'; ?>
```
