<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Block;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\Paragraph;

class ParagraphTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Hello']]];
        $node = Paragraph::fromArray($data);

        $this->assertSame('paragraph', $node->getType());
        $this->assertSame($data, $node->toArray());
    }
}
