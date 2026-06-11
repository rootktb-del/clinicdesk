<?php

require_once __DIR__ . '/../core/Database.php';

abstract class BaseModel
{
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    // main query execution method with prepared statement
    protected function execute(string $sql, string $types = "", array $params = []) {
        try {
            return $this->db->query(
                $sql,
                $types,
                $params
            );
        } catch (Throwable $e) {
            error_log("[DB ERROR] " . $e->getMessage());
            return false;
        }
    }

    protected function fetchOne($result): ?array //get one row from the result of a prepared statement
{
    if (!$result instanceof mysqli_result) {
        return null;
    }

    $row = $result->fetch_assoc();

    return $row ?: null;
}

protected function fetchAll($result): array // get all rows from the result of a prepared statement
{
    if (!$result instanceof mysqli_result) {
        return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

}

?>