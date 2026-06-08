<?php

session_start();

require __DIR__ . '/controllers/AuthController.php';
require __DIR__ . '/controllers/DashboardController.php';
require __DIR__ . '/controllers/UserController.php';
require __DIR__ . '/controllers/DoctorController.php';
require __DIR__ . '/controllers/AppointmentController.php';
require __DIR__ . '/controllers/PrescriptionController.php';
require __DIR__ . '/controllers/ReportController.php';


$page = $_GET['page'] ?? 'login';
$action = $_GET['action'] ?? 'index';

$authController = new AuthController();
$dashboardController = new DashboardController();
$userController = new UserController();
$doctorController = new DoctorController();
$appointmentController = new AppointmentController();
$prescriptionController = new PrescriptionController();
$reportController = new ReportController();

switch ($page) {

    case 'login':
        if ($action === 'post') {
            $authController->login();
        } else {
            $authController->showLogin();
        }
        break;

    case 'logout':
        $authController->logout();
        break;

    // ---------------- DASHBOARD ----------------
    case 'dashboard':
        $dashboardController->index();
        break;

    // ---------------- USERS (ADMIN) ----------------
    case 'users':
        if ($action === 'toggle') {
            $userController->toggle();
        } else {
            $userController->index();
        }
        break;

    // ---------------- DOCTORS ----------------
    case 'doctors':
        if ($action === 'create') {
            $doctorController->create();
        } elseif ($action === 'edit') {
            $doctorController->edit();
        } else {
            $doctorController->index();
        }
        break;

    // ---------------- APPOINTMENTS ----------------
    case 'appointments':

        if ($action === 'book') {
            $appointmentController->book();
        } elseif ($action === 'updateStatus') {
            $appointmentController->updateStatus();
        } else {
            $appointmentController->index();
        }

        break;

    // ---------------- PRESCRIPTIONS ----------------
    case 'prescriptions':

        if ($action === 'create') {
            $prescriptionController->create();
        } elseif ($action === 'store') {
            $prescriptionController->store();
        } elseif ($action === 'download') {
            $prescriptionController->download();
        } else {
            $prescriptionController->index();
        }

        break;

    // ---------------- REPORTS ----------------
    case 'reports':

        if ($action === 'export') {
            $reportController->export();
        } else {
            $reportController->index();
        }

        break;

    // ---------------- DEFAULT ----------------
    default:
        redirect("views/errors/404.php");
        break;
}