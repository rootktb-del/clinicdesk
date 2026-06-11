<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/PrescriptionModel.php';

class DashboardController
{
    private Auth $auth;
    private UserModel $userModel;
    private DoctorModel $doctorModel;
    private AppointmentModel $appointmentModel;
    private PrescriptionModel $prescriptionModel;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->userModel = new UserModel();
        $this->doctorModel = new DoctorModel();
        $this->appointmentModel = new AppointmentModel();
        $this->prescriptionModel = new PrescriptionModel();
    }


    public function index(): void // show dashboard based on role
    {
        if (!$this->auth->check()) {
            Helpers::redirect("index.php?page=login");
        }

        $role = $this->auth->role();

        if ($role === "admin") {
            $this->adminDashboard();
        } elseif ($role === "doctor") {
            $this->doctorDashboard();
        } else {
            $this->patientDashboard();
        }
    }


    private function adminDashboard(): void// show admin dashboard
    {
        $totalUsers = $this->userModel->countAll();
        $totalDoctors = $this->userModel->countAll("doctor");
        $totalPatients = $this->userModel->countAll("patient");

        require __DIR__ . '/../views/dashboard/admin.php';
    }


    private function doctorDashboard(): void// show doctor dashboard
    {
        $doctor = $this->doctorModel->findByUserId($this->auth->currentUser()['id']);

        $todayAppointments = $this->appointmentModel->getByDoctor(
            $doctor['id'],
            1,
            ['today' => true]
        );

        $totalAppointments = $this->appointmentModel->countFiltered("doctor", $doctor['id'], []);
        $pendingAppointments = $this->appointmentModel->countFiltered("doctor", $doctor['id'], ['status' => 'pending']);
        $completedAppointments = $this->appointmentModel->countFiltered("doctor", $doctor['id'], ['status' => 'completed']);

        require __DIR__ . '/../views/dashboard/doctor.php';
    }


    private function patientDashboard(): void// show patient dashboard
    {
        $patientId = $this->auth->currentUser()['id'];

        $activeAppointments = $this->appointmentModel->countFiltered("patient", $patientId, ['active' => true]);
        $completedAppointments = $this->appointmentModel->countFiltered("patient", $patientId, ['status' => 'completed']);
        $totalPrescriptions = count($this->prescriptionModel->getByPatient($patientId));

        $nextAppointment = $this->appointmentModel->getByPatient($patientId, 1, ['next' => true])[0] ?? null;

        require __DIR__ . '/../views/dashboard/patient.php';
    }
}