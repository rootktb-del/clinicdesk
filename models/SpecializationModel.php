<?php


require_once __DIR__ . '/BaseModel.php';

class SpecializationModel extends BaseModel
{
    //get all specializations
    public static function getAll(): array //get specializations from db
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