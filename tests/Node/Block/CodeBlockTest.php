<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Block;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\CodeBlock;

class CodeBlockTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'codeBlock', 'attrs' => ['language' => 'php'], 'content' => [['type' => 'text', 'text' => 'echo 1;']]];
        $node = CodeBlock::fromArray($data);

        $this->assertSame('codeBlock', $node->getType());
        $this->assertSame('php', $node->getLanguage());
        $this->assertSame($data, $node->toArray());
    }

    public function testWithoutLanguage(): void
    {
        $data = ['type' => 'codeBlock', 'content' => []];
        $node = CodeBlock::fromArray($data);

        $this->assertNull($node->getLanguage());
        $this->assertSame($data, $node->toArray());
    }
}
