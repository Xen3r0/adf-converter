<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Block;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\MediaGroup;

class MediaGroupTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'mediaGroup', 'content' => [
            ['type' => 'media', 'attrs' => ['type' => 'file', 'id' => 'abc', 'collection' => 'col']],
        ]];
        $node = MediaGroup::fromArray($data);

        $this->assertSame('mediaGroup', $node->getType());
        $this->assertSame($data, $node->toArray());
    }
}
