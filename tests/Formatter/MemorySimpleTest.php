<?php

declare(strict_types=1);

namespace Slam\Laminas\Log\Tests\Formatter;

use Laminas\Log\Formatter\Simple as LaminasSimple;
use PHPUnit\Framework\TestCase;
use Slam\Laminas\Log\Formatter\MemorySimple;

/**
 * @covers \Slam\Laminas\Log\Formatter\MemorySimple
 */
final class MemorySimpleTest extends TestCase
{
    public function testInit(): void
    {
        $formatter = new MemorySimple();

        self::assertInstanceOf(LaminasSimple::class, $formatter);
        self::assertNotNull($formatter->format([]));
    }
}
