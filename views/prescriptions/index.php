<?php
$pageTitle = "My Prescriptions";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>My Prescriptions</h1>
    </section>

    <section class="content">

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">Prescription List</h3>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Diagnosis</th>
                            <th>Notes</th>
                            <th>File</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($prescriptions as $p): ?>

                            <tr>

                                <td>
                                    Doctor #<?= $p['doctor_user_id'] ?>
                                </td>

                                <td><?= $p['appt_date'] ?></td>

                                <td><?= $p['appt_time'] ?></td>

                                <td>
                                    <?= htmlspecialchars($p['diagnosis']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($p['notes'] ?? '-') ?>
                                </td>

                                <td>

                                    <?php if (!empty($p['file_path'])): ?>

                                        <a href="index.php?page=prescriptions&action=download&id=<?= $p['appointment_id'] ?>"
                                           class="btn btn-sm btn-success">

                                            Download PDF

                                        </a>

                                    <?php else: ?>

                                        <span class="text-muted">No file</span>

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