<?php

/*
 * This file is part of the kompakt/directory-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\DirectoryRunner\Event;

use Symfony\Contracts\EventDispatcher\Event;

class FileErrorEvent extends Event
{
    protected $exception = null;
    protected $file = null;

    public function __construct(\Exception $exception, \SplFileInfo $file)
    {
        $this->exception = $exception;
        $this->file = $file;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function getFile()
    {
        return $this->file;
    }
}