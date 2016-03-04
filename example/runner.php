<?php

/*
 * This file is part of the kompakt/directory-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

use Kompakt\DirectoryRunner\Subscriber\Debugger;
use Kompakt\DirectoryRunner\Runner;
use Kompakt\DirectoryRunner\EventNames;
use Symfony\Component\EventDispatcher\EventDispatcher;

require sprintf('%s/bootstrap.php', __DIR__);
$dir = sprintf("%s/lib", dirname(__DIR__));

$dispatcher = new EventDispatcher();
$eventNames = new EventNames();
$runner = new Runner($dispatcher, $eventNames, $dir);
$debugger = new Debugger($dispatcher, $eventNames);
$debugger->activate();

$callback = function($fileInfo)
{
    return preg_match('/\.php$/', $fileInfo->getFilename());
};

$runner->run($callback);

