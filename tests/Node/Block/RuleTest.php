<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Block;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\Rule;

class RuleTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'rule'];
        $node = Rule::fromArray($data);

        $this->assertSame('rule', $node->getType());
        $this->assertSame($data, $node->toArray());
    }
}
