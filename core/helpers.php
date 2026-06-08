<?php

class Helper
{
    public static function redirect(string $path)
    {
        header("Location: " . BASE_URL . "/" . ltrim($path, "/"));
        exit();
    }

    public static function sanitize(string $value): string
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

    public static function setFlash(string $key, string $message = null)
    {
        if ($message === null) {
            $msg = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $msg;
        }

        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash(string $key): ?string
    {
        if (!isset($_SESSION['flash'][$key])) {
            return null;
        }
    
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
    
        return $message;
    }



    public static function is_post() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }


    
}