<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Inline;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Inline\Text;
use Xen3r0\Adf\Node\Mark\Strong;

class TextTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'text', 'text' => 'Hello', 'marks' => [['type' => 'strong']]];
        $node = Text::fromArray($data);

        $this->assertSame('text', $node->getType());
        $this->assertSame('Hello', $node->getText());
        $this->assertCount(1, $node->getMarks());
        $this->assertInstanceOf(Strong::class, $node->getMarks()[0]);
        $this->assertTrue($node->hasMark(Strong::class));
        $this->assertSame($data, $node->toArray());
    }

    public function testWithoutMarks(): void
    {
        $data = ['type' => 'text', 'text' => 'plain'];
        $node = Text::fromArray($data);

        $this->assertSame([], $node->getMarks());
        $this->assertFalse($node->hasMark(Strong::class));
        $this->assertSame($data, $node->toArray());
    }

    public function testFluentApi(): void
    {
        $node = (new Text('x'))->setText('y')->addMark(new Strong());

        $this->assertSame('y', $node->getText());
        $this->assertCount(1, $node->getMarks());
    }
}
