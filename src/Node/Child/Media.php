<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Child;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\Node;

final class Media extends Node
{
    private string $mediaType = 'file';
    private ?string $id = null;
    private ?string $collection = null;
    private ?string $url = null;
    private ?string $occurrenceKey = null;
    private ?int $width = null;
    private ?int $height = null;
    private ?string $alt = null;

    public function getType(): string
    {
        return NodeType::Media->value;
    }

    public function getMediaType(): string
    {
        return $this->mediaType;
    }

    public function setMediaType(string $mediaType): static
    {
        $this->mediaType = $mediaType;

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

    public function getCollection(): ?string
    {
        return $this->collection;
    }

    public function setCollection(?string $collection): static
    {
        $this->collection = $collection;

        return $this;
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

    public function getOccurrenceKey(): ?string
    {
        return $this->occurrenceKey;
    }

    public function setOccurrenceKey(?string $occurrenceKey): static
    {
        $this->occurrenceKey = $occurrenceKey;

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

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): static
    {
        $this->alt = $alt;

        return $this;
    }

    public static function fromArray(array $data): static
    {
        $node = (new self())->setMediaType((string) ($data['attrs']['type'] ?? 'file'));

        if (isset($data['attrs']['id'])) {
            $node->setId((string) $data['attrs']['id']);
        }

        if (isset($data['attrs']['collection'])) {
            $node->setCollection((string) $data['attrs']['collection']);
        }

        if (isset($data['attrs']['url'])) {
            $node->setUrl((string) $data['attrs']['url']);
        }

        if (isset($data['attrs']['occurrenceKey'])) {
            $node->setOccurrenceKey((string) $data['attrs']['occurrenceKey']);
        }

        if (isset($data['attrs']['alt'])) {
            $node->setAlt((string) $data['attrs']['alt']);
        }

        if (isset($data['attrs']['width'])) {
            $node->setWidth((int) $data['attrs']['width']);
        }

        if (isset($data['attrs']['height'])) {
            $node->setHeight((int) $data['attrs']['height']);
        }

        return $node;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $attrs = ['type' => $this->mediaType];

        if (null !== $this->id) {
            $attrs['id'] = $this->id;
        }

        if (null !== $this->collection) {
            $attrs['collection'] = $this->collection;
        }

        if (null !== $this->url) {
            $attrs['url'] = $this->url;
        }

        if (null !== $this->occurrenceKey) {
            $attrs['occurrenceKey'] = $this->occurrenceKey;
        }

        if (null !== $this->width) {
            $attrs['width'] = $this->width;
        }

        if (null !== $this->height) {
            $attrs['height'] = $this->height;
        }

        if (null !== $this->alt) {
            $attrs['alt'] = $this->alt;
        }

        return ['type' => 'media', 'attrs' => $attrs];
    }
}
