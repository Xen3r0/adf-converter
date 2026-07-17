<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Inline;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\InlineNode;

final class Status extends InlineNode
{
    private string $text = '';
    private ?string $color = null;
    private ?string $localId = null;

    public function getType(): string
    {
        return NodeType::Status->value;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getLocalId(): ?string
    {
        return $this->localId;
    }

    public function setLocalId(?string $localId): static
    {
        $this->localId = $localId;

        return $this;
    }

    public static function fromArray(array $data): static
    {
        $node = (new self())->setText((string) ($data['attrs']['text'] ?? ''));

        if (isset($data['attrs']['color'])) {
            $node->setColor((string) $data['attrs']['color']);
        }

        if (isset($data['attrs']['localId'])) {
            $node->setLocalId((string) $data['attrs']['localId']);
        }

        return $node;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $attrs = ['text' => $this->text];

        if (null !== $this->color) {
            $attrs['color'] = $this->color;
        }

        if (null !== $this->localId) {
            $attrs['localId'] = $this->localId;
        }

        return ['type' => 'status', 'attrs' => $attrs];
    }
}
