<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Exception;

final class InvalidAdfDocumentException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('The given content is not a valid ADF JSON document.');
    }
}
