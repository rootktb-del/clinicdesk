<?php


require_once __DIR__ . '/BaseModel.php';

class DoctorModel extends BaseModel
{
    public function findByUserId(int $userId): ?array
    {
        $sql = "
            SELECT d.*, u.name, u.email, u.phone, s.name AS specialization_name
            FROM doctors d
            JOIN users u ON u.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            WHERE d.user_id = ?
            LIMIT 1
        ";

        $result = $this->execute($sql, "i", [$userId]);

        return $this->fetchOne($result);
    }

    public function getAll(): array
    {
        $sql = "
            SELECT 
                d.id,
                u.name,
                s.name AS specialization
            FROM doctors d
            JOIN users u ON u.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
        ";

        $result = $this->execute($sql);

        return $this->fetchAll($result);
    }

    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) AS total FROM doctors";

        $result = $this->execute($sql);

        $row = $this->fetchOne($result);

        return (int) ($row['total'] ?? 0);
    }

    public function getAllPaginated(int $page): array
    {
        $paginator = new Paginator($this->countAll(), 10, $page);
        $limit = $paginator->getPerPage();
        $offset = $paginator->offset();

        $sql = "
            SELECT 
                d.id,
                u.name,
                u.email,
                u.phone,
                d.consultation_fee,
                d.available_days,
                s.name AS specialization
            FROM doctors d
            JOIN users u ON u.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            ORDER BY d.created_at DESC
            LIMIT ? OFFSET ?
        ";

        $result = $this->execute($sql, "ii", [$limit, $offset]);

        return $this->fetchAll($result);
    }

    public function create(array $data): int
    {
        $sql = "
            INSERT INTO doctors 
            (user_id, specialization_id, bio, consultation_fee, available_days)
            VALUES (?, ?, ?, ?, ?)
        ";

        $result = $this->execute(
            $sql,
            "iisss",
            [
                $data['user_id'],
                $data['specialization_id'],
                $data['bio'] ?? null,
                $data['consultation_fee'],
                $data['available_days'] ?? "Sun,Mon,Tue,Wed,Thu"
            ]
        );

        if ($result === false) {
            return 0;
        }

        return $this->db->lastInsertId();
    }

    public function update(int $doctorId, array $data): bool
    {
        $sql = "
            UPDATE doctors
            SET specialization_id = ?,
                bio = ?,
                consultation_fee = ?,
                available_days = ?
            WHERE id = ?
        ";

        $result = $this->execute(
            $sql,
            "isssi",
            [
                $data['specialization_id'],
                $data['bio'] ?? null,
                $data['consultation_fee'],
                $data['available_days'],
                $doctorId
            ]
        );

        return $result !== false;
    }

    public function getAvailableDays(int $doctorId): array
    {
        $sql = "SELECT available_days FROM doctors WHERE id = ? LIMIT 1";

        $result = $this->execute($sql, "i", [$doctorId]);
        $row = $this->fetchOne($result);

        if (!$row || empty($row['available_days'])) {
            return [];
        }

        return array_map('trim', explode(',', $row['available_days']));
    }
}