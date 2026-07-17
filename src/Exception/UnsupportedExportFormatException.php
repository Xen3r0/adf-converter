<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Exception;

final class UnsupportedExportFormatException extends \InvalidArgumentException
{
    public function __construct(string $class)
    {
        parent::__construct(\sprintf('No exporter registered for node class "%s".', $class));
    }
}
