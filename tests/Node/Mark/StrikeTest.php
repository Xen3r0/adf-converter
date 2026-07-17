<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Mark;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Mark\Strike;

class StrikeTest extends TestCase
{
    public function testFromArrayAndJsonSerialize(): void
    {
        $mark = Strike::fromArray(['type' => 'strike']);

        $this->assertSame('strike', $mark->getType());
        $this->assertSame(['type' => 'strike'], $mark->jsonSerialize());
    }
}
