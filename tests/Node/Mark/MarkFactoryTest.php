<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Mark;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Exception\UnsupportedMarkTypeException;
use Xen3r0\Adf\Node\Mark\MarkFactory;
use Xen3r0\Adf\Node\Mark\Strong;

class MarkFactoryTest extends TestCase
{
    public function testCreateDispatchesToTheRightClass(): void
    {
        $mark = MarkFactory::create(['type' => 'strong']);

        $this->assertInstanceOf(Strong::class, $mark);
    }

    public function testCreateThrowsOnUnknownType(): void
    {
        $this->expectException(UnsupportedMarkTypeException::class);

        MarkFactory::create(['type' => 'somethingUnknown']);
    }

    public function testCreateAllReturnsEmptyArrayWithoutMarks(): void
    {
        $this->assertSame([], MarkFactory::createAll([]));
    }

    public function testCreateAllBuildsEveryMark(): void
    {
        $marks = MarkFactory::createAll(['marks' => [['type' => 'strong'], ['type' => 'em']]]);

        $this->assertCount(2, $marks);
    }
}
