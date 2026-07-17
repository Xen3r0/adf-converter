<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Block;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\Blockquote;

class BlockquoteTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'blockquote', 'content' => [['type' => 'paragraph', 'content' => []]]];
        $node = Blockquote::fromArray($data);

        $this->assertSame('blockquote', $node->getType());
        $this->assertSame($data, $node->toArray());
    }
}
