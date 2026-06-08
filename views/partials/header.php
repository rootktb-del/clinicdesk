<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = Auth::currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= APP_NAME ?? "ClinicDesk" ?> - <?= $pageTitle ?? "" ?></title>

    <link rel="stylesheet" href="public/assets/adminlte/css/adminlte.min.css">
    <link rel="stylesheet" href="public/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">