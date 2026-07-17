<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Exception\InvalidAdfDocumentException;

class InvalidAdfDocumentExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $exception = new InvalidAdfDocumentException();

        $this->assertSame('The given content is not a valid ADF JSON document.', $exception->getMessage());
    }
}
