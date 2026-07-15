<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $username    = trim($_POST['username']);
    $full_name   = trim($_POST['full_name']);
    $email       = trim($_POST['email']);
    $mobile      = trim($_POST['mobile']);
    $institute   = trim($_POST['institute']);
    $designation = trim($_POST['designation']);
    $role_id     = intval($_POST['role_id']);
    $password    = $_POST['password'];

    $hashed_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $sql = "
    INSERT INTO users
    (
        username,
        password,
        full_name,
        email,
        mobile,
        institute,
        designation,
        role_id,
        status
    )
    VALUES
    (
        '$username',
        '$hashed_password',
        '$full_name',
        '$email',
        '$mobile',
        '$institute',
        '$designation',
        '$role_id',
        'active'
    )
    ";

    if(mysqli_query($conn,$sql))
    {
        header("Location: users.php");
        exit;
    }
    else
    {
        $message = "Error creating user";
    }
}

$roles = mysqli_query(
    $conn,
    "SELECT id, role_name FROM roles ORDER BY role_name"
);

?>

<div class="content-wrapper">

<section class="content-header">
<div class="container-fluid">
<h1>Add New User</h1>
</div>
</section>

<section class="content">

<div class="container-fluid">

<div class="card">

<div class="card-header">
<h3 class="card-title">User Details</h3>
</div>

<div class="card-body">

<?php if($message != ''): ?>
<div class="alert alert-danger">
<?= $message; ?>
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
required>
</div>

<div class="form-group">
<label>Full Name</label>
<input type="text"
name="full_name"
class="form-control"
required>
</div>

<div class="form-group">
<label>Email</label>
<input type="email"
name="email"
class="form-control">
</div>

<div class="form-group">
<label>Mobile</label>
<input type="text"
name="mobile"
class="form-control">
</div>

</div>

<div class="col-md-6">

<div class="form-group">
<label>Institute</label>
<input type="text"
name="institute"
class="form-control">
</div>

<div class="form-group">
<label>Designation</label>
<input type="text"
name="designation"
class="form-control">
</div>

<div class="form-group">
<label>Role</label>
<select
name="role_id"
class="form-control"
required>

<option value="">
Select Role
</option>

<?php while($role = mysqli_fetch_assoc($roles)): ?>

<option value="<?= $role['id']; ?>">
<?= $role['role_name']; ?>
</option>

<?php endwhile; ?>

</select>
</div>

<div class="form-group">
<label>Password</label>
<input type="password"
name="password"
class="form-control"
required>
</div>

</div>

</div>

<button type="submit"
class="btn btn-success">

<i class="fas fa-save"></i>
Save User

</button>

<a href="users.php"
class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>

</section>

</div>

<?php include '../../includes/footer.php'; ?>