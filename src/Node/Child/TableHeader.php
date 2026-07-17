<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Child;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\BlockNode;

final class TableHeader extends BlockNode
{
    private ?int $colspan = null;
    private ?int $rowspan = null;
    private ?string $background = null;

    public function getType(): string
    {
        return NodeType::TableHeader->value;
    }

    public function getColspan(): ?int
    {
        return $this->colspan;
    }

    public function setColspan(?int $colspan): static
    {
        $this->colspan = $colspan;

        return $this;
    }

    public function getRowspan(): ?int
    {
        return $this->rowspan;
    }

    public function setRowspan(?int $rowspan): static
    {
        $this->rowspan = $rowspan;

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(?string $background): static
    {
        $this->background = $background;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function attrs(): array
    {
        $attrs = [];

        if (null !== $this->colspan) {
            $attrs['colspan'] = $this->colspan;
        }

        if (null !== $this->rowspan) {
            $attrs['rowspan'] = $this->rowspan;
        }

        if (null !== $this->background) {
            $attrs['background'] = $this->background;
        }

        return $attrs;
    }

    public static function fromArray(array $data): static
    {
        $node = (new self())->setContent(self::contentFromArray($data));

        if (isset($data['attrs']['colspan'])) {
            $node->setColspan((int) $data['attrs']['colspan']);
        }

        if (isset($data['attrs']['rowspan'])) {
            $node->setRowspan((int) $data['attrs']['rowspan']);
        }

        if (isset($data['attrs']['background'])) {
            $node->setBackground((string) $data['attrs']['background']);
        }

        return $node;
    }
}
