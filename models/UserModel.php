<?php


require_once __DIR__ . '/BaseModel.php';

class UserModel extends BaseModel
{
    // find user by his id
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";

        $result = $this->execute($sql, "i", [$id]);

        if (!$result instanceof mysqli_result) {
            return null;
        }

        return $this->fetchOne($result);
    }
    

    // find user by his email
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";

        $result = $this->execute($sql, "s", [$email]);

        if (!$result instanceof mysqli_result) {
            return null;
        }

        return $this->fetchOne($result);
    }

    // create a new user record
    public function create(array $data): int
    {
        $sql = "INSERT INTO users (name, email, password, role, phone, avatar, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $result = $this->execute(
            $sql,
            "ssssssi",
            [
                $data['name'],
                $data['email'],
                $data['password'], // MUST already be hashed
                $data['role'] ?? 'patient',
                $data['phone'] ?? null,
                $data['avatar'] ?? null,
                $data['is_active'] ?? 1
            ]
        );

        if ($result === false) {
            return 0;
        }

        return $this->db->lastInsertId();
    }

    // update user record
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE users 
                SET name = ?, phone = ?, avatar = ?
                WHERE id = ?";

        $result = $this->execute(
            $sql,
            "sssi",
            [
                $data['name'],
                $data['phone'] ?? null,
                $data['avatar'] ?? null,
                $id
            ]
        );

        return $result !== false;
    }

    // change user's password
    public function updatePassword(int $id, string $newHash): bool
    {
        $sql = "UPDATE users SET password = ? WHERE id = ?";

        $result = $this->execute($sql, "si", [$newHash, $id]);

        return $result !== false;
    }








    // get paginated list of users
    public function getAllPaginated(int $page, string $role = ""): array
    {
        $paginator = new Paginator($this->countAll($role), 10, $page);
        $limit = $paginator->getPerPage();
        $offset = $paginator->offset();

        if (!empty($role)) { // optional role filter
            $sql = "SELECT * FROM users WHERE role = ? ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $result = $this->execute($sql, "sii", [$role, $limit, $offset]);
        } else {
            $sql = "SELECT * FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $result = $this->execute($sql, "ii", [$limit, $offset]);
        }

        if (!$result instanceof mysqli_result) {
            return [];
        }
        return $this->fetchAll($result);
    }






    // count all users
    public function countAll(string $role = ""): int
    {
        if (!empty($role)) { // optional role filter
            $sql = "SELECT COUNT(*) as total FROM users WHERE role = ?";
            $result = $this->execute($sql, "s", [$role]);
        } else {
            $sql = "SELECT COUNT(*) as total FROM users";
            $result = $this->execute($sql);
        }

        if (!$result instanceof mysqli_result) {
            return 0;
        }

        return (int) ($this->fetchOne($result)['total'] ?? 0);
    }

    // activate/deactivate user
    public function toggleActive(int $id): bool
    {
        $sql = "UPDATE users 
                SET is_active = NOT is_active 
                WHERE id = ?";

        $result = $this->execute($sql, "i", [$id]);

        return $result !== false;
    }
}