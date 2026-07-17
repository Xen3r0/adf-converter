<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Child;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Child\TableHeader;

class TableHeaderTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = [
            'type' => 'tableHeader',
            'attrs' => ['colspan' => 2, 'rowspan' => 1, 'background' => '#eeeeee'],
            'content' => [['type' => 'paragraph', 'content' => []]],
        ];
        $node = TableHeader::fromArray($data);

        $this->assertSame('tableHeader', $node->getType());
        $this->assertSame(2, $node->getColspan());
        $this->assertSame(1, $node->getRowspan());
        $this->assertSame('#eeeeee', $node->getBackground());
        $this->assertSame($data, $node->toArray());
    }

    public function testWithoutAttrs(): void
    {
        $data = ['type' => 'tableHeader', 'content' => []];
        $node = TableHeader::fromArray($data);

        $this->assertNull($node->getColspan());
        $this->assertNull($node->getRowspan());
        $this->assertNull($node->getBackground());
        $this->assertSame($data, $node->toArray());
    }
}
