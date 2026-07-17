<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Mark;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Mark\Link;

class LinkTest extends TestCase
{
    public function testFromArrayAndJsonSerializeWithTitle(): void
    {
        $mark = Link::fromArray(['type' => 'link', 'attrs' => ['href' => 'https://example.com', 'title' => 'Example']]);

        $this->assertSame('link', $mark->getType());
        $this->assertSame('https://example.com', $mark->getHref());
        $this->assertSame('Example', $mark->getTitle());
        $this->assertSame(
            ['type' => 'link', 'attrs' => ['href' => 'https://example.com', 'title' => 'Example']],
            $mark->jsonSerialize()
        );
    }

    public function testWithoutTitle(): void
    {
        $mark = Link::fromArray(['type' => 'link', 'attrs' => ['href' => 'https://example.com']]);

        $this->assertNull($mark->getTitle());
        $this->assertSame(['type' => 'link', 'attrs' => ['href' => 'https://example.com']], $mark->jsonSerialize());
    }
}
