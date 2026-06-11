<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/PrescriptionModel.php';

class PrescriptionController
{
    private Auth $auth;
    private AppointmentModel $appointmentModel;
    private PrescriptionModel $prescriptionModel;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->appointmentModel = new AppointmentModel();
        $this->prescriptionModel = new PrescriptionModel();
    }


    public function create(): void // show prescription create form for doctor
    {
        Auth::requireRole("doctor");

        $appointmentId = (int)($_GET['id'] ?? 0);

        $appointment = $this->appointmentModel->findById($appointmentId);

        if (!$appointment) {
            Helpers::redirect("index.php?page=404");
        }

        require __DIR__ . '/../views/prescriptions/create.php';
    }


    public function store(): void // doctor store prescription form processing
    {
        Auth::requireRole("doctor");

        if (!Helpers::is_post()) {
            Helpers::redirect("index.php?page=appointments");
        }

        if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
            Helpers::flash("error", "Invalid request.");
            Helpers::redirect("index.php?page=appointments");
        }

        $appointmentId = (int)($_POST['appointment_id'] ?? 0);
        $diagnosis = trim($_POST['diagnosis'] ?? '');
        $medications = trim($_POST['medications'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        $appointment = $this->appointmentModel->findById($appointmentId);

        if (!$appointment) {
            Helpers::flash("error", "Appointment not found.");
            Helpers::redirect("index.php?page=appointments");
        }

        // basic file upload
        $filePath = null;

        if (!empty($_FILES['prescription_file']['name'])) {

            $file = $_FILES['prescription_file'];

            if ($file['type'] !== "application/pdf") {
                Helpers::flash("error", "Only PDF files allowed.");
                Helpers::redirect("index.php?page=appointments");
            }

            if ($file['size'] > 3 * 1024 * 1024) {
                Helpers::flash("error", "File too large (max 3MB).");
                Helpers::redirect("index.php?page=appointments");
            }

            $fileName = "prescription_" . $appointmentId . "_" . time() . ".pdf";
            $uploadDir = __DIR__ . "/../public/uploads/prescriptions/";

            move_uploaded_file($file['tmp_name'], $uploadDir . $fileName);

            $filePath = $fileName;
        }

        $data = [
            "appointment_id" => $appointmentId,
            "diagnosis" => $diagnosis,
            "medications" => $medications,
            "notes" => $notes,
            "file_path" => $filePath
        ];

        $this->prescriptionModel->create($data);

        Helpers::flash("success", "Prescription added.");
        Helpers::redirect("index.php?page=appointments");
    }


    public function download(): void // download prescription file for doctor and patient
    {
        Auth::requireRole("admin", "doctor", "patient");

        $id = (int)($_GET['id'] ?? 0);

        $prescription = $this->prescriptionModel->findByAppointmentId($id);

        if (!$prescription || !$prescription['file_path']) {
            Helpers::flash("error", "File not found.");
            Helpers::redirect("index.php?page=appointments");
        }

        $file = __DIR__ . "/../public/uploads/prescriptions/" . $prescription['file_path'];

        if (!file_exists($file)) {
            Helpers::flash("error", "File missing on server.");
            Helpers::redirect("index.php?page=appointments");
        }

        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=prescription.pdf");

        readfile($file);
        exit;
    }
}