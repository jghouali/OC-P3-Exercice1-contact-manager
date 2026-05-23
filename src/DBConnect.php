<?php

namespace App;

use \PDO;

class DBConnect
{
    private ?PDO $pdo = null;

    public function getPDO(
        // this credential are by default, need to set by calling new getPDO()
        ?string $host = "127.0.0.1",
        ?string $db = "contact_manager",
        ?string $user = "contact_manager",
        ?string $password = "contact_manager"
    ): PDO {
        if ($this->pdo === null) {
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$db;charset=utf8mb4",
                $user,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        }
        return $this->pdo;
    }
}
