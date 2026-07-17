<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Inline;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\InlineNode;

final class InlineCard extends InlineNode
{
    private ?string $url = null;

    /** @var array<string, mixed>|null */
    private ?array $data = null;

    public function getType(): string
    {
        return NodeType::InlineCard->value;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param array<string, mixed>|null $data
     */
    public function setData(?array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): static
    {
        $node = new self();

        if (isset($data['attrs']['url'])) {
            $node->setUrl((string) $data['attrs']['url']);
        }

        if (isset($data['attrs']['data']) && \is_array($data['attrs']['data'])) {
            $node->setData($data['attrs']['data']);
        }

        return $node;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $attrs = [];

        if (null !== $this->url) {
            $attrs['url'] = $this->url;
        }

        if (null !== $this->data) {
            $attrs['data'] = $this->data;
        }

        return ['type' => 'inlineCard', 'attrs' => $attrs];
    }
}
