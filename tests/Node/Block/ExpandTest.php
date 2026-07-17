<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Block;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\Expand;

class ExpandTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'expand', 'attrs' => ['title' => 'More info'], 'content' => [['type' => 'paragraph', 'content' => []]]];
        $node = Expand::fromArray($data);

        $this->assertSame('expand', $node->getType());
        $this->assertSame('More info', $node->getTitle());
        $this->assertSame($data, $node->toArray());
    }

    public function testWithoutTitle(): void
    {
        $data = ['type' => 'expand', 'content' => []];
        $node = Expand::fromArray($data);

        $this->assertNull($node->getTitle());
        $this->assertSame($data, $node->toArray());
    }
}
