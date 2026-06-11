<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/UserModel.php';

class AuthController
{
    private UserModel $userModel;
    private Auth $auth;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->auth = new Auth();
    }


    public function showLogin() //show login form view
    {
        // If already logged in then redirect to dashboard
        if ($this->auth->check()) {
            Helpers::redirect('index.php?page=dashboard');
        }

        require __DIR__ . '/../views/auth/login.php';
    }


    public function login() //login form processing
    {
        if (!Helpers::is_post()) {
            Helpers::redirect('index.php?page=login');
        }

        // CSRF check
        $token = $_POST['csrf_token'] ?? '';

        if (!CSRF::validateToken($token)) {
            Helpers::flash('error', 'Invalid request.');
            Helpers::redirect('index.php?page=login');
        }

        // Sanitize input
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            Helpers::flash('error', 'Invalid credentials');
            Helpers::redirect('index.php?page=login');
        }

        // Find user
        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            Helpers::flash('error', 'Invalid credentials');
            Helpers::redirect('index.php?page=login');
        }

        // Check if active
        if ((int)$user['is_active'] !== 1) {
            Helpers::flash('error', 'Account suspended. Contact admin.');
            Helpers::redirect('index.php?page=login');
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            Helpers::flash('error', 'Invalid credentials');
            Helpers::redirect('index.php?page=login');
        }

        // set Login user session
        $this->auth->login($user);

        // redirect to dashboard
        Helpers::redirect('index.php?page=dashboard');
    }


    // logout user
    public function logout()
    {
        if (Helpers::is_post()) {
            Helpers::redirect('index.php?page=dashboard');
        }

        $token = $_POST['csrf_token'] ?? '';

        if (!CSRF::validateToken($token)) {
            Helpers::flash('error', 'Invalid request.');
            Helpers::redirect('index.php?page=dashboard');
        }

        $this->auth->logout();

        Helpers::redirect('index.php?page=login');
    }
}