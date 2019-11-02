<?php

/*
 * This file is part of the kompakt/directory-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\DirectoryRunner\Event;

use Symfony\Contracts\EventDispatcher\Event;

class EndErrorEvent extends Event
{
    protected $exception = null;
    protected $dir = null;

    public function __construct(\Exception $exception, \RecursiveDirectoryIterator $dir)
    {
        $this->exception = $exception;
        $this->dir = $dir;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function getDir()
    {
        return $this->dir;
    }
}