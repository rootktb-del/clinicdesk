<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/SpecializationModel.php';

class DoctorController
{
    private Auth $auth;
    private DoctorModel $doctorModel;
    private UserModel $userModel;
    private SpecializationModel $specializationModel;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->doctorModel = new DoctorModel();
        $this->userModel = new UserModel();
        $this->specializationModel = new SpecializationModel();
    }


    public function index(): void
    {
        Auth::requireRole("admin");

        $doctors = $this->doctorModel->getAllPaginated();

        require __DIR__ . '/../views/doctors/index.php';
    }


    public function create(): void
    {
        Auth::requireRole("admin");

        $users = $this->userModel->getAll(); // users not already doctors (optional filter)
        $specializations = $this->specializationModel->getAll();

        require __DIR__ . '/../views/doctors/create.php';
    }


    public function store(): void
    {
        Auth::requireRole("admin");

        if (!Helpers::is_post()) {
            Helpers::redirect("index.php?page=doctors");
        }

        if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
            Helpers::flash("error", "Invalid request.");
            Helpers::redirect("index.php?page=doctors&action=create");
        }

        $userId = (int)($_POST['user_id'] ?? 0);
        $specId = (int)($_POST['specialization_id'] ?? 0);
        $fee = (float)($_POST['consultation_fee'] ?? 0);
        $bio = trim($_POST['bio'] ?? '');

        $days = $_POST['available_days'] ?? [];
        $availableDays = implode(",", $days);

        if (!$userId || !$specId) {
            Helpers::flash("error", "Missing required fields.");
            Helpers::redirect("index.php?page=doctors&action=create");
        }

        $data = [
            "user_id" => $userId,
            "specialization_id" => $specId,
            "consultation_fee" => $fee,
            "bio" => $bio,
            "available_days" => $availableDays
        ];

        $this->doctorModel->create($data);

        Helpers::flash("success", "Doctor created successfully.");
        Helpers::redirect("index.php?page=doctors");
    }


    public function edit(): void
    {
        Auth::requireRole("admin");

        $id = (int)($_GET['id'] ?? 0);

        $doctor = $this->doctorModel->findByUserId($id);

        if (!$doctor) {
            Helpers::redirect("index.php?page=404");
        }

        $specializations = $this->specializationModel->getAll();

        require __DIR__ . '/../views/doctors/edit.php';
    }


    public function update(): void
    {
        Auth::requireRole("admin");

        if (!Helpers::is_post()) {
            Helpers::redirect("index.php?page=doctors");
        }

        if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
            Helpers::flash("error", "Invalid request.");
            Helpers::redirect("index.php?page=doctors");
        }

        $id = (int)($_POST['id'] ?? 0);

        $specId = (int)($_POST['specialization_id'] ?? 0);
        $fee = (float)($_POST['consultation_fee'] ?? 0);
        $bio = trim($_POST['bio'] ?? '');

        $days = $_POST['available_days'] ?? [];
        $availableDays = implode(",", $days);

        $data = [
            "specialization_id" => $specId,
            "consultation_fee" => $fee,
            "bio" => $bio,
            "available_days" => $availableDays
        ];

        $this->doctorModel->update($id, $data);

        Helpers::flash("success", "Doctor updated.");
        Helpers::redirect("index.php?page=doctors");
    }
}