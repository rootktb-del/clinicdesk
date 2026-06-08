<?php
$pageTitle = "Admin Dashboard";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>Admin Dashboard</h1>
    </section>

    <section class="content">

        <!-- STATS ROW -->
        <div class="row">

            <!-- TOTAL USERS -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">

                    <div class="inner">
                        <h3><?= $statsByRole['users'] ?? 0 ?></h3>
                        <p>Total Users</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>

                </div>
            </div>

            <!-- DOCTORS -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">

                    <div class="inner">
                        <h3><?= $statsByRole['doctors'] ?? 0 ?></h3>
                        <p>Doctors</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-user-md"></i>
                    </div>

                </div>
            </div>

            <!-- PATIENTS -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">

                    <div class="inner">
                        <h3><?= $statsByRole['patients'] ?? 0 ?></h3>
                        <p>Patients</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-procedures"></i>
                    </div>

                </div>
            </div>

            <!-- TODAY APPOINTMENTS -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">

                    <div class="inner">
                        <h3><?= $todayAppointmentsCount ?? 0 ?></h3>
                        <p>Today's Appointments</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>

                </div>
            </div>

        </div>


        <!-- QUICK INFO BOXES -->
        <div class="row">

            <div class="col-md-6">

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">System Overview</h3>
                    </div>

                    <div class="card-body">

                        <ul>
                            <li><b>Total Users:</b> <?= $statsByRole['users'] ?? 0 ?></li>
                            <li><b>Doctors:</b> <?= $statsByRole['doctors'] ?? 0 ?></li>
                            <li><b>Patients:</b> <?= $statsByRole['patients'] ?? 0 ?></li>
                            <li><b>Appointments Today:</b> <?= $todayAppointmentsCount ?? 0 ?></li>
                        </ul>

                    </div>

                </div>

            </div>

            <!-- RECENT APPOINTMENTS -->
            <div class="col-md-6">

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">Recent Appointments</h3>
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered">

                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if (!empty($recentAppointments)): ?>

                                    <?php foreach ($recentAppointments as $a): ?>

                                        <tr>

                                            <td><?= htmlspecialchars($a['patient_name']) ?></td>
                                            <td><?= htmlspecialchars($a['doctor_name']) ?></td>
                                            <td><?= $a['appt_date'] ?></td>

                                            <td>
                                                <span class="badge badge-info">
                                                    <?= $a['status'] ?>
                                                </span>
                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No appointments found
                                        </td>
                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>