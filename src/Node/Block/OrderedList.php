<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Block;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\BlockNode;

final class OrderedList extends BlockNode
{
    private ?int $order = null;

    public function getType(): string
    {
        return NodeType::OrderedList->value;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder(?int $order): static
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function attrs(): array
    {
        return null !== $this->order ? ['order' => $this->order] : [];
    }

    public static function fromArray(array $data): static
    {
        $node = (new self())->setContent(self::contentFromArray($data));

        if (isset($data['attrs']['order'])) {
            $node->setOrder((int) $data['attrs']['order']);
        }

        return $node;
    }
}
