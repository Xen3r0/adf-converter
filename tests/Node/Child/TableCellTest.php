<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Child;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Child\TableCell;

class TableCellTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = [
            'type' => 'tableCell',
            'attrs' => ['colspan' => 2, 'rowspan' => 3, 'background' => '#ffffff'],
            'content' => [['type' => 'paragraph', 'content' => []]],
        ];
        $node = TableCell::fromArray($data);

        $this->assertSame('tableCell', $node->getType());
        $this->assertSame(2, $node->getColspan());
        $this->assertSame(3, $node->getRowspan());
        $this->assertSame('#ffffff', $node->getBackground());
        $this->assertSame($data, $node->toArray());
    }

    public function testWithoutAttrs(): void
    {
        $data = ['type' => 'tableCell', 'content' => []];
        $node = TableCell::fromArray($data);

        $this->assertNull($node->getColspan());
        $this->assertNull($node->getRowspan());
        $this->assertNull($node->getBackground());
        $this->assertSame($data, $node->toArray());
    }
}
