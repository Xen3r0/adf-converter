<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Block;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\BlockNode;

final class Expand extends BlockNode
{
    private ?string $title = null;

    public function getType(): string
    {
        return NodeType::Expand->value;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function attrs(): array
    {
        return null !== $this->title ? ['title' => $this->title] : [];
    }

    public static function fromArray(array $data): static
    {
        $node = (new self())->setContent(self::contentFromArray($data));

        if (isset($data['attrs']['title'])) {
            $node->setTitle((string) $data['attrs']['title']);
        }

        return $node;
    }
}
