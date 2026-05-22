<?php

namespace App\Controllers;

use App\Models\ContactManager;

class Command
{
    private ContactManager $contactManager;

    public function __construct()
    {
        $this->contactManager = new ContactManager();
    }

    public function loopAndDispatch(): void
    {
        while (true) {
            $line = readline('> ');

            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $this->process($line);
        }
    }

    public function process(string $command)
    {
        $commandArray = preg_split('/[\s,]+/', $command);
        switch ($commandArray[0]) {
            case 'help':
                echo "Commande saisie : help" . PHP_EOL;
                break;
            case 'list':
                $this->list();
                break;
            case 'detail':
                $id = $commandArray[1];
                if (!ctype_digit($id)) {
                    echo "$id n'est pas valide" . PHP_EOL;
                    break;
                }
                $this->detail($id);
                break;
            case 'create':

                if (count($commandArray) < 4) {
                    echo "Il faut 3 arguments" . PHP_EOL;
                    break;
                }

                $parameters = [];
                for ($i = 1; $i < count($commandArray); $i++) {
                    $parameters[] = $commandArray[$i];
                }

                $phoneNumber = array_pop($parameters);
                if (!preg_match("/^[0-9]{1,20}$/", $phoneNumber)) {
                    echo "$phoneNumber n'est pas un N° de téléphone valide" . PHP_EOL;
                    break;
                }

                $email = array_pop($parameters);
                if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
                    echo "$email n'est pas un eMail valide" . PHP_EOL;
                    break;
                }

                $name = implode(" ", $parameters);
                if (!preg_match("/^[\p{L}][\p{L}\s'-]{1,149}$/u", $name)) {
                    echo "$name n'est pas un nom valide" . PHP_EOL;
                    break;
                }

                $this->create($name, $email, $phoneNumber);
                break;
            case 'delete':
                $id = $commandArray[1];
                if (!ctype_digit($id)) {
                    echo "$id n'est pas valide" . PHP_EOL;
                    break;
                }
                $this->delete($id);
                break;
            case 'quit':
                $this->quit();
                break;
            default:
                echo "Commande invalide : help" . PHP_EOL;
                break;
        }
    }

    public function list(): void
    {
        echo "Commande saisie : list" . PHP_EOL;
        foreach ($this->contactManager->findAll() as $contact) {
            echo $contact . PHP_EOL;
        }
    }

    public function detail(int $id): void
    {
        echo "Commande saisie : detail $id" . PHP_EOL;
        $contact = $this->contactManager->findById($id);
        echo $contact . PHP_EOL;
    }

    public function create(string $name, string $email, string $phoneNumber): void
    {
        echo "Commande saisie : create $name $email $phoneNumber" . PHP_EOL;
        $this->contactManager->insertContact($name, $email, $phoneNumber);
    }

    public function delete(int $id): void
    {
        echo "Commande saisie : delete $id" . PHP_EOL;
        $this->contactManager->deleteContact($id);
    }

    public function quit(): void
    {
        exit;
    }
}
