<?php
$pageTitle = "404 Not Found";

require_once BASE_PATH . "/../partials/header.php";
?>

<div class="content-wrapper">

    <section class="content">

        <div class="error-page">

            <h2 class="headline text-warning">404</h2>

            <div class="error-content">
                <h3>Page Not Found</h3>

                <p>
                    The page you requested does not exist.
                </p>

                <a href="index.php" class="btn btn-primary">
                    Back to Dashboard
                </a>
            </div>

        </div>

    </section>

</div>

<?php require_once BASE_PATH . "/../partials/footer.php"; ?>