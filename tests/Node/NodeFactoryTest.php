<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Exception\UnsupportedNodeTypeException;
use Xen3r0\Adf\Node\Block\Paragraph;
use Xen3r0\Adf\Node\NodeFactory;

class NodeFactoryTest extends TestCase
{
    public function testCreateDispatchesToTheRightClass(): void
    {
        $node = NodeFactory::create(['type' => 'paragraph', 'content' => []]);

        $this->assertInstanceOf(Paragraph::class, $node);
    }

    public function testCreateThrowsOnUnknownType(): void
    {
        $this->expectException(UnsupportedNodeTypeException::class);

        NodeFactory::create(['type' => 'somethingUnknown']);
    }

    public function testCreateThrowsWhenTypeIsMissing(): void
    {
        $this->expectException(UnsupportedNodeTypeException::class);

        NodeFactory::create([]);
    }
}
