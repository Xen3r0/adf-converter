<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Block;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\BulletList;

class BulletListTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'bulletList', 'content' => [['type' => 'listItem', 'content' => []]]];
        $node = BulletList::fromArray($data);

        $this->assertSame('bulletList', $node->getType());
        $this->assertSame($data, $node->toArray());
    }
}
