<?php
$user = Auth::currentUser();
$role = $user['role'] ?? 'guest';
?>

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">


        <ul class="nav nav-pills nav-sidebar flex-column" role="menu">

                <!-- DASHBOARD (ALL ROLES) -->
                <li class="nav-item">
                    <a href="index.php?page=dashboard"
                       class="nav-link <?= $page === 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <?php if ($role === 'admin'): ?>

                    <li class="nav-header">ADMIN</li>

                    <li class="nav-item">
                        <a href="index.php?page=users"
                           class="nav-link <?= $page === 'users' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?page=doctors"
                           class="nav-link <?= $page === 'doctors' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-user-md"></i>
                            <p>Doctors</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?page=appointments"
                           class="nav-link <?= $page === 'appointments' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-calendar"></i>
                            <p>Appointments</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?page=reports"
                           class="nav-link <?= $page === 'reports' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Reports</p>
                        </a>
                    </li>

                <?php elseif ($role === 'doctor'): ?>

                    <li class="nav-header">DOCTOR</li>

                    <li class="nav-item">
                        <a href="index.php?page=appointments"
                           class="nav-link <?= $page === 'appointments' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p>My Schedule</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?page=prescriptions"
                           class="nav-link <?= $page === 'prescriptions' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-notes-medical"></i>
                            <p>Prescriptions</p>
                        </a>
                    </li>

                <?php elseif ($role === 'patient'): ?>

                    <li class="nav-header">PATIENT</li>

                    <li class="nav-item">
                        <a href="index.php?page=appointments"
                           class="nav-link <?= $page === 'appointments' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-calendar-plus"></i>
                            <p>Appointments</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?page=prescriptions"
                           class="nav-link <?= $page === 'prescriptions' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-file-medical"></i>
                            <p>My Prescriptions</p>
                        </a>
                    </li>

                <?php endif; ?>
        </ul>

    </nav>