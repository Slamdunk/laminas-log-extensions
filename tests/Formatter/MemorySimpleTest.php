<?php

declare(strict_types=1);

namespace Slam\Laminas\Log\Tests\Formatter;

use Laminas\Log\Formatter\Simple as LaminasSimple;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Slam\Laminas\Log\Formatter\MemorySimple;

#[CoversClass(MemorySimple::class)]
final class MemorySimpleTest extends TestCase
{
    public function testInit(): void
    {
        $formatter = new MemorySimple();

        self::assertInstanceOf(LaminasSimple::class, $formatter);
        self::assertNotNull($formatter->format([]));
    }
}
