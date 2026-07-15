<?php

function hasRole($role)
{
    return isset($_SESSION['role']) &&
           $_SESSION['role'] === $role;
}

function hasAnyRole(array $roles)
{
    return isset($_SESSION['role']) &&
           in_array($_SESSION['role'], $roles);
}

function requireRole($role)
{
    if (!hasRole($role)) {

        header("Location: dashboard.php?error=access_denied");
        exit();

    }
}

function requireAnyRole(array $roles)
{
    if (!hasAnyRole($roles)) {

        header("Location: dashboard.php?error=access_denied");
        exit();

    }
}