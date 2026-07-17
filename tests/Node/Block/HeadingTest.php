<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Block;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\Heading;

class HeadingTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'heading', 'attrs' => ['level' => 3], 'content' => [['type' => 'text', 'text' => 'Title']]];
        $node = Heading::fromArray($data);

        $this->assertSame('heading', $node->getType());
        $this->assertSame(3, $node->getLevel());
        $this->assertSame($data, $node->toArray());
    }

    public function testDefaultLevel(): void
    {
        $node = Heading::fromArray(['type' => 'heading', 'content' => []]);

        $this->assertSame(1, $node->getLevel());
    }
}
