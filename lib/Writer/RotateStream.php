<?php

declare(strict_types=1);

namespace Slam\Laminas\Log\Writer;

use DateTime;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream as LaminasStream;

final class RotateStream extends LaminasStream
{
    /**
     * On average, we write 100 byte at time, and so doing the check
     * once every 10.000.000 bytes written we can save resources.
     */
    private int $checkProbability = 100000;

    /**
     * On linux, PHP is able to write only 2.147.483.647 bytes.
     * Let's rotate the file at 75% of this size.
     */
    private int $maxFileSize = 1610612735;

    private ?string $streamOrUrl;

    private string $mode;

    private int $inc = 1;

    public function __construct(array|string $streamOrUrl, ?string $mode = null, ?string $logSeparator = null)
    {
        if (\is_array($streamOrUrl)) {
            $mode           = $streamOrUrl['mode']          ?? null;
            $logSeparator   = $streamOrUrl['log_separator'] ?? null;
            $streamOrUrl    = $streamOrUrl['stream']        ?? null;
        }

        // Setting the default mode
        if (null === $mode) {
            $mode = 'a';
        }

        $this->streamOrUrl = $streamOrUrl;
        $this->mode        = $mode;

        parent::__construct($this->streamOrUrl, $this->mode, $logSeparator);
    }

    public function getStreamname(): ?string
    {
        return $this->streamOrUrl;
    }

    public function setCheckProbability(int $checkProbability): void
    {
        $this->checkProbability = $checkProbability;
    }

    public function getCheckProbability(): int
    {
        return $this->checkProbability;
    }

    public function setMaxFileSize(int $maxFileSize): void
    {
        $this->maxFileSize = $maxFileSize;
    }

    public function getMaxFileSize(): int
    {
        return $this->maxFileSize;
    }

    protected function doWrite(array $event): void
    {
        \assert(null !== $this->streamOrUrl);

        if ($this->inc === $this->checkProbability && \is_file($this->streamOrUrl)) {
            if (\filesize($this->streamOrUrl) > $this->maxFileSize) {
                parent::doWrite([
                    'timestamp'    => new DateTime(),
                    'priority'     => Logger::NOTICE,
                    'priorityName' => 'NOTICE',
                    'message'      => 'LOG ROTATE',
                    'extra'        => [],
                ]);

                $this->rotateFile();
            }

            \assert(null !== $this->streamOrUrl);

            \clearstatcache(true, $this->streamOrUrl);
            $this->inc = 1;
        } else {
            ++$this->inc;
        }

        parent::doWrite($event);
    }

    private function rotateFile(): void
    {
        \assert(null !== $this->streamOrUrl);
        \assert(null !== $this->stream);

        \fclose($this->stream);

        $ext = 1;
        do {
            $newName = \sprintf('%s.%s', $this->streamOrUrl, $ext);
            ++$ext;
        } while (\is_file($newName));

        \rename($this->streamOrUrl, $newName);

        $this->stream = \fopen($this->streamOrUrl, $this->mode, false);
    }
}
