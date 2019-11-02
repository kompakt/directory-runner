<?php

/*
 * This file is part of the kompakt/directory-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\DirectoryRunner\Event;

use Symfony\Contracts\EventDispatcher\Event;

class StartEvent extends Event
{
    protected $dir = null;

    public function __construct(\RecursiveDirectoryIterator $dir)
    {
        $this->dir = $dir;
    }

    public function getDir()
    {
        return $this->dir;
    }
}