<?php
require_once 'config/db.php';
session_start();
if(isset($_SESSION['user_id'])){header("Location: dashboard.php");exit();}
$message="";
if($_SERVER["REQUEST_METHOD"]=="POST"){
$username=trim($_POST['username']);
$password=$_POST['password'];
$sql="SELECT u.*,r.role_name FROM users u INNER JOIN roles r ON u.role_id=r.id WHERE u.username=? LIMIT 1";
$stmt=mysqli_prepare($conn,$sql);
mysqli_stmt_bind_param($stmt,"s",$username);
mysqli_stmt_execute($stmt);
$result=mysqli_stmt_get_result($stmt);
if($user=mysqli_fetch_assoc($result)){
 if($user['status']!='active'){$message="Account is inactive.";}
 elseif(password_verify($password,$user['password'])){
  $_SESSION['user_id']=$user['id'];
  $_SESSION['username']=$user['username'];
  $_SESSION['full_name']=$user['full_name'];
  $_SESSION['role']=$user['role_name'];
  $_SESSION['email']=$user['email'];
  $_SESSION['LAST_ACTIVITY']=time();
  header("Location: dashboard.php");exit();
 } else {$message="Invalid username or password.";}
}else{$message="Invalid username or password.";}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Intelligent Medical Equipment CMMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<style>
body{background:#eef5fb}
.hero{background:linear-gradient(135deg,#0d6efd,#0b7285);color:#fff;padding:40px;border-radius:16px}
footer{background:#0b2d4d;color:#fff;padding:20px;margin-top:30px}
.card{border-radius:16px}
</style>
</head>
<body>
<div class="container py-5">
<div class="row g-4">
<div class="col-lg-8">
<div class="hero shadow">
<h1>Intelligent Medical Equipment CMMS</h1>
<p class="lead">Open-source Healthcare Technology Management Platform</p>
<p>This project integrates CMMS, MEMM (Medical Equipment Monitoring Module), IoT, Predictive Maintenance and Equipment Health Cards for biomedical engineering departments.</p>
<p><strong>We welcome collaboration from biomedical engineers, developers, researchers and students.</strong></p>
<a class="btn btn-light" href="https://github.com/almorawaka/HTM_CMMS_2026-" target="_blank"><i class="fab fa-github"></i> GitHub Repository</a>
</div>
<div class="card mt-4 shadow">
<div class="card-header bg-success text-white">Suggestions</div>
<div class="card-body">
<form action="save_suggestion.php" method="POST">
<input class="form-control mb-3" name="name" placeholder="Name">
<input class="form-control mb-3" name="email" placeholder="Email">
<textarea class="form-control mb-3" rows="5" name="suggestion" placeholder="Your suggestion"></textarea>
<button class="btn btn-primary">Submit Suggestion</button>
</form>
</div></div></div>
<div class="col-lg-4">
<div class="card shadow">
<div class="card-header bg-primary text-white text-center"><h3>Login</h3></div>
<div class="card-body">
<?php if(isset($_GET['logout'])){?><div class="alert alert-success">Logged out successfully.</div><?php }?>
<?php if(isset($_GET['timeout'])){?><div class="alert alert-warning">Session expired.</div><?php }?>
<?php if($message!=""){?><div class="alert alert-danger"><?= htmlspecialchars($message)?></div><?php }?>
<form method="POST">
<label>Username</label>
<input type="text" name="username" class="form-control mb-3" required>
<label>Password</label>
<input type="password" name="password" class="form-control mb-3" required>
<button class="btn btn-primary w-100">Login</button>
</form>
</div></div></div></div></div>
<footer class="text-center">
<h5>Developed for the Global Biomedical Engineering Community</h5>
<p>Open-source Intelligent Medical Equipment CMMS</p>
</footer>
</body>
</html>
