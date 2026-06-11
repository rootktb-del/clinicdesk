<?php

class Helper
{
    public static function redirect(string $path)// redirect to given path
    {
        header("Location: " . BASE_URL . "/" . ltrim($path, "/"));
        exit();
    }

    public static function sanitize(string $value): string// sanitize output to prevent XSS
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }


    public static function formatDate(string $date): string
    {
        if (!$date) return "";

        return date("d M Y", strtotime($date));
    }

    public static function formatTime(string $time): string
    {
        if (!$time) return "";

        return date("h:i A", strtotime($time));
    }

    public static function setFlash(string $key, string $message = null)//set flash message insession
    {
        if ($message === null) {
            $msg = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $msg;
        }

        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash(string $key): ?string //get flash message from session
    {
        if (!isset($_SESSION['flash'][$key])) {
            return null;
        }
    
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);// unset message when done
    
        return $message;
    }



    public static function is_post() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }// check if the request method is POST


    
}