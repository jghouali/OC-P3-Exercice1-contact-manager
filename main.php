<?php
while (true) {
    $line = readline("Entrez votre commande : ");
    echo "Vous avez saisi : $line\n";
    switch ($line) {
        case 'list':
            echo "affichage de la liste";
            break;
        default:
            echo "help : affiche cette aide

list : liste les contacts

create [name], [email], [phone number] : crée un contact

delete [id] : supprime un contact

quit : quitte le programme";
    }
}
