<?php
$user = Auth::currentUser();
$role = $user['role'] ?? 'guest';
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- LEFT SIDE -->
    <ul class="navbar-nav">

        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="index.php?page=dashboard" class="nav-link">Home</a>
        </li>

    </ul>

    <!-- RIGHT SIDE -->
    <ul class="navbar-nav ml-auto">

        <!-- USER INFO -->
        <li class="nav-item dropdown">

            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
                <?= htmlspecialchars($user['name'] ?? 'User') ?>
            </a>

            <div class="dropdown-menu dropdown-menu-right">

                <span class="dropdown-item-text">
                    Role: <b><?= htmlspecialchars($role) ?></b>
                </span>

                <div class="dropdown-divider"></div>

                <!-- LOGOUT FORM -->
                <form method="POST" action="index.php?page=logout" class="px-3">

                    <input type="hidden" name="csrf_token"
                           value="<?= CSRF::generateToken() ?>">

                    <button class="btn btn-danger btn-sm btn-block">
                        Logout
                    </button>

                </form>

            </div>

        </li>

    </ul>

</nav>