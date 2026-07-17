<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Child;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Child\Media;

class MediaTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = [
            'type' => 'media',
            'attrs' => [
                'type' => 'file',
                'id' => 'abc-123',
                'collection' => 'attachments',
                'url' => 'https://example.atlassian.net/media/abc-123',
                'occurrenceKey' => 'occ-1',
                'width' => 640,
                'height' => 480,
                'alt' => 'a screenshot',
            ],
        ];
        $node = Media::fromArray($data);

        $this->assertSame('media', $node->getType());
        $this->assertSame('file', $node->getMediaType());
        $this->assertSame('abc-123', $node->getId());
        $this->assertSame('attachments', $node->getCollection());
        $this->assertSame('https://example.atlassian.net/media/abc-123', $node->getUrl());
        $this->assertSame('occ-1', $node->getOccurrenceKey());
        $this->assertSame(640, $node->getWidth());
        $this->assertSame(480, $node->getHeight());
        $this->assertSame('a screenshot', $node->getAlt());
        $this->assertSame($data, $node->toArray());
    }

    public function testDefaultsWithoutOptionalAttrs(): void
    {
        $data = ['type' => 'media', 'attrs' => ['type' => 'link']];
        $node = Media::fromArray($data);

        $this->assertSame('link', $node->getMediaType());
        $this->assertNull($node->getId());
        $this->assertNull($node->getCollection());
        $this->assertNull($node->getUrl());
        $this->assertNull($node->getOccurrenceKey());
        $this->assertNull($node->getWidth());
        $this->assertNull($node->getHeight());
        $this->assertNull($node->getAlt());
        $this->assertSame($data, $node->toArray());
    }
}
