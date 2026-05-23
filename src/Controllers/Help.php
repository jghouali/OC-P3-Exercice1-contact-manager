<?php

namespace App\Controllers;

use App\Models\ContactManager;

class Help
{
    private ContactManager $contactManager;

    public function __construct(?ContactManager $contactManager = null)
    {
        // Needed for args completion
        $this->contactManager = $contactManager;
    }

    public function displayHelp(): void
    {
        foreach ($this->getCommand() as $command) {
            echo sprintf('%s %s : %s' . PHP_EOL, self::color($command, 'blue'), $this->getArguments($command), $this->getDescription($command));
        }
        echo PHP_EOL . PHP_EOL . PHP_EOL . 'Attention à la syntaxe des commandes, les espaces et virgules sont importants.' . PHP_EOL . PHP_EOL;
    }

    public function getCommand(): array
    {
        return ['help', 'list', 'create', 'modify', 'delete', 'detail', 'quit'];
    }

    public function getDescription(string $command): string
    {
        $description = [
            'help' => 'affiche cette aide',
            'list' => 'liste les contacts',
            'create' => 'crée un contact',
            'modify' => 'modifie un contact',
            'delete' => 'supprime un contact',
            'detail' => 'affiche un contact',
            'quit' => 'quitte le programme'
        ];
        return $description[$command];
    }

    public function getArguments(string $command): ?string
    {
        $arguments = [
            'help' => null,
            'list' => null,
            'create' => 'name[,] email[,] phone number',
            'modify' => 'id[,] name[,] email[,] phone number',
            'delete' => 'id',
            'detail' => 'id',
            'quit' => null
        ];
        return $arguments[$command];
    }

    static function color(string $text, string $color): string
    {
        // Return ANSI escae sequence for color
        $colors = [
            'red' => '31',
            'green' => '32',
            'yellow' => '33',
            'blue' => '34',
            'magenta' => '35',
            'cyan' => '36',
            'white' => '37',
        ];

        return "\033[" . $colors[$color] . "m" . $text . "\033[0m";
    }

    public function getCompletion(): void
    {
        // set the callable callback for readline completion
        readline_completion_function(
            function ($prompt, $index): array {

                // Fresh informations
                $contacts = $this->contactManager->getContacts();

                // Need an Id array to return for delete and detail completion
                $contactsIds = [];
                foreach ($contacts as $contact) {
                    $contactsIds[] = $contact->getId();
                };

                // The current line buffer
                $line = readline_info('line_buffer');

                //Display an helper, for argument needed
                if ($line == "delete ") {
                    echo PHP_EOL . $this->getArguments('delete');
                    return $contactsIds;
                } elseif ($line == "create ") {
                    echo PHP_EOL . $this->getArguments('create');
                    return [''];
                } elseif ($line == "modify ") {
                    echo PHP_EOL . $this->getArguments('modify');
                    return $contactsIds;
                } elseif ($line == "detail ") {
                    echo PHP_EOL . $this->getArguments('detail');
                    return $contactsIds;
                }

                // complete commands
                return array_filter($this->getCommand(), function ($commandIteration) use ($prompt) {
                    return str_starts_with($commandIteration, $prompt);
                });
            }
        );
    }
}
