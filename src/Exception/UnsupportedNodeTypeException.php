<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Exception;

final class UnsupportedNodeTypeException extends \InvalidArgumentException
{
    public function __construct(string $type)
    {
        parent::__construct(\sprintf('Unsupported ADF node type "%s".', $type));
    }
}
