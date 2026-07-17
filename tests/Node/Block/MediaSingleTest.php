<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Block;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\MediaSingle;

class MediaSingleTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = [
            'type' => 'mediaSingle',
            'attrs' => ['layout' => 'center', 'width' => 50.5, 'widthType' => 'percentage'],
            'content' => [['type' => 'media', 'attrs' => ['type' => 'file', 'id' => 'abc', 'collection' => 'col']]],
        ];
        $node = MediaSingle::fromArray($data);

        $this->assertSame('mediaSingle', $node->getType());
        $this->assertSame('center', $node->getLayout());
        $this->assertSame(50.5, $node->getWidth());
        $this->assertSame('percentage', $node->getWidthType());
        $this->assertSame($data, $node->toArray());
    }

    public function testWithoutAttrs(): void
    {
        $data = ['type' => 'mediaSingle', 'content' => []];
        $node = MediaSingle::fromArray($data);

        $this->assertNull($node->getLayout());
        $this->assertNull($node->getWidth());
        $this->assertNull($node->getWidthType());
        $this->assertSame($data, $node->toArray());
    }
}
