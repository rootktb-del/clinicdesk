<?php
$pageTitle = "Doctors";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>Doctors</h1>
    </section>

    <section class="content">

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">All Doctors</h3>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Specialization</th>
                            <th>Fee</th>
                            <th>Available Days</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($doctors as $doc): ?>

                            <tr>

                                <td><?= $doc['id'] ?></td>

                                <td><?= htmlspecialchars($doc['name']) ?></td>

                                <td><?= htmlspecialchars($doc['email']) ?></td>

                                <td><?= htmlspecialchars($doc['phone'] ?? '-') ?></td>

                                <td>
                                    <span class="badge badge-info">
                                        <?= htmlspecialchars($doc['specialization']) ?>
                                    </span>
                                </td>

                                <td>
                                    $<?= htmlspecialchars($doc['consultation_fee']) ?>
                                </td>

                                <td>

                                    <?php
                                    $days = explode(',', $doc['available_days']);
                                    ?>

                                    <?php foreach ($days as $day): ?>
                                        <span class="badge badge-secondary">
                                            <?= trim($day) ?>
                                        </span>
                                    <?php endforeach; ?>

                                </td>

                                <td>

                                    <a href="index.php?page=doctors&action=edit&id=<?= $doc['id'] ?>"
                                       class="btn btn-sm btn-primary">
                                        Edit
                                    </a>

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