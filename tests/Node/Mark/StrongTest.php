<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Mark;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Mark\Strong;

class StrongTest extends TestCase
{
    public function testFromArrayAndJsonSerialize(): void
    {
        $mark = Strong::fromArray(['type' => 'strong']);

        $this->assertSame('strong', $mark->getType());
        $this->assertSame(['type' => 'strong'], $mark->jsonSerialize());
    }
}
