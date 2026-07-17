<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Child;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Child\ListItem;

class ListItemTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'listItem', 'content' => [['type' => 'paragraph', 'content' => []]]];
        $node = ListItem::fromArray($data);

        $this->assertSame('listItem', $node->getType());
        $this->assertSame($data, $node->toArray());
    }
}
