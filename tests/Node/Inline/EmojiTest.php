<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Inline;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Inline\Emoji;

class EmojiTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'emoji', 'attrs' => ['shortName' => ':smile:', 'id' => '1f604', 'text' => '😄']];
        $node = Emoji::fromArray($data);

        $this->assertSame('emoji', $node->getType());
        $this->assertSame(':smile:', $node->getShortName());
        $this->assertSame('1f604', $node->getId());
        $this->assertSame('😄', $node->getText());
        $this->assertSame($data, $node->toArray());
    }

    public function testWithoutOptionalAttrs(): void
    {
        $data = ['type' => 'emoji', 'attrs' => ['shortName' => ':smile:']];
        $node = Emoji::fromArray($data);

        $this->assertNull($node->getId());
        $this->assertNull($node->getText());
        $this->assertSame($data, $node->toArray());
    }
}
