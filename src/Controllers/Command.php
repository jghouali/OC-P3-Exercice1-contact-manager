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
        switch ($command) {
            case 'help':
                echo "Commande saisie : help\n";
                break;
            case 'list':
                $this->list();
                break;
            case 'create':
                echo "Commande saisie : create\n";
                break;
            case 'delete':
                echo "Commande saisie : delete\n";
                break;
            case 'quit':
                echo "Commande saisie : quit\n";
                break;
            default:
                echo "Commande invalide : help\n";
                break;
        }
    }

    public function list(): void
    {
        echo "Commande saisie : list\n";
        foreach ($this->contactManager->findAll() as $contact) {
            echo $contact . PHP_EOL;
        }
    }
}
