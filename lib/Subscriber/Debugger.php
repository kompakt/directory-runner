<?php

/*
 * This file is part of the kompakt/directory-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\DirectoryRunner\Subscriber;

use Kompakt\DirectoryRunner\EventNamesInterface;
use Kompakt\DirectoryRunner\Event\EndErrorEvent;
use Kompakt\DirectoryRunner\Event\EndEvent;
use Kompakt\DirectoryRunner\Event\FileErrorEvent;
use Kompakt\DirectoryRunner\Event\FileEvent;
use Kompakt\DirectoryRunner\Event\StartErrorEvent;
use Kompakt\DirectoryRunner\Event\StartEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Debugger
{
    protected $dispatcher = null;
    protected $eventNames = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
    }

    public function activate()
    {
        $this->handleListeners(true);
    }

    public function deactivate()
    {
        $this->handleListeners(false);
    }

    public function onStart(StartEvent $event)
    {
        $this->writeln(
            sprintf(
                '+ DEBUG: Start'
            )
        );
    }

    public function onStartError(StartErrorEvent $event)
    {
        $this->writeln(
            sprintf(
                '+ DEBUG: Start error %s',
                $event->getException()->getMessage()
            )
        );
    }

    public function onFile(FileEvent $event)
    {
        $this->writeln(
            sprintf(
                '  + DEBUG: File (%s)',
                $event->getFile()->getPathname()
            )
        );
    }

    public function onFileError(FileErrorEvent $event)
    {
        $this->writeln(
            sprintf(
                '  ! DEBUG: File error (%s): %s',
                $event->getFile(),
                $event->getException()->getMessage()
            )
        );
    }

    public function onEnd(EndEvent $event)
    {
        $this->writeln(
            sprintf(
                '+ DEBUG: End'
            )
        );
    }

    public function onEndError(EndErrorEvent $event)
    {
        $this->writeln(
            sprintf(
                '+ DEBUG: End error %s',
                $event->getException()->getMessage()
            )
        );
    }

    protected function handleListeners($add)
    {
        $method = ($add) ? 'addListener' : 'removeListener';

        $this->dispatcher->$method(
            $this->eventNames->start(),
            [$this, 'onStart']
        );

        $this->dispatcher->$method(
            $this->eventNames->startError(),
            [$this, 'onStartError']
        );

        $this->dispatcher->$method(
            $this->eventNames->end(),
            [$this, 'onEnd']
        );

        $this->dispatcher->$method(
            $this->eventNames->endError(),
            [$this, 'onEndError']
        );

        $this->dispatcher->$method(
            $this->eventNames->file(),
            [$this, 'onFile']
        );

        $this->dispatcher->$method(
            $this->eventNames->fileError(),
            [$this, 'onFileError']
        );
    }

    protected function writeln($msg)
    {
        echo sprintf("%s\n", $msg);
    }
}