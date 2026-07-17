<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Inline;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Inline\InlineCard;

class InlineCardTest extends TestCase
{
    public function testFromArrayAndToArrayWithUrl(): void
    {
        $data = ['type' => 'inlineCard', 'attrs' => ['url' => 'https://example.com']];
        $node = InlineCard::fromArray($data);

        $this->assertSame('inlineCard', $node->getType());
        $this->assertSame('https://example.com', $node->getUrl());
        $this->assertNull($node->getData());
        $this->assertSame($data, $node->toArray());
    }

    public function testFromArrayAndToArrayWithData(): void
    {
        $data = ['type' => 'inlineCard', 'attrs' => ['data' => ['@type' => 'Link', 'url' => 'https://example.com']]];
        $node = InlineCard::fromArray($data);

        $this->assertNull($node->getUrl());
        $this->assertSame(['@type' => 'Link', 'url' => 'https://example.com'], $node->getData());
        $this->assertSame($data, $node->toArray());
    }

    public function testWithoutAttrs(): void
    {
        $node = InlineCard::fromArray(['type' => 'inlineCard']);

        $this->assertSame(['type' => 'inlineCard', 'attrs' => []], $node->toArray());
    }
}
