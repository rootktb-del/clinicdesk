<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';

class AppointmentController
{
    private Auth $auth;
    private AppointmentModel $appointmentModel;
    private DoctorModel $doctorModel;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->appointmentModel = new AppointmentModel();
        $this->doctorModel = new DoctorModel();
    }


    public function index(): void
    {
        Auth::requireRole("admin", "doctor", "patient");

        $user = Auth::currentUser();
        $role = $user['role'];

        $page = (int)($_GET['p'] ?? 1);

        if ($role === "admin") {
            $appointments = $this->appointmentModel->getAll($page, $_GET);
        } elseif ($role === "doctor") {
            $doctor = $this->doctorModel->findByUserId($user['id']);
            $appointments = $this->appointmentModel->getByDoctor($doctor['id'], $page, $_GET);
        } else {
            $appointments = $this->appointmentModel->getByPatient($user['id'], $page, $_GET);
        }

        require __DIR__ . '/../views/appointments/index.php';
    }


    public function book(): void
    {
        Auth::requireRole("patient");

        $doctors = $this->doctorModel->getAll();

        require __DIR__ . '/../views/appointments/book.php';
    }


    public function store(): void
    {
        Auth::requireRole("patient");

        if (!Helpers::is_post()) {
            Helpers::redirect("index.php?page=appointments&action=book");
        }

        if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
            Helpers::flash("error", "Invalid request.");
            Helpers::redirect("index.php?page=appointments&action=book");
        }

        $patientId = Auth::currentUser()['id'];
        $doctorId = (int)($_POST['doctor_id'] ?? 0);
        $date = $_POST['appt_date'] ?? '';
        $time = $_POST['appt_time'] ?? '';
        $reason = trim($_POST['reason'] ?? '');

        if (!$doctorId || !$date || !$time) {
            Helpers::flash("error", "Missing required fields.");
            Helpers::redirect("index.php?page=appointments&action=book");
        }

        // conflict check
        if ($this->appointmentModel->hasConflict($doctorId, $date, $time)) {
            Helpers::flash("error", "This slot is already booked.");
            Helpers::redirect("index.php?page=appointments&action=book");
        }

        $data = [
            "patient_id" => $patientId,
            "doctor_id" => $doctorId,
            "appt_date" => $date,
            "appt_time" => $time,
            "reason" => $reason,
            "status" => "pending"
        ];

        $this->appointmentModel->book($data);

        Helpers::flash("success", "Appointment booked successfully.");
        Helpers::redirect("index.php?page=appointments");
    }


    public function updateStatus(): void
    {
        Auth::requireRole("doctor", "admin");

        if (!Helpers::is_post()) {
            Helpers::redirect("index.php?page=appointments");
        }

        if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
            Helpers::flash("error", "Invalid request.");
            Helpers::redirect("index.php?page=appointments");
        }

        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if (!in_array($status, ["pending", "confirmed", "completed", "cancelled"])) {
            Helpers::flash("error", "Invalid status.");
            Helpers::redirect("index.php?page=appointments");
        }

        $this->appointmentModel->updateStatus($id, $status);

        Helpers::flash("success", "Appointment updated.");
        Helpers::redirect("index.php?page=appointments");
    }


    public function cancel(): void
    {
        Auth::requireRole("patient");

        if (!Helpers::is_post()) {
            Helpers::redirect("index.php?page=appointments");
        }

        if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
            Helpers::flash("error", "Invalid request.");
            Helpers::redirect("index.php?page=appointments");
        }

        $id = (int)($_POST['id'] ?? 0);

        $this->appointmentModel->updateStatus($id, "cancelled");

        Helpers::flash("success", "Appointment cancelled.");
        Helpers::redirect("index.php?page=appointments");
    }
}