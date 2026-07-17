<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Block;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\Node;

final class Rule extends Node
{
    public function getType(): string
    {
        return NodeType::Rule->value;
    }

    public static function fromArray(array $data): static
    {
        return new self();
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return ['type' => 'rule'];
    }
}
