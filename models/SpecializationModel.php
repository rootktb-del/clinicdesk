<?php


require_once __DIR__ . '/BaseModel.php';

class SpecializationModel extends BaseModel
{
    public static function getAll(): array
    {
        $sql = "
            SELECT 
                s.id,
                s.name AS specialization
            FROM specializations s
        ";

        $result = $this->execute($sql);

        return $this->fetchAll($result);
    }

}