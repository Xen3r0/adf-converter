<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Exception\UnsupportedNodeTypeException;

class UnsupportedNodeTypeExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $exception = new UnsupportedNodeTypeException('foo');

        $this->assertSame('Unsupported ADF node type "foo".', $exception->getMessage());
    }
}
