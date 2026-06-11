<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    private Auth $auth;
    private UserModel $userModel;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->userModel = new UserModel();
    }


    public function index(): void // show paginated user list with role filter for admin
    {
        Auth::requireRole("admin");

        $page = (int)($_GET['p'] ?? 1);
        $role = $_GET['role'] ?? '';

        $users = $this->userModel->getAllPaginated($page, $role);

        require __DIR__ . '/../views/users/index.php';
    }


    public function create(): void // show user create form for admin
    {
        Auth::requireRole("admin");

        require __DIR__ . '/../views/users/create.php';
    }


    public function store(): void// store new user record from user create form
    {
        Auth::requireRole("admin");

        if (!Helpers::is_post()) {
            Helpers::redirect("index.php?page=users");
        }

        if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
            Helpers::flash("error", "Invalid request.");
            Helpers::redirect("index.php?page=users&action=create");
        }

        $name = trim($_POST['name'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $role = $_POST['role'] ?? 'patient';
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$name || !$email || !$password) {
            Helpers::flash("error", "Missing required fields.");
            Helpers::redirect("index.php?page=users&action=create");
        }

        $existing = $this->userModel->findByEmail($email);

        if ($existing) {
            Helpers::flash("error", "Email already exists.");
            Helpers::redirect("index.php?page=users&action=create");
        }

        $data = [
            "name" => $name,
            "email" => $email,
            "role" => $role,
            "phone" => $phone,
            "password" => password_hash($password, PASSWORD_BCRYPT)
        ];

        $this->userModel->create($data);

        Helpers::flash("success", "User created successfully.");
        Helpers::redirect("index.php?page=users");
    }


    public function edit(): void // show user edit form for admin
    {
        Auth::requireRole("admin");

        $id = (int)($_GET['id'] ?? 0);

        $user = $this->userModel->findById($id);

        if (!$user) {
            Helpers::redirect("index.php?page=404");
        }

        require __DIR__ . '/../views/users/edit.php';
    }


    public function update(): void // admin update user record from the user edit form
    {
        Auth::requireRole("admin");

        if (!Helpers::is_post()) {
            Helpers::redirect("index.php?page=users");
        }

        if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
            Helpers::flash("error", "Invalid request.");
            Helpers::redirect("index.php?page=users");
        }

        $id = (int)($_POST['id'] ?? 0);

        $user = $this->userModel->findById($id);

        if (!$user) {
            Helpers::flash("error", "User not found.");
            Helpers::redirect("index.php?page=users");
        }

        $data = [
            "name" => trim($_POST['name'] ?? ''),
            "phone" => trim($_POST['phone'] ?? '')
        ];

        $this->userModel->update($id, $data);

        Helpers::flash("success", "User updated.");
        Helpers::redirect("index.php?page=users");
    }


    public function toggle(): void // admin toggle/untoggle user active status
    {
        Auth::requireRole("admin");

        if (!Helpers::is_post()) {
            Helpers::redirect("index.php?page=users");
        }

        if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
            Helpers::flash("error", "Invalid request.");
            Helpers::redirect("index.php?page=users");
        }

        $id = (int)($_POST['id'] ?? 0);

        if ($id === Auth::currentUser()['id']) { // prevent admin from deactivating their own account
            Helpers::flash("error", "You cannot deactivate your own account.");
            Helpers::redirect("index.php?page=users");
        }

        $this->userModel->toggleActive($id);

        Helpers::flash("success", "User status updated.");
        Helpers::redirect("index.php?page=users");
    }
}