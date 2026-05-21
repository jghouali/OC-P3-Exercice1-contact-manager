<?php

namespace App\Models;

use App\DBConnect;
use \PDO;

class ContactManager
{
    private PDO $pdo;
    private array $contacts = [];

    public function __construct()
    {
        $this->pdo = (new DBConnect())->getPDO();
    }

    public function findAll(): array
    {
        $findAllStatement = $this->pdo->prepare(
            'SELECT id, name, email, phone_number
            FROM contact'
        );
        $findAllStatement->execute();
        //$contacts = $findAllStatement->fetchAll();
        $contacts = [];
        while ($result = $findAllStatement->fetch()) {
            $contacts[] = new Contact($result['id'], $result['name'], $result['email'], $result['phone_number']);
        }

        $this->contacts = $contacts;
        //var_dump($contacts);
        return $this->contacts;
    }

    public function findById(int $id): Contact
    {
        $findByIdStatement = $this->pdo->prepare(
            'SELECT id, name, email, phone_number
            FROM contact
            WHERE id = :id'
        );
        $findByIdStatement->bindValue(':id', $id, PDO::PARAM_INT);
        $findByIdStatement->execute();
        $result = $findByIdStatement->fetch();
        return new Contact($result['id'], $result['name'], $result['email'], $result['phone_number']);
    }
}
