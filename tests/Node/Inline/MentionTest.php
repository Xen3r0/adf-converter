<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Inline;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Inline\Mention;

class MentionTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = [
            'type' => 'mention',
            'attrs' => ['id' => 'acc-1', 'text' => '@Jane', 'userType' => 'DEFAULT', 'accessLevel' => 'CONTAINER'],
        ];
        $node = Mention::fromArray($data);

        $this->assertSame('mention', $node->getType());
        $this->assertSame('acc-1', $node->getId());
        $this->assertSame('@Jane', $node->getText());
        $this->assertSame('DEFAULT', $node->getUserType());
        $this->assertSame('CONTAINER', $node->getAccessLevel());
        $this->assertSame($data, $node->toArray());
    }

    public function testWithoutOptionalAttrs(): void
    {
        $data = ['type' => 'mention', 'attrs' => ['id' => 'acc-1']];
        $node = Mention::fromArray($data);

        $this->assertNull($node->getText());
        $this->assertNull($node->getUserType());
        $this->assertNull($node->getAccessLevel());
        $this->assertSame($data, $node->toArray());
    }
}
