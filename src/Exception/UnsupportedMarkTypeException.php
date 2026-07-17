<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Exception;

final class UnsupportedMarkTypeException extends \InvalidArgumentException
{
    public function __construct(string $type)
    {
        parent::__construct(\sprintf('Unsupported ADF mark type "%s".', $type));
    }
}
