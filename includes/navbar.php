<?php require_once __DIR__.'/../config/app.php'; ?>
    
<nav class="main-header navbar navbar-expand navbar-white navbar-light">

<ul class="navbar-nav">

<li class="nav-item">
<a class="nav-link" data-widget="pushmenu" href="#" role="button">
<i class="fas fa-bars"></i>
</a>
</li>

</ul>

<ul class="navbar-nav ml-auto">

<li class="nav-item">

<span class="nav-link">

<?= $_SESSION['full_name']; ?>

(
<?= $_SESSION['role']; ?>
)

</span>

</li>

<li class="nav-item">

<a href="<?= $base_url ?>/logout.php">

<i class="fas fa-sign-out-alt"></i>

Logout

</a>

</li>

</ul>

</nav>