<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Mark;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Mark\TextColor;

class TextColorTest extends TestCase
{
    public function testFromArrayAndJsonSerialize(): void
    {
        $mark = TextColor::fromArray(['type' => 'textColor', 'attrs' => ['color' => '#daa520']]);

        $this->assertSame('textColor', $mark->getType());
        $this->assertSame('#daa520', $mark->getColor());
        $this->assertSame(['type' => 'textColor', 'attrs' => ['color' => '#daa520']], $mark->jsonSerialize());
    }

    public function testDefaultColor(): void
    {
        $mark = TextColor::fromArray(['type' => 'textColor']);

        $this->assertSame('#000000', $mark->getColor());
    }
}
