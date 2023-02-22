<?php

declare(strict_types=1);

namespace Slam\Laminas\Log\Tests;

use Laminas\Log\LoggerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Slam\Laminas\Log\Exception;
use Slam\Laminas\Log\LoggerAwareInterface;
use Slam\Laminas\Log\LoggerAwareTrait;

#[CoversClass(LoggerAwareTrait::class)]
final class LoggerAwareTraitTest extends TestCase
{
    private MockObject&LoggerInterface $logger;

    private LoggerAwareInterface $loggerAware;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->loggerAware = new class() implements LoggerAwareInterface {
            use LoggerAwareTrait;
        };
    }

    public function testSetAndRetrieve(): void
    {
        $this->loggerAware->setLogger($this->logger);

        self::assertSame($this->logger, $this->loggerAware->getLogger());
    }

    public function testLoggerCannotBeOverwritten(): void
    {
        $logger1 = clone $this->logger;
        $logger2 = clone $this->logger;

        $this->loggerAware->setLogger($logger1);

        $this->expectException(Exception\RuntimeException::class);
        $this->loggerAware->setLogger($logger2);
    }

    public function testHasADefaultNullLogger(): void
    {
        self::assertInstanceOf(LoggerInterface::class, $this->loggerAware->getLogger());
    }
}
