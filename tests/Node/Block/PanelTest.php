<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Block;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\Panel;

class PanelTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = ['type' => 'panel', 'attrs' => ['panelType' => 'warning'], 'content' => [['type' => 'paragraph', 'content' => []]]];
        $node = Panel::fromArray($data);

        $this->assertSame('panel', $node->getType());
        $this->assertSame('warning', $node->getPanelType());
        $this->assertSame($data, $node->toArray());
    }

    public function testDefaultPanelType(): void
    {
        $node = Panel::fromArray(['type' => 'panel', 'content' => []]);

        $this->assertSame('info', $node->getPanelType());
    }
}
