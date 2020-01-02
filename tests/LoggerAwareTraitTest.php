<?php

declare(strict_types=1);

namespace Slam\Laminas\Log\Tests;

use Laminas\Log\LoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Slam\Laminas\Log\Exception;
use Slam\Laminas\Log\LoggerAwareInterface;
use Slam\Laminas\Log\LoggerAwareTrait;

/**
 * @covers \Slam\Laminas\Log\LoggerAwareTrait
 */
final class LoggerAwareTraitTest extends TestCase
{
    /**
     * @var MockObject&LoggerInterface
     */
    private $logger;

    /**
     * @var LoggerAwareInterface
     */
    private $loggerAware;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);

        /** @var LoggerAwareInterface $loggerAware */
        $loggerAware       = $this->getObjectForTrait(LoggerAwareTrait::class);
        $this->loggerAware = $loggerAware;
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
