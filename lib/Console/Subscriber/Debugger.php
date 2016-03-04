<?php

/*
 * This file is part of the kompakt/directory-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\DirectoryRunner\Console\Subscriber;

use Kompakt\DirectoryRunner\EventNamesInterface;
use Kompakt\DirectoryRunner\Event\EndErrorEvent;
use Kompakt\DirectoryRunner\Event\EndEvent;
use Kompakt\DirectoryRunner\Event\FileErrorEvent;
use Kompakt\DirectoryRunner\Event\FileEvent;
use Kompakt\DirectoryRunner\Event\StartErrorEvent;
use Kompakt\DirectoryRunner\Event\StartEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Debugger
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $output = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
    }

    public function activate(OutputInterface $output)
    {
        $this->output = $output;
        $this->handleListeners(true);
    }

    public function deactivate()
    {
        $this->handleListeners(false);
    }

    public function onStart(StartEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<info>+ DEBUG: Start</info>'
            )
        );
    }

    public function onStartError(StartErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>+ DEBUG: Start error %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onEnd(EndEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<info>+ DEBUG: End</info>'
            )
        );
    }

    public function onEndError(EndErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>+ DEBUG: End error %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onFile(FileEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '  <info>+ DEBUG: File (%s)</info>',
                $event->getFile()
            )
        );
    }

    public function onFileError(FileErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '  <error>! DEBUG: File error (%s): %s</error>',
                $event->getFile(),
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
}