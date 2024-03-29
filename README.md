# Slam Laminas\Log extensions

[![Latest Stable Version](https://img.shields.io/packagist/v/slam/laminas-log-extensions.svg)](https://packagist.org/packages/slam/laminas-log-extensions)
[![Downloads](https://img.shields.io/packagist/dt/slam/laminas-log-extensions.svg)](https://packagist.org/packages/slam/laminas-log-extensions)
[![Integrate](https://github.com/Slamdunk/laminas-log-extensions/workflows/CI/badge.svg?branch=master)](https://github.com/Slamdunk/laminas-log-extensions/actions)
[![Code Coverage](https://codecov.io/gh/Slamdunk/laminas-log-extensions/coverage.svg?branch=master)](https://codecov.io/gh/Slamdunk/laminas-log-extensions?branch=master)

Extensions for [Laminas\Log](https://github.com/laminas/laminas-log)

## Installation

Execute:

`composer require slam/laminas-log-extensions`

## Usage

The main functionality of this package is the RotateStream writer.
PHP cannot handle files larger than 2 GB, so if you log a lot you can end up
losing some if you reach this limit.

`Slam\Laminas\Log\Writer\RotateStream` rotates the write when it reaches ~1.5 GB.

```php
use Slam\Laminas\Log\Writer\RotateStream;
use Laminas\Log\Formatter\Simple;
use Laminas\Log\Logger;

$writer = new RotateStream(__DIR__ . '/log.txt');
$writer->setFormatter(new Simple());

// Do the check everytime, defaults to once every 100000 log entries
$writer->setCheckProbability(1);
// 10 bytes max file size, defaults to ~1.5 GB
$writer->setMaxFileSize(10);

$logger = new Logger();
$logger->addWriter($writer);

for ($i = 0; $i < 10; ++$i) {
    $logger->info($i);
    sleep(1);
}
```

This is what you'll find in the directory:

```
$ ls log.txt*
log.txt  log.txt.1  log.txt.2  log.txt.3  log.txt.4  log.txt.5  log.txt.6  log.txt.7  log.txt.8  log.txt.9

$ cat log.txt.9
2017-09-05T11:08:46+02:00 INFO (6): 8
2017-09-05T11:08:47+02:00 NOTICE (5): LOG ROTATE

$ cat log.txt
2017-09-05T11:08:47+02:00 INFO (6): 9
```
