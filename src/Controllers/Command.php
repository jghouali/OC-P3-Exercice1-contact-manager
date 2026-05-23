<?php

namespace App\Controllers;

use App\Models\ContactManager;

class Command
{
    // We keep $contactManager in Memory
    private ContactManager $contactManager;
    private Help $helpController;

    public function __construct()
    {
        $this->contactManager = new ContactManager();
        $this->helpController = new Help($this->contactManager);
        $this->helpController->getCompletion();
    }

    public function loopAndDispatch(): void
    {
        while (true) {
            $line = readline('Entrez votre commande (help, list, detail, create, modify, delete, quit) : ');

            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $this->dispatch($line);
        }
    }

    public function dispatch(string $command): void
    {
        // We split command like ['$command']['$args'] ... ['$args']
        $commandArray = preg_split('/[\s,]+/', $command);
        switch ($commandArray[0]) {
            case 'help':
                $this->helpController->displayHelp();
                break;
            case 'list':
                $this->list();
                break;
            case 'detail':
                $id = $commandArray[1];
                if (!ctype_digit($id)) {
                    echo Help::color("$id n'est pas valide", 'red') . PHP_EOL . PHP_EOL;
                    break;
                }
                // Is $id present in $contactManager->$contacts
                if (array_key_exists($id, $this->contactManager->getContacts())) {
                    $this->detail($id);
                } else {
                    echo Help::color("$id n'existe pas", 'red') . PHP_EOL . PHP_EOL;
                }
                break;
            case 'create':
                // For create, we need 1 command and 3 args
                if (count($commandArray) < 4) {
                    echo Help::color("Il faut 3 arguments", 'red') . PHP_EOL . PHP_EOL;
                    break;
                }

                // I process only args so 1st element is dropped
                $parameters = [];
                for ($i = 1; $i < count($commandArray); $i++) {
                    $parameters[] = $commandArray[$i];
                }

                // I process larst args first since they are one word argument
                $phoneNumber = array_pop($parameters);
                if (!preg_match("/^[0-9]{1,20}$/", $phoneNumber)) {
                    echo Help::color("$phoneNumber n'est pas un N° de téléphone valide", 'red') . PHP_EOL . PHP_EOL;
                    break;
                }

                $email = array_pop($parameters);
                if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
                    echo Help::color("$email n'est pas un eMail valide", 'red') . PHP_EOL . PHP_EOL;
                    break;
                }

                // The other elements are considered to be "Name"
                $name = implode(" ", $parameters);
                if (!preg_match("/^[\p{L}][\p{L}\s'-]{1,149}$/u", $name)) {
                    echo Help::color("$name n'est pas un nom valide", 'red') . PHP_EOL . PHP_EOL;
                    break;
                }

                $this->create($name, $email, $phoneNumber);
                break;
            case 'modify':
                // For create, we need 1 command and 4 args
                if (count($commandArray) < 5) {
                    echo Help::color("Il faut 4 arguments", 'red') . PHP_EOL . PHP_EOL;
                    break;
                }

                // I process only args so 1st element is dropped and 2nd one is the Id
                $parameters = [];
                for ($i = 1; $i < count($commandArray); $i++) {
                    if ($i === 1) {
                        $id = $commandArray[$i];
                    } else {
                        $parameters[] = $commandArray[$i];
                    }
                }
                $id = $id ?? 0;

                // Is $id present in $contactManager->$contacts
                if (array_key_exists($id, $this->contactManager->getContacts())) {
                    $this->detail($id);
                } else {
                    echo Help::color("$id n'existe pas", 'red') . PHP_EOL . PHP_EOL;
                }

                // I process larst args first since they are one word argument
                $phoneNumber = array_pop($parameters);
                if (!preg_match("/^[0-9]{1,20}$/", $phoneNumber)) {
                    echo Help::color("$phoneNumber n'est pas un N° de téléphone valide", 'red') . PHP_EOL . PHP_EOL;
                    break;
                }

                $email = array_pop($parameters);
                if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
                    echo Help::color("$email n'est pas un eMail valide", 'red') . PHP_EOL . PHP_EOL;
                    break;
                }

                // The other elements are considered to be "Name"
                $name = implode(" ", $parameters);
                if (!preg_match("/^[\p{L}][\p{L}\s'-]{1,149}$/u", $name)) {
                    echo Help::color("$name n'est pas un nom valide", 'red') . PHP_EOL . PHP_EOL;
                    break;
                }

                $this->update($id, $name, $email, $phoneNumber);
                break;
            case 'delete':
                $id = $commandArray[1];
                if (!ctype_digit($id)) {
                    echo Help::color("$id n'est pas valide", 'red') . PHP_EOL . PHP_EOL;
                    break;
                }
                // Check in-memory if present
                if (array_key_exists($id, $this->contactManager->getContacts())) {
                    $this->delete($id);
                } else {
                    echo Help::color("$id n'existe pas", 'red') . PHP_EOL . PHP_EOL;
                }
                break;
            case 'quit':
                $this->quit();
                break;
            default:
                echo Help::color("Commande invalide : ", 'red') . $command . PHP_EOL . PHP_EOL;
                break;
        }
        readline_add_history($command);
    }

    public function list(): void
    {
        //echo "Commande saisie : list" . PHP_EOL;
        echo Help::color("Liste des contacts :", 'blue') . PHP_EOL . PHP_EOL;
        echo Help::color("id, name, email, phone number", 'blue') . PHP_EOL . PHP_EOL;
        foreach ($this->contactManager->findAll() as $contact) {
            echo Help::color($contact, 'green') . PHP_EOL . PHP_EOL;
        }
    }

    public function detail(int $id): void
    {
        //echo "Commande saisie : detail $id" . PHP_EOL;
        //$contact = $this->contactManager->findById($id);

        // I'm checking the memory because when I perform update
        // or create operations, I call findAll() again
        $contact = $this->contactManager->getContacts()[$id];
        echo Help::color($contact, 'green') . PHP_EOL . PHP_EOL;
    }

    public function create(string $name, string $email, string $phoneNumber): void
    {
        //echo "Commande saisie : create $name $email $phoneNumber" . PHP_EOL;
        if ($this->contactManager->insertContact($name, $email, $phoneNumber)) {
            echo Help::color("Contact $name $email $phoneNumber créé parfaitement !", 'green') . PHP_EOL . PHP_EOL;
        };
        $this->contactManager->findAll();
    }

    public function update(int $id, string $name, string $email, string $phoneNumber): void
    {
        //echo "Commande saisie : update $id $name $email $phoneNumber" . PHP_EOL;
        if ($this->contactManager->updateContact($id, $name, $email, $phoneNumber)) {
            echo Help::color("Contact $id $name $email $phoneNumber mise à jour parfaitement !", 'green') . PHP_EOL . PHP_EOL;
        };
        $this->contactManager->findAll();
    }

    public function delete(int $id): void
    {
        //echo "Commande saisie : delete $id" . PHP_EOL;
        if ($this->contactManager->deleteContact($id)) {
            echo Help::color("Contact supprimé avec succès !", 'green') . PHP_EOL . PHP_EOL;
        };
        $this->contactManager->findAll();
    }

    public function quit(): void
    {
        exit;
    }
}
