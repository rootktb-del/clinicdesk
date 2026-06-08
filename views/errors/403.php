<?php
$pageTitle = "403 Forbidden";

require_once BASE_PATH . "/../partials/header.php";
?>

<div class="content-wrapper">

    <section class="content">

        <div class="error-page">

            <h2 class="headline text-danger">403</h2>

            <div class="error-content">
                <h3>Access Denied</h3>

                <p>
                    You do not have permission to access this page.
                </p>

                <a href="index.php" class="btn btn-primary">
                    Back to Dashboard
                </a>
            </div>

        </div>

    </section>

</div>

<?php require_once BASE_PATH . "/../partials/footer.php"; ?>