<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Exception\UnsupportedExportFormatException;

class UnsupportedExportFormatExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $exception = new UnsupportedExportFormatException('Foo\\Bar');

        $this->assertSame('No exporter registered for node class "Foo\\Bar".', $exception->getMessage());
    }
}
