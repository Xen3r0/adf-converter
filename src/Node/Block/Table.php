<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Block;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\BlockNode;

final class Table extends BlockNode
{
    private ?bool $isNumberColumnEnabled = null;
    private ?string $layout = null;
    private ?int $width = null;
    private ?string $displayMode = null;

    public function getType(): string
    {
        return NodeType::Table->value;
    }

    public function isNumberColumnEnabled(): ?bool
    {
        return $this->isNumberColumnEnabled;
    }

    public function setNumberColumnEnabled(?bool $isNumberColumnEnabled): static
    {
        $this->isNumberColumnEnabled = $isNumberColumnEnabled;

        return $this;
    }

    public function getLayout(): ?string
    {
        return $this->layout;
    }

    public function setLayout(?string $layout): static
    {
        $this->layout = $layout;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getDisplayMode(): ?string
    {
        return $this->displayMode;
    }

    public function setDisplayMode(?string $displayMode): static
    {
        $this->displayMode = $displayMode;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function attrs(): array
    {
        $attrs = [];

        if (null !== $this->isNumberColumnEnabled) {
            $attrs['isNumberColumnEnabled'] = $this->isNumberColumnEnabled;
        }

        if (null !== $this->layout) {
            $attrs['layout'] = $this->layout;
        }

        if (null !== $this->width) {
            $attrs['width'] = $this->width;
        }

        if (null !== $this->displayMode) {
            $attrs['displayMode'] = $this->displayMode;
        }

        return $attrs;
    }

    public static function fromArray(array $data): static
    {
        $node = (new self())->setContent(self::contentFromArray($data));

        if (isset($data['attrs']['isNumberColumnEnabled'])) {
            $node->setNumberColumnEnabled((bool) $data['attrs']['isNumberColumnEnabled']);
        }

        if (isset($data['attrs']['layout'])) {
            $node->setLayout((string) $data['attrs']['layout']);
        }

        if (isset($data['attrs']['width'])) {
            $node->setWidth((int) $data['attrs']['width']);
        }

        if (isset($data['attrs']['displayMode'])) {
            $node->setDisplayMode((string) $data['attrs']['displayMode']);
        }

        return $node;
    }
}
