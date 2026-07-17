<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Block;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\OrderedList;

class OrderedListTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'orderedList', 'attrs' => ['order' => 5], 'content' => [['type' => 'listItem', 'content' => []]]];
        $node = OrderedList::fromArray($data);

        $this->assertSame('orderedList', $node->getType());
        $this->assertSame(5, $node->getOrder());
        $this->assertSame($data, $node->toArray());
    }

    public function testWithoutOrder(): void
    {
        $data = ['type' => 'orderedList', 'content' => []];
        $node = OrderedList::fromArray($data);

        $this->assertNull($node->getOrder());
        $this->assertSame($data, $node->toArray());
    }
}
