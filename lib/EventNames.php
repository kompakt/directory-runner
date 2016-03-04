<?php

/*
 * This file is part of the kompakt/directory-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\DirectoryRunner;

use Kompakt\DirectoryRunner\EventNamesInterface;

class EventNames implements EventNamesInterface
{
    protected $namespace = null;

    public function __construct($namespace = 'directory_runner')
    {
        $this->namespace = $namespace;
    }

    public function start()
    {
        return sprintf('%s.start', $this->namespace);
    }

    public function startError()
    {
        return sprintf('%s.start_error', $this->namespace);
    }

    public function file()
    {
        return sprintf('%s.file', $this->namespace);
    }

    public function fileError()
    {
        return sprintf('%s.file_error', $this->namespace);
    }

    public function end()
    {
        return sprintf('%s.end', $this->namespace);
    }

    public function endError()
    {
        return sprintf('%s.end_error', $this->namespace);
    }
}