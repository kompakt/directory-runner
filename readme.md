# Kompakt\DirectoryRunner

Run through a directory with event-emitting along the way.

## Install

Through Composer:

+ `composer require kompakt/directory-runner`

## Example

Iterate over all `json` files in a given directory:

```php
use Kompakt\DirectoryRunner\Subscriber\Debugger;
use Kompakt\DirectoryRunner\Runner;
use Kompakt\DirectoryRunner\EventNames;
use Symfony\Component\EventDispatcher\EventDispatcher;

$dir = 'path/to/dir';

$dispatcher = new EventDispatcher();
$eventNames = new EventNames();
$runner = new Runner($dispatcher, $eventNames, $dir);
$debugger = new Debugger($dispatcher, $eventNames);
$debugger->activate();

$callback = function($fileInfo)
{
    return preg_match('/\.json$/', $fileInfo->getFilename());
};

$runner->run($callback);
```

## License

kompakt/directory-runner is licensed under the MIT license - see the LICENSE file for details