<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Mark;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Mark\Underline;

class UnderlineTest extends TestCase
{
    public function testFromArrayAndJsonSerialize(): void
    {
        $mark = Underline::fromArray(['type' => 'underline']);

        $this->assertSame('underline', $mark->getType());
        $this->assertSame(['type' => 'underline'], $mark->jsonSerialize());
    }
}
