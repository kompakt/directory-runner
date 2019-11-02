<?php

/*
 * This file is part of the kompakt/directory-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\DirectoryRunner;

use Kompakt\DirectoryRunner\EventNamesInterface;
use Kompakt\DirectoryRunner\Event\EndErrorEvent;
use Kompakt\DirectoryRunner\Event\EndEvent;
use Kompakt\DirectoryRunner\Event\FileErrorEvent;
use Kompakt\DirectoryRunner\Event\FileEvent;
use Kompakt\DirectoryRunner\Event\StartErrorEvent;
use Kompakt\DirectoryRunner\Event\StartEvent;
use Kompakt\DirectoryRunner\Exception\RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Runner
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $dir = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames,
        $dir
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
        $this->dir = new \RecursiveDirectoryIterator($dir);
    }

    public function run($callback = null, $first = 0, $max = 0)
    {
        $first = $this->filterParams($first);
        $max = $this->filterParams($max);

        try {
            if (!$this->start())
            {
                $this->end();
                return;
            }

            $i = -1;

            foreach ($this->dir as $fileInfo)
            {
                if (is_callable($callback) && !$callback($fileInfo))
                {
                    continue;
                }

                $i++;

                if ($max)
                {
                    // activate pagination if max > 0

                    if ($i < $first)
                    {
                        continue;
                    }

                    if ($i > $first + $max - 1)
                    {
                        break;
                    }
                }

                if (!$this->file($fileInfo))
                {
                    continue;
                }
            }

            $this->end();
        }
        catch (\Exception $e)
        {
            throw new RuntimeException(sprintf('Dir runner error'), null, $e);
        }
    }

    protected function start()
    {
        try {
            $this->dispatcher->dispatch(
                new StartEvent($this->dir),
                $this->eventNames->start()
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                new StartErrorEvent($e, $this->dir),
                $this->eventNames->startError()
            );

            return false;
        }
    }

    protected function file(\SplFileInfo $file)
    {
        try {
            $this->dispatcher->dispatch(
                new FileEvent($file),
                $this->eventNames->file()
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                new FileErrorEvent($e, $file),
                $this->eventNames->fileError()
            );

            return false;
        }
    }

    protected function end()
    {
        try {
            $this->dispatcher->dispatch(
                new EndEvent($this->dir),
                $this->eventNames->end()
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                new EndErrorEvent($e, $this->dir),
                $this->eventNames->endError()
            );

            return false;
        }
    }

    protected function filterParams($n)
    {
        $n = (int) $n;
        return ($n < 0) ? 0 : $n;
    }
}