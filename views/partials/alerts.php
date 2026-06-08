<?php
$success = getFlash("success");
$error = getFlash("error");
?>

<?php if ($success): ?>
    <div class="alert alert-success">
        <?= sanitize($success) ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger">
        <?= sanitize($error) ?>
    </div>
<?php endif; ?>