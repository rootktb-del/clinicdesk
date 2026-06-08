<?php
$pageTitle = "Doctor Dashboard";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>Doctor Dashboard</h1>
    </section>

    <section class="content">

        <div class="row">

            <!-- TODAY -->
            <div class="col-lg-3 col-6">

                <div class="small-box bg-primary">

                    <div class="inner">
                        <h3><?= $todayCount ?? 0 ?></h3>
                        <p>Today's Appointments</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>

                </div>

            </div>

            <!-- PENDING -->
            <div class="col-lg-3 col-6">

                <div class="small-box bg-warning">

                    <div class="inner">
                        <h3><?= $pendingCount ?? 0 ?></h3>
                        <p>Pending</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>

                </div>

            </div>

            <!-- COMPLETED -->
            <div class="col-lg-3 col-6">

                <div class="small-box bg-success">

                    <div class="inner">
                        <h3><?= $completedCount ?? 0 ?></h3>
                        <p>Completed</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-check"></i>
                    </div>

                </div>

            </div>

            <!-- TOTAL -->
            <div class="col-lg-3 col-6">

                <div class="small-box bg-info">

                    <div class="inner">
                        <h3><?= $totalAppointments ?? 0 ?></h3>
                        <p>Total Appointments</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-user-md"></i>
                    </div>

                </div>

            </div>

        </div>


        <!-- TODAY'S APPOINTMENTS -->
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">Today's Schedule</h3>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($todayAppointments)): ?>

                            <?php foreach ($todayAppointments as $a): ?>

                                <tr>

                                    <td><?= htmlspecialchars($a['patient_name']) ?></td>
                                    <td><?= $a['appt_time'] ?></td>

                                    <td>
                                        <span class="badge badge-info">
                                            <?= $a['status'] ?>
                                        </span>
                                    </td>

                                    <td><?= htmlspecialchars($a['reason'] ?? '-') ?></td>

                                    <td>

                                        <!-- STATUS UPDATE FORM -->
                                        <form method="POST"
                                              action="index.php?page=appointments&action=updateStatus"
                                              style="display:inline;">

                                            <input type="hidden" name="csrf_token"
                                                   value="<?= CSRF::generateToken() ?>">

                                            <input type="hidden" name="id"
                                                   value="<?= $a['id'] ?>">

                                            <select name="status" class="form-control form-control-sm">

                                                <option value="confirmed">Confirm</option>
                                                <option value="completed">Complete</option>
                                                <option value="cancelled">Cancel</option>

                                            </select>

                                            <button class="btn btn-sm btn-primary mt-1">
                                                Update
                                            </button>

                                        </form>

                                        <!-- ADD PRESCRIPTION -->
                                        <?php if ($a['status'] === 'completed'): ?>

                                            <a href="index.php?page=prescriptions&action=create&id=<?= $a['id'] ?>"
                                               class="btn btn-sm btn-success mt-1">

                                                Prescription

                                            </a>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No appointments today
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- UPCOMING -->
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">Upcoming Appointments</h3>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($upcomingAppointments)): ?>

                            <?php foreach ($upcomingAppointments as $a): ?>

                                <tr>

                                    <td><?= htmlspecialchars($a['patient_name']) ?></td>
                                    <td><?= $a['appt_date'] ?></td>
                                    <td><?= $a['appt_time'] ?></td>

                                    <td>
                                        <span class="badge badge-warning">
                                            <?= $a['status'] ?>
                                        </span>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No upcoming appointments
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