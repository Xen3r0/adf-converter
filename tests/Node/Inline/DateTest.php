<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Inline;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Inline\Date;

class DateTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'date', 'attrs' => ['timestamp' => '1700000000000']];
        $node = Date::fromArray($data);

        $this->assertSame('date', $node->getType());
        $this->assertSame('1700000000000', $node->getTimestamp());
        $this->assertSame($data, $node->toArray());
    }
}
