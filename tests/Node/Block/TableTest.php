<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Block;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\Table;

class TableTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = [
            'type' => 'table',
            'attrs' => ['isNumberColumnEnabled' => true, 'layout' => 'wide', 'width' => 760, 'displayMode' => 'fixed'],
            'content' => [['type' => 'tableRow', 'content' => []]],
        ];
        $node = Table::fromArray($data);

        $this->assertSame('table', $node->getType());
        $this->assertTrue($node->isNumberColumnEnabled());
        $this->assertSame('wide', $node->getLayout());
        $this->assertSame(760, $node->getWidth());
        $this->assertSame('fixed', $node->getDisplayMode());
        $this->assertSame($data, $node->toArray());
    }

    public function testWithoutAttrs(): void
    {
        $data = ['type' => 'table', 'content' => []];
        $node = Table::fromArray($data);

        $this->assertNull($node->isNumberColumnEnabled());
        $this->assertNull($node->getLayout());
        $this->assertNull($node->getWidth());
        $this->assertNull($node->getDisplayMode());
        $this->assertSame($data, $node->toArray());
    }
}
