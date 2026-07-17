<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Inline;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Inline\Status;

class StatusTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'status', 'attrs' => ['text' => 'In Progress', 'color' => 'blue', 'localId' => 'loc-1']];
        $node = Status::fromArray($data);

        $this->assertSame('status', $node->getType());
        $this->assertSame('In Progress', $node->getText());
        $this->assertSame('blue', $node->getColor());
        $this->assertSame('loc-1', $node->getLocalId());
        $this->assertSame($data, $node->toArray());
    }

    public function testWithoutOptionalAttrs(): void
    {
        $data = ['type' => 'status', 'attrs' => ['text' => 'Done']];
        $node = Status::fromArray($data);

        $this->assertNull($node->getColor());
        $this->assertNull($node->getLocalId());
        $this->assertSame($data, $node->toArray());
    }
}
