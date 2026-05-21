<?php

spl_autoload_register(static function ($fqcn): void {
    $path = sprintf('%s.php', str_replace(['App', '\\'], ['src', '/'], $fqcn));
    require_once $path;
});

use App\Controllers\Command;
use App\Models\ContactManager;

$contactManager = new ContactManager();
$commandController = new Command();

$commandController->loopAndDispatch();
