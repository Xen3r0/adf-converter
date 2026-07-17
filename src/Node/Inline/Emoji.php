<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Inline;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\InlineNode;

final class Emoji extends InlineNode
{
    private string $shortName = '';
    private ?string $id = null;
    private ?string $text = null;

    public function getType(): string
    {
        return NodeType::Emoji->value;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): static
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public static function fromArray(array $data): static
    {
        $node = (new self())->setShortName((string) ($data['attrs']['shortName'] ?? ''));

        if (isset($data['attrs']['id'])) {
            $node->setId((string) $data['attrs']['id']);
        }

        if (isset($data['attrs']['text'])) {
            $node->setText((string) $data['attrs']['text']);
        }

        return $node;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $attrs = ['shortName' => $this->shortName];

        if (null !== $this->id) {
            $attrs['id'] = $this->id;
        }

        if (null !== $this->text) {
            $attrs['text'] = $this->text;
        }

        return ['type' => 'emoji', 'attrs' => $attrs];
    }
}
