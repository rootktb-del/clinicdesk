<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';

class ReportController
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


    public function index(): void // show report filters and results for admin
    {
        Auth::requireRole("admin");

        $filters = [];

        $startDate = $_GET['start_date'] ?? '';
        $endDate = $_GET['end_date'] ?? '';
        $doctorId = (int)($_GET['doctor_id'] ?? 0);
        $status = $_GET['status'] ?? '';

        if ($startDate && $endDate) {
            $filters['start_date'] = $startDate;
            $filters['end_date'] = $endDate;
        }

        if ($doctorId) {
            $filters['doctor_id'] = $doctorId;
        }

        if ($status) {
            $filters['status'] = $status;
        }

        $reports = $this->appointmentModel->getAll(1, $filters);

        require __DIR__ . '/../views/reports/index.php';
    }


    public function export(): void // export report as CSV for admin
    {
        Auth::requireRole("admin");

        $startDate = $_GET['start_date'] ?? '';
        $endDate = $_GET['end_date'] ?? '';
        $doctorId = (int)($_GET['doctor_id'] ?? 0);
        $status = $_GET['status'] ?? '';

        if (!$startDate || !$endDate) {
            Helpers::flash("error", "Date range required.");
            Helpers::redirect("index.php?page=reports");
        }

        $filters = [
            "start_date" => $startDate,
            "end_date" => $endDate,
            "doctor_id" => $doctorId,
            "status" => $status
        ];

        $reports = $this->appointmentModel->getAll(1, $filters);

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=clinic_report.csv");

        $output = fopen("php://output", "w");

        fputcsv($output, [
            "Patient",
            "Doctor",
            "Date",
            "Time",
            "Status",
            "Reason"
        ]);

        foreach ($reports as $row) {
            fputcsv($output, [
                $row['patient_name'] ?? '',
                $row['doctor_name'] ?? '',
                $row['appt_date'],
                $row['appt_time'],
                $row['status'],
                $row['reason']
            ]);
        }

        fclose($output);
        exit;
    }
}