<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Mark;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Mark\Code;

class CodeTest extends TestCase
{
    public function testFromArrayAndJsonSerialize(): void
    {
        $mark = Code::fromArray(['type' => 'code']);

        $this->assertSame('code', $mark->getType());
        $this->assertSame(['type' => 'code'], $mark->jsonSerialize());
    }
}
