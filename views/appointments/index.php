<?php
$pageTitle = "Appointments";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>Appointments</h1>
    </section>

    <section class="content">

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">All Appointments</h3>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($appointments as $a): ?>

                            <tr>

                                <td><?= $a['id'] ?></td>

                                <td>
                                    <?= htmlspecialchars($a['patient_name'] ?? '') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($a['doctor_name'] ?? '') ?>
                                </td>

                                <td><?= $a['appt_date'] ?></td>
                                <td><?= $a['appt_time'] ?></td>

                                <td>
                                    <?php if ($a['status'] === 'pending'): ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php elseif ($a['status'] === 'confirmed'): ?>
                                        <span class="badge badge-info">Confirmed</span>
                                    <?php elseif ($a['status'] === 'completed'): ?>
                                        <span class="badge badge-success">Completed</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Cancelled</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($a['reason'] ?? '-') ?>
                                </td>

                                <td>

                                    <?php if (Auth::role() === 'doctor'): ?>

                                        <form method="POST" action="index.php?page=appointments&action=updateStatus">
                                            <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken() ?>">
                                            <input type="hidden" name="id" value="<?= $a['id'] ?>">

                                            <select name="status" class="form-control form-control-sm">

                                                <option value="confirmed">Confirm</option>
                                                <option value="completed">Complete</option>
                                                <option value="cancelled">Cancel</option>

                                            </select>

                                            <button class="btn btn-primary btn-sm mt-1">
                                                Update
                                            </button>
                                        </form>

                                    <?php endif; ?>


                                    <?php if (Auth::role() === 'patient' && $a['status'] === 'pending'): ?>

                                        <form method="POST" action="index.php?page=appointments&action=cancel">
                                            <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken() ?>">
                                            <input type="hidden" name="id" value="<?= $a['id'] ?>">

                                            <button class="btn btn-danger btn-sm">
                                                Cancel
                                            </button>
                                        </form>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </section>

</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>