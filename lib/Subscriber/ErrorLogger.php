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
use Kompakt\DirectoryRunner\Event\FileErrorEvent;
use Kompakt\DirectoryRunner\Event\StartErrorEvent;
use Kompakt\DirectoryRunner\Event\StartEvent;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ErrorLogger
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $logger = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames,
        Logger $logger
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
        $this->logger = $logger;
    }

    public function activate()
    {
        $this->handleListeners(true);
    }

    public function deactivate()
    {
        $this->handleListeners(false);
    }

    public function onStartError(StartErrorEvent $event)
    {
        $this->logger->error($event->getException()->getMessage());
    }

    public function onEndError(EndErrorEvent $event)
    {
        $this->logger->error($event->getException()->getMessage());
    }

    public function onFileError(FileErrorEvent $event)
    {
        $this->logger->error(
            sprintf(
                '%s: "%s"',
                $event->getFile()->getPathname(),
                $event->getException()->getMessage()
            )
        );
    }

    protected function handleListeners($add)
    {
        $method = ($add) ? 'addListener' : 'removeListener';

        $this->dispatcher->$method(
            $this->eventNames->startError(),
            [$this, 'onStartError']
        );

        $this->dispatcher->$method(
            $this->eventNames->endError(),
            [$this, 'onEndError']
        );

        $this->dispatcher->$method(
            $this->eventNames->fileError(),
            [$this, 'onFileError']
        );
    }
}