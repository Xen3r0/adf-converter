<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Mark;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Mark\BackgroundColor;

class BackgroundColorTest extends TestCase
{
    public function testFromArrayAndJsonSerialize(): void
    {
        $mark = BackgroundColor::fromArray(['type' => 'backgroundColor', 'attrs' => ['color' => '#daa520']]);

        $this->assertSame('backgroundColor', $mark->getType());
        $this->assertSame('#daa520', $mark->getColor());
        $this->assertSame(['type' => 'backgroundColor', 'attrs' => ['color' => '#daa520']], $mark->jsonSerialize());
    }

    public function testDefaultColor(): void
    {
        $mark = BackgroundColor::fromArray(['type' => 'backgroundColor']);

        $this->assertSame('#ffffff', $mark->getColor());
    }
}
