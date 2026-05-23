<?php

declare(strict_types=1);

// Classes are in App namespace = src folder
spl_autoload_register(static function ($fqcn): void {
    $path = sprintf('%s.php', str_replace(['App', '\\'], ['src', '/'], $fqcn));
    require_once $path;
});

use App\Controllers\Command;

$commandController = new Command();

$commandController->loopAndDispatch();
