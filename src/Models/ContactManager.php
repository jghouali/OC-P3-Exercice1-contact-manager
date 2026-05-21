<?php

namespace App\Models;

use App\DBConnect;
use \PDO;

class ContactManager
{
    private PDO $pdo;

    public function __construct(array $contacts = [])
    {
        $this->pdo = (new DBConnect())->getPDO();
    }

    public function findAll(): array
    {
        $findAllStatement = $this->pdo->prepare(
            'SELECT name, email, phone_number
            FROM contact'
        );
        $findAllStatement->execute();
        //$contacts = $findAllStatement->fetchAll();
        $contacts = [];
        while ($result = $findAllStatement->fetch()) {
            $contacts[] = $result;
        }
        var_dump($contacts);
        return $contacts;
    }
}
