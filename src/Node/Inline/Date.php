<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Inline;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\InlineNode;

final class Date extends InlineNode
{
    private string $timestamp = '';

    public function getType(): string
    {
        return NodeType::Date->value;
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    public function setTimestamp(string $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public static function fromArray(array $data): static
    {
        return (new self())->setTimestamp((string) ($data['attrs']['timestamp'] ?? ''));
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return ['type' => 'date', 'attrs' => ['timestamp' => $this->timestamp]];
    }
}
