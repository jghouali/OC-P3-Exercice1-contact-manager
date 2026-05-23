<?php

namespace App\Models;

use App\DBConnect;
use \PDO;

class ContactManager
{
    private PDO $pdo;
    // contacts in-memory
    private array $contacts = [];

    public function __construct()
    {
        $this->pdo = (new DBConnect())->getPDO();
        $this->contacts = $this->findAll();
    }

    public function getContacts(): array
    {
        return $this->contacts;
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
            $contacts[$result['id']] = new Contact($result['id'], $result['name'], $result['email'], $result['phone_number']);
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

    public function insertContact(string $name, string $email, string $phoneNumber): bool
    {
        $insertContactStatement = $this->pdo->prepare(
            'INSERT INTO contact
            ( name, email, phone_number )
            VALUES 
            ( :name, :email, :phoneNumber )'
        );
        $insertContactStatement->bindValue(':name', $name, PDO::PARAM_STR);
        $insertContactStatement->bindValue(':email', $email, PDO::PARAM_STR);
        $insertContactStatement->bindValue(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
        $result = $insertContactStatement->execute();
        return $result;
    }

    public function updateContact(int $id, string $name, string $email, string $phoneNumber): bool
    {
        $updateContactStatement = $this->pdo->prepare(
            'UPDATE contact
            SET
                name = :name,
                email = :email,
                phone_number = :phoneNumber
            WHERE 
                id =:id'
        );
        $updateContactStatement->bindValue(':name', $name, PDO::PARAM_STR);
        $updateContactStatement->bindValue(':email', $email, PDO::PARAM_STR);
        $updateContactStatement->bindValue(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
        $updateContactStatement->bindValue(':id', $id, PDO::PARAM_INT);
        $result = $updateContactStatement->execute();
        return $result;
    }

    public function deleteContact(int $id): bool
    {
        $deleteContactStatement = $this->pdo->prepare(
            'DELETE FROM contact
            WHERE
            id = :id'
        );
        $deleteContactStatement->bindValue(':id', $id, PDO::PARAM_INT);
        $result = $deleteContactStatement->execute();
        return $result;
    }
}
