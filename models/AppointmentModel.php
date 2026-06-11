<?php


require_once __DIR__ . '/BaseModel.php';

class AppointmentModel extends BaseModel
{
    public function book(array $data): bool // Book appointment by inserting in db
    {
        $sql = "
            INSERT INTO appointments
            (patient_id, doctor_id, appt_date, appt_time, reason)
            VALUES (?, ?, ?, ?, ?)
        ";

        $result = $this->execute(
            $sql,
            "iisss",
            [
                $data['patient_id'],
                $data['doctor_id'],
                $data['appt_date'],
                $data['appt_time'],
                $data['reason'] ?? null
            ]
        );

        // If UNIQUE constraint fails or query error
        if ($result === false) {
            return false;
        }

        return true;
    }
    // Check if doctor has another appointment at the same date and time
    public function hasConflict(int $doctorId, string $date, string $time): bool
    {
        $sql = "
            SELECT id 
            FROM appointments
            WHERE doctor_id = ?
              AND appt_date = ?
              AND appt_time = ?
            LIMIT 1
        ";

        $result = $this->execute($sql, "iss", [$doctorId, $date, $time]);

        if (!$result instanceof mysqli_result) {
            return false;
        }

        return $this->fetchOne($result) !== null;
    }
    //get appointment for a patient
    public function getByPatient(int $patientId, int $page, array $filters = []): array
    {
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $sql = "
            SELECT a.*, 
                   u.name AS doctor_name,
                   s.name AS specialization
            FROM appointments a
            JOIN doctors d ON d.id = a.doctor_id
            JOIN users u ON u.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            WHERE a.patient_id = ?
            ORDER BY a.appt_date DESC, a.appt_time DESC
            LIMIT ? OFFSET ?
        ";

        $result = $this->execute($sql, "iii", [$patientId, $limit, $offset]);

        return $this->fetchAll($result);
    }

    // get a doctor's appointments
    public function getByDoctor(int $doctorId, int $page, array $filters = []): array
    {
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $sql = "
            SELECT a.*,
                   u.name AS patient_name,
                   u.phone AS patient_phone
            FROM appointments a
            JOIN users u ON u.id = a.patient_id
            WHERE a.doctor_id = ?
            ORDER BY a.appt_date DESC, a.appt_time DESC
            LIMIT ? OFFSET ?
        ";

        $result = $this->execute($sql, "iii", [$doctorId, $limit, $offset]);

        return $this->fetchAll($result);
    }

    //get all appointments paginated for admin
    public function getAll(int $page, array $filters = []): array
    {
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $sql = "
            SELECT a.*,
                   p.name AS patient_name,
                   duser.name AS doctor_name
            FROM appointments a
            JOIN users p ON p.id = a.patient_id
            JOIN doctors d ON d.id = a.doctor_id
            JOIN users duser ON duser.id = d.user_id
            ORDER BY a.appt_date DESC, a.appt_time DESC
            LIMIT ? OFFSET ?
        ";

        $result = $this->execute($sql, "ii", [$limit, $offset]);

        return $this->fetchAll($result);
    }

    // Count total appointments with a filter
    public function countFiltered(string $scope, int $scopeId, array $filters = []): int
    {
        if ($scope === "doctor") {
            $sql = "SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = ?";
            $result = $this->execute($sql, "i", [$scopeId]);
        } elseif ($scope === "patient") {
            $sql = "SELECT COUNT(*) AS total FROM appointments WHERE patient_id = ?";
            $result = $this->execute($sql, "i", [$scopeId]);
        } else {
            $sql = "SELECT COUNT(*) AS total FROM appointments";
            $result = $this->execute($sql);
        }

        if (!$result instanceof mysqli_result) {
            return 0;
        }

        return (int) ($this->fetchOne($result)['total'] ?? 0);
    }

    // update status of an appointment
    public function updateStatus(int $id, string $status, string $notes = ""): bool
    {
        $sql = "
            UPDATE appointments
            SET status = ?,
                doctor_notes = ?
            WHERE id = ?
        ";

        $result = $this->execute(
            $sql,
            "ssi",
            [$status, $notes, $id]
        );

        return $result !== false;
    }

    // find appointment by id
    public function findById(int $id): ?array
    {
        $sql = "
            SELECT a.*,
                   pu.name AS patient_name,
                   du.name AS doctor_name,
                   s.name AS specialization
            FROM appointments a
            JOIN users pu ON pu.id = a.patient_id
            JOIN doctors d ON d.id = a.doctor_id
            JOIN users du ON du.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            WHERE a.id = ?
            LIMIT 1
        ";

        $result = $this->execute($sql, "i", [$id]);

        return $this->fetchOne($result);
    }
}