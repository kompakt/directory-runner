<?php

/*
 * This file is part of the kompakt/directory-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\DirectoryRunner\Exception;

use Kompakt\DirectoryRunner\Exception as BaseException;

class InvalidArgumentException extends \InvalidArgumentException implements BaseException
{}