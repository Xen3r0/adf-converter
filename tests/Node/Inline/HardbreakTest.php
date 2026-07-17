<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Inline;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Inline\Hardbreak;

class HardbreakTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'hardBreak'];
        $node = Hardbreak::fromArray($data);

        $this->assertSame('hardBreak', $node->getType());
        $this->assertSame($data, $node->toArray());
    }
}
