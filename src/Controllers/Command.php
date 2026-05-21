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
        $commandArray = preg_split('/[\s]+/', $command);
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
                echo "Commande saisie : create" . PHP_EOL;
                break;
            case 'delete':
                echo "Commande saisie : delete" . PHP_EOL;
                break;
            case 'quit':
                echo "Commande saisie : quit" . PHP_EOL;
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
}
