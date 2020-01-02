<?php

declare(strict_types=1);

namespace Slam\Laminas\Log\Formatter;

use Laminas\Log\Formatter\Simple as LaminasSimple;

final class MemorySimple extends LaminasSimple
{
    public const DEFAULT_FORMAT = '%timestamp% %priorityName% > %message% %extra%';

    /**
     * @var string
     */
    protected $dateTimeFormat = 'Y-m-d+H:i:s';

    public function format($event)
    {
        return \number_format(\memory_get_usage(true) / 1000000, 1, ',', '.') . ' ' . parent::format($event);
    }
}
