<?php
require_once __DIR__ . '/../config/app.php';
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Logo -->
    <a href="<?= $base_url ?>dashboard.php" class="brand-link">
        <i class="fas fa-hospital-alt ml-2 mr-2"></i>
        <span class="brand-text font-weight-light">
            CMMS
        </span>
    </a>

    <div class="sidebar">

        <nav class="mt-2">

            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                <!-- Dashboard -->

                <li class="nav-item">
                    <a href="<?= $base_url ?>dashboard.php"
                       class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Equipment -->

                <li class="nav-item has-treeview">

                    <a href="#" class="nav-link">

                        <i class="nav-icon fas fa-hospital"></i>

                        <p>
                            Equipment
                            <i class="right fas fa-angle-left"></i>
                        </p>

                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="<?= $base_url ?>modules/equipment/equipment.php"
                               class="nav-link">

                                <i class="fas fa-stethoscope nav-icon"></i>

                                <p>Equipment List</p>

                            </a>

                        </li>

                    </ul>

                </li>

                <!-- Maintenance -->

                <li class="nav-item has-treeview">

                    <a href="#" class="nav-link">

                        <i class="nav-icon fas fa-tools"></i>

                        <p>
                            Maintenance
                            <i class="right fas fa-angle-left"></i>
                        </p>

                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="<?= $base_url ?>modules/maintenance/maintenance.php"
                               class="nav-link">

                                <i class="fas fa-wrench nav-icon"></i>

                                <p>Repair Requests</p>

                            </a>

                        </li>

                        <li class="nav-item">

                            <a href="<?= $base_url ?>modules/maintenance/work-orders.php"
                               class="nav-link">

                                <i class="fas fa-clipboard-list nav-icon"></i>

                                <p>Work Orders</p>

                            </a>

                        </li>

                        <li class="nav-item">

                            <a href="<?= $base_url ?>modules/maintenance/pm-schedule.php"
                               class="nav-link">

                                <i class="fas fa-calendar-check nav-icon"></i>

                                <p>PM Schedule</p>

                            </a>

                        </li>

                    </ul>

                </li>



                <!-- LIVE MONITORING -->






                <li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-heartbeat"></i>
        <p>
            Live Monitoring
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>

    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="<?= $base_url ?>modules/live-monitoring/live-equipment-dashboard.php"
               class="nav-link">
                <i class="fas fa-desktop nav-icon"></i>
                <p>Dashboard</p>
            </a>
        </li>

 

        <li class="nav-item">

            <a href="<?= $base_url ?>modules/live-monitoring/monitored-equipment.php"
               class="nav-link">

                <i class="fas fa-heartbeat nav-icon"></i>

                <p>Monitored Equipment</p>

            </a>

        </li>

    </ul>
</li>

        

   









                <!-- Inventory -->

                <li class="nav-item has-treeview">

                    <a href="#" class="nav-link">

                        <i class="nav-icon fas fa-boxes"></i>

                        <p>
                            Inventory
                            <i class="right fas fa-angle-left"></i>
                        </p>

                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="<?= $base_url ?>modules/inventory/inventory.php"
                               class="nav-link">

                                <i class="fas fa-box nav-icon"></i>

                                <p>Spare Parts</p>

                            </a>

                        </li>

                    </ul>

                </li>

                <!-- Master Data -->

                <li class="nav-item has-treeview">

                    <a href="#" class="nav-link">

                        <i class="nav-icon fas fa-database"></i>

                        <p>
                            Master Data
                            <i class="right fas fa-angle-left"></i>
                        </p>

                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="<?= $base_url ?>modules/master/categories.php"
                               class="nav-link">

                                <i class="fas fa-tags nav-icon"></i>

                                <p>Equipment Categories</p>

                            </a>

                        </li>

                       

                        <li class="nav-item">

                            <a href="<?= $base_url ?>modules/master/institutes.php"
                               class="nav-link">

                                <i class="fas fa-hospital-user nav-icon"></i>

                                <p>Institutes</p>

                            </a>

                        </li>

                        <li class="nav-item">

                            <a href="<?= $base_url ?>modules/master/departments.php"
                               class="nav-link">

                                <i class="fas fa-building nav-icon"></i>

                                <p>Departments</p>

                            </a>

                        </li>



                         <li class="nav-item">

                            <a href="<?= $base_url ?>modules/master/manufacturers.php"
                               class="nav-link">

                                <i class="fas fa-industry nav-icon"></i>

                                <p>Manufacturers</p>

                            </a>

                        </li>
                        

                        <li class="nav-item">

                            <a href="<?= $base_url ?>modules/master/vendors.php"
                               class="nav-link">

                                <i class="fas fa-truck nav-icon"></i>

                                <p>Vendors</p>

                            </a>

                        </li>

                    </ul>

                </li>

                <!-- Users -->

                <li class="nav-item">

                    <a href="<?= $base_url ?>modules/users/users.php"
                       class="nav-link">

                        <i class="nav-icon fas fa-users"></i>

                        <p>Users</p>

                    </a>

                </li>

            </ul>

        </nav>

    </div>

</aside>