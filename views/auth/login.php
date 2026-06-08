<?php
$pageTitle = "Login";

require_once __DIR__ . "/../partials/header.php";
?>

<div class="login-page">

    <div class="login-box">

        <div class="login-logo">
            <b>Clinic</b>Desk
        </div>

        <div class="card">

            <div class="card-body login-card-body">

                <p class="login-box-msg">Sign in to start your session</p>

                <?php if (isset($_SESSION['flash'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['flash']['error'] ?? '') ?>
                    </div>
                    <?php unset($_SESSION['flash']); ?>
                <?php endif; ?>

                <form method="POST" action="index.php?page=auth&action=login">

                    <input type="hidden" name="csrf_token"
                           value="<?= CSRF::generateToken() ?>">

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control"
                               placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control"
                               placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                Sign In
                            </button>
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>