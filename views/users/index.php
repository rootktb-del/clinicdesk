<?php
$pageTitle = "Users";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>Users</h1>
    </section>

    <section class="content">

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">All Users</h3>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($users as $user): ?>

                            <tr>

                                <td><?= $user['id'] ?></td>

                                <td><?= htmlspecialchars($user['name']) ?></td>

                                <td><?= htmlspecialchars($user['email']) ?></td>

                                <td>
                                    <span class="badge badge-info">
                                        <?= htmlspecialchars($user['role']) ?>
                                    </span>
                                </td>

                                <td><?= htmlspecialchars($user['phone'] ?? '-') ?></td>

                                <td>
                                    <?php if ($user['is_active']): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>

                                <td>

                                    <!-- EDIT -->
                                    <a href="index.php?page=users&action=edit&id=<?= $user['id'] ?>"
                                       class="btn btn-sm btn-primary">
                                        Edit
                                    </a>

                                    <!-- TOGGLE ACTIVE -->
                                    <form method="POST"
                                          action="index.php?page=users&action=toggle"
                                          style="display:inline;">

                                        <input type="hidden" name="csrf_token"
                                               value="<?= CSRF::generateToken() ?>">

                                        <input type="hidden" name="id"
                                               value="<?= $user['id'] ?>">

                                        <button class="btn btn-sm btn-warning">
                                            Toggle
                                        </button>

                                    </form>

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