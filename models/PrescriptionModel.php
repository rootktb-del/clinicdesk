<?php


require_once __DIR__ . '/BaseModel.php';

class PrescriptionModel extends BaseModel
{
    // find prescription by appointment id
    public function findByAppointmentId(int $apptId): ?array
    {
        $sql = "
            SELECT *
            FROM prescriptions
            WHERE appointment_id = ?
            LIMIT 1
        ";

        $result = $this->execute($sql, "i", [$apptId]);

        return $this->fetchOne($result);
    }

    // create a prescription for an appointment
    public function create(array $data): int
    {
        $sql = "
            INSERT INTO prescriptions
            (appointment_id, diagnosis, medications, notes, file_path)
            VALUES (?, ?, ?, ?, ?)
        ";

        $result = $this->execute(
            $sql,
            "issss",
            [
                $data['appointment_id'],
                $data['diagnosis'],
                $data['medications'],
                $data['notes'] ?? null,
                $data['file_path'] ?? null
            ]
        );

        if ($result === false) {
            return 0;
        }

        return $this->db->lastInsertId();
    }

    // update a prescription
    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE prescriptions
            SET diagnosis = ?,
                medications = ?,
                notes = ?,
                file_path = ?
            WHERE id = ?
        ";

        $result = $this->execute(
            $sql,
            "ssssi",
            [
                $data['diagnosis'],
                $data['medications'],
                $data['notes'] ?? null,
                $data['file_path'] ?? null,
                $id
            ]
        );

        return $result !== false;
    }

    //get prescriptions of a patient
    public function getByPatient(int $patientId): array
    {
        $sql = "
            SELECT p.*,
                   a.appt_date,
                   a.appt_time,
                   d.user_id AS doctor_user_id
            FROM prescriptions p
            JOIN appointments a ON a.id = p.appointment_id
            JOIN doctors d ON d.id = a.doctor_id
            WHERE a.patient_id = ?
            ORDER BY p.created_at DESC
        ";

        $result = $this->execute($sql, "i", [$patientId]);

        return $this->fetchAll($result);
    }
}