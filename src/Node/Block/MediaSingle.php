<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Block;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\BlockNode;

final class MediaSingle extends BlockNode
{
    private ?string $layout = null;
    private ?float $width = null;
    private ?string $widthType = null;

    public function getType(): string
    {
        return NodeType::MediaSingle->value;
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

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getWidthType(): ?string
    {
        return $this->widthType;
    }

    public function setWidthType(?string $widthType): static
    {
        $this->widthType = $widthType;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function attrs(): array
    {
        $attrs = [];

        if (null !== $this->layout) {
            $attrs['layout'] = $this->layout;
        }

        if (null !== $this->width) {
            $attrs['width'] = $this->width;
        }

        if (null !== $this->widthType) {
            $attrs['widthType'] = $this->widthType;
        }

        return $attrs;
    }

    public static function fromArray(array $data): static
    {
        $node = (new self())->setContent(self::contentFromArray($data));

        if (isset($data['attrs']['layout'])) {
            $node->setLayout((string) $data['attrs']['layout']);
        }

        if (isset($data['attrs']['width'])) {
            $node->setWidth((float) $data['attrs']['width']);
        }

        if (isset($data['attrs']['widthType'])) {
            $node->setWidthType((string) $data['attrs']['widthType']);
        }

        return $node;
    }
}
