<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Mark;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Mark\Subsup;

class SubsupTest extends TestCase
{
    public function testFromArrayAndJsonSerialize(): void
    {
        $mark = Subsup::fromArray(['type' => 'subsup', 'attrs' => ['type' => 'sup']]);

        $this->assertSame('subsup', $mark->getType());
        $this->assertSame('sup', $mark->getSubsupType());
        $this->assertSame(['type' => 'subsup', 'attrs' => ['type' => 'sup']], $mark->jsonSerialize());
    }

    public function testDefaultSubsupType(): void
    {
        $mark = Subsup::fromArray(['type' => 'subsup']);

        $this->assertSame('sub', $mark->getSubsupType());
    }
}
