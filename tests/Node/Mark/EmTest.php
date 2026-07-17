<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Mark;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Mark\Em;

class EmTest extends TestCase
{
    public function testFromArrayAndJsonSerialize(): void
    {
        $mark = Em::fromArray(['type' => 'em']);

        $this->assertSame('em', $mark->getType());
        $this->assertSame(['type' => 'em'], $mark->jsonSerialize());
    }
}
