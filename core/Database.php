<?php

require_once __DIR__ . '/config/database.php';

class Database
{
    private static $instance = null;
    private $connection;
    private $config;


    private function __construct()
    {
        $this->config = dbconfig::dbConfig(); //Gets the values from config/database.php and stores in $config

        $this->connection = mysqli_connect($this->config['host'], $this->config['username'], $this->config['password']);

        if (!$this->connection) {
            throw new RuntimeException(
            "[DB ERROR] Database connection failed."
            );
        }

        $this->createDatabase();

        mysqli_select_db($this->connection, $this->config['dbname']);

        $this->createTables();
    }

    private function __clone() {}

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }



    public function query(string $sql, string $types = "", array $params = [])
    {
        $stmt = $this->connection->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("[DB ERROR] Query preparation failed.");
        }



        // Bind parameters if given
        if (!empty($types) && !empty($params)) {

            if (strlen($types) !== count($params)) {
                throw new InvalidArgumentException(
                "[DB ERROR] Parameter count does not match type count."
                );
                
            }else{$stmt->bind_param($types, ...$params);}

        }

        if (!$stmt->execute()) {
            throw new RuntimeException("[DB ERROR] Query execution failed.");
        }

        $result = $stmt->get_result();

        if ($result !== false) {
            return $result; // SELECT queries return result set
        }

        $stmt->close();
        return true; // INSERT/UPDATE/DELETE return true/false
    }


    public function lastInsertId(): int
    {
        return $this->connection->insert_id;
    }





    private function createDatabase()
    {
        $sql = "CREATE DATABASE IF NOT EXISTS " . $this->config['dbname'];
        if (!mysqli_query($this->connection, $sql)) {
            die("[DB ERROR] Could not create database. " . mysqli_error($this->connection));
        }
    }




    private function createTables()
    {
        $sql="CREATE TABLE IF NOT EXISTS users (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(120) NOT NULL,
            email VARCHAR(180) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin','doctor','patient') NOT NULL DEFAULT 'patient',
            phone VARCHAR(20) DEFAULT NULL,
            avatar VARCHAR(255) DEFAULT NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    if (!mysqli_query($this->connection, $sql)) {
        die("[DB ERROR] Could not create table. " . mysqli_error($this->connection));
    }

    $check = $this->connection->query(
        "SELECT id FROM users WHERE email = 'admin@clinic.local' LIMIT 1"
    );

// check if admin already exists so it doesn't duplicate when re opening program
    if ($check->num_rows === 0) {
        
    $sql="INSERT INTO users (name, email, password, role)
            VALUES ('Admin', 'admin@clinic.local',
            'pass', 'admin');
    ";
    }

    if (!mysqli_query($this->connection, $sql)) {
        die("[DB ERROR] Could not create table. " . mysqli_error($this->connection));
    }


    $sql="CREATE TABLE IF NOT EXISTS specializations (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE
        )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    if (!mysqli_query($this->connection, $sql)) {
        die("[DB ERROR] Could not create table. " . mysqli_error($this->connection));
    }
//specialization name is UNIQUE so I use INSERT *IGNORE* since it skips already existing ones, and the constructor is called more than once when re-opening the program
    $sql="INSERT IGNORE INTO specializations (name) VALUES
            ('General Practice'), ('Cardiology'), ('Dermatology'),
            ('Pediatrics'), ('Orthopedics'), ('Neurology'),
            ('Ophthalmology'), ('ENT'), ('Psychiatry');
    ";

    if (!mysqli_query($this->connection, $sql)) {
        die("[DB ERROR] Could not create table. " . mysqli_error($this->connection));
    }


    $sql="CREATE TABLE IF NOT EXISTS doctors (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NOT NULL UNIQUE,
        specialization_id INT UNSIGNED NOT NULL,
        bio TEXT DEFAULT NULL,
        consultation_fee DECIMAL(8,2) NOT NULL DEFAULT 0.00,
        available_days VARCHAR(50) NOT NULL DEFAULT 'Sun,Mon,Tue,Wed,Thu',
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (specialization_id) REFERENCES specializations(id) ON DELETE RESTRICT
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    if (!mysqli_query($this->connection, $sql)) {
        die("[DB ERROR] Could not create table. " . mysqli_error($this->connection));
    }

    $sql="CREATE TABLE IF NOT EXISTS appointments (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        patient_id INT UNSIGNED NOT NULL,
        doctor_id INT UNSIGNED NOT NULL,
        appt_date DATE NOT NULL,
        appt_time TIME NOT NULL,
        status ENUM('pending','confirmed','completed','cancelled')
        NOT NULL DEFAULT 'pending',
        reason VARCHAR(255) DEFAULT NULL,
        doctor_notes TEXT DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY no_double_booking (doctor_id, appt_date, appt_time),
        FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    if (!mysqli_query($this->connection, $sql)) {
        die("[DB ERROR] Could not create table. " . mysqli_error($this->connection));
    }

    $sql="CREATE TABLE IF NOT EXISTS prescriptions (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        appointment_id INT UNSIGNED NOT NULL UNIQUE, -- one prescription per appointment
        diagnosis TEXT NOT NULL,
        medications TEXT NOT NULL,
        notes TEXT DEFAULT NULL,
        file_path VARCHAR(255) DEFAULT NULL, -- optional scanned PDF upload
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    if (!mysqli_query($this->connection, $sql)) {
        die("[DB ERROR] Could not create table. " . mysqli_error($this->connection));
    }
        }
    

        

    public function __destruct()
    {
        mysqli_close($this->connection);
    }
}