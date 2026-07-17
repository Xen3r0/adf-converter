<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Exception\UnsupportedMarkTypeException;

class UnsupportedMarkTypeExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $exception = new UnsupportedMarkTypeException('foo');

        $this->assertSame('Unsupported ADF mark type "foo".', $exception->getMessage());
    }
}
