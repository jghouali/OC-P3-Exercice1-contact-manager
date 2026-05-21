<?php

spl_autoload_register(static function ($fqcn): void {
    $path = sprintf('%s.php', str_replace(['App', '\\'], ['src', '/'], $fqcn));
    require_once $path;
});

use App\Models\ContactManager;

$contactManager = new ContactManager();

while (true) {
    $line = readline("Entrez votre commande : ");
    echo "Vous avez saisi : $line\n";
    switch ($line) {
        case 'list':
            echo "affichage de la liste" . PHP_EOL;
            foreach ($contactManager->findAll() as $contact) {
                echo $contact . PHP_EOL;
            }
            //var_dump($contactManager->findAll());
            break;
        default:
            echo "help : affiche cette aide

list : liste les contacts

create [name], [email], [phone number] : crée un contact

delete [id] : supprime un contact

quit : quitte le programme";
    }
}
