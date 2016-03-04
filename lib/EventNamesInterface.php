<?php

/*
 * This file is part of the kompakt/directory-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\DirectoryRunner;

interface EventNamesInterface
{
    public function start();
    public function startError();
    public function file();
    public function fileError();
    public function end();
    public function endError();
}