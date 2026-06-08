<?php
$pageTitle = "Patient Dashboard";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>Patient Dashboard</h1>
    </section>

    <section class="content">

        <div class="row">

            <!-- ACTIVE APPOINTMENTS -->
            <div class="col-lg-3 col-6">

                <div class="small-box bg-info">

                    <div class="inner">
                        <h3><?= $activeAppointments ?? 0 ?></h3>
                        <p>Active Appointments</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-calendar"></i>
                    </div>

                </div>

            </div>

            <!-- COMPLETED APPOINTMENTS -->
            <div class="col-lg-3 col-6">

                <div class="small-box bg-success">

                    <div class="inner">
                        <h3><?= $completedAppointments ?? 0 ?></h3>
                        <p>Completed</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-check"></i>
                    </div>

                </div>

            </div>

            <!-- PRESCRIPTIONS -->
            <div class="col-lg-3 col-6">

                <div class="small-box bg-warning">

                    <div class="inner">
                        <h3><?= $prescriptionsCount ?? 0 ?></h3>
                        <p>Prescriptions</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-file-medical"></i>
                    </div>

                </div>

            </div>

            <!-- PLACEHOLDER BOX -->
            <div class="col-lg-3 col-6">

                <div class="small-box bg-danger">

                    <div class="inner">
                        <h3><?= $upcomingAppointmentCount ?? 0 ?></h3>
                        <p>Upcoming</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>

                </div>

            </div>

        </div>


        <!-- NEXT APPOINTMENT CARD -->
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">Next Appointment</h3>
            </div>

            <div class="card-body">

                <?php if (!empty($nextAppointment)): ?>

                    <p><b>Doctor:</b> <?= htmlspecialchars($nextAppointment['doctor_name']) ?></p>
                    <p><b>Date:</b> <?= $nextAppointment['appt_date'] ?></p>
                    <p><b>Time:</b> <?= $nextAppointment['appt_time'] ?></p>
                    <p><b>Status:</b> <?= $nextAppointment['status'] ?></p>

                <?php else: ?>

                    <p class="text-muted">No upcoming appointments</p>

                <?php endif; ?>

            </div>

        </div>


        <!-- RECENT APPOINTMENTS -->
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">Recent Appointments</h3>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($recentAppointments)): ?>

                            <?php foreach ($recentAppointments as $a): ?>

                                <tr>

                                    <td><?= htmlspecialchars($a['doctor_name']) ?></td>
                                    <td><?= $a['appt_date'] ?></td>
                                    <td><?= $a['appt_time'] ?></td>

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

    </section>

</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>