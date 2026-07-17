<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node;

abstract class BlockNode extends Node
{
    /** @var Node[] */
    private array $content = [];

    /**
     * @return Node[]
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @param Node[] $content
     */
    public function setContent(array $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function addContent(Node $node): static
    {
        $this->content[] = $node;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $data = ['type' => $this->getType()];

        $attrs = $this->attrs();
        if ([] !== $attrs) {
            $data['attrs'] = $attrs;
        }

        $data['content'] = $this->content;

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    protected function attrs(): array
    {
        return [];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return Node[]
     */
    protected static function contentFromArray(array $data): array
    {
        return array_map(
            static fn (array $child): Node => NodeFactory::create($child),
            $data['content'] ?? []
        );
    }
}
