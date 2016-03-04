<?php

/*
 * This file is part of the kompakt/directory-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\DirectoryRunner\Event;

use Symfony\Component\EventDispatcher\Event;

class FileEvent extends Event
{
    protected $file = null;

    public function __construct(\SplFileInfo $file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }
}