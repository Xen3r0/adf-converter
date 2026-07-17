<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Child;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Child\TableRow;

class TableRowTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'tableRow', 'content' => [['type' => 'tableCell', 'content' => []]]];
        $node = TableRow::fromArray($data);

        $this->assertSame('tableRow', $node->getType());
        $this->assertSame($data, $node->toArray());
    }
}
