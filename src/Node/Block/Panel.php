<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Block;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\BlockNode;

final class Panel extends BlockNode
{
    private string $panelType = 'info';

    public function getType(): string
    {
        return NodeType::Panel->value;
    }

    public function getPanelType(): string
    {
        return $this->panelType;
    }

    public function setPanelType(string $panelType): static
    {
        $this->panelType = $panelType;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function attrs(): array
    {
        return ['panelType' => $this->panelType];
    }

    public static function fromArray(array $data): static
    {
        $node = (new self())->setContent(self::contentFromArray($data));
        $node->setPanelType((string) ($data['attrs']['panelType'] ?? 'info'));

        return $node;
    }
}
