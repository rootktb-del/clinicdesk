<?php

class Auth
{

    public static function login(array $user): bool
    {
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'   => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ];

        return true;
    }

    public static function logout()
    {
        session_unset();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        Helpers::redirect("views/auth/login.php");
        exit();
    }

    public static function check():bool
    {
        if (isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }

    public static function currentUser():?array
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        return null;
    }

        public static function role():?string
    {
        if (isset($_SESSION['user']['role'])) {
            return $_SESSION['user']['role'];
        }
        return null;
    }

    public static function requireRole(... $roles){
        
        if (!self::check()) { 
            Helpers::redirect("views/auth/login.php"); //Check if user is logged in, if not redirect to login page
            exit();
        }

        if(!in_array($_SESSION['user']['role'], $roles)){
            Helpers::redirect("views/errors/403.php"); // check if user has required role, if not redirect to 403 page
            exit();
        }
    }

    
}