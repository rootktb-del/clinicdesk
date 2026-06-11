<?php
$user = Auth::currentUser();
$role = $user['role'] ?? '';
$page = $_GET['page'] ?? 'dashboard';
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- BRAND -->
    <a href="index.php?page=dashboard" class="brand-link">
        <span class="brand-text font-weight-light">ClinicDesk</span>
    </a>

    <div class="sidebar">

        <!-- USER PANEL -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">

            <div class="info">
                <a href="#" class="d-block">
                    <?= htmlspecialchars($user['name'] ?? 'User') ?>
                </a>
                <small class="text-muted">
                    <?= htmlspecialchars($role) ?>
                </small>
            </div>

        </div>

        <nav class="mt-2">

            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">

                <!-- LOGOUT -->
                <li class="nav-item mt-3">

                    <form method="POST" action="index.php?page=logout">

                        <input type="hidden" name="csrf_token"
                               value="<?= CSRF::generateToken() ?>">

                        <button class="btn btn-danger btn-block">
                            Logout
                        </button>

                    </form>

                </li>

            </ul>

        </nav>

    </div>

</aside>