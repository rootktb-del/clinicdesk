<?php
$pageTitle = "Reports";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>Reports</h1>
    </section>

    <section class="content">

        <!-- FILTER CARD -->
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">Filter Reports</h3>
            </div>

            <div class="card-body">

                <form method="GET" action="index.php">

                    <input type="hidden" name="page" value="reports">

                    <div class="row">

                        <div class="col-md-3">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                   value="<?= $_GET['start_date'] ?? '' ?>">
                        </div>

                        <div class="col-md-3">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control"
                                   value="<?= $_GET['end_date'] ?? '' ?>">
                        </div>

                        <div class="col-md-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Doctor ID</label>
                            <input type="number" name="doctor_id" class="form-control"
                                   value="<?= $_GET['doctor_id'] ?? '' ?>">
                        </div>

                    </div>

                    <br>

                    <button class="btn btn-primary">
                        Filter
                    </button>

                    <a href="index.php?page=reports&action=export&start_date=<?= $_GET['start_date'] ?? '' ?>&end_date=<?= $_GET['end_date'] ?? '' ?>&status=<?= $_GET['status'] ?? '' ?>&doctor_id=<?= $_GET['doctor_id'] ?? '' ?>"
                       class="btn btn-success">

                        Export CSV
                    </a>

                </form>

            </div>

        </div>


        <!-- RESULTS TABLE -->
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">Report Results</h3>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Reason</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($reports)): ?>

                            <?php foreach ($reports as $r): ?>

                                <tr>

                                    <td><?= htmlspecialchars($r['patient_name'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($r['doctor_name'] ?? '-') ?></td>
                                    <td><?= $r['appt_date'] ?></td>
                                    <td><?= $r['appt_time'] ?></td>

                                    <td>
                                        <span class="badge badge-info">
                                            <?= $r['status'] ?>
                                        </span>
                                    </td>

                                    <td><?= htmlspecialchars($r['reason'] ?? '-') ?></td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No results found
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